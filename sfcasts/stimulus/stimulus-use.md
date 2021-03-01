# Stimulus Behaviors: stimulus-use

We have a little itty bitty problem. When we click off of our search area, the
suggestion box... just sticks there. We need that to close. How can we do that?

We *could* do it manually. We would register a click listener on the entire
page `document` and then detect if a click was inside our search area or
outside of it.

## Hello stimulus-use

But... I have a *way* better solution. Search for "stimulus use" to find a
[GitHub page](https://github.com/stimulus-use/stimulus-use). `stimulus-use` is a
library that's *packed* full of behaviors for Stimulus controllers. It's awesome.

I'll click down here to get into the [documentation](https://stimulus-use.github.io/stimulus-use).

Here's a great example of the power of this library. Suppose you want to do
something whenever an element *appears* in the viewport - like as the user is
scrolling - or *disappears* from the viewport. You can easily do that with one of
the behaviors called `useIntersection`.

Basically, you activate it, give it some options if you want... and boom! The
library will automatically call an `appear()` method on your controller when your
element *enters* the viewport and `disappear()` when it *leaves* the viewport.
How cool is that?

One of the other behaviors - `useClickOutside` - is exactly what *we* need.

## Installing & Activating useClickOutside

So let's get this installed. Over on "Usage"... actually "Getting Started", the
name of the library is `stimulus-use`. Spin over to your terminal and install it:

```terminal
yarn add stimulus-use --dev
```

Again, the `--dev` part isn't really important - that's just how I like to install
things.

While that's working, let's go look at the documentation for `useClickOutside`.
I'll scroll down to "usage".

Ok: step 1 is to activate the behavior in our `connect()` method. Cool. Copy this
line... and let's make sure the library finished downloading. It did.

Over in the controller, go to the top to import the behavior:
`import {}` and then the behavior we need - `useClickOutside`.

Sweet! PhpStorm auto-completed the rest of that line for me.

Below, add a `connect()` method and paste: `useClickOutside(this)`.

For step 2, look at the docs: we need to add a `clickOutside()` method. Ok!
Let's add it at the bottom: `clickOutside(event)`. When the user clicks outside
of our controller element, we will set `this.resultTarget.innerHTML = ''`.

Done. Let's test it! Head back to the browser and refresh. Type a little to get
some suggestions, then click off. Beautiful! And if I type again... it's back,
then click off... and gone again.

People: that was like four lines of code!

## Debouncing with useDebounce

Since that was *so* fast, let's do something else.

If I type really, really fast - watch the Ajax counter right here - yup! We
make an Ajax call for *every* single letter no matter *how* fast we type. That's
overkill. The fix for this is to *wait* for the user to *pause* for a moment - maybe
for 200 milliseconds - before making an Ajax call. That's called debouncing. And
there's a behavior for that: `useDebounce`.

Let's try it! Scroll up to the example. Of course, we need start by importing it.
Oh, and this `ApplicationController` thing? Don't worry about that: that's another,
optional feature of this library, they're just mixing examples.

Over in the controller, at the top, import `useDebounce`. Next... if you look at
the other example, we activate it the same way. So, in `connect()`,
`useDebounce(this)`. I'll add semi-colons... but they're obviously not needed.

Here's how this behavior works: we add a static `debounces` property set to an
array of methods that should *not* be called until a slight pause. That pause
is 200 milliseconds by default.

For us, we want to debounce the `onSearchInput` method. Copy the name then
head up to the top of the controller: `static debounces = []` with `onSearchInput`
inside.

Let's try it! Back to the browser, refresh and... type real fast! Ah! It exploded!
This is due to a limitation of this feature. Because our browser is calling
`onSearchInput`, the behavior can't hook into it properly. Debouncing only works
for methods that *we* call ourselves.

But that's no problem! We just need to organize things a bit better. Try this:
close up `onSearchInput` early and move most of the logic into a new method
called `async search()` with a `query` argument.

Again, we're making this `async` because we have an `await` inside.

For `onSearchInput`, we *don't* need the `async` anymore... and we can
now call `this.search()` and pass it `event.currentTarget.value`. Below, set
the `q` value to `query`.

This is good: we've refactored our code to have a nice, reusable `search()`
method. And *now* we can change the `debounce` from `onSearchInput` to `search`.

Testing time! Refresh and... type real fast. Yes! Only one Ajax call.

Alright! This feature is *done*! Next, on the checkout page, let's add a confirmation
modal when the user *removes* an item from the cart. For this, we'll leverage a
great third party library from inside our controller: SweetAlert.
