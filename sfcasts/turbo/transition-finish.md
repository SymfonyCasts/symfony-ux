# Transition Finish

Coming soon...

Okay. The fade out and fade transition works until you visit a page we've already
been to edit, then things get weird instead of fading out, then it sort of like fades
out and then phase in, again, this happens because back over here and turbo helper
dot JS, both, he goes turbo before render and turbo render are called when both a
real page renders and when a preview renders, that means that when a preview is
shown, it has the same effect as a real page. So when we click to a previous page,
the preview is instantly shown and then fades in like a normal page. When the HS call
finishes for the real page, that also fades in

Tricky. Huh? The solution is to detect once.

If once a rendering is a preview and then do something different. If it is, then we
want to actually start with full opacity, then fade out the same as what happens in a
normal click. How do we detect if this is a preview by looking for the data turbo
preview attribute on the HTML on the watch. If we go to back to a page, that's a
preview that could say data, turbo preview attributes, okay. And terrible helper.
Start by going all the way to the bottom and creating a new method called is preview
rendered. This method will return. We'll use that attribute to then return document,
document elements. That's how you get the HTML tag that has attributes. And then we
can pass it data dash turbo dash preview. So not looking for that data, the attra to
have a value, just whether or not it exists.

Copy that. And back up to our listeners. Let's start with before render to check, to
see if a preview is currently being shown. So if this.is preview rendered, and then
actually in the ELLs, we will do our normal thing before we fill in what we're going
to do here. I just want to mention that this is kind of confusing since we're inside
of it before render, if a preview is being rendered, then it hasn't actually been
rendered onto the page yet. Still, even though that's true, the current page will
already have the data turbo preview attribute on it, which means we can use our is
preview rendered function to figure out if what we're about to render as a preview.
Anyways, if this is a preview, we want to remove the turbo loading class so that the
preview starts at full opacity. Then after one frame, we want to re add it cause the
preview to fade out because in a moment the real page will fade. And so it looks like
this I'll copy the code from below. I want to say event that detail, that new body,
that class less dash or remove turbo loading.

And then I'll go steal down our request animation frame code, and I'll copy the
classless, add stuff from below. Perfect. So we will remove term unloading from the
new body. One frame later, we're going to add it to the new body. Cool. Now, now
turbo render.

We only want to

Remove the trivial loading class. If this isn't not a preview. So we're going to wrap
this. And if statement, if not, this.is preview rendered, okay, then we will remove
that term loading class. Yes, I know it's pretty complex, but it should do it. Let's
try do a full pay refresh. And if I click two new pages that all still looks fine and
I've clicked back to a previous page. Yes, that did it. So you can see the preview
fades out. And then once the new content comes in, it fades in, but there's one last
edge case. Click back in your browser.

Hmm. It instantly goes low opacity and then fades in not terrible, but a little odd
that's because the snapshot of each old page is taken right before the new page is
swapped in. Thanks to our new fade out functionality. It means that snapshots are
taken with the turbo loading class. In other words, snapshots are taken when a page
has low opacity. So when these snapshot is restored, it has low capacity. Then the
class is removed and it fades in when we click back or forward, ideally it would just
show the page instantly with no transition.

So when you click back or forward like this, even though it's pulling the page from
the snapshot cache, it's not considered a preview. And so the is preview rendered
method is going, will be, is going to return a false. That means that we're down in
this case. So we want to do here is what we want to do here is basically make it so
that the page that's about to be rendered, doesn't have a transition. What I'm going
to say is constant is restoration is called a restoration visit. When you click back
and forth = event.detail.new body dot class list that contains promote dash loading.
So that might look a little confusing at first,

But remember all because of our

Transition system, we just built a S a snapshot is always going to have a turbo
loading class on it. So as you know, this isn't a preview. The only way that, that
the body could have a turbo loading class on it at this point is if it's a
restoration visit and if it's a restoration visit and a copy part of this, where does
going gonna say event that detailed that new body dot class list

That remove turbo dash loading.

I'll put a little comment there that kind of describes this. Oh, and if you're still
missing, hold on. This is not going to make sense yet. We're looking at me like I'm
crazy. It's because I forgot the, if statement around this, let me move my comment
up. So if this is a restoration visit, then this remove the turbo loading glass and
we're done. That will cause the page to start with full capacity and it will never
change. Phew. Okay. Let's see if this help, well back refresh the page, click around
to a new page, another new page, click to a previous page, and now hit back perfect
back and forth show instantly. Yes. I know this is tricky. My hope is that this is
going to be easier in the future with turbo. It is doable now, but you do need to
kind of keep track of a lot of things before we keep going. Let's isolate all of this
logic, which is getting kind of big into its own methods. So I'm going to copy both
of these document, that add event listeners,

Remove them,

Go down to the bottom and let's create a new function here called initialize
transitions. How paste that in and then back up in the constructor, we'll say
this.initialized transitions, okay. That at least gives all this code down here, a
name. So we kind of understand what it does. Oh. And don't forget to take the sleep
out of our public /index PHP file. And inside of the ask style /app, that CSS let's
change the transition to something more realistic, like 200 milliseconds and change
the password into something less extreme, like appointed eight. Let's see what that
looks like on our first page, much faster and

Cool. That is a

Nice, subtle little effect. And if I go to a preview, it looks good. I never had it
back. It shows instantly next where you're going to try something kind of crazy. What
if, when a user hovers over a link, we prefetch that URL with turbo then instantly
displayed it. Is that possible? Is it a good idea? Let's do some experimenting.

