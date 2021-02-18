# The Values API

What if we want a color to be automatically selected on page load? Like maybe
there's a most popular color that we want to suggest.

The best approach would probably be to pre-select an option in the `select`
element. Then we could read that from inside our Stimulus controller - probably
in `connect()` - and set that color square as "selected".

But for the purposes of learning, we're going to do something different to show
a solution to a *really* common problem: the problem of how to pass options and
server-side data *into* your Stimulus controller. In this case, our Symfony code
will decide *which* color should be selected by default and we need to pass that
info *into* Stimulus.

## Passing info Via a Data Attribute

We already know one approach to do this: data attributes! We invented a data
attribute on the button to add more information to it. So... if we want to pass
something to our controller, why not add a data attribute to the top level
controller element?

A fantastic suggestion! Break the element onto multiple lines and then add
`data-color-id=`. To keep things simple, let's pre-select whatever the *second*
color is. To do that, copy this whole `addToCartForm.vars` thing from down here,
paste, and at the very end, add `[1].id` to use the id of the second color.

Done! Inside the controller... inside `connect()`, let's see if we can log
this: `console.log()`, get the top-level element - `this.element` - and read
its data: `.dataset.colorId`.

Let's give it a go! When we refresh... we can see the `data-color-id` in the
HTML and... over in the log... there's the id!

## Hello Values API

So this works great. But this is *such* a common thing to do - passing info from
your server into your Stimulus controller - that stimulus gives us a special system
for handling this... with a couple of *really* nice advantages. It's called the
values API.

Here's how it works. Step 1, add a `static values` property set to an object. Each
"value" that we want to allow to be passed into our controller will be a *key* in
this object. So we want a `colorId` value. Set this to the *type* that this will
be. In our case, `colorId` will be a `Number`.

As *soon* as we define this, we can magically access a `colorIdValue` property.
Let's log it: `console.log(this.colorIdValue)`.

Finally, to *pass* this value into the controller, we *will* use a data attribute,
but with a special syntax. It's `data-` the name of the controller -
`color-square` - the name of our value - `color-id` - the name is `colorId` in our
controller, but it will be `color-id` in HTML - then `-value=` the actual *value*.

I know what you're thinking: that is *ugly*! Don't worry, we're going to learn an
*awesome* shortcut in a minute.

## Type Coercion

But let's try it. Move over and refresh. Woohoo! It works! And something *subtle*
just changed. If you dug deeper, you'd find out that the `2` in the log is a `Number`
type. But before we started using the values API, the `2` was a *string*!

That second part makes sense. Technically, in HTML, data attributes *are* strings!
If you read something from the `dataset` property, it will *always* be a string.

But the values API allows us to set the *type* for each value. It then handles
*converting* the string *into* that type. We can use things like `Object`, `Array`,
`Boolean`, or any other JavaScript type.

## The stimulus_controller() Method

I *love* the values API and it has one *sweet* trick up its sleeve that we'll
learn about in the next video. But the syntax, woof.

So far, we've created all of our `data-controller` elements by hand... because...
it's *pretty* simple to write `data-controller="color-square"`.

But WebpackEncoreBundle gives us a *shortcut* method. Check it out: inside
the element where we want the `data-controller` to be, add
`{{ stimulus_controller() }}` and pass the name of the controller: `color-square`.

Then, for the second *optional* argument, we can pass an array of *values*. We
have one: `color-id` set to the long `addToCartForm.vars` line.

Celebrate by deleting `data-controller` and the value.

*This* will give us the *exact* same result as before. We can see it: inspect
element, find `data-controller` and... refresh! Sweet! It *does* look the same
as before *and* we still get the log.

I love this feature because the values API is incredibly powerful and this removes
*all* the pain of using it.

## Values are HTML Escaped Automatically

Oh, and this function *also* automatically escapes each value so it's safe to use
in an HTML attribute. So if a value contains a double-quote, it won't break your
page. We'll see this later when we use the values API to pass props to a React app.

## Values Names are Normalized

The function *also* normalizes the value *names* automatically. If you want, you
can use `colorId` here... and we don't even need the quotes anymore. This is
nice because it now exactly matches the name of the value inside the controller.

When this renders - I'll refresh and go back to Elements - it *still* outputs the
same attribute name... and it all still works.

Next: let's use this new `colorIdValue` property to pre-select the color. Thanks
to the organization of our controller, that's going to be pretty easy. And thanks
to a feature of the values API that we have *not* seen yet, we're going to end
up with *less* code after adding the new feature. Cool.
