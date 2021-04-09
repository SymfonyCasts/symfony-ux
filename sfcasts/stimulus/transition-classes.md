# CSS Transition Classes

Right now, the search preview results are hiding and showing correctly... but there
are *no* CSS transitions yet. Why not?

Because... adding the *transition* is... actually up to us! By defining it in CSS.

## Adding the Transition Classes

Go back to `assets/styles/app.css` and head to the bottom. I'm going to paste
in three new CSS rules.

[[[ code('951b22e814') ]]]

This 2000 milliseconds here is probably too slow... but it will make it easy to
see how the feature works.

Before we talk about what's going on... it should already work! Move over and
refresh the page. Type and... beautiful! It faded in! When I click off, it fades
out! Amazing!

## The Class Lifecycle of Showing an Element

But this... deserves some explanation. Back in the controller, we defined six classes
that `useTransition` should use. The option keys come from `useTransition`, but I
*totally* made up the class names on the right. Though... they make sense! Because
we're going to create a "fade" transition, each class starts with `fade` and then
matches the option name it relates to.

*Anyways*, move back to the CSS file where we define the style for these classes.
Here's the magic. When we call `this.enter()`, `useTransition` immediately adds
the `fade-enter-active` class. That doesn't cause a transition, but it establishes
that, if the opacity changes, we *want* it to transition over 2000 milliseconds.

*One* frame later, it adds *another* class - `fade-enter-from` - and *removes*
the `d-none` class. The result is that the element is now "shown"... but with an
opacity set to 0. One frame after that, it removes `fade-enter-from` but adds
`fade-enter-to`. Thanks to this, our browser starts transitioning the opacity
from 0 to 1! Awesome!

So... what happens next? `useTransition` is smart. It *detects* that a transition
is currently happening and will take 2000 milliseconds. So... it waits. Yup! It
literally waits for two seconds for the transition to finish. And *then* it removes
both `fade-enter-to` and `fade-enter-active` because its work is done. The element
faded in and is now fully visible.

Isn't that amazing? `stimulus-use` didn't invent this idea: you'll see it in other
libraries like Vue. But it *is* so handy.

## The Class Lifecycle of Hiding an Element

In our controller, when we call `this.leave()` to hide the element, a similar
process happens. First, `fade-leave-active` is added to the element, which establishes
that we *want* a 2000 millisecond transition on `opacity`. Next, it adds
`fade-leave-from`, which makes sure that the opacity is definitely set to `1`,
which it already was. One frame later, it *removes* `fade-leave-from` and replaces
it with `fade-leave-to`. The result is that the element starts a 2 second opacity
transition from 1 to 0. Two seconds later, after the transition has finished,
`useTransition` adds the `d-none` class and removes both `fade-leave-to` and
`fade-leave-active`. The element is now *fully* hidden.

How cool is that? Learning *how* this works is fun. But the result is even better.
And in your day-to-day use, it's really simple. Now that we have these three CSS
rules defined, we could reuse this exact `useTransition` in any other controller
to add, fade in and fade out functionality to it. Heck, you could even create a
re-usable JavaScript module that sets up the behavior and these options
automatically for you!

Next: there's one last thing I want to talk about. Stimulus is used by a lot of
people, including the Ruby on Rails world. And so, it turns out that there are a
bunch of pre-made Stimulus controllers that you can download and use directly in
your app! Yay! Let's install one and learn how to register it with our Stimulus
application.
