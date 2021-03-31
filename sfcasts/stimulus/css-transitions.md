# CSS Transitions with useTransition

I want to add a CSS transition so that our search preview fades in and out, instead
of showing up immediately. This may seem like a small detail, but this kind of stuff
can be the difference between a mediocre interface and truly *beautiful* experience.

# Why CSS Transitions can be Tricky

So how can we do this? We learned some CSS transition basics earlier. In
`assets/styles/app.css`, if you look in here for `cart-item`, we set
`transition: opacity 500ms`. When that element *also* has a `removing` class,
we set `opacity` to 0.

The result is that, as soon as we added this `removing` class, the element's
`opacity` changed from 1 to 0 over 500 milliseconds.

So CSS transitions are simple, right?

Well... not always. If we repeated that trick here, we could transition the
`opacity` of this element to 0. But then... the element would still technically
*be* there... it would just be invisible. So if, for example, if the user clicked
right here, to them, it would look like they were clicking this photo... but in
reality, they would be clicking the invisible dropdown above, which would take
them to the disappearing ink pens page..

Ok then: so should we just fade this element to opacity 0, wait 500 milliseconds
for the transition to finish... and *then* fully hide the element? That's exactly
what we need to do! But sheesh, this is getting complicated! And if, later, we
decided to *change* the transition in CSS from 500 milliseconds to 1 second, we
would need to remember to go into our Stimulus controller and *also* change the
delay *there* to 1 second so that we don't hide the element before it's finished
fading out.

So that is why CSS transitions are trickier than they seem at first. Fortunately,
the `stimulus-use` library we installed earlier - to get behaviors like `clickOutside`
and `dispatch` - has a solution for us!

Head to their docs and find a behavior called `useTransition`. This is a brand new
feature that allows us to add CSS transitions when we need to hide or show an element.
At the time of recording, this is currently a beta feature, which means it could
change a bit - but I'll update the video if that happens.

## Upgrading stimulus-use

The feature was introduced in version 0.24.0... and it's *so* new that I need to
upgrade my `stimulus-use` to get it!

Find your terminal and run:

```terminal
yarn upgrade stimulus-use@beta
```

I'm using `@beta` because the feature isn't included yet in a stable release at
the time of this recording. This `0.24.0-1` is a beta release. Once they release
`0.24` stable or higher, you won't need that.

Open up the current `search-preview_controller`. As a reminder, as we type, this
makes an Ajax call to an endpoint that returns the HTML of the search preview, which
we then put into this `resultTarget`. That's this element right here.

## Initializing the Behavior

To get the new `useTransition` behavior to work, we'll do the same thing as the
other behaviors. Import `useTransition`... and down in `connect()`, initialize
it: `useTransition(this)`.

But this behavior has several *required* options. The first is called `element`.
This is the element that we want to hide or show. For us, it will be
`this.resultTarget`.

I'm going to paste in the next *six* options. These are all CSS classes that
we're going to create in our CSS file in a minute. Don't worry about them yet.
The last options we need is `hiddenClass` set to `d-none`.

You'll see how this is used in a second, but `d-none` is a Bootstrap class that
sets `display: none` on an element.

## New enter(), leave() Methods

Before we talk about these options, now that we've initialized the `useTransition()`
behavior, our controller has 3 new methods: `enter()`, `leave()`, and `toggle()`.

Down after we make the Ajax call, to *show* the `resultTarget` element, say
`this.enter()`.

Then, later, when we want to hide it, we can say `this.leave()`. And we will *not*
want to clear the HTML anymore. We'll keep the HTML, but hide the element.

## The Elements Hides and Shows!

Okay! That's all we need in the controller. If we do *nothing* else, the new
behavior already gives us an easy way to hide or show an element *without*
CSS transitions.

Let me show you. Reload the page, inspect element on the input and look at the
`result` target element. Woh! It now has a `d-none` class! `useTransition` added
that!

This happens thanks to the `hiddenClass` option that we passed. This tells
`useTransition` which class to use to fully hide an element. And, by default,
`useTransition` assumes that your element *start* hidden. So as soon as our
controller is initialized, it adds that class.

If you have a situation where an element starts *visible* instead of hidden,
you'll need to add a `transitioned` option set to `false`... though that may
be renamed to an option called `initialState` that you would set to `enter`.
Check the docs to be sure.

*Anyways*, watch the element as I start typing. Boom! The `d-none` class is
gone and the element is visible! But there was no transition yet: it just happened
instantly. When we click off of the element, the `d-none` is instantly added back.
Like I said, if we do *nothing* else, this behavior gives us an easy way to hide
or show an element.

So... how can we make this *actually* transition? That's up to us! Next, let's
add the CSS needed to make the element fade in and fade out. We'll explore the
lifecycle of how `useTransition` intelligently adds and removes classes at
*just* the right time to make this all possible.
