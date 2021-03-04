# Making a Configureable, Reusable Controller

We can already reuse this new controller on any form where we want the user to
confirm before submitting. That's awesome. But to *truly* unlock its potential,
we need to make it configurable, giving us the ability to change the title, text,
icon and *confirm* button text.

Fortunately, the values API makes this easy. At the top of our controller, add a
`static values = {}` and... let's make a few things customizable. I'll use the
same keys that SweetAlert uses. So we'll say `title: String`, `text: String`,
`icon: String` and `confirmButtonText: String`. We could configure more... but
that's enough for me.

Below, use these. Set `title` to `this.titleValue` or `null`. There's no built-in
way to give a value a *default*... so it's common to use this "or" syntax. This
means use `titleValue` if it's set and "truthy", else use `null`.

Let's do the others: `this.textValue` or `null`, `this.iconValue` or `null` and,
down here  `this.confirmButtonTextValue` or *yes*... because if you have a confirm
button with no text... it looks silly.

I like this! Let's see how it looks if we don't pass *any* of these values. Refresh
and... yup! It works... but *probably* we should configure those.

Head to the template - `cart.html.twig` - to pass them in. Do that by adding a
2nd argument to `stimulus_controller()`. Let's see, pass `title` set to
"remove this item?", `icon` set to `warning` - there are five built-in icon types
you can choose from - and `confirmButtonText` set to "yes, remove it".

Let's check it! Refresh and remove. That looks awesome! And more importantly, we
can now properly re-use this on any form.

## Submitting via AJAX

While we're here, I want to add one more option to our controller: the ability to
submit the form - after confirmation - via Ajax instead of a *normal* form submit.
Let me tell you... my *ultimate* goal. After confirming, I want to submit the form
via Ajax then remove that row from the cart table without *any* full page refresh.

Quick side note about this. Our next tutorial in this series - which will be about
Stimulus's sister technology "Turbo" - will show an even *easier* way to submit
any form via Ajax. So definitely check that out.

But doing this with Stimulus will be a good exercise and *will* give us more
control and flexibility over the process... which you sometimes need.

## Setting up SweetAlert for the AJAX Submit

Ok: to support submitting via Ajax, we need to tweak our SweetAlert config. Add
a `showLoaderOnConfirm` key set to true. Then add a `preConfirm` option set to
an arrow function. This is going to replace the `.then()`.

And... actually let's organize things a bit more: add a method down here
called `submitForm()`. For now, just `console.log('submitting form')`. Then up in
`preConfirm`, call `this.submitForm()`.

This deserves some explanation. When you use the `preConfirm` option in SweetAlert,
its callback will be executed after the user *confirms* the dialog. The big
difference between this and what we had before - with `.then()` - is that this
allows us to do something asynchronous - like an Ajax call - and the SweetAlert
modal will stay open and show a loading icon until that Ajax call finishes.

Let's make sure we've got it hooked up. Refresh, and... yes! There's the log.

## Submitting a Form via AJAX

Now let's actually *submit* that form via Ajax. Replace the `console.log()` with
`return fetch()`. For the URL, `this.element` is a form... so we can use
`this.element.action`. Pass an object as the second argument. This needs two things:
the method - set to `this.element.method` - and the request `body`, which will be
the form fields.

How do we get those? It's awesome! `new URLSearchParams()` - that's the object
we used earlier - then `new FormData()` - that's *another* core JavaScript object...
that even works in IE 11! - and pass this the form: `this.element`.

That's a really nice way to submit a form via Ajax and include all of its fields.
Oh, and notice the `return`. We're *returning* the `Promise` from `fetch()`... so
that we can return that same `Promise` from `preConfirm`. When you return a
`Promise` from `preConfirm`, instead of closing the modal immediately after
clicking the "Yes" button, SweetAlert will *wait* for that `Promise` to finish.
So, it will wait for our Ajax call to finish before closing.

And we can *now* see this in action! Refresh and click remove. Watch the confirm
button: it should turn into a loading icon while the Ajax call finishes. And...
go!

Gorgeous! I think that worked! It didn't remove the row from the page - we still
need to work on that - but if we refresh... it *is* gone.

## Making the Ajax Form Submit Configurable

But I don't want this Ajax submit to always happen on *all* the forms where I use
this confirm submit controller... because it requires extra work to, sort of,
"reset" the page after the Ajax call finishes. So let's make this behavior
configurable.

Over in the controller, up on values, add one more called `submitAsync` which
will be a `Boolean`.

Down in `submitForm()`, use that: if *not* `this.submitAsyncValue`,
then `this.element.submit()` and `return`.

Let's make sure the Ajax call is gone. Actually... let me add a few more items
to my cart... because it's getting kind of empty. Add the sofa in all three
colors... then go back to the cart. Let's remove this one and... beautiful. It's
back to the full page refresh.

*Now* let's reactivate the Ajax submit on *just* this form by passing in the
`submitAsync` value. In the template, set `submitAsync` to `true`.

At this point, we have a clean submit confirm controller that can be reused on
any form. As a bonus, you can even tell it to submit the form via Ajax.

But when we submit via Ajax, we need to somehow remove the row that was just deleted.
To do that, we're going to create a *second* controller around the entire cart
area and make the two controllers communicate to each other. Teamwork? Yup,
that's next.
