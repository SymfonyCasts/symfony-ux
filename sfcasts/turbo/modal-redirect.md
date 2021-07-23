# Full Page Redirect from a Frame

Our Turbo-frame-powered modal is now *almost* perfect. When we submit successfully,
it closes the modal. But... dang! That's *all* it did. The product list did *not*
update... so it's not *super* obvious that this worked!

Look at the console log of the event for the successful form submit. Let's see.
Inside `response`, ooh! We can see what URL the frame was redirected to! You
can also get this from `fetchResponse`: this `fetchResponse.location` is an object
that points to the final, redirected URL.

So the reason we're looking at this is that what we *really* want to do is, after
the form submits successfully, read this URL and navigate the *entire* page to
it with Turbo! We want a frame that's, sort of a "hybrid". We want the form submit
to *stay* in the frame... but then once the submit is successful, we want to navigate
the whole page to the redirected URL as if we were *not* in a frame.

## Navigating the Redirect with Turbo

And... yea! We can do that! At the top of the controller, import Turbo:
`import * as Turbo from '@hotwired/turbo'`.

Below, remove the `console.log`, then `Turbo.visit(fetchResponse.location)`.

Let's do this! Refresh, open the modal, typy, typy, submit and... cool! The whole
page navigated to the reviews page! Oh, and back in our code, we can remove
`this.modal.hide()`. We don't need that anymore: we're navigating the entire
page, so that will naturally replace the modal.

## "Binding" this for a Listener Method

I'm pretty happy with this, but let's clean things up a bit. Copy the code inside
the arrow function, scroll down, and create a new method called
`beforeFetchResponse()` with an `event` argument. I'm doing this for readability.

In `connect()`, call that. We don't even need an arrow function: just reference
`this.beforeFetchResponse`.

There *is* a problem with this... but let's try it! Refresh, go back to the admin
page, open up the modal and fill this out with real data. Submit!

It didn't redirect! And we have that error back in the console. What happened?
It's not super obvious at first, but in our new method, the `this` variable is
no longer referencing the `controller` object. This is the classic problem with
callback functions, and we normally work around "this" by passing an arrow function.
But if you *do* want to point directly to the method, you *can* by *binding* the
method.

Check it out: say `this.boundBeforeFetchResponse` - I'm actually creating a new
property `= this.beforeFetchResponse.bind(this)`. Then, below, point to the
bound method.

This creates a new property that points to the method.... but where we have
*guaranteed* that the `this` variable in that method will point to `this` object.
That's the job of `bind`. And this isn't a Stimulus problem, it's a problem you
run into whenever you combine JavaScript, callbacks and objects.

It looks weird at first... but when we submit the form... it *does* solve our issue:
back to the good behavior!

## Disconnecting the Event Listener

Oh, but I do want to handle one small detail. Over in the controller, add a
`disconnect()` method. Then copy the `document.addEventListener()` line, paste,
and change it to `document.removeEventListener()`.

Why are we doing this? If we add an event listener to a controller's element, like
`this.element`, then if that element is removed from the page, it's no big deal
that our listener is still technically attached to it. Nothing can interact or
trigger events on that element anymore. And your browser will probably garbage
collect that element - and the listener - anyways.

But if we add an event listener to the `document`, then *every* time a new
`data-controller="modal-form"` appears on the page, our connect method will be
called and we'll attach yet *another* listener. Even after a controller's element
is removed from the page, its `beforeFetchResponse()` would *still* be called!

So, to be the responsible developers that we are, we *remove* the listener in
`disconnect()`, which is called when the element attached to this controller is
removed from the page.

## Changing the Redirect Back to the List Page

*Anyways*, to put the cherry on top of our new feature, head back to
`ProductAdminController`. Change the redirect *back* to
`product_admin_index`, which just makes more sense.

Time to try the entire process. Go to the admin area and do a full refresh. Click
to open the modal - that loaded via the frame - hit save - that submitted via the
frame - and if fill in some real data. This is going to submit - like normal -
*to* the frame. Then, we'll detect that it was *successful* and... boom! The new
product shows up! That's because we just *navigated* to this page with
Turbo. That's smooth.

Next: we just did something pretty custom. We submitted a form *into* a turbo
frame... but then navigated the entire *page* on success. This is not something
a turbo frame does natively... but it's kind of handy. So let's add a reusable
way to do this whenever we want.
