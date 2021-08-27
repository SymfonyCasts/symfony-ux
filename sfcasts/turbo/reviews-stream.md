# Turbo Stream for Instant Review Update

When we submit a new review, we update two different parts of the page. First,
the review list and review form. And second, the quick stats area up here.

Over in `ProductController`, in the reviews action, we do this by returning a turbo
stream: `reviews.stream.html.twig` is responsible for updating both spots.

Cool, but remember that the reviews list and review form live inside of a turbo
frame. And so, before we started messing around and doing crazy stuff with Turbo
Streams, we updated that section simply by returning a redirect to the reviews
page on success. The Turbo Frame followed that redirect, grabbed the matching
`<turbo-frame>` from that page and updated it here.

Unfortunately... as soon as we wanted to *also* update the quick stats area, we had
to change *completely* to rely on turbo streams. The problem is that we can't return
a turbo stream *and* a redirect from the controller.... so we chose to return a
stream... which means that the stream needs to update *both* sections of the page.

## Returning a Redirect And Publishing a Stream

Okay. So why are we talking about all of this again? Because now that we have
Mercure running, we *can*, in a sense, return two things from our controller. Check
it out: copy this dummy Mercure update code, remove it... and paste it down in
the success area.

We're updating the `product-reviews` stream, which is the stream that we're
listening to thanks to our code in `_reviews.html.twig`. Back in the controller,
instead of *returning* a stream, copy the render line, delete that section, paste
inside the update... and fix the formatting. Oh, also change this to `renderView()`:
`render()` returns a `Response` object... but all we need is the *string* from
this template. That's what `renderView()` gives us.

Thanks to this, our controller will now redirect like it did before... but it
will *also* publish a stream to Mercure along the way.

Let's try it. Refresh the page... and scroll all the way down to the bottom. I
want to trigger the weather widget Ajax call just so that we can cleanly see what
happens with the network requests when we submit. Clear out the Ajax requests...
then add a new review.

Cool! It looks like that worked! Check out the network requests. The first is the
POST form submit. This returned a redirect, the frame system *followed* that
redirect, found the frame on the next page, and updated this area. The normal
Turbo Frames behavior. *Then* our *stream* caused the quick stats area to update...
and it also re-updated the reviews area... because, right now, our stream template
is still updating *both* things.

## Only Streaming the Quick Stats

So probably we could stop streaming the `_reviews.html.twig` template... since
the turbo-frame is taking care of that part of the page. *We* only need to focus
on updating the quick stats.

Let's try this again. Right now we have 16 reviews. Head down and add the 17th.
Ah! Silly validation! Type a bit more and submit. Yes! It still works!
The behavior *is* slightly different than before: it renders a new review form...
because that's what's rendered inside the `<turbo-frame>` on the redirected page.
And... up above, the quick stats area *did* update.

So this is a really pure example of a turbo-stream in action. Inside of our
`ProductController`, we can just redirect like normal, which powers the
turbo-frame. Then, the minute that we realize that we need to update a *different*
part of the page - something *outside* of the frame - we can do that through
Mercure.

## Updating the Page of Every User

But this is even cooler than it looks at first. In `reviews.stream.html.twig`,
temporarily put *back* the `product-review` stream.

Back at your browser, copy the URL and open this page in a second tab. Make
sure both pages are refreshed. Ok: both show 17 reviews. In the original tab,
scroll down and submit review number 18. It *does* show up here: no surprises.

Now check out the other tab. The quick stats *also* update here! And, down below,
yup! There's review number 18! That's amazing! Sure, I'm sitting on one computer
with two tabs open. But if two people - on opposite sides of the planet - were
both viewing this page at the same time, the *same* thing would happen. When we
post a new review, *everyone's* page is updated!

This opens up a new possibility for turbo streams. We already know that we can
use streams to update any part of our page, like something that's outside of the
frame that we're currently working in. But we can *also* use streams to update
*any* part of *any* user's page... so that when a user in Belgium adds a new review,
a different user in Japan - who was already on that page - will instantly see it.

## Making Update Ids Specific to the Product

But now, in the second tab, navigate to a different product. Back in the first,
post review number 19. When I submit, this, of course, works. But check out
the second tab. Woh! This product should *not* have 19 reviews... and all of these
reviews are for the *other* product, not this one! Refresh. Yup! This product
has *way* less reviews. Our stream update is affecting *every* product page!

And... this makes sense. If you're on a product page - *any* product page - then
you're listening to the `product-reviews` Mercure topic. When we publish an update,
we target the `product-quick-stats` and `product-review` elements... both of
which exist on *every* product page!

Fortunately, this is simple to fix. In `_reviews.html.twig`, we need to make sure
that every element that we target with a turbo stream has a dynamic part in it
so that it's *specific* to that product. In the `id` attribute, change it to
`product-{{ product.id }}-review`.

In `reviews.stream.html.twig`, do the same thing so they match. Repeat
this for the quick stats, which lives in `show.html.twig`... here it is. Add
`{{ product.id }}` inside the id. Copy that... and in the stream template,
add it here too.

Perfect. If two users are viewing two different products, they will *still* both
be listening to the same Mercure topic. When a review is posted to the first
product, the second user *will* receive the update... but they won't have any elements
matching those ids on their page. So, it will do nothing.

Click to post another review. Ah! That killed the frame! Of course: we just changed
the id of the frame... so we need to refresh. Post one more review. It shows up here...
but it did *not* affect the other page.

Ok: thanks to the new system, we can simplify our turbo stream even more to
deliver *exactly* the updates we want to every user. That's next.
