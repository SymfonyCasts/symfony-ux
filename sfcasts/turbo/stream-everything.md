# Multiple Updates in one Stream

Coming soon...

Anyways, because I want to be able to update the quick stats area, let's *continue*
to return a stream. But in addition to updating the quick stats area, we can
*also* update the reviews area. And... it's pretty easy!

The entire content of `_reviews.html.html` lives inside of an element with a
`product-review` id. So in `reviews.stream.html.twig`, add a *second*
`<turbo-stream>`. Yup, we can include as *many* instructions as we want in a
stream. Set the `action=""` to `replace` and the `target` to `product-review`, the
id of the element that surrounds the reviews area. Inside, include the reviews
template. Don't forget the include the `<template>` element - I'll remember in
a minute.

We're using `replace` instead of `update` because `_reviews.html.twig` *contains*
that element. So we want to *replace* the existing `product-review` element
with the *new* one... instead of just updating its `innerHTML`.

Oh, before we try this, go back to `reviews.stream.html.twig`: I forgot the
`<template>` element! If you forget that, you'll get a very clear error that to
remind you.

Ok: move over and refresh. Let's add another glowing review and submit. Yes!
It worked! I see my new review! But... the form is gone.

As *so* often happens. That makes complete sense
previously when the frame was being redirected to the reviews page. So it was
actually being redirected to this page right here. That page contains a fresh form.
And so the fresh form showed up at the bottom of their page after we submit a review,
but now over in reviews, streamed at HR twig, when we render underscore reviews that
age two on that twig, if you look at that template, we are not passing in a review
form variable. And we have logic in there that basically says that that checks for
that and just skips, rendering the form. And so nothing renders. We could create a
review form object and pass it and pass it into here if we want. But I kind of like
this, except that a little success message down here would help. So let's see if
through form, we also check to see if the user's not logged in. So let's add a little
else here on the bottom. So what all else with an `alert-success` message.

Alright, let's try that out with another five star. Review it in our five star review
and oh, that's much better. Okay. I'm having too much fun. Okay. Let's think what if
we want it to, for some reason, add a link below this that I can click that would
load another review form. Well, that's really easy. Remember we're inside of a turbo
frame here. So all we need to do is add a link inside of this frame that navigates us
to the review page where this same frame exists with the fresh form. Check it out
over right after our success message. I'll add an anchor tag, curly curly `path()`, and
then we'll generate `app_product_reviews`. And then this needs an `id`, wildcard ID,
`product.id`. We'll put some text inside of here.

All right, check it out, refresh the page. Let's do a review. There is a success
message. And then when we just click this normal link, that loads that fresh and we
can do it again, go team frames and streams. Finally, there's one last detail I want
to handle. And it's minor. Imagine if for some reason this review form were submitted
without JavaScript. And so it performs a normal full page. Submit, not a submit
through turbo or the turbo frame until now that was totally okay. Our controller
saves the new review and then redirects to a legitimate page. But now we're returning
this bizarre stream HTML, which our server wouldn't know what to do with, and it
would just render it out of the page. Fortunately, whenever turbo makes an Ajax
request, it adds a header that advertises that it supports turbo streams. We can
check for that in the controller.

Here's how it looks wrap our stream, render with if `TurboStreamResponse::STREAM_FORMAT`
equals `$request` to make sure you have the request object, arrow,
`->getPpreferredFormat()`, and then I'll wrap the next line in curly braces. That's it. So
let's get for preferred format is looking for something called the `Accept` header. And
it's checking to see, trying to make sure that the request that's being made is said,
I accept the, um, turbo stream format. So if it doesn't, we return our stream. If it
doesn't, we do our normal behavior. So this, once again, it works just fine without
JavaScript. Next, let's have some fun with turbo streams by creating and processing
them manually in JavaScript. This will give us a better understanding of how streams
work and a better appreciation of the next big part of streams that we'll discuss
after

[inaudible].

