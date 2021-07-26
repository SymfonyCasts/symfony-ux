# Restore Visit

Coming soon...

Refresh. So do the cart page and add another item for the sidebar. A few minutes ago,
we saw a nice green success flash message on the top of this page, where did it go
look at the network tools and scroll up? Ah, here's the problem. When we submitted
the, add the cart into the frame, our controller redirected and the turbo frame
followed that redirect that's this exhibit here, here's the post to gov `/cart`. And
this is the request for the redirect. This page does contain a success flash message,
whereas it item added and remember flash messages that are only rendered once as soon
as they're rendered, they're not rendered again, but we did. You never actually see
this response on the page. Nope. We detect that this redirect just happened. Cancel
the render, which would have happened only inside the frame and then use turbo to
navigate to this URL. That's this second request. Fortunately, once we, again here,
the flash message, it doesn't show because it

Was already rendered.

Yep. Our system works great except that they redirected page as requested twice. And
we only rented the second one. This is actually really hard to work around and it's
mostly not Turbo's fault. There are from a high level two solutions. The first is
that we can add some code to our flash logic so that if the request is for a turbo
frame, it doesn't render that way. It won't get used and it'll render on the next
four request, but that feels really hacking to me. If you're not careful, you can end
up with multiple flash messages that I'll render on the page at the same time. The
other solution is the hard one, but more correct. Basically when we return a redirect
internally, internally turbo uses the `fetch()` function to make all of its Ajax calls.
When we return a redirect, it automatically follows that and makes a second request,
which we're seeing down here turns out that's not a behavior of turbo.

That's just how fetch works. If you use fetch to make an Ajax request to one URL and
it redirects, it will automatically make a second request. The ideal solution would
be for fetch to not follow the redirect to this URL, to make only the first request.
Stop then us the URL to the second request so that we can visit it with turbo on.
Unfortunately that's literally not possible for complex reasons that might change
someday. You can tell `fetch()` to not follow a redirect, but if you do it, won't tell
you what you were out. It would have redirected to it, hides it. So what we want to
do is literally not possible in browsers as of today. So what a mess, fortunately,
there are still two ways to solve this correctly and I'll show you both. The first is
quick, easy and involves turbo. It also involves on an internal option that they tell
you not to use.

The second solution involves some cool work in our Symfony app. So let's start with
the turbo solution. It's beautifully simple, and it all starts with a question. If
the turtle frame already makes an Ajax request to the redirected page, can we simply
tell turbo to navigate to that page and use this HTML without making a second
request? Think about it over in `turbo-helper.js`, this `fetchResponse`, which represents,
uh, already contains the HTML we want. We just need turbo to put that onto the page
and update the address bar. So let's do it start by finding your terminal. And yes,
once again, running 

```terminal
yarn upgrade @hotwired/turbo
```

this time and upgrades to turbo
RC one turbo. It seems to release a feature just before I knew next over in a `turbo-helper.js`
helper. We're to a second argument, determined `Turbo.visit()`. There's an option here
called `action`. And one of the values you can put here is `restore`.

The actual restore tells turbo to visit this URL, but with the same behavior as when
you click the back or forward buttons in your browser specifically, if the page is
all right in the snapshot, cache, use that and make no network request. If it's not
already in the snapshot cache, it will make a network request. This is the part where
we're breaking the rules. Restoration visits are reserved for clicking back and
forward. While setting this action restore will work. The documentation says that
this is internal and we shouldn't do this, which is kind of a shame anyways, let's
ignore that for now. If we refresh the page and head back to the cart and add on
another item, it's we still don't see the flash message that's because even though
turbo has made a request for this URL, it was never put into the snapshot cache. We
need to do that manually. Yeah.

And here's how I'm just going to put some code up here. It's a little bit internal.
So everybody clear the cache. We can say `const snapshot =` and then 
`Turbo.PageSnapshot.fromHTMLString()`, and then we're going to pass it. The response HTML,
which we can get by saying `fetchResponse.responseHTML`, except there's one
little gotcha on here. This is actually a return. They promise. So we need to `await`
that. And as soon as we await that we need to `async` this, I, it gives us a snapshot
object from that HTML. Then we can say `Turbo.navigator.view.snapshotCache.put()`
And then we give us the location, which we're going to a special `fetchResponse.location`. 
That's just basically the URL for this snapshot. And then
the `snapshot` object. So both of these pretty low level, but that puts that snapshot
in the cache at four, that you were out. Let's try it, do the whole flow again,
refresh the page, go to the cards, submit. And we got it. Be flash messes shows up
and down in the network tools. You can see only one request for this page. That's
awesome. So maybe we just stick with the solution and hope it won't break in the
future. Even though the action restore is meant as an internal flag, I couldn't find
any conversation about why it's internal or what risks you have, but if you want to
play it safe, we have another solution. Change this back to a normal visit.

Also take off the `async`

And next let's solve this problem again by doing some fancy communication between
turbo and Symfony.

