# Stream Everything

Coming soon...

Yeah. When we submit the product reviews form, instead of redirecting like we were
before, we're now returning this `TurboStreamResponse`. When the AGS call finishes
turbo notices that we're returning this type of response instead of an HTML page. And
so instead of handling the HTML like a frame, normally would it passes it to the
turbo stream system right now we're using it to update the quick stats area of the
page with this random HTML. If you refresh the real goal is to automatically update
the review count. And the review average, as soon as the newer review is submitted to
do that without repeating ourselves over in `show.html.twig` a template for the
product show page, copy the quick stats code, and then you create a new template in
`template/product/` called how about `_quickStats.html.twig` paste
that there. Now we can reuse this in two places first and `show.html.twig`, we can
include `product/_quickStats.html.twig`

And then on top of that, because in our new reviews stream, we can do this same
thing. All right, let's try that. So refresh, this still works. We have a 10 reviews
and an average of 4.1 scroll down and let's add the 11th you review submit. And oh,
the entire review section is gone. My web debug toolbar, is it red? Ah, a 500 air.
Let's open that up. Of course, variable product does not exist coming from our 
`_quickStats.html.twig`. Of course, the problem is that we're including quick
stats from `reviews.stream.html.twig` but he our `ProductController`. We're not
passing any variables in there. So our quick stats needs a `product` variable. So no
problem. We'll pass product here, set to product, and that will pass all the way into
the quick stats template. Okay. Take two. I'll review the refresh page again. That
did works. We now have 11 reviews.

Let's start adding the 12th review submit. And this page is this part of the page is
still stuck. That's okay. Let's scroll up. Yes. It automatically updated this area
with the real data. That is so cool, but it's not quite yet while we want, because we
need to fix the reviews frame. This form just sitting here is not going to work this
entire area lives in the `templates/products/_reviews.html.twig`
two months, wait template. Now as a reminder, this entire template lives inside the
`product-review `frame. So both the PR review list and the form. Thanks to this. Before
we started messing around with turbo streams after submit, we redirected to the
reviews page and that included this template with this frame. And so because of this,
the entire frame updated with the new review listing and a fresh empty form.

So we kind of have two choices. One we could redirect on success like we were doing
before and let the normal turbo frame logic do its magic. But if we do that or we can
use a turbo stream to update whatever we want, but we can't do both. We need to
choose between these stream or redirecting so that the frame can do its job. Well,
actually we can do both, but that's a topic for a little later for now. Since we're
choosing to return a turbo stream response, we need to update everything via that
turbo stream. In other words, we also need to update this reviews area via the
stream, and that's actually pretty easy. The entire contents of `_reviews.html.html`
is inside of an element with a `product-review` id.

So in reviews that stream to reg add a second `<turbo-stream>`. Yup. You can return
as many instructions as you want this time set the `action="""` to `replace` and the `target`
two `product-review` the ID, uh, of that element inside include that template
`product/_reviews.html.twig`, okay. We're using replace
instead of update because the `_reviews.html.twig` template contains
the outer element. So we will replace the existing `product-review` element with the
new one instead of just updating its innerHTML. All right. Testing time refresh and
wow. I, I really love his product

Summit and [inaudible]

Oh, before we try this, go back to reviews that stream to Asia. Sorry. I forgot my
`<template>` elements. If you forget that, you'll get a very clear error that says you
forgot your template element. So let's not do that. All right. Now I move over. I
will refresh this product is really wild. Wowing me that's hit submit, and yes, it
shows up, but the form is gone as so often happens. That makes complete sense
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

