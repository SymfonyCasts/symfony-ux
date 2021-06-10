# Cleanup Before Snapshotting (e.g. Modals)

Refresh the page, open a modal, click back, then click forward again. Say hello
to a very strange-looking page. The modal did *not* completely hide itself. The
problem is that hiding a modal is asynchronous: Bootstrap *waits* for the transition
to finish before finally removing all of the elements, like this backdrop.

But the snapshot does *not* wait: Turbo takes the snapshot immediately, which is
when the modal has only *started* to be removed and when the backdrop is still
visible. Worse, because the modal element is technically *removed* from the page,
it's CSS transition is canceled. That's... a very low-level detail... but it
means that Bootstrap's modal system is never notified that the animation finished,
and so it never does its final cleanup.

## Forcing Bootstrap's Modal to Close Immediately

The solution is to force both the modal and the backdrop to hide *synchronously*
in this situation: to remove the animation that you normally see on close.

Telling a modal that you want it to work *without* an animation *is* something
you can configure. But I don't want to remove the animation entirely: I only
want to remove it when I'm hiding it right before the snapshot is taken.
Unfortunately, changing whether or not you want a modal to have an animation
*after* you create the modal is... well... not something that's really supported.

So it's a bit ugly to get this working. I'll paste in the code.

This does the same thing as before: it finds the element, gets the modal instance
and calls `hide()` on it. But it also does some extra stuff. Most importantly, before
it hides, we remove the `fade` class from the modal. We also reach *into* this
ugly internal backdrop object's config to set it `isAnimated` to false.

The results is that bootstrap will now know that both the modal and the backdrop
should *not* use an animation: both should hide instantly.

The precise fix for this type of problem will be different each time you run into
it. And you usually, you'll need to dig around in the third-party code a little bit
to figure out the best option. Figuring this out, I admit was tricky. But ultimately
don't over-think it: your goal is to basically clear out any elements that you don't
want visible in the snapshot. Often, you can just find the problematic element and
remove it.

The good news about what we have here is that this will fix the problem for the
entire site. Let's see it. Refresh the page, open the model, click back, click
forward and... yes! It's gone. If we click to add a new product, the modal still
works! You might notice that the backdrop is missing this time, but that's only due
to the bug in Bootstrap 5.0.1 that I mentioned earlier. That will *not* be a problem
in 5.0.2.

By the way, if you're having trouble figuring out how to clean up some third
party code before the page is snapshotted, there is one other, less-elegant,
but simpler solution. Instead of trying to *remove* some problematic element,
you could disable the snapshot cache *only* when that element is open.

I won't actually try this live in the video, but let's see how this might work.
Bootstrap's modal system dispatches an event both when a modal is opened and
when it is hidden. We can use that to add and remove the `turbo-cache-control`
meta tag that we saw earlier.

For example, check out this code:

```js
// assets/app.js
const findCacheControlMeta = () => {
    return document.querySelector('meta[name="turbo-cache-control"]');
}

document.addEventListener('show.bs.modal', () => {
    if (findCacheControlMeta()) {
        // don't modify an existing one
        return;
    }

    const meta = document.createElement('meta');
    meta.name = 'turbo-cache-control';
    meta.content = 'no-cache';
    meta.dataset.removable = true;
    document.querySelector('head').appendChild(meta);
});
```

It listens to the `show.bs.modal` event, which is dispatched every time *any*
modal is opened. Inside, if there is already an existing `turbo-cache-control`
meta tag, we do nothing: we don't want to change any cache behavior. But if
there is *not* one, we add a `turbo-cache-control` set to `no-cache`.

Thanks to this, if we leave the page after the modal is open, Turbo will see
that this page should *not* be cached. Hitting back or revisiting the page
will result in a normal navigation visit where *no* snapshot is used.

Notice that I added an extra `removable` key to the meta tag's `dataset`. That's
useful to *remove* this meta tag when the modal is closed. Check out the other
half of this code:

```js
// assets/app.js
const findCacheControlMeta = () => {
    return document.querySelector('meta[name="turbo-cache-control"]');
}

// ...

document.addEventListener('hidden.bs.modal', () => {
    const meta = findCacheControlMeta();
    // only remove it if we added it
    if (!meta || !meta.dataset.removable) {
        return;
    }

    meta.remove();
});
```

The `hidden.bs.modal` event is dispatched after a modal has been *fully* removed
from the page. If we find a `turbo-cache-control` meta tag *and* it has the
`removable` data key - which means *we* added it - we know it can now be safely
removed. Thanks to this, if we navigate away from the page, Turbo will create a
snapshot like normal.

This solution is, maybe less-elegant than the one I'm using... but in practice,
it works really well, and could be repeated for any other problematic JavaScript
elements on your site.

Next: now that we've crushed the Bootstrap modal, let's see one other example
with a Sweetalert modal. I'll also show you a Webpack trick where we can import
Sweetalert to help us hide the element... but without causing the SweetAlert
JavaScript to be downloaded on every page.
