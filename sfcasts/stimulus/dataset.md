# Element.dataset

To make our widget fully work, when we click a color, we need to change the
*real* select element's value. That way, when we submit, the form will POST the
correct color. Inspect element near the `select` and look at its options: each value
is the *id* of that color in the database. So somehow, when we click a color square,
we need to know the *id* of that color so we can set it as the select element's
value.

## Adding our Own Custom data- Attributes

Fortunately, there's a native way to add extra information to DOM elements:
data attributes!

Over in the template, find the button and add a new data attribute:
`data-color-id` equals `{{ color.id }}`.

[[[ code('7da1a12889') ]]]

This is the first time that we've used a data attribute that has *nothing* to do
with Stimulus. We're just inventing this for our own purposes. The only rules
about data attribute names is that they must, of course, start with `data-`
and they must be all lowercase. You also usually see dashes between words like
`color-id`.

Over in our controller, we *could* read the attribute manually... but we don't
have to! JavaScript has a built-in way to read `data-` attributes: it's
the `dataset` property.

At the bottom of `selectColor()`, `console.log(event.currentTarget)` - to get
the button - then `.dataset.colorId`.

[[[ code('bb797c95f0') ]]]

Notice that the `color-id` from the HTML becomes `colorId` inside this `dataset`
property. This is... once again, *not* a Stimulus thing. This is just how data
attributes work.

Let's test it out. Open the console and... when I click, yes! We see ids!

## Adding the select Target

Now that we've got that, the next step is to find the `select` element. And,
*whenever* we need to find something, it means that we need a *target*.

Over in the controller, add a second target called, how about, `select`.

[[[ code('d426543d00') ]]]

Then, in the HTML, add that target. Oh... but this is trickier because the
`form_widget()` function is rendering the `select` element *for* us. No problem:
we can pass a custom attribute. Add a second argument to form widget, pass an
associative array, give this an `attr` key set to another associative array with
`data-color-square-target` set to `select`.

[[[ code('833ca82a7c') ]]]

Back over in the controller, assuming I haven't messed anything up, we *should*
now be able to reference the select with `this.selectTarget`. Set its value
with `.value = ` and then `event.currentTarget.dataset.colorId`.

[[[ code('7643bcc1db') ]]]

Ok! Let's try this thing! Refresh click and... awesome! As we click the colors,
the `select` updates. This is fun!

At this point... we're done! This *will* work! To celebrate, let's hide the
`select` element. We could do that in Twig or in Stimulus - it's up to you. If
you wanted to make your site work with and without JavaScript for some reason,
you could hide the `select` element and *show* the color boxes at the same time
in Stimulus.

Anyways, in the controller, add a `connect()` method. Then hide it with
`this.selectTarget.classList.add('d-none')`, which will add a `display: none`
since we're using Bootstrap.

[[[ code('51d8756a9d') ]]]

Go refresh. Oh, that is *lovely*. Let's add a red sofa to the cart. When we submit...
I think it worked! Go check out the shopping cart. It *did*!

Now that this is working, go check out our Stimulus controller. Yup, this whole
feature required about *15* lines of JavaScript! That's thanks to the fact that all
of our markup gets to live in Twig. Then, our JavaScript can stay lean and mean.

Over in the browser, click to go back to the sofa page. Next: I want to allow the
user to click a color again to *unselect* that color. That won't be too hard, but
to make it even easier, let's take advantage of the fact that we can store state
on our controller.
