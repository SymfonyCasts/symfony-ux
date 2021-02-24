# State in your Controller

If we click a color multiple times, nothing happens. I want this to *unselect*
that color. To accomplish this, we don't need to do anything special. On click,
we could look at the `currentTarget` to see if it *already* has the `selected`
class.

If you think about it, we're sort of storing "state" information - which color
is currently selected - inside HTML elements. Specifically, if we want to know
what the currently-selected color is, we need to check to see which color square
has the `selected` class.

That's okay... but people! Stimulus gives us an *object*! And that means we can store
info on it! We did this earlier with the "current count" on our counter controller.

## Adding a new State Property

So on click, let's start storing *which* color id is currently selected. At the top
of the class, invent a new property: how about `selectedColorId = null`.

[[[ code('808bdb62bb') ]]]

Then, down in `selectColor()`, create a variable - `const clickedColorId =` set
to the `event.currentTarget` code. Right below this, set the state property:
`this.selectedColorId = clickedColorId`.

[[[ code('8bf8c71863') ]]]

We don't really need a variable yet, but it will make life a bit easier in
a minute. Down at the bottom, instead of referencing the event code, just
use `this.selectedColorId`.

[[[ code('962c061830') ]]]

This by itself.... doesn't really do anything to help us. But we can now more
easily use the property to figure out if the color that's being clicked is
*already* selected.

Add an if statement near the top: if
`clickedColorId === this.selectedColorId`, then we know that we're clicking on a
color box that is *already* selected.

For this situation, copy the `classList` code from below, and make it
`event.currentTarget.classList.remove('selected')`. Also set
`this.selectedColorId = null` and `this.selectTarget.value = ''`, or `null` would
be fine. Then return.

[[[ code('1836c0ab3c') ]]]

So when we click the already-selected color, we go here. Else we do the normal logic.

Let's try it! Refresh and let's inspect element, find the `select` and temporarily
take off the `d-none` so we can see it.

Now, if we click red, it works! Click green, it works. Click green again... yes!
It loses the border *and* the `select` element changes.

## Reusable Controller Methods

Before we keep going, I want to reorganize things *just* a bit in our controller.
End the `selectColor` method early and move most of the logic into a new
`setSelectedColor()` method with a `clickedColorId` argument.

Then, call this from above: `this.setSelectedColor()`... and steal the
`event.currentTarget` code. We don't need a variable anymore.

[[[ code('fb1b62f9ae') ]]]

This isn't going to *quite* work yet, but I want to explain *why* we're doing
this. This *is* optional, but I like to have as many re-usable methods in my
controller as possible. The nice thing about `setSelectedColor()` is that it's
not dependent on the `event`: before we were reading `event.currentTarget`.

Now, *anyone* can call this method from *anywhere*, pass a color id and...
everything will just work! Well, it's going to work... once we finish refactoring.

We can't use `event.currentTarget` anymore. But this is actually
kind of cool! What we *really* need to find here is the *currently-selected*
color box... since we're inside an if statement where we've determined that the
user is tying to select a color that is already selected and *we* need to remove
its `selected` class.

Now, thanks to the `selectedColorId` property, we can find the "currently
selected color square" really easily! Let's add a helper method to do this:
`findSelectedColorSquare()`

[[[ code('d3a409fd36') ]]]

Inside `return this.colorSquareTargets.find()`. What we're going to do is loop
over all the color square targets and return the *one* whose `data-color-id`
attribute matches `this.selectedColorId`.

Pass `find()` a function with an `element` argument. I'm going to use the super
fancy single line syntax to return `element.dataset.colorId === this.selectedColorId`.

[[[ code('4803923f1d') ]]]

So this method will either return the Element if one is selected or `null`. I'll
add some docs above this to advertise that.

[[[ code('c1e388a074') ]]]

Let's go use the new method:
`this.findSelectedColorSquare().classList.remove('selected')`.
And... we have one more spot down here: where we *add* that class. Since we've
already set the new `selectedColorId` property, this will find the *new* element:
`this.findSelectedColorSquare().classList.add('selected')`.

[[[ code('70e00da1f5') ]]]

This shows off one of the nice things about storing state like `selectedColorId`:
we can create useful methods - like `findSelectedColorSquare()` - and call them
whenever we want.

Let's make sure I didn't break anything. Refresh, click red and click it again.
All good!

Next: there's one big feature of Stimulus that we haven't talked about and it's
actually brand *new* to Stimulus! It's the value's API.
