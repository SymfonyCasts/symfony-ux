# On Value Change Callback

Now that we've created a `colorId` value, we can pass that from the server into
Stimulus and read it as `this.colorIdValue`. Let's use that to select the color
square on load.

## Setting the Initial Color

Thanks to our organization, this will be no problemo. Replace the log with:
if `this.colorIdValue`: just in case we want to make this value optional. Inside,
call `this.setSelectedColor()` and pass `this.colorIdValue`.

[[[ code('ecc1e22e13') ]]]

I *do* love that we created that re-usable `setSelectedColor()` method! Let's try
this: fly over to the browser and... it... doesn't work? In the console, we have
a giant error:

> Cannot read property `classList` of undefined

... coming from `setSelectedColor()`.

## Watch out for the Stronger Values Types

Let's go take a look. I think it's coming from right here. For some reason, the
`findSelectedColorSquare()` method is *not* finding the element... which is *odd*.

Scroll down to it. Ahh. The problem is the stronger *type* that the
values API gave us. `element.dataset.colorId`, which just uses the normal
`dataset` functionality, will be a `String`. But `this.selectedColorId`
will now be a `Number`... because if we scroll up, we set it to `this.colorIdValue`,
which we know is a true `Number` type.

So our stronger type makes these not triple-equal each other. The easiest fix is
to use double equals.

[[[ code('c977b776e0') ]]]

In case you're wondering, at this time, there isn't anything like the values API
for individual elements *inside* our controller.

Anyways, let's try it. Refresh and... got it! The color green is pre-selected! And
if you temporarily unhide the `select` element itself... yep! That updated too.

## Value Change Callback

There's one last feature about the values API that we haven't talked about yet.
And it's *really* going to help us. In fact, it's going to let us to delete a
*lot* of code from our controller. It's called a change callback.

Very simply, we can tell Stimulus to automatically call a function whenever a
value changes, like when our `colorId` value changes. How? With a specially named
method.

Add a new method called `colorIdValueChanged()`. Inside, go steal the code from
earlier: `this.setSelectedColor(this.colorIdValue)`.

[[[ code('359b6f6a38') ]]]

And now we can *remove* the code inside `connect()`.

Here's the flow: on load, the `colorId` value will be read from our data attribute.
That will *change* the `colorId` value and cause our callback to be executed.
The naming of the method *is* important: it must be *exactly* named like this
for Stimulus to recognize it as a change callback.

Let's give it a go! Refresh and... yea, it *did* work! To make it more obvious,
click red, then reload. Back to green!

## Callbacks even Listen to Attribute Changes

Ready to have your mind blown? Find the `data-controller` element in your inspector.
2 is the id of the green item. Let's change it to 1, which is red. Woh! The
selected color square changed! Our callback is even executed when the value's
`data-attribute` is updated! That's bonkers.

## Using Values Instead of a Property

Look back at our controller. Now, I'm wondering something: do we really need both
a `selectedColorId` property *and* a `colorId` value? Don't they both... kinda
store the currently selected color?

Yep! And the answer is that we do *not* need both.

A value is basically a property with superpowers. Values have the ability to read
an initial value from a data attribute, support change callbacks *and* wear a
bright red cape.

Check this out: in the `colorIdValueChanged()` method, let's add all the
logic that we need to get this to work on its *own*. In other words, I want to
replace `this.setSelectedColor()` with code that does the same thing.

Start by setting the value on the select:
`this.selectTarget.value = this.colorIdValue`.

[[[ code('28865772c7') ]]]

The only other thing that we need to do inside  here is a loop over the color
squares to set the `selected` class correctly. Do that with
`this.colorSquareTargets.forEach()` and pass this an arrow function with an
`element` argument. Inside, we can use an if statement to figure out if we should
be adding the class or removing it: if `element.dataset.colorId == this.colorIdValue`
then we know this element *is* now the current color. Add the class with
`element.classList.add('selected')`. Else, remove the `selected` class.

[[[ code('a525c14155') ]]]

Nice! Up in `selectColor()`, we don't need to call `setSelectedColor()` anymore.
Instead, just set the value! Copy the `event.currentTarget` code and say
`this.colorIdValue = ` and paste.

[[[ code('cfae532906') ]]]

That's it! When we click a color square, `selectColor` will be called. Then we
set `this.colorIdValue` and *that* triggers our `colorIdValueChanged()` method.
Booya!

Test drive time! When we refresh... the initial color *is* selected. And when
we click... that works too! We *did* lose the ability to click a second time
to *unselect* a color - but we'll fix that in a minute.

Before we do, let's celebrate by removing a *ton* of code! We don't need
`setSelectedColor()` anymore... or `findSelectedColorSquare()`... or the
`selectedColorId` property. I'll remove that in a minute.

If you want to get back the ability to click again to *unselect* a color, we
can do that with a little extra logic in `selectColor`. Add `const clickedColor`
equals the `event.currentTarget` code.

For the next line, use the ternary syntax: `this.colorIdValue = `, *if*
`clickedColor == this.colorIdValue` - so, if the clicked color is *already*
selected - then set it to `null`. Otherwise set it to `clickedColor`.

[[[ code('0c70993240') ]]]

Test it out: refresh... then click green again. Gone!

## Our Tiny Controller

Go back to your editor. This is the *final* version of our controller. Oh,
after I remove the unused `selectedColorId` property... *now* this is the final
version of our controller.

And look at it! It's less than *30* lines of code and is *incredibly* readable.
*This* is how I want my JavaScript to look.

Head back to the homepage of our site. This has a functional search... but it's
*so* boring. Next: let's add an AJAX-powered "quick search" that shows
matching results under the search box as we type.
