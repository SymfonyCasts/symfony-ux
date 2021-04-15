# Using the autocomplete-controller

Now that we have a new `autocomplete` controller registered, let's try to use it
instead of our custom `search-preview` controller to power the search preview
functionality.

## Setting up the Controller in HTML

To see how, go back to the docs. Hmm... looking at this example, we need to add the
`data-controller` attribute to an element that's around both the `input` that we
type into *and* the `div` where the results will go. We also need to pass a `url`
*value* set to where the Ajax call should be made. The input needs a target called
`input`... and the results go into a target called `results`. We don't need to worry
about this hidden element: that's only needed if you're building a form and want
to update a form field when the user selects an option.

Open the template for the homepage: `templates/product/index.html.twig`. First,
change the name of the controller from `search-preview` to `autocomplete`. Then...
nice! We were *already* passing a value called `url`, so that's done.

[[[ code('8dca50384c') ]]]

Next, on the input, we don't need the "action" anymore: the controller will handle
setting that up for us. But we *do* need to *identity* this as the text input by
adding a target: `data-autocomplete-target="input"`.

[[[ code('698150357e') ]]]

Finally, update the "results" target to use the new controller name - `autocomplete` -
and the target name is now `results` with an "S".

[[[ code('e8028446c2') ]]]

Done. Let's try it! Move over, find our site, refresh, type "di" and... nothing
happened! Well, not *nothing*. I can see that there *was* an Ajax call. In fact,
*two* Ajax calls: I can see that down in the web debug toolbar.

Let's check out one of these in our Network tool. Look at the "Preview". Whoa! It's
a little small... but this is the *full* HTML page. Let's make this bigger. Yep,
this didn't render just the results *partial*, it returned the full page!

What happened?

## How the autocomplete Ajax Works

Okay. I noticed a few things. To start, by *complete* chance, the `autocomplete`
controller sends the contents of our input as a query parameter called `q`... which
is *exactly* what we were using before! You can see that in
`src/Controller/ProductController.php`: we read `q` as our search term. Awesome!

[[[ code('14a278ceb1') ]]]

But we *also* look for a `?preview` query parameter to know if we should render
a page *partial*. 

[[[ code('94192010fe') ]]]

Previously, in `search-preview` controller, we added that query
parameter manually in JavaScript. We can't do that now... but that's ok! We can
add it to the `url` *value*.

In `index.html.twig`, back up on the `url`, add a second argument to `path` and
pass `preview: 1`. That will fix the full page problem.

[[[ code('e04f095063') ]]]

But if you try it now... the same thing happened again! An Ajax call was made...
but no results are showing up! On the network tab... yeah! It *is* now
returning the partial, not the full page. So... why don't we actually *see* the
results below the input?

## Adding the role="option" Attribute

Because... the *other* rule of this library - which I *totally* would have noticed
if I had read the documentation a bit more carefully (sorry!) - is that each result
must be identified by a `role="option"` attribute. But we don't need this
`data-autocomplete-value` attribute: that controls the value that would go into
the hidden `input`... which we don't need. But we *definitely* need the
`role="option"` thing.

Let's go add it! The template for the partial lives at
`templates/product/_searchPreview.html.twig`. On the `<a>` tag, which represents
a single option, add `role="option"`.

[[[ code('47d01ab566') ]]]

Oh, and down here on the "no results", we need to do the same thing: `role="option"`.
And if you look again at the documentation, to have an option but make it *not*
selectable, you can add `aria-disabled="true"`. That will make it show up on the
list... but I won't be able to select it.

[[[ code('b661a23abc') ]]]

This time, back on the site, we don't even need to refresh the page: I can type and
boom! There it is! It looks *exactly* like before! And as a bonus, the controller
has a feature that ours never did: the ability to hit up and down on our keyboard
to go through the results. *And*, pressing enter, activates that item.

Oh, and by the way! *Technically*, we did *not* need to make our controller for
the Ajax call return a *partial*. If we returned the *whole* page from the Ajax
call... it would still work *exactly* like it does now... because the `autocomplete`
controller looks for the `role="option"` attribute and only renders *those*.
Rendering just the *partial* is a bit faster... but it's technically *not* needed...
which is kind of amazing!

Let's celebrate by deleting our old `search-preview` controller. Thanks for teaching
us how to use Stimulus! But now it's time for us move forward with less custom code.

## Making third-Party Controllers Lazy

I have *one* more wish... or question: could we make this `autocomplete` controller
load lazily? Earlier we made the whole `chart-js` controller "lazy" in
`controllers.json` by setting `fetch: 'lazy'`. We also made our own
`submit-confirm_controller` lazy by adding this special comment above the class.

But what about a third-party controller? We don't register this in `controllers.json`
and... we can't exactly open up the file and add a comment in it. So, can we make
it lazy?

We can! Though, due to some rigidness in how Webpack works, the syntax... isn't
amazing. In `bootstrap.js`, we need to change the `import` to pass through the
special `@symfony/stimulus-bridge/lazy-controller-loader`. You can do that by
literally saying `import { Autocomplete } from...` - the name of that loader -
an exclamation point, and then the name of the module that you want to import.

So, this module will now be passed *through* the loader. That... by itself, won't
change anything. But now, before the exclamation point, add `?lazy=true`.

The `lazy-controller-loader` is what looks for the `stimulusFetch` comment above
the controller. Since that won't be there, this `?lazy=true` is our way to *force*
laziness.

And, normally, this would be *all* we need! It's a bit ugly, but not too bad!
And we get the laziness we want.

However, notice that the `stimulus-autocomplete` module uses a *named* export
instead of a *default* export. What I mean is, when we use this, we have to say
`import { Autocomplete }` - that's a *named* export - instead of just being able
to say `import Autocomplete`. If we tried *this*, it would not work.

*Anyways*, since the library uses a named export, we need to notify the loader
about this so it knows where to find the code. We do that by adding one more loader
option: `&export=Autocomplete`: the name of the import.

[[[ code('3b747a37ca') ]]]

Ok, *now* we're done. It's ugly, but it gets the job done *if* you need a 3rd
party controller to load lazily.

Let's see it in action. Move over, refresh the page... and go down to your Network
tools filtered for JavaScript. Yes! Look at this long name: this is the file that
contains `stimulus-autocomplete`. The "Initiator" proves that it's being downloaded
lazily, only *after* the `data-controller="autocomplete"` was found on the page.

If we go to *any* other page... that code is *never* downloaded.

The new `stimulus-autocomplete` controller allows us to have *less* custom code
*and* better functionality, with the ability to press up and down on the search
results. But we did lose one thing: the nice CSS transitions! Can we somehow *add*
those to this third-party controller? As long as the controller dispatches the right
events, we totally can. Let's learn how next
