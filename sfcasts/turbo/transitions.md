# Transitions

Coming soon...

It's my favorite. What about CSS transitions between pages as we click around. And
this is something that a competing library swap does very well, but in turbo, it's
not as easy. Well, it will be easier. Once a PR is merged. Here's the basic problem
right now, when you click turbo makes an Ajax call for the new HTML. Then when that
Ajax call finishes, it immediately puts the new body onto the page. To be able to
have a CSS transition between visits. We need a way to pause that process. We needed
to be like this turbo makes agents call the AGS call finishes. We tell turbo to not
immediately render that new page. We start whatever CSS transitions. We want like a
fade out. And then once that transition finishes, we tell turbo to finally do its job
of putting in the new body. The missing piece right now, which is polar request is
about, and which has gotten a thumbs up from the maintainers is that there's no
ability to pause that process.

That's currently not possible though. I hope and think it will be soon. If you're
interested in complex CSS transitions, keep an eye on this issue. Does this mean that
we can't add any transitions? Actually, no, it just means we can't get at super
precise animations. For example, when we, if we wanted to slide the old content up,
wait for that to finish, then immediately slide the con new content down. That's not
going to work until we have more control over the process, but if we just want to
fade out the old page and fade in the new page, that will work. Why? Because if the
fade out doesn't quite finish before the fade in starts, that's probably not a huge
deal. It's a little imprecise, but it will still look good. So even though we can't
go perfect CSS transitions yet, let's learn how to do this. It's a fascinating
example of the power of turbos event listeners.

All right, here's the plan. One of visits starts. So like when we click on a link,
we're going to add a new turbo loading class to the old, the body that turbine
loading class is going to cause the body to fade out then right before rendering the
new page, we're going to get access to that new body and have that same turbo letting
loading class to it, to set it to a low opacity. You know what? Let's not talk about
this. So here's the plan at various times when the old page is leaving, a new page
has been added. We're going to add some CSS classes that allow us to cause those to
fade in, fade out. Let's actually start with the CSS. Some of our `assets/styles/app.css`
hand right up here in the body. I'm going to say that. I would say that I
want to `transition: opacity 1000ms`.

So two things about this. First of all, 1000 milliseconds is way too long for a
transition, but it'll make this very easy for us to see while we're developing.
Second thing is that this doesn't cause a transition to the body. It just says if the
opacity ever changes, I want you to change the opacity gradually over one second,
instead of immediately blow this, let's say `body.turbo-loading` and here is
where we're going to set the opacity to `.2` is probably too low of an opacity,
but again, it'll make it easy for us to see this. Now that's terrible. Loading class
is not something that's part of turbo. That's something that we are going to add to
cause the transition.

All right, so let's do this. Go back and do `assets/turbo/turbo-helper.js`. And
I'll in the constructor. Here we are. These are constructed with our listeners. I'm
going to add some new event listeners down here. So step one is that I want to fade
out the body as soon as I click a link. So as soon as we click a link over here, we
want to add the `turbo-loading` class to the `<body>`. And that will cause the old body to
fade out, to do that. We can add a listener here, `document.addEventListener()`
to a, uh, `turbo:visit`. This is yet again, another event that we
haven't seen before.

This is bend is called immediately. After a visit starts inside of here, we're gonna
say `document.body`. That's an easy way to get the body element. Then 
`.classList.add('turbo-loading')` I'll put law comment on this, the old body cool
fitting up should be done to make it easy to see if this is working, go to 
`public/index.php`. Hm let's add a little `sleep()` for one seconds in here temporarily.
Alright, let's go refresh the page. This will be kind of slow then. Ready for it.
Nice. Paige fades out. But then the new content shows immediately. We haven't added
the fade in effect yet. Let's do that head back to `turbo-helper`. And I'm going to
paste in two more listener functions, both for events. Let's walk through this. Both
of these events we've seen before `turbo:render` and `turbo:render` turbo
before render fires right before the new body is added to the page. By adding the
turbo dash loading class to it, we're setting its opacity to `.2` to start. We want to
have it start faded out. Then the turbo render event is dis is triggered right after
that new body is added to the page here. We want to remove the `turbo-loading` class,
which would set the opacity me back to one and things to be transitioned in our CSS
where the opacity changes. It should change slowly over one second,

But

We can't remove the class immediately. So I can't just have this line here. This
turbo loading line, like right inside the listener. Why, why not? We need the new
body to actually be rendered for at least a single frame with the turbo loading class
so that its opacity can be set to point to, to start if we removed it immediately by
just popping this line right here. Okay. The element would actually start at full
pack opacity with no transition. So that's why we have this `requestAnimationFrame()`
function here. That's a built in browser function that says, Hey, once you do render
the next frame, please call my function. That allows the element to be rendered for
one frame with the low capacity. And then we removed the class to force it, to
transition to full capacity, pretty freaking cool. Huh? Let's try it. I'll refresh
and click out and phase in every transition looks perfect on till you visit a page
you've already been to,

Uh, whoa. That was weird. So it sort of faded in and

Then faded in again. Well, that's find out what's going on next and use no more turbo
smartness to fix it. By the end, we are going to have perfect fade transitions.

