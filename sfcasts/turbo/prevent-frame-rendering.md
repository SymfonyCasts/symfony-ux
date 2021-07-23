# Prevent a turbo-frame from Rendering

As usual, I'm going to complicate things! But I have a good reason: I really
want us to understand the how to get the most out of frames... and we have a
potential bug hiding.

Head over to `ProductAdminController`. As we just talked about, this redirects to
the `product_admin_index` page. Let's pretend that we want to redirect this to the
"reviews" page for the new product. Change this to `app_product_reviews` and pass
the `id` wildcard set to the new id: `$product->getId()`.

Cool. But this change won't affect our modal. When the modal submit is successful,
we're simply closing the modal, staying on the page and we're completely *ignoring*
the frame inside the now-closed modal. This new redirect would only affect us
if we want directly to the `/new` admin page and submitted the form *outside* of
a frame.

So, since this won't affect us, it shouldn't break anything! Famous last words.
Refresh, open the modal, add some details and submit. Oh! The modal *did* close...
but we have an error in the console!

> Response has no matching `<turbo-frame id="product-info">` element.

Ah, the problem is that, even though we closed the modal, the `turbo-frame`
*still* followed the redirect to the product review page. Then, like it *always*
does, it looked for a `<turbo-frame>` with `id="product-info"` on that page, which
it doesn't have.

So what we *really* want to do is just... close the modal and tell turbo to *not*
follow redirect. Unfortunately, the `turbo:submit-end` event is too late to tell
Turbo to do that!

We could ignore this error... hack an empty turbo-frame onto the reviews page...
but let's fix this properly. It's a good challenge.

## Order of Turbo Events

When we submit this form, *four* events are triggered in this order:
`turbo:before-fetch-request`, `turbo:submit-start`, `turbo:before-fetch-response`
and finally `turbo:submit-end`. And *then* the frame is rendered.

But, wait a second. If the frame isn't rendered until *after* `turbo:submit-end`,
why is it too late to tell Turbo to *not* render the frame? The truth is that
`turbo:submit-end` isn't *actually* too late. The *real* problem is that Turbo
doesn't give us a way to *cancel* rendering from this event. But it *does* give
you this power from the event right *before* this one: `turbo:before-fetch-response`.

## turbo:before-fetch-response

This event is triggered right *after* the Ajax call finishes, actually after
*both* Ajax calls have finished: the POST and then second request to the redirected
page. But, the frame has *not* been re-rendered.

This time, we *do* need to attach the event to `document` because this event is
dispatched directly on `document`. For now, I'm going to *not* hide the modal.

Refresh, open the modal and fill out the form so we can see what the event looks
like for a successful form submit. Cool. In the console, we see *two* of these
events. The first happened when we opened the modal: that's the GET request to
load the form. The second is from the form submit.

Open this up and look at the `detail` property: it has a `fetchResponse` object
and inside of it that... awesome! A `succeeded` key and also a `redirected` key!
So it tells us if the request was successful and *also* if it was redirected.

So here's the plan: when this event happens, *if* a modal is open *and* the Ajax
call was successful *and* the Ajax call was a redirect, we'll *assume* that a form
submitted and hide the modal.

Back in the listener function, delete the code. Then, if *not* `this.modal` - so
if the modal has never opened - or if *not* `this.modal._isShown` - an internal way
to detect whether a modal is visible or not - then we don't need to do anything.
Just going to `return`.

But if the modal *is* open, set `const fetchResponse` to `event.detail.fetchResponse`:
that's the object we were just looking at. If `fetchResponse.succeeded` *and*
`fetchResponse.redirected`, then we're going to assume this was a successful form
submit and hide the modal.

If we stopped now, this would do the *exact* same thing as before... just with
more code. It would hide the modal... but then the frame would *still* try to render
and give us that annoying error. But there's a key difference between this event
a `turbo:submit-end`: *this* event is *cancellable*. In this event we're allowed
to say `event.preventDefault()`.

Normally, we use `event.preventDefault()` to prevent form submits prevent link
clicks. Some custom events - like this one - *also* allow you to call this method...
and it could mean *anything*. In this case, it communicates to Turbo that we would
like to *prevent* this response from rendering.

Let's try it. Refresh, open, fill out the form and submit. Yes! The modal closed...
*this* time with *no* error!

We're amazing! Oh, except... hmm... this still isn't quite what we want. The
modal closed... but the page didn't reload or refresh... so we don't see the
new product in the list immediately. Let's fix that next and finish our
Turbo-powered modal system.
