# useTransition in a Neat, Reusable Module

We need to initialize the `useTransition()` behavior on this controller so we can
call `this.enter()` or `this.leave()` inside toggle to show or hide the results.

This time, instead of putting all the code right here, let's create a reusable
function so that we can *quickly* add our fade transition behavior to other
controllers in the future.

## Creating addFadeTransition()

In the `assets/` directory, create a new directory called, how about, `util/`. And
inside that a new file called `add-transition.js`. I'll paste in the code: you can
get this from the code block on this page.

This exports, a named function called `addFadeTransition()` that adds the
`useTransition` behavior to any controller. *Most* of what you see here is identical
to what when we originally leveraged `useTransition`.

## Setting up the Transition Behavior

Cool! Back in our controller, in the `connect()` method, which is normally where
we add behaviors, say `addFadeTransition` and hit tab to autocomplete this... so
that PhpStorm adds the `import` for us! Booya! Pass the controller - which is
`this` - and then we need to pass which element is going to be hidden or shown, which
we don't actually have access to yet. Let's add a target for that. Pass `this.results`...
then initialize that target above: `static targets = ` an array with `results` inside.

In the template, we need to add the target to the results `div`. Hmm, this *already*
has a target for the `autocomplete` controller. Copy that, paste, and add an
*identical* target for our `autocomplete-transition` controller.

It *is* a bit weird to have two different targets on the same element... but this
is totally allowed. If you really didn't like this, you could actually *find* this
element *manually* in our controller by using the `this.element.querySelector()`
to find an element with this attribute.

*Anyways*, back in our toggle method, because we've initialized the `useTransition`
behavior, we now have `enter()` and `leave()` methods. And so, if
`event.detail.action` equals `open`, call `this.enter()`. Else, call `this.leave()`.

Let's try it! Move over, Refresh and type "de"... yes! It transitioned!

As a reminder, the details of this transition - like the fact that it fades for
2 seconds... live in the `app.css` file. Search for "fade": here they are.

That 2000 milliseconds is *way* too long - I'm only using that so the transition
is obvious.

## The skipHiddenProperty Value

*Anyways*, back at the browser, type to re-open the suggestions then click off of
this to close it. That happened instantly! There was no transition!

Inspect the element. Ah: see that little `hidden` attribute on the results `div`?
That was added by the `stimulus-autocomplete` controller as *soon* as we clicked
off. And thanks that, the element became invisible *instantly* instead of waiting
for our transition.

Normally, that's great! It's how `stimulus-autocomplete` hides the results. But now
that *we* are controlling the hiding and showing with our transition behavior, we do
*not* want this `hidden` attribute to be added. Fortunately, assuming my PR is
merged, we can pass a *value* to disable that behavior.

In the template, on the `autocomplete` controller, pass a new value called
`skipHiddenProperty` set to `true`.

That literally says: please do not set that `hidden` property: we are handling the
hiding and showing ourselves.

Let's try it out again. I'll type... we still get the nice fade in... and if I click
off. Yes! It fades out!

And.... we're done! I mean, the whole tutorial is done! I hope you found this journey
through Stimulus as refreshing as I did. I *love* coding with Stimulus.

In the next tutorial in the series - about Turbo - I hope to show you that we can
have an even *more* dynamic and speedy app while writing even *less* custom
JavaScript. Let us know what cool stuff your building. And, as always, if you have
any questions, we're here for you in the comments section.

Okay, friends, seeya next time!
