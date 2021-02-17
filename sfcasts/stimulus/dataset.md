# Element.dataset

To make the color selector fully work, when we click a color, we need to change
*real* select element's value. That way, when we submit, the form will POST the
correct color. Inspect element near the select and loop at the options: each value
is the id of that color in the database. So somehow, when we click a color square,
we need to know the *id* of that color so we can set the select element's value
*to* that.

## Adding our Own Custom data- Attributes

Fortunately, there's a native way to add extra information to DOM elements:
data attributes!

Over in the template, find the button and add a new data attribute:
`data-color-id` equals `{{ color.id }}`.

This is the first time that we've used a data attribute that has *nothing* to do
with Stimulus. We're just inventing this data attribute for our own purposes.
The only rule about data elements is that they must, of course, start with `data-`
and then the rest of the string needs to be lowercase. You also usually see
dashes between words like `color-id`.

Over in our controller, we *could* read the attribute manually... but we don't
have to! JavaScript has a built-in way to read data from `data-` attributes: it's
the `dataset` property.

At the bottom of `selectColor()`, `console.log(event.currentTarget)` - to get
the button - then `dataset.colorId`.

Notice that the `color-id` from the HTML becomes `colorId` inside this `dataset`
property. This is... once again, *not* a Stimulus thing. This is just how data
attributes work.

Let's test it out. Open the console and... when I click, yes! We see the ids!

## Adding the select Target

Now that we've got that, the next step is to find the `select` element. And,
*whenever* we need to find something, it means that we need a *target*.

Over in the controller, add a second target called, how about `select`.

Then, in the HTML, add that target. Oh... but this is trickier because the
`form_widget()` function is rendering the `select` element for us. No problem:
we can pass a custom attribute. Add a second argument to form widget, pass an
associative array, give this an `attr` key set to another associative array and,
inside, add `data-color-square-target` set to `target`.

Back over in the controller, assuming I've set this all up correctly, we should
now be able to reference this via `this.selectTarget` and then we set its value
with `.value = ` and then `event.currentTarget.dataset.colorId`.

Ok! Let's try this thing! Refresh click and... awesome! As we click the colors,
the `select` updates. This is kinda fun!

At this point... we're done! This *will* work! To celebrate, let's hide the
`select` element. We could do that in Twig or in Stimulus - it's up to you. If
you wanted to make your site work with and without JavaScript for some reason,
you could hide the `select` element in the controller and *show* the color boxes
at the same time.

Anyways, in the controller, add a `connect()` method. Then hide it with
`this.selectTarget.classList.add('d-none')`, which will add a `display: none`
since we're using Bootstrap.

Go refresh. Oh, that is *lovely*. Let's add a red sofa to the cart. When we submit,
it looks like it worked! Go check out the shopping cart. It *did*!

Now that this is working, go check out our Stimulus controller. Yup, this whole
feature required about 15 lines of JavaScript. That's thanks to the fact that all
of our markup gets to live in Twig. Then, our JavaScript can stay lean and mean.

Over in the browser, click to go back to the sofa page. Next: I want to allow the
use to click a color again to *unselect* that color. That won't be too hard, but
to make it even easier, let's take advantage of the fact that we can store state
on our controller.
