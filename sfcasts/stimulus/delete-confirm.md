# Form Submit Confirmation Controller

Let's add a few items to our cart, like a floppy disk - gotta have those - and
maybe also some CD's so I can burn a mixtape for Leanna. Now head to the cart page.

A user can already remove an item from their cart. Open up the template to see
how: `templates/cart/cart.html.twig`. Scroll down a bit... here it is... around
line 50. The "remove" button is inside a form. When the user clicks, the form
submits and the controller removes the item from the cart. It's super smooth and
super boring. I love it!

## A Massively Re-usable "Submit Confirm" Controller

But *now*, I need to enhance this. When the user clicks "remove", I
want to open a modal where the user can *confirm* that they want to remove the
item. In fact, this is going to be even *cooler* than it sounds because the
Stimulus controller we're about to create will be re-usable across *any* form
in our *entire* app. Want to pop up a confirmation before the user submits a
checkout form... or change password form? This *one* controller will be able to
handle *all* of those cases.

Let's get to work. Start by creating the Stimulus controller. In
`assets/controllers/`, add a new file called, how about,
`submit-confirm_controller.js`. I'm calling this `submit-confirm`... and not
"delete-confirm" or "cart-remove-confirm" because it will be reusable on *any*
form.

Start the normal way: `import { Controller } from 'stimulus'` and then
`export default class extends Controller` with a `connect()` method to make sure
everything is hooked up: `console.log()`... a dinosaur (🦖).

[[[ code('2ef7dd1308') ]]]

Next up, go activate this in the template. Adding it to the `form` tag should be
fine: `{{ stimulus_controller('submit-confirm') }}`.

[[[ code('41d41ea60b') ]]]

Let's make sure it's connected! I'll re-open my console.. refresh and... roar!
We even see *two* dinosaurs because there are *two* different controllers on this
page. My 4 year old son would be *thrilled*.

## Hello SweetAlert2

To create the actual modal, search for [sweetalert2](https://sweetalert2.github.io/).
I *love* this library. It's an easy - but highly customizable - alert system. If
you scroll down a bit... one of these examples is for a modal that confirms
deleting something. Here it is. This is almost exactly what we want.

Let's go get this library installed. Spin over to your terminal and run:

```terminal
yarn add sweetalert2 --dev
```

## Adding the "submit" Action

Before we *use* that new library, let's set up the *action* on our form: when the
user submits the form, we want to run some code.

In the template, on the form, add `data-action=""` then the name of our
controller - `submit-confirm` - a `#` sign and... let's have this call a new
method named `onSubmit`.

[[[ code('bdba7ee1b5') ]]]

Copy that, then head over to our controller. Rename `connect()` to `onSubmit()`
and give it an `event` argument. Start by calling `event.preventDefault()` so
that the form doesn't submit immediately. Then let's `console.log(event)` so we
can see this working.

[[[ code('7e55ec1a64') ]]]

Head back over, refresh, hit remove and... awesome! The submit event *is* being
triggered. Nothing can stop us... except, maybe typos!

*Now* let's bring in SweetAlert. Back over on its docs, copy the entire delete
example and, in the controller, remove the log and paste.

Oh and this `Swal` variable needs to be imported: `import Swal from 'sweetalert2';`

[[[ code('01d554bfda') ]]]

Yay! Let's try it. Head back over to our site, refresh and hit remove. Tada!
That's *so* cool! If we click cancel, nothing happens. And if we click yes,
delete it... we get this other message. But it's not *actually* removing the item...
yet.

Look back at the code. Here's how this works: when you click a button, the
`.then()` callback is executed. That's why we saw that second message: on confirm,
it called `Swal` again.

To make this actually *submit*, replace the `Swal.fire()` with `this.element` -
which will be the `form` - `.submit()`.

[[[ code('64017b801c') ]]]

That's it! Oh, and if you're thinking:

> Hey! Won't this cause an infinite loop... where we call `submit()` and that
> causes a `submit` event... that triggers our `submit` action... which will then
> open SweetAlert again?

Fortunately... that will *not* happen. When you call `.submit()` on a form element,
the form *does* submit, but the `submit` event is *not* dispatched. And so, our
action method will *not* be called again. That's just how JavaScript and the DOM
work - not a Stimulus thing. I say that a lot.

Anyways, let's see if this works! Refresh, click remove and this time confirm.
Woohoo! The form submitted, the page reloaded, and the item is gone!

But I think we can make this even *more* awesome. How? By making our controller
*configurable* - like the text that it displays - so we can *truly* reuse it
anywhere in our app. Even in this situation, saying "yes, delete it" on the button...
when you're *actually* removing an item from a *cart*... it doesn't really make
sense.

And as an extra bonus, we're going to add an option to make the form submit
via Ajax. That's all next.
