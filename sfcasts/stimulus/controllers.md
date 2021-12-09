# Stimulus Controllers

Ok: time for Stimulus! First stimulus is... a JavaScript library! If you start
a new project and install Encore fresh, like we did, then thanks to the recipe,
`stimulus` is already inside of your `package.json` file.

We also have an `@symfony/stimulus-bridge` library. This adds superpowers on top
of stimulus. I'll tell you exactly what those are as we go along.

If you don't have these packages, install them with:

```terminal
yarn add stimulus @symfony/stimulus-bridge --dev
```

Let me close a few tabs and open up our `assets/app.js` file. This imports
a `bootstrap.js` file that the recipe *also* gave us. And you *will* need this:
if you don't have it, you can get it from the code block on this page or the
`stimulus-bridge` docs.

The *one* line in this file starts stimulus by telling it to look for stimulus
controllers in this `controllers/` directory, which is literally `assets/controllers/`.
Symfony gives us one dummy controller to start.

And... yea, the entire point of this file is to say:

> Hey Stimulus! I have some controllers in this `controllers/` directory.

We'll learn what all this weird `lazy-controller` stuff is a bit later, it's not
important yet.

## Creating our First Controller

The best way to see how Stimulus works is just to try it. Delete the
`hello_controller.js` file: let's create our first controller from scratch. Call
it `counter_controller.js`. To learn the Stimulus basics, we're going to create
an element that tracks how many times we click it.

Oh and this naming convention *is* important. All of our controller files will
be `something_controller.js`. And you'll see why in a minute.

***TIP
If you start a new project today, you'll be using Stimulus 3. You can check by looking in your
`package.json` file for `@hotwired/stimulus`. The *only* thing you need to change for Stimulus 3
is the import statement. Use:

```diff
-import { Controller } from 'stimulus';
+import { Controller } from '@hotwired/stimulus';
```
***

Inside the file, these always start the same way: `import {}` from `stimulus`
and what we want to import is `Controller`. Then, `export default` a class
that `extends Controller`. Inside the class, add a method called `connect()`
with `this.element.innerHTML =` a message:

> You have clicked zero times 😢

[[[ code('63f9ae80dc') ]]]

## Adding the data-controller Element

That's all we need for now. To see what this *does*, we need to add a
matching element to one of our pages. Open `templates/product/index.html.twig`.

This is the template for the homepage. Down a bit, how about at the top of the
main content, add `<div data-controller="counter"></div>`.

[[[ code('b556fbbd2d') ]]]

We *can* put something *in* the `div`, but we don't need to for our example.

The `data-controller` *connects* this element to the controller class that we
just created. Because we named the file `counter_controller.js`, the controller's
name internally in Stimulus is `counter`: it strips off the `_controller.js` part.

So we connected the element to that controller with `data-controller="counter"`.
Thanks to that, this should work!

As a reminder, I still have a `yarn watch` going over in my terminal, so it's
been happily rebuilding each time we make a change.

Spin over to your browser and click to get to the homepage. Yes! It's alive!
The empty div has our message! Inspect that element. Yep! We can see
`data-controller` and the text inside.

## Elements & Controller Objects

This is the magic of stimulus. As *soon* as it sees an element with
`data-controller="counter"`, it *instantiates* an instance of our "counter"
controller and calls the `connect()` method... named that way because Stimulus is
"connecting" this object to a specific element on the page. And, as you can see,
the element we just got connected to is *available* via `this.element`.

That allowed us to easily set its inner HTML.

## Multiple Controller Instances on the Page

The beauty is that we can have as *many* of these elements on the page at the
same time as we want. I'll copy the `div` and, up in the `aside`, paste.

[[[ code('9e53f235a5') ]]]

Go refresh now. *Two* messages! And the *really* cool part is that each element
is connected to a separate *instance* of our controller class. This means we
get to write clean JavaScript code in a class *and* store information specific to
*its* element as *properties* on that object. We'll do that very soon.

So... with Stimulus, we get objects that are bound to individual HTML elements
and are instantiated automatically when those elements appear on the page. I would
use Stimulus *just* for that! It's the simple, object-oriented JavaScript approach
I've always tried to create on my own.

But wait there's more! Next: let's add a `count` property and a click listener
to *show* how each element is connected to a separate controller object. Then I'll
show you the feature of Stimulus that absolutely knocked me over when I first saw
it.
