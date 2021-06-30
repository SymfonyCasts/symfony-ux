# <link rel="prefetch"

Looking at the code of this `prefetch` script, there is *another* way this can
be used. If you add a `data-prefetch-with-link="true"` attribute to a link, instead
of making an Ajax call, this will add a `<link rel="prefetch">` element to the
`head` tag of the page.

## Hello <link rel="prefetch"

What does that do? Great question! To explain, let's back up a little. So far, this
whole prefetch script here has been pure Turbo magic: it makes an Ajax call
and stores it into Turbo's snapshot cache. But actually, your browser has a
"prefetch" feature built into it! And *that* is what this `data-prefetch-with-link`
code is leveraging.

To see how it works. close this prefetch script and comment out its import. I want
to see how true prefetching works without *any* Turbo magic... because prefetching
can be used on any site - even if it doesn't use Turbo.

Here's the deal: imagine that, when a user goes to a specific page on our site,
we're *fairly* sure that you know what the next page - or pages - will be that the
user will go to. In that case, we can *hint* to the user's browser that. If it has
some extra time, it can *prefetch* that URL so that if the user *does* navigate to
it, it will load instantly from cache.

Let's try this. Add an item to your cart and then head to the cart page. It might
be obvious that, once a user visits this page, they often click the "Check out"
link next. So let's add a *hint* that the browser should "prefetch" that page.

## Adding a prefetch link

How? Open the template for this page: `templates/cart/cart.html.twig`. On top,
override a block called `metas`. This is *not* a standard Symfony block. But
earlier in the tutorial, in `base.html.twig`, we added this.

Inside the block, add `link` - but instead of `rel="stylesheet"`, use
`rel="prefetch"`. Then set the `href` to the checkout URL: `{{ path() }}` then
name of that route, which is `app_checkout`.

That's it! Let's go see what happens. Refresh the page and, on the network tools,
click to see *all* types of requests... and scroll to the top. The top request,
of course, is for `/cart`. But now... scroll down... there it is! A request for
`/checkout` that took 360 milliseconds! This happens thanks to the `prefetch` link
we just added. And even though you don't see it here, your browser knows to fetch
this with the *lowest* priority: other requests take priority.

So what happens *now* when we go to the checkout page? Let's find out: click
"Check out"... then scroll back up to the top of the requests. Cool. Turbo -
which doesn't know or care that we're doing this `prefetch` stuff - made its
Ajax call like normal. But when it did, our browser was smart enough to *instantly*
pull that from the *prefetch* cache: no second request was *actually* made! Instead
of waiting 360 milliseconds for the Ajax request to finish and *then* rendering
to, Turbo started rendering immediately.

## Best of Both Worlds?

So this method of manually adding a `link` tag isn't as fancy as the hover technique
we saw earlier. But it also avoids making *two* requests whenever we click a link.
On the negative side, when we go to the cart page, a request will be made for the
checkout page *regardless* of whether or not the user even gets *close* to clicking
the checkout link.

So... neither approach is perfect. Could we... combine the two ideas? Yep! And
that's exactly what the `data-prefetch-with-link` attribute attempts to do: it
waits until you hover, and *then* adds the `prefetch` link. There are other tiny
libraries that do something similar - like "instant.page" and "quicklink" - which
makes sense, since adding a prefetch `link` tag has *nothing* to do with Turbo.

But... the devil is in the details. Suppose that we use `prefetch` script - or
one of those other libraries - to dynamically inject a `<link rel="prefetch">` into
our `head` element whenever we hover over a link. That will work great. But when
we navigate to a new page with Turbo, that `<link>` tag will *not* be included
on the next page.

Watch: if we click to the cart page, and look in the head... actually, let me refresh
to avoid any surprises. Here's the `<link rel="prefetch"`. But now click to another
page, then look in the `<head>`. Uh, duh, I'm *still* on the cart page - click
to the homepage. Now, the `prefetch` link is gone! This is just how Turbo works:
when we navigate, the JavaScript and CSS tags inside the `head` element *do* persist
across pages. But everything else is removed and replaced with whatever is on the
*new* page.

This has a big impact on `prefetch`. Our browser *did* `prefetch` the checkout
page a minute ago when we were on `/cart`. But because the `link` tag is gone, our
browser basically "forgets" that it did that. In a perfect world, as we navigate
with Turbo, any `prefetch` links that were dynamically added would *remain* in
your `head` element. That's probably possible by keeping track of all the links
that you've prefetched and leveraging a Turbo event listener, but I haven't
experimented with it yet. If you *do* play around with this and get some nice
results, I would love to hear about it.

Here's takeaway: even though these prefetch options are really cool and they can
make your site really, really fast, none of these are perfect yet. So use them wisely.
In the real-world, I would probably use an "opt-in" approach with the hover logic
that leverages the native `prefetch` links.

Okay: we are *done* with Turbo Drive! So let's turn Turbo Frames: a feature that
allows us to separate our site into little pieces that can navigate independently.
