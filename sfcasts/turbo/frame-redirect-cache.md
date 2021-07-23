# Frame Redirect Cache

Where else could we use our new turbo frame redirect system? Go to the cart.
On the featured product sidebar, we could leverage a frame right here. Right now,
this form submits to the whole page. And so, on success, the entire page is
redirected to that product with a nice flash message. I love it! That's exactly
the behavior I want.

But go back to the cart. This time, let's change the color and be annoying by trying
to buy a *negative* quantity. Hit add. It *still* changed the entire page...
which isn't as smooth as I'd like. It would be way cooler if the error could
show up in the sidebar on the cart page.

## Adding the turbo-frame

Time to add a frame! The template for this "add to cart" section lives over
at `templates/product/_cart_add_controls.html.twig`. This template is included
on two pages: the product show page and also over on the sidebar. When we submit
the form, as we just saw, it's handled by the product show page. This means that
if we added a frame around this entire template, when we submit, the response
*would* contain a matching turbo-frame, since the product show page is re-using
this template.

In other words... adding a frame here should just work. On top, add `<turbo-frame>`
with `id="add-to-cart-controls"`. Add the closing frame at the bottom.

Just *with* that, refresh the page and go to the cart. Submit with a negative quantity.
That is *so* much nicer. Now change to red change, set the quantity to 5 and
hit add.

Um, did that work? The color changed back and the quantity reset... and I don't
see any errors. But that wasn't very obvious. I also don't see the item in the
cart until I refresh.

So this makes sense: when we submit the form, it redirects to the product show page.
And *that* renders a success message - which we don't see - and an empty form.
This would all work much better if we could go *back* to the original success
behavior where we navigate the *entire* page after adding the item.

## Activating data-turbo-form-redirect

Fortunately, that's exactly what our new frame system does! Let's add the attribute
that we invented to this frame. I'll move it onto multiple lines to keep my sanity,
then add `data-turbo-form-redirect="true"`.

Testing time! Refresh the cart page. If we submit the form with errors, everything
stay s right here. But if we submit it *successfully*... yes! That redirected over
to the product show page!

## We Preventing the Snapshot Cache From Clearing!

Though... dang! There are two weird things going on. First, we're missing our
flash message! We'll talk about that later.

To see the second, watch the shopping cart header as we add more and more items
to our cart. Yikes! It jumps backwards and forwards!

This is a result of the preview system. When we submit this form, for *just* a
moment, it shows the cached preview of the page. For example, at this moment,
the cached version - which comes from the last time we navigated *away* from this
page - still holds the value 14. So when we hit add, it jumps back to 14 and
ahead to 16. Now, a page with 15 sits in the cache.

This is *not* normally a problem. If you submit a POST form with Turbo and have a
successful response, Turbo *automatically* clears it's internal snapshot cache. It
does that *precisely* to avoid this problem: a successful form submit typically
causes something to *change* on the server. So, to be safe, Turbo decides that
it shouldn't use any of its old, cached pages.

## Manually Clearing the Cache

So... if that's true, why are we seeing this problem? In `turbo-helper`, we're
calling `event.preventDefault()` in the `turbo:before-fetch-response` listener.
This tells Turbo to prevent rendering the page but it has a side effect: it
*also* prevents it from clearing its snapshot cache.

But now that we know that, it's no problem: we can clear it manually by saying
`Turbo.clearCache()`.

Refresh and watch the cart header. *Much* better.

## Bug with Not Clearing the Current Page

By the way, there *is* still one spot where this jumpy cart thing happens. Go
to the cart page and add an item. Now watch the number when I click back to the
shopping cart... it's 21 right now. See that? It temporarily jumped back to 20.

This happens due to, what I think is, a fairly straightforward bug in Turbo that
I hope will be fixed in the future. Here's the scoop. As we just talked about, when
you successfully submit a POST form, Turbo clears it's snapshot cache. And we even
manually did that a minute ago. But right *after* it clears the snapshot cache,
as we're navigating away, it re-caches the page that we just submitted.

This means that when we hit add, it clears the snapshot cache but then re-caches
this page with a shopping cart number set to 21.

This is pretty rare situation. To trigger this, you need a form that submits to
*another* page. And then the problem only happens if you navigate *back* to that
form later. I'm going to ignore this.

Next: the *bigger* weird issue with our new system is that, when we add an item,
it redirects... but there's no success flash message. This page actually *does* have
a flash message - we saw it a few minutes ago - it should be showing right here.
But something unexpected is happening behind the scenes that's hiding it.

Let's find out what next and improve our system to prevent it.
