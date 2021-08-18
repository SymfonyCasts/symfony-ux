# Multiple Updates in one Stream

Our goal is to be able to update the quick stats area *and* the reviews area all
at once. We can't do that by redirecting or returning a normal HTML page... because
that would only affect the reviews frame. So let's *continue* to return a
stream... but a stream where we update the quick stats area *and* the reviews.

The entire content of `_reviews.html.twig` lives inside of an element with a
`product-review` id. So, in `reviews.stream.html.twig`, add a *second*
`<turbo-stream>`. Yup, we can include as *many* instructions as we want in a
stream. Set the `action=""` to `replace` and the `target` to `product-review`, the
id of the element that surrounds the reviews area. Inside, include the reviews
template. Oh, but don't forget to include the `<template>` element - I'll remember
that in a minute.

[[[ code('0e2c691015') ]]]

We're using `replace` instead of `update` because `_reviews.html.twig` *contains*
the target. So we want to *replace* the existing `product-review` element
with the *new* one... instead of just updating its `innerHTML`.

Before we try this, I'll go back to `reviews.stream.html.twig` and add the
`<template>` element. If you *do* forget this, you'll get a clear error that says
that a `template` element was expected.

Ok: move over and refresh. Let's add another glowing review... and submit. Yes!
It worked! I see my new review! But... the form is gone.

## Adding a Success Message

As *so* often happens... this makes total sense. *Before*, the frame was being
redirected to the reviews page. So it was being redirected to this page here...
and *this* page contains a *fresh* form. So, naturally, the fresh form showed up
at the bottom of the reviews frame after successfully submitting a review.

But now, over in `reviews.stream.html.twig`, when we render `_reviews.html.twig`,
if you look at that template, we are *not* passing in a `reviewForm` variable. And
I already have logic here that checks to see if that variable exists and conditionally
renders the form. So, in our case, it renders nothing.

We *could* create a `reviewForm` object in the controller and pass it into here.
But, I kind of like this... except that having a success message would help a lot.

So let's see: we check for `reviewForm` and we also check to see if the user is
*not* logged in. Add an else on the bottom with a success alert. In our situation,
the only way to get here is if the form *was* just submitted successfully. But
you could also pass a `success` variable to the template to be more
explicit.

[[[ code('2c88e82fa2') ]]]

Anyways, let's test this thing out with *another* glowing review. When we submit...
that's *lovely*.

## A Link to Reload the Form

I'm having too much fun so here's a challenge. Imagine we want to add a link below
this success message to "Add another review". When we click it, it should load
a fresh form right into the frame. How could we do that?

Well... that's almost disappointingly easy! Remember: we're inside of a
`turbo-frame`... so all we need to do is add a link in the frame that navigates
us to the review page... because the review page *renders* this frame
*with* a fresh form!

Check it out: right after the success message, add an anchor tag with
`{{ path() }}` to generate a URL to the `app_product_reviews` route. This needs
an `id` wildcard set to `product.id`. Put some text inside.

[[[ code('1f6b3c801f') ]]]

Move back over, refresh...  and, once again, profess your love - or maybe disgust -
for this product: your call. Submit. There's our success message. When we click
this normal link... yes! That was awesome! Go team streams and frames!

## Checking for the Stream "Accept" Request Header

Finally, there's *one* last detail I want to handle... and it's minor. Imagine if,
for some reason, this review form were submitted without JavaScript. And so it
performs a normal full page submit, not a submit through Turbo.

Until now, that was *totally* okay! Our controller saves the new review and then
redirected to a legitimate page. But now we're returning this bizarre stream HTML...
which our browser wouldn't know what to do with... it would probably just render
it onto the page... which is not great!

Fortunately, whenever Turbo makes an Ajax request, it adds an `Accept` header to
the request that *advertises* that it supports Turbo streams. We can check
for that in the controller.

Here's how it looks: wrap our stream render with if
`TurboStreamResponse::STREAM_FORMAT` equals `$request->getPreferredFormat()`.

[[[ code('eaa99b1e97') ]]]

That's it. This preferred format thing basically looks at the `Accept` request header
to see if the request supports turbo streams. All Ajax requests made through
Turbo *send* this header.

If the request *does* support streams, then... we return a stream! If it doesn't,
we do our normal behavior: redirect the page. So once again, this will work fine
without JavaScript. Also, even though I've not done *any* work with it yet, Turbo
can also be used to build Native iOS or Android apps: you can read about it in
their docs. Streams don't really make sense in that context, so coding like this
also makes sure your code supports native apps... if you ever choose to go in that
direction.

Next: let's have some fun with Turbo Streams! I want to see if we can create
and process them manually in JavaScript. Apart from being cool, this will give us
a better understanding of how streams work and a better appreciation for the next
big part of streams that we'll discuss after.
