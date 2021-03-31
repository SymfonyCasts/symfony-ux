# CSS Transition Classes

Coming soon...

back. So that's great book. Where does the transition come from? That's up to us
to add and CSS Go back to assets styles at CSS, and I'll go to the bottom at the
bottom. I'm going to paste in three CSS classes.

This 2000 milliseconds here is probably too slope, but it will make it easy for us to
see how the feature works. Before we talk about what's going on here. It should
already work. Whoever want to do a fourth refresh just to be sure and type beautiful.
It faded in. And when I click off, it fades out. This deserves some explanation.
First back in the controller, we defined it. Six classes that use transition should
use. I totally made up these names over here. They have obvious names like
interactive corresponds to interactive. And since this transition is going to be a
fade in, fade out, I called it fate, but these class names could be anything, but
they correspond to what we did in our CSS file. So here's the magic. When we call
this.enter, use transition immediately adds the fade, enter active class. That
doesn't cause a transition, but it establishes that if the opacity changes, we want
it to happen slowly.

Over 2000 milliseconds, one frame later, it adds another class fade enter from and it
removes the D dash non-class. This shows the element, but within opacity set to zero
one frame, after that it removes fade enter from, but adds, fade, enter to the
results your browser starts transitioning, you know, pasty from zero to one. What
happens next? Use transition is smart. It detects that a transition is currently
happening and will take 2000 milliseconds. So it waits. Yup. It literally waits for
two seconds for the transition to finish. Then it removes both. Fade enter two and
fade enter active because it's work is done. The element is now fully visible. The
element faded in and is now fully visible. Isn't that amazing stimulus used. Didn't
invent this idea. You'll see it in other libraries like view, but it is handy

In

Our controller. When we call this.leave to hide the element, the process happens in
reverse. First fade leave active is added to the element which establishes that we
want a 2000 millisecond transition on a pasty. Next it adds fade leave from,

Um,

Which makes sure that the opacity is definitely set to one, which it already was.
Anyways. One frame later, it removes fade lead from and replaces it with fade. Leave
to The results. The transit, the opacity transitions from one to zero over two
seconds. After the transition has finished use transient use transition adds the D
dash non-class and removes both failed leave too. And fade leave active since they're
not needed anymore.

Phew. Learning how this works is pretty cool,

But the result is even better. And in your day to day use, it's really simple. Now
that we have these three CSS classes defined, we could reuse this exact use
transition in other controllers, in any other controller to add, fade in and fade out
functionality to it. Next there's one last thing I want to talk about stimulus is
used by a lot of people, including the Ruby on rails world. And so it turns out that
there are a bunch of pre-made stimulus controllers that you can download and use
directly in your app. Yay. Let's install one and learn how to register it with our
stimulus instance.
