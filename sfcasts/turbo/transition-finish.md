# Polished CSS Transitions

The fade-out and fade-in transition works... until you visit a page that you've
already been to... then things get weird. Instead of fading out, it, sort of,
fades in... then fades in again?

This happens because, back over in `turbo-helper.js`, both `turbo:before-render`
and `turbo:render` happen when both a real page renders *and* when a preview renders.

[[[ code('6dafa2774c') ]]]

That means that, when a preview is shown, it gets the same transition effect as a
real page. When we click a page we've previously been to, the preview instantly
shows - starting faded out - and then fades in. When the Ajax call finishes for the
real page, that *also* starts faded out and then fades in.

Tricky, eh? The solution is to detect if what's rendering is a preview and then do
something different. Specifically, if we *are* rendering a preview, we want to
start with *full* opacity and then fade out, so that we get the same effect as
a normal visit.

## Detecting if a Preview is Rendering

How do we detect if what's rendering is a preview? By looking for the
`data-turbo-preview` attribute on the `html` element. Watch, if we go to back to
a *previous* page, watch the `html` tag. Yup! It has a `data-turbo-preview`
attribute while it's showing.

Back in `turbo-helper`, start by going all the way to the bottom and creating a new
method called `isPreviewRendered()`. Inside, return `document.documentElement` -
that's how you get the HTML tag - `.hasAttribute('data-turbo-preview')`.

[[[ code('2211fcea4b') ]]]

We're using `hasAttribute` instead of `dataset` because we don't care what
the *value* is - it would be an empty string - we just care whether or not it
*exists*.

Copy that method name and head back up to our listeners. Start with `before:render`:
if `this.isPreviewRendered()`... then do nothing for the moment... but in the else,
do the normal logic.

Before we add the preview logic, I need to mention that this *can* be confusing.
Because we're inside of `before:render`, if a preview is being rendered, then it
hasn't *actually* been rendered onto the page yet. Even though that's true, the
current page *will* already have the `data-turbo-preview` attribute on it, which
means we *can* use our `isPreviewRendered()` function to figure out if what we're
*about* to render is a preview.

Anyways, *if* this is a preview, we want to remove the `turbo-loading` class so that
the preview starts at *full* opacity. Then, one frame later, we want to re-add that
class to cause the preview to fade out... because, once the new Ajax call finishes,
the real page will fade *in*.

Copy the code from below, paste, but *remove* the class. Then, steal the
`requestAnimationFrame()` code, paste *that*... and grab the `classList.add()`
from below and use that exactly.

[[[ code('6cf4c64dd2') ]]]

Perfect! So this will remove `turbo-loading` from the new body, then, one frame
later, re-add it to cause the fade out.

Now, in `turbo:render`, we only want to remove the `turbo-loading` class if this
is *not* a preview. So if not `this.isPreviewRendered()`, then remove that
`turbo-loading` class.

[[[ code('2a8f579526') ]]]

Yes, I know, it's *pretty* complex. Let's take it for a test drive. Do a full page
refresh. If we click to new pages... this all still looks fine. And if we click
to a previous page... yes! That did it! The preview instantly shows, fades
out, then the new page fades in.

## Restore Visits: No Transitions

But... there's one last edge case. Click the "back" button in your browser. Hmm.
It instantly goes to low opacity and then fades in. Not terrible... but a little
odd. This happens because the snapshot of every page is taken right *before* the
*new* page is "swapped in". Thanks to our new fade out functionality... it means that
snapshots are taken when the page has the `turbo-loading` class! In other words,
snapshots are taken when the page has *low* opacity! Thanks to this, when the
snapshot is restored, it has low opacity. Once the class is removed by our listener
code, it fades in.

For me, since clicking back and forward loads instantly, I'd prefer to not have
*any* CSS transition.

How can we do that? When you click back or forward like this, even though it's
pulling the page from the snapshot cache, it is *not* considered a "preview". And so
the `isPreviewRendered()` returns false. *That* means that we're down in this case.
Here, *if* this is a "restoration" visit - that's what it's called when you click
the back or forward buttons in your browser - then we want the new page to *start*
with full opacity and *not* have a transition.

Check it out: say `const isRestoration` equals
`event.detail.newBody.classList.contains('turbo-loading')`.

That... probably looks a bit confusing. Because of the transition system we just
built, every page snapshot will have a `turbo-loading` class. Since we know
this is *not* a preview, if the body has the `turbo-loading` class, then this
*must* be a snapshot that's being used for a *restoration* visit. And *if* it's
a restoration visit, say `event.detail.newBody.classList.remove('turbo-loading')`.
I'll add a note above explaining this. Oh, duh, sorry - this probably looks
super confusing because I forgot to wrap this in an if `isRestoration`.
*If* this is a restoration, remove that class and return.

[[[ code('c39a19d536') ]]]

This will cause the page to start with full capacity and never change.

Phew! Okay, let's make sure this helps. Head back, refresh, click around to a new
page, another new page, click to a previous page, and now hit back. Got it! Back
and forth show instantly.

Yup, this *is* tricky. My hope is that CSS transitions will be easier in the
future with Turbo. It *is* doable now, but you *do* need to keep track of several
things.

## Organizing our Logic

Before we keep going, let's isolate all of this logic - which is getting kind of
big - into its own method. Copy both `document.addEventListener()` sections, remove
them, go down to the bottom, and create a new method called `initializeTransitions()`.
Paste all that logic there, head back up to the constructor and call it:
`this.initializeTransitions()`.

[[[ code('ba28eb52fd') ]]]

This at least gives all this code down here a name so that future "us" can better
remember what it does.

Oh, and while we're cleaning things up, don't forget to take the `sleep` out of
`public/index.php `... and inside of `styles/app.css`, change the transition
to something more realistic, like 200 milliseconds. Also change the opacity
to something less extreme, like .8.

Let's see what this - more "real-world" - setup looks like. The reload is faster
and the transition is... a nice, subtle effect! If we click to a preview page,
that's good... and hitting back also works.

Next: let's try something kind of crazy. What if, when a user hovers over
a link, we prefetch that URL so that Turbo can display it even *faster*. This
little trick - which is *super* fun with Turbo - can actually be used to speed
up *any* site.
