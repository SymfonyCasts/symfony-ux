# CSS Page Transitions

What about CSS transitions between pages as we click around? This is something that
a competing library called Swup does very well. But in Turbo, it's not so easy. Well,
it *will* be easier once a PR is merged into Turbo.

Here's the basic problem: when you click, Turbo makes an Ajax call for
the new HTML. Then, when that Ajax call finishes, it *immediately* puts the new body
onto the page. To be able to have a CSS transition between visits, we need a way
to *pause* that process. When the Turbo Ajax call finishes, we need to be able to
tell Turbo to *not* immediately render the new page so that we can *instead* start
a CSS transition - like fading out the old page. Then, once that transition
finishes, we tell Turbo to *finally* finish its job of putting in the new body.

The missing piece right now, which the pull request addresses, and which *has*
gotten a thumbs up from the maintainers, is that there's no ability to pause that
process. If you're interested in complex CSS transitions, keep an eye on this issue.

Does this mean that we can't add *any* transitions? Actually, no! It just means we
can't create super-precise animations. For example, imagine that we want to slide
the old content up, wait for that transition to finish, then immediately slide the
new content down. That's *not* going to work until we have more control over the
process.

But if we just want to fade out the old page and fade *in* the new page, that
*will* work. Why? Because if the fade out doesn't *quite* finish before the fade
in starts... that's probably not a huge deal. It's a little imprecise, but it will
still look good. So even though we can't add *perfect* CSS transitions yet, let's
learn how to do this. It's a fascinating example of the power of Turbo events.

So here's the plan: at various times while the old page is leaving and the new page
is entering, we're going to add some CSS classes that allow us to cause those
to fade out and fade in.

## Adding the Transition CSS

Let's actually start with the CSS. Open up `assets/styles/app.css`. Right on top
inside `body`, add `transition: opacity 1000ms`.

Two things about this. First, 1000 milliseconds is *way* too long for a transition,
but it'll make this easy for us to see while we're developing. Second, if you're
new to CSS transitions, this line doesn't *cause* a transition. It just says that
*if* the opacity of the body ever changes, I want it to change gradually over one
second, instead of immediately.

Below this, add `body.turbo-loading`. Inside, set the opacity to `.2`... which is
probably too low of an opacity for a nice effect... but again, it'll make it easy
for us to see.

This `turbo-loading` class is *not* something that's part of Turbo: it's something
that *we* are going to add to cause the transition.

## Triggering the Fade Out Transition

Let's do it. Go back to `assets/turbo/turbo-helper.js` and, in the constructor,
here we are, add a new event listener at the bottom. Step one is, when we click
a link, we want to add the `turbo-loading` class to the `<body>`. That will cause
the old body to fade out.

Do that with `document.addEventListener()` and, this time, listen to an event
called `turbo:visit`. This is yet *another* event that we haven't seen before. This
is triggered immediately after a visit *starts*. Inside, say `document.body` - that's
an easy way to get the `body` element - then  `.classList.add('turbo-loading')`.
I'll add a comment that explains what this does.

To make it easy to see if this is working, go to `public/index.php`... and add a
1 second `sleep()` temporarily.

Ok: let's go refresh the page... this will be kind of slow. Ready? Click! Nice!
The page faded out. But then the new content shows up immediately. We haven't added
the fade *in* effect yet.

## Triggering the Fade In Transition

Let's do *that*. Head back to `turbo-helper.js`. I'm going to paste in two more
listener functions. Let's walk through this: we've seen both of these events before.

`turbo:before-render` fires right *before* the new body is added to the page. This
allows us to add the `turbo-loading` class to the *new* body before it's added to the
page. This will set its opacity to `.2` to start: we want it to *start* faded out.

Then the `turbo:render` event is triggered right *after* that new body is added
to the page. Here, we want to *remove* the `turbo-loading` class. That will set the
opacity *back* to 1... and thanks to the transition, it should happen slowly over
1 second.

But we can't remove the class immediately... we can't just put this line directly
here in the listener. Why not? We need the new body to be rendered for at least 1
"frame" with the lower opacity... so with the `turbo-loading` class. If we
remove it immediately - by just putting the line right here - the element will
actually start at *full* opacity with *no* transition... because it never got the
chance to render with the *low* opacity.

This is why we have this `requestAnimationFrame()` function. This is a built-in
browser function that says:

> Hey, once you *do* render the next frame, please call this function.

This allows the element to be rendered for one frame with the low capacity...
and *then* we remove the class to transition to full opacity. Pretty freaking cool.

Let's try it. Refresh, and... click. Yes! The fade out and fade in transition looks
perfect! Yay! Until... we visit a page we've already been to. Woh. That was weird.
It... sort of faded in and... then faded in again?

Let's find out what's going on next and use more Turbo smartness to fix it. By
the end, we are going to have *perfect* fade transitions.
