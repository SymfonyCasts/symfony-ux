# Css Transitions

Coming soon...

I want to add a CSS transition so that our search preview fades in and out, instead
of showing up immediately, this may seem like a small detail, but this kind of stuff
can add up fast to making a really beautiful interface. So how can we do this? We
learned some CSS transition basics earlier and assets styles app at CSS. If you look
in here for a cart

Item,

Transition opacity 500 milliseconds to a cart item class, and then in opacity zero,

When that

Also has a removing class, the result was that as soon as we added this removing
class, the elements opacity changed from one to zero over 500 milliseconds. So CSS
transitions are simple, right?

Well, not quite,

If we repeated that trip here,

We could transition the opacity of this element here to zero, but then this element
would still technically be there. It would just be invisible. So if, for example, I
clicked right here to the user. It would look like they were clicking this photo
below, but in reality, they would be clicking this disappearing ink pens, and that's
where they would go, okay then. So should we just fade this element to opacity zero,
wait, 500 milliseconds for the finish and then fully hide it. Exactly. That's exactly
what we need to do, but sheesh, this is getting complicated. And if we later decided
to change this transition from 500 milliseconds to one second, we would need to go
into our stimulus controller and make sure that we waited, that we changed the
waiting time from two, one second, so that we didn't hide this element before it was
finished fading out. So that is why CSS transitions are trickier than they seem. At
first, fortunately stimulus use the library we installed earlier to get behaviors
like click outside and dispatch has a solution for us.

Had, did their docs

Find a behavior called use transition. This is a brand new feature that will, that
makes adding CSS transitions as we hide or show elements. Super nice. In fact, it's
still actually in beta. As I record this, it was introduced in version zero dot 20
four.zero. In fact, it's so new that I need to upgrade my stimulus use, right?

To get it. Find your terminal and run yarn, upgrade stimulus

Use add beta.

I'm using

App beta because the feature isn't included in a stable release at the time of me
recording this zero at 24 dash zero dash one is a beta release. But once they zeroed
out 24 or higher stable version is released. You won't need that.

Let's open

Up the country search preview controller. As a reminder, as we type this makes an
Ajax call to an end point that returns the HTML of the search preview, which we then
put into this results talk results, target. That's this element right

Here.

You get the behavior to work. It's just like the other behaviors we're going to
import use transition then down here and connect we'll initialize it. Use transition
this, but this behavior does have several required options. The first is called
element. This is the element that we want to hide or show for us. That's going to be
this.result target.

I'm good

Paste. The next six options. These are all CSS classes that we're going to define in
a second. So don't worry about them yet. The last thing we need is an option called
hidden class set to D dash none. You'll see how this is used in a second, but D dash
none in bootstrap as a display, none to the element.

So

Before we talk about these options, now that we've initialized the use transit
behavior, our control has three new methods, enter, leave, and toggle. So down after
we make the Ajax call, we can show the element by saying this dock enter. And then
down, when we want to hide it, we can say this.leaf. And in fact we want to do is
actually not clear out the HTML anymore. We'll keep the HTML in there, but then we'll
just make the element hide.

Okay? That's all we need in the controller. If we didn't

Nothing else, this would just give us an easy way to hide or show an element
instantly.

Let me show you. Now when we load the page

Inspect element on the input and look at the search preview

Target it now

Has a D dash non-class use transition just added that.

Thanks to the hidden class option that we passed up here. This tells you is
transition, which class do you assume when it wants fully hide something. He was
trying to just an assumes that your element is hidden by default. If your element is
showing by default, you need to pass an extra option called something. Anyways, let
me start. When we start typing, watch the element down there. Boom, the de non-class
has taken off, but there was no transition yet. It just happened instantly. And when
we click off of the element, the Dean not is instantly added back. So that's great
book. Where does the transition come from? That's up to us to add and CSS Go back to
assets styles at CSS, and I'll go to the bottom at the bottom. I'm going to paste in
three CSS classes.

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

