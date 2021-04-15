# Autocomplete with Transitions

The great thing about the third party `stimulus-autocomplete` controller is that
we were able to quickly create a nice search auto-complete feature with *full*
control over the HTML. And this all required zero custom JavaScript.

The only bummer is that we lost our fade-in, fade-out transition! How can we add
that back?

When we originally added the transition, we did it by leveraging `useTransition()`
from the `stimulus-use` library. We added the behavior in `connect()` in a custom
controller... then called either `this.enter()` or `this.leave()` to show or hide
the element *with* the transition.

But... we can't exactly go into the `stimulus-autocomplete` source code and hack
that stuff in! But we *can* make this work... as long as this 3rd party controller
dispatches the right events.

If you look at their docs... and scroll down: they have an Events section! And
they *do* trigger a bunch of events! This `toggle` one looks perfect:

> Fires when the results element is shown or hidden.

We can use that to trigger our transition.

## Using my stimulus-autocomplete Fork

Before we jump in, to get this to work, we need a pull request to be merged into
this library, which... at the time of this recording... is still *waiting* to be
merged. Rats! Since this is unmerged, we'll use a *fork* of this library, which
contains the tweaks from this pull request. Living on the edge!

How can we use a fork? Open your `package.json` file and find the
`stimulus-autocomplete` line. Set its version to a specific branch on my
repository: I'll paste that in. This is my username, the name of the library...
and then it points to a branch I created called `toggle-event-always-dist`.

To download this new version, find your terminal and run:

```terminal
yarn install
```

## Creating & Using a 2nd Controller

While that downloads, let's discuss the plan. In order to leverage the
`useTransition` behavior, we're going to need our own custom controller. In
`assets/controllers/`, create a new file called, how about,
`autocomplete-transition_controller.js`. I'll go steal some code from another
controller. The only thing we need right now is a `connect()` method... and we'll
`console.log` "i want transitions".

Back in the template for this page - which lives at
`templates/product/index.html.twig` - we're now going to add *two* controllers to
the same element. We can do that with `stimulus_controller()`... we just need to
tweak the format a bit.

The first argument becomes an object... and controller becomes a key in
the object assigned to its set of values. Once I finish rearranging things...
yup: we're passing an object with *one* controller set to these values for that
controller. Now we can add our new controller name - `autocomplete-transition` -
and this doesn't need any values, so set it to an empty object.

Let's see if the new controller is connected! Find our site... I'll open my
dev tools, refresh the page, check the console and... got it! There's our log. If
you inspect element on the text box.. you can see that the `data-controller`
attribute now has *both* controllers on it.

## Adding an Action for "toggle"

To get this all to all work, we need our new controller to be notified whenever the
autocomplete results should be shown or hidden... so that we can trigger the
transition.

*This* is where that `toggle` event comes in handy. The `stimulus-autocomplete`
controller dispatches all of its events on the "main element" that's registered
for the controller, which means it's going to be on *this* div. Let's add an
action for the `toggle` event. Do that with a new attribute: `data-action=""`,
the name of the event we want to listen to - `toggle` - an `->`, the name of
the controller to call - that's our new custom controller `autocomplete-transition` -
a `#` sign and the method to call on that controller: let's use `toggle`.

Copy that and head over to our controller. Add the new `toggle()` method with an
`event` argument... and `console.log(event)`.

With any luck, whenever the autocomplete results are shown or hidden, we should see
this line get hit. Let's see if that happens! Refresh the page. I'll go back to
the console and... yes! Actually an event was dispatched *immediately* when the
component initialized. This second one is from when the element was shown. Open
up the `event` object and look at the `detail` property. Ah: it has a sub-key call
`action` set to the string `open`. When the results close - like when we click
off of the area... we see another event. This time `action` is set to `close`.

In the `toggle()` method, we can use this info to either call `this.enter()` to
fade *in* the element or `this.leave()` to fade it out.

But in order to even *have* those methods, we need to initialize the
`useTransition` behavior on this controller.

Let's do that next... but in a way that will allow us to more easily reuse
our transitions in other controllers. Code-reuse: booya!
