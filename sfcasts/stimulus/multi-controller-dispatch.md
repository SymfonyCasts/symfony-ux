# Multi Controller Communication

When we confirm the modal, the delete form now submits via Ajax. That's cool...
but it created a problem. The row just sits there! And actually, it's more
complicated than simply removing that row. The total at the bottom also needs to
change... and if this is the *only* item in the cart, we need to show the
"your cart is empty" message.

Let me add a couple items to the cart... to keep things interesting.

## Need to Update the Page? Make an HTML Ajax Request!

You might be tempted to start trying to do all of this in JavaScript. Removing
the row would be pretty easy... though, we *would* need to move the
`data-controller` from the form to the div around the entire row so we have
access to that element.

But updating the total and - worse - printing the "your cart is empty" message
without duplicating the message we already have in Twig... is starting to
look pretty annoying! Is there an easier way?

There is! And it's delightfully refreshing. Stop trying to do everything in
JavaScript and instead rely on your server-side templates. So instead of
removing the row... and changing the total... and rendering the "your cart is empty"
message all in JavaScript, we can make a single Ajax call to an endpoint that
returns the new HTML for the entire cart area. Then we replace the cart's content
with the new HTML and... done!

## The Case for Two Controllers

But wait a second. Go look at the template. Right now, our `stimulus_controller()`
is on the `form` element... so each row has its own Stimulus controller. To be able
to replace the HTML for the *entire* cart area, does this mean we need to move
the `data-controller` attribute to the `<div>` that's around the entire cart section?
Because... in order to set that `innerHTML` on this element, it *does* need to
live inside our Stimulus controller. So, do we need to move our controller here?

The answer is... no: we do *not* need to move the `data-controller` attribute
onto this `div`. Well, let me clarify. We *could* move the `data-controller` from
our `form` up to the `div` that's around the cart area.

If we did that, we would need to do some refactoring in our controller. Specifically,
instead of referencing `this.element` to get the form, we would need to reference
`event.currentTarget`. So that's kind of annoying... but no huge deal... and it
*would* give us the ability to replace the entire HTML of the cart area after
making the Ajax request.

So why *aren't* we going to do this? The *real* reason I don't want to move the
controller up to this top level element is because, well... it doesn't really
make sense for our `submit-confirm` controller to *both* show a confirmation dialog
on submit *and* make an Ajax call to refresh the HTML for the cart area. Those are
two *very* different jobs. And if we *did* smash the code for making the Ajax call
into this controller, we would *no* longer be able to reuse the `submit-confirm`
controller for other forms on our site... because it would now hold code specific
to the cart area.

So what's the better solution? First, keep `submit-confirm` exactly how it is.
It does its small job *wonderfully*. I am so proud. Second, add the new
functionality to a *second* controller.

## Creating the Second Controller

Check it out: in `assets/controllers/` create a new `cart-list_controller.js`.
I'll cheat and copy the top of my `submit-confirm` controller... paste it here,
but we don't need sweetalert. Add the usual `connect()` method with
`console.log()`... a shopping cart.

[[[ code('cafbd15664') ]]]

The job of this controller will be to hold any JavaScript needed for the cart area.
So basically, any JavaScript for this `<div>`. In practice, this means its job
will be to replace the cart HTML with fresh HTML via an Ajax request after an
item is removed.

In `templates/cart/cart.html.twig`, find the `<div>` around the entire cart
area... here it is. Add `{{ stimulus_controller() }}` and pass `cart-list`.

[[[ code('bb1e2b7e31') ]]]

Ok! Let's make sure that's connected. Head over and... refresh. Got it.

## Controllers Are Independent

In Stimulus, each controller acts in isolation: each is its own little independent
unit of code. And while it *is* possible to make one controller call a method
directly on another, it's not terribly common.

But in this case, we have a problem. In our new controller, we need to run some
code - make an Ajax request to get the fresh cart HTML - only *after* the other
controller has finished submitting the delete form via Ajax. Somehow the
`submit-confirm` controller needs to notify the `cart-list` controller that its
Ajax call has finished.

So the big question is: how do we do that?

## Dispatching a Custom Event

By doing *exactly* what native DOM elements already do: dispatch an event. Yup,
we can dispatch a custom event in one controller and listen to it from another.
*And*, the `stimulus-use` library we installed earlier has a behavior for this!
It's called `useDispatch`. You *can* dispatch events *without* this behavior...
this just makes it easier.

Here's how it works. Start the normal way. In `submit-confirm_controller.js`,
import the behavior - `import { useDispatch } from 'stimulus-use'` then create a
`connect()` method with `useDispatch(this)` inside. This time, pass an extra option
via the second argument: `debug` set to `true`.

[[[ code('b9918eaa69') ]]]

I'm adding this `debug` option temporarily. All `stimulus-use` behaviors support
this option. When it's enabled, *most* log extra debug info to the console, which
is handy for debugging. We'll see that in a minute.

Head down to `submitForm()`. Here's the plan: if the form submits via Ajax,
let's wait for it to finish and then dispatch a custom event. Do that by adding
`const response = await`... and then we need to make the method `async`.

[[[ code('e2adebc6d9') ]]]

To dispatch the event, the `useDispatch` behavior gives us a handy new
`dispatch()` method. So we can say `this.dispatch()` and then the name of our
custom event, which can be anything. Let's call it `async:submitted`.
You can also pass a second argument with any extra info that you want to
attach to the event. I'll add  `response`.

[[[ code('123a8b576c') ]]]

We won't need that in *our* example... but thanks to this, the `event` object
that's passed to any listeners will have a `detail` property with an extra
`response` key on *it* set to the `response` object... which might be handy in
other cases.

And... that's it! It's a bit technical, but thanks to the `async` on
`submitForm()`, the `submitForm()` method *still* returns a Promise that resolves
*after* this Ajax call finishes. That's important because we return that `Promise`
in `preConfirm()`... which is what tells SweetAlert to stay open with a
loading icon until that call finishes.

Anyways, let's try it! Spin over, refresh, remove an item and confirm. Yes!
Check out the log! We just dispatched a normal DOM event from our `form` element
called `submit-confirm:async:submitted`. By default, the `useDispatch` behavior
prefixes the event name with the name of our controller, which is nice.

Next: let's listen to this in our other controller and use it to reload the cart
HTML. As a bonus, we'll add a CSS transition to make things look *really* smooth.
