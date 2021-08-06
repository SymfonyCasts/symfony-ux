# Streams: Reusing Templates

When we submit the product review form, instead of redirecting like we were
before, we're now returning this `TurboStreamResponse`. When the Ajax call finishes,
Turbo *notices* that we're returning this type of response... instead of a real HTML
page. And so, instead of handling the HTML like a frame *normally* would, it passes
it to the Turbo Stream system.

Right now, we're using it to update the quick stats area of the page with this
random HTML. If you refresh, the *real* goal is to update the review count and
review average as soon as the new review is submitted.

## Reusing Templates in a Stream

To do that, without repeating ourselves, over in `show.html.twig` - the template
for the product show page - copy the quick stats code... and create a new
template in `templates/product/` called, how about `_quickStats.html.twig`. Paste
the code here.

We can now *reuse* this in two places. In `show.html.twig` include it:
`product/_quickStats.html.twig`

Then, in the *stream* template, do the same thing!

Pretty cool, right?

Let's try that. Refresh. *This* still works and shows 10 reviews. Scroll down
and add review number 11. Submit and... oh! The entire reviews section is gone!
My web debug toolbar is angry: that Ajax call returned a 500 error.

Open up its profiler.

> Variable product does not exist

Coming from `_quickStats.html.twig`. Of course, the problem is that we're
including `_quickStats.html.twig` from `reviews.stream.html.twig`. In
`ProductController`, we're not passing any variables to that template... but
in quick stats, we *need* a `product`!

No problem: pass product set to `$product`, which will make it available all
the way into the quick stats template.

Okay: take two. Refresh again. We now have *11* reviews... so let's go add
number 12. Submit. The reviews section is still weird - but that's ok. Scroll
up. Yes! Our Turbo Stream updated the area with the real data! That is *so* cool!

## Return a Stream or HTML, not both (yet)

*Now* we need to fix the reviews area... because showing a filled-in form with
a disabled button... doesn't exactly scream "the review was successfully saved".

The entire reviews area lives in `templates/product/_reviews.html.twig`... and
*all* of it is surrounded by the `product-review `frame. So both the list
of reviews *and* the form are in this frame.

Thanks to this, *before* we started messing around with turbo streams, after submit,
we redirected to the reviews page. That page includes this template with this frame.
And so, *the* entire frame updated, including the *new* review and a fresh,
empty form.

At this point, we have two choices. First, we could redirect on success like we were
doing before and let the normal turbo frame logic do its magic. *Or* we can return
a turbo stream and update whatever elements we want. *But*, we can't do *both*.
Our controller can only return *one* thing, so we need to choose between returning
a redirect *or* returning a stream.

Well, actually we *can* do both... but let's keep that a secret between you and
I for now. It's a topic for later... and requires one extra piece of technology.

So... what can we do? Well, if we want to be able to update the quick stats area
*and* the reviews area, the answer is to return a stream that contains *multiple*
instructions. Let's see how next.
