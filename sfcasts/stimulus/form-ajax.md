# Ajax-Submitting an Entire Form

When we click the "Save" button, we want to submit the form via Ajax. Take a look
at the structure of the modal. The save button is actually *outside* of the form:
the form lives inside of `modal-body`. This means that we *can't* add a Stimulus
submit action to the *form*... because clicking save won't actually submit the form!
The button does nothing! Instead, over in `_modal.html.twig`, we're going to add
an action to the `button` itself.

Add `data-action=` - we can just use the default action for a button, which is
`click` - then our controller name - `modal-form` - a `#` sign and a new
method name. How about `submitForm`.

[[[ code('91631fc517') ]]]

Copy that name and go add it to our stimulus controller `submitForm()`.

[[[ code('f24e1a1168') ]]]

## Finding the form Without a Target

Let's see: we need to find the `form` element so we can read the data from all of
its fields. Normally, when we need to find something, we add a target. Should we
do that in this situation?

We *could*. But I'd like to make it as *easy* as possible to reuse our `modal-form`
controller on *other* forms. If we can avoid needing to add extra attributes to
the `form` element, which is rendered by this `form_start()` function, that
will make reusing all of this on *other* forms much easier.

Instead, in our controller, let's leverage the `modalBodyTarget` - which is going
to be this element right here - and look *inside* of it for a `form` element. With
jQuery, we can do that with: `const $form = $(this.modalBodyTarget)` then
`.find('form')`.

[[[ code('52d7692b48') ]]]

If you use jQuery in Stimulus, you will always use `$()` and then some element
so that we're looking *inside* that element... instead of inside the whole page.
If we wanted to look for something inside of our *entire* controller element,
we would use `$(this.element)`. The point is: we always want our selecting
to be looking *inside* our controller.

I also, as a convention, prefix my variable names with a `$` when they are jQuery
objects. There's nothing special about that variable name.

If you wanted to do this *without* jQuery, it would be really similar:
`this.modalBodyTarget` then `.getElementsByTagName()`, pass it `form`... and
use the `0` index to get the first and only match.

## Making the Ajax Call

Anyways, to make the Ajax call, we're going to need the data from all of the
fields in the form. Without jQuery, if you look at our `submit-confirm_controller`,
and scroll down to `submitForm()`, we learned that you can do this with a
combination of `URLSearchParams` and `FormData`... passing it the `form` element,
which in this case was `this.element`.

We *could* do that same thing here. But since we're using jQuery, there's a
shortcut: `console.log($form.serialize())`.

[[[ code('47b1373572') ]]]

Let's try that. Move over, refresh the page, open the modal and fill in at least
one of the fields. Hit save. Nothing visually happened... but look at the log.

There it is! We get a long string, which is the format we can use in the
Ajax call. If you look closely, the product name *does* contain "shoelaces".

To make the Ajax call, we need three things... and we already have the first.
We need the form field data - we have that with `$form.serialize()` and also the
URL to submit to & `method` to use. We can get those last two directly from
the `form` element.

Say `$.ajax()` and I'll pass it the options format where even the URL is
an option. Set that to `$form.`. Now you might expect me to read the `action`
attribute off of the form. But instead, say `.prop('action')`.

[[[ code('e5365a0cd0') ]]]

That's *slightly* different... and bit smarter: this will return the correct action
URL even if there is *no* `action` attribute... which means that a form should
submit back to the *current* URL.

If you look back at `submit-confirm_controller`, *that* time we used
`this.element.action`... which is a property that exists on all `form` elements.
In jQuery, we're asking it to give us that same property.

Repeat this for `method` set to `$form.prop('method')`.

[[[ code('8f66b141b6') ]]]

Finally, for the data, we can say `data` set to `$form.serialize()`.

[[[ code('564e0f2f5c') ]]]

So there you have it: a jQuery version of how you can submit a form via Ajax.

Now, this *will* submit the form... but we want to also *do* something with the
response.

Look at `ProductAdminController::new()`. If we submit to this URL and the form
fails validation, it will automatically re-render our `_form.html.twig` template.
But *this* time the form will render with errors.

So... great! This means that after the Ajax call is finished, we want to put
the returned HTML *back* onto `this.modalBodyTarget`. Copy that and say
`this.modalBodyTarget.innerHTML` equals await `$.ajax()`. Then make `submitForm()`
*async*.

## Debugging the Ajax Error

Moment of truth! Spin over, refresh the page, click the button, but leave the
form blank this time. Hit save and... ah! There's an error!

What happened? Let's find out next... and make sure that our form - no matter
*how* or where it's submitted - will *always* work.
