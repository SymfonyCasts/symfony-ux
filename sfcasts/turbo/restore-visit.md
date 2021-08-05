# Manual "Restore" Visit

Refresh, go to the cart page and add another item from the sidebar. A few minutes
ago, after doing this, we saw a nice green success flash message on the top of the
page. Where did it go?

Look at the network tools and scroll up. Ah, here's the problem. When we submitted
the add to cart form into the frame, our controller redirected and the turbo frame
*followed* that redirect. This request is the POST to `/cart`... and this is the
Ajax request for the redirect. That response *does* contain a success flash message:
"Item added!".

But remember: flash messages are only rendered *one* time. Or, to be more precise,
as soon as we render a flash message, Symfony *removes* it so that it's never
rendered again.

The problem is that... we never actually *see* this response on the page. Nope.
We detect that this redirect happened, cancel the render - which only would have
rendered inside the frame anyways - and then use Turbo to navigate to this URL.
That's the *second* identical request. Unfortunately, once we get there, the flash
message is gone... because it was already rendered... even though *we* never saw it.

Yep, our system works great except that the redirected page is requested twice...
and we only render the second one.

## Ajax Calls and Redirects: A Conundrum

This is actually tough to fix... and it's mostly not Turbo's fault. We *could* try
to work around this by adding some code to our flash logic. Like, *if* the request
is for a turbo frame, don't render the flash message. That way, it won't get used
and will render on the next *full* request.

But... that feels hacky to me. The *real* solution is harder, but more correct:
avoid the second, duplicate request!

Internally Turbo uses the `fetch()` function to make its Ajax calls. When
we return a redirect, `fetch` automatically follows that and makes a second Ajax
request, which we see down here. So, this "follow the redirect" behavior does
*not* come from Turbo... it's just how `fetch` works.

The ideal solution would be for `fetch()` to... *not* follow the redirect: to
make only the *first* request, stop, then tell us the redirect URL so that *we*
can visit it with Turbo.

Unfortunately... that's literally *not* possible. For complex reasons that might
change someday, you *can* tell `fetch()` to *not* follow a redirect. But if you
do, `fetch()` purposely *hides* the URL that it *would* have redirected to...
which means we have no idea what URL to make Turbo navigate to! Yup, our ideal
solution is entirely *not* possible in browsers as of today. What a mess!

Fortunately, there are still two ways to solve this correctly, and I'll show you
both. The first is quick, easy and... involves using an internal option in Turbo
that the documentation specifically tells you *not* to use. Exciting! The second
solution involves some work in our Symfony app, but avoids using that option.

## Upgrading Turbo... Again

So let's start with the pure Turbo solution. It's beautifully simple and... it all
starts with a question: if the turbo-frame already makes the Ajax request to the
redirected page, could we simply tell Turbo to navigate to that page and use *that*
HTML... without making a second request? Think about it: over in `turbo-helper.js`,
this `fetchResponse` already contains the HTML we want! We just need Turbo to put
that onto the page and update the address bar.

Doing this *is* possible... mostly. Start by finding your terminal and, once again,
running:

```terminal
yarn upgrade @hotwired/turbo
```

## The Internal "restore" Option

This upgrades Turbo to RC-1. Turbo seems to always release a new feature just
*before* I need it. In this case, it's a `PageSnapshot` class we'll use later.

Now, over in `turbo-helper.js`, add a second argument to `Turbo.visit()` - an
options argument. One option here is called `action`.... and one of the values
you can set it to is `restore`.

[[[ code('41a53eafea') ]]]

The action `restore` tells Turbo to visit this URL, but with the same behavior as
if you clicked the back or forward buttons in your browser. Specifically, if the
page is already in the snapshot cache, use that snapshot and make *no* network
request. If it's not already in the snapshot cache, then it *will* make a network
request.

*This* is the part where we're breaking the rules. "Restoration visits" are reserved
for clicking the back and forward buttons. Setting this action to `restore` *will*
work... but the documentation says that this is "internal" and that we should
*not* use this `action` directly.

But... let's ignore that for now. Refresh the page, head back to the cart and
add another item. Hmm, we *still* don't see the flash message. Oh, that's because
even though Turbo *has* made a request for this URL - via the redirect - that
response was never put into the snapshot cache. Remember: a snapshot of a page is
normally taken the moment you navigate *away* from that page. We're going to need
to put the HTML into the snapshot cache manually.

Here's how... and some of this *is* pretty deep in Turbo. Say
`const snapshot = Turbo.PageSnapshot.fromHTMLString()` and pass it the response
HTML, which we can get by saying `fetchResponse.responseHTML`. Except...
`responseHTML` returns a Promise... so we need to `await` that. And as soon as
we await *that*, we need to make the method `async`.

This gives us a Snapshot object from that HTML. To put this into the cache, say
`Turbo.navigator.view.snapshotCache.put()` and pass this the URL - or "location" -
of the page - `fetchResponse.location` - and then the `snapshot` object.

[[[ code('bd4094b934') ]]]

This is... pretty low-level, but *that* is how you can manually add a page to the
cache. Let's try it!

Do the whole flow again: refresh the page, go to the cart, submit, and... we got
it! The flash message shows up and, down in the network tools, we see only one
request for this page. That's awesome!

## Is this Internal Option Safe?

So... maybe we just stick with this solution and hope it won't break in the
future. Even though the action `restore` is meant as an internal flag, I couldn't
find any conversation about *why* it's internal or what risks there are: the
note in the documentation was added years ago when the feature was first
introduced.

But... if you want to play it safe, we have another solution. Change this back to
a normal visit... and also take off the `async`.

Next: let's solve this problem again by doing some fancy communication between
Turbo and Symfony.
