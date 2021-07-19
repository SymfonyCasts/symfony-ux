# Globally Disable Buttons on Form Submit

Log back in... and head to any product page. Thanks to the work that we did
earlier, when we submit the review form, the opacity *does* go lower while the
frame is loading. You can see this fairly well on the button... but it *is* still
a bit subtle. So here's an idea: what if we also *disabled* this submit button
while the frame was loading? That would give us an even *better* loading indicator
*and*, as a bonus, it would help prevent double submits. The best part? We can make
this happen for *every* form on our site by leveraging an event that Turbo dispatches.

## Listening to turbo:before-submit

In your editor, open up `assets/turbo/turbo-helper.js`. Anywhere in the constructor,
listen to a new event: `document.addEventListener('turbo:submit-start')`. Pass this
an arrow function with an event argument. Inside, let's `console.log()` the string
`submit-start` and also the event object.

Turbo triggers this `turbo:submit-start` event whenever *any* form is submitted
with turbo, whether it's inside of a Turbo frame or just a normal form that Turbo
Drive is handling.

Let's go see if this works. Move over, refresh, submit, and go check the console.
There it is!

Now *some* Turbo events have a `detail` key inside them with extra info. And this
*is* one of those events. This `formSubmission` key holds all kinds of information
about the form submit that's about to start. Most importantly, for us, it has a
`submitter` key set to the button that *triggered* the submit. That's this button
right here!

This is awesome because we can use that to add a `disabled` attribute! The path
to this is `detail.formSubmission.submitter`.

## Disabling the Submitter Button

Head back to our code and replace the log with `event.detail.formSubmission.submitter`.
Add the `disabled` attribute with `.toggleAttribute('disabled', true)`.

When you use `toggleAttribute` with a second argument of `true`, it means:

> I want you to add this attribute... but I don't need it to be `disabled="something"`.
> I just need the `disabled` attribute.

Let's try that. Refresh the page... and then inspect the button element. Watch
it when I click. Yes! Perfect! For just a moment, it had a `disabled` attribute,
which made it even *more* obvious that it was loading. *And*, we can't click to
submit it twice.

Behind the scenes, *our* code added the `disabled` attribute. Then, when the
frame finished loading, the entire contents of the frame were replaced with a
new, non-disabled form to give us the exact effect we want.

## Fixing Disabled Forms in Turbo Snapshots

Scroll up, log out, then go to the registration form. This form does *not* live
in a Turbo frame. But it *still* gets the new submit behavior! Yup, with
just a few lines of code, *every* form on our site just got a little fancier.

But... there is one... super edge case. If you submitted the form and navigated away
from the page *while* the form was still submitting, that would cause Turbo to
take a snapshot of the page *with* the disabled button. If the user then clicked
*back* on their browser, the button would *still* be disabled.

This is probably *such* a rare edge case that... maybe we don't care. But let's
code for it.

Back in `turbo-helper.js`, create a new variable: `const submitter =`. Copy the
`event.detail` line from below, paste here, and just use `submitter` below.

We're doing this so we can *also* give this button a new class:
`submitter.classList.add('turbo-submit-disabled')`.

This class doesn't do anything and doesn't have any CSS attached to it. I just
invented it as a way to *mark* that this button was disabled *because* of our
loading logic.

Why is that helpful? Above this, we're listening to `turbo:before-cache`. This
is called right *before* Turbo takes a snapshot of the page. We can *use* the
`turbo-submit-disabled` class to *find* the disabled button and *remove* that
attribute.

But let's not put the logic here: let's call a new function:
`this.reenableSubmitButtons()`.

Copy that method name, scroll *all* the way to the bottom and paste to create it.
Inside, use `document.querySelectorAll()` to find any element with the
`turbo-submit-disabled` class that we added. Foreach over this, pass a callback
with a `button` argument, and then say: `button.toggleAttribute('disabled', false)`.
Fully clean things up by removing the class:
`button.classList.remove('turbo-submit-disabled')`.

It's pretty hard to actually *repeat* the edge case we just fixed... but let's
at least make sure we didn't break anything. Submit the form. Yup! That still
looks great!

Next: there's another place that we can leverage a Turbo Frame to do something cool.
While viewing a product, *if* we're an admin, it would be awesome to be
able to click an "edit" button that would Ajax load the "product form" right
into this space. So... let's do it!
