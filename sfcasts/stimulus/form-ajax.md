# Ajax-Submitting an Entire Form

When we click the "Save" button, we want to submit the form via Ajax. Take a look
at the structure of the modal. The save button is actually *outside* of the form:
the form lives inside of `modal-body`. This means that we *can't* add a Stimulus
submit action to the form... because clicking save won't actually submit the form!
The button does nothing! Instead, over an `_modal.html.twig`, we're going to an
action to the `button` itself.

Add `data-action=`, we can just use the default action for a button, which is
`click` - then our controller name - `modal-form` - a `#` sign and then a new
method name. How about `submitForm`.

Copy that name and go add it to our stimulus controller `submitForm()`.

## Finding the form Without a Target

Let's see: we need to find the `form` element so we can read the data from all of
its fields. Normally, when we need to find something, we add a target. Should we
do that now?

We *could*. But I'd like to make it as *easy* as possible to reuse our `modal-form`
controller for *other* forms. If we can avoid needing to add extra attributes to
the `form` element, which is rendered by this a `form_start()` function, that
will make reuse *much* easier.

Instead, in our controller, let's leverage the `modalBodyTarget`, which is going
to be this element right here - and look inside of it for a `form` element with
jQuery. We can do that with: `const $form = $(this.modalBodyTarget)`, then
`.find('form')`.

If you use jQuery in Stimulus, you will always use `$()` and then some element
so that we're looking *inside* that element, instead of inside the whole page.
If we wanted to look for something inside of our *entire* controller element,
we would use `this.element` inside. The point is: we always want our selecting
to be looking *inside* our controller.

I also, as a convention, prefix my variable names with a `$` when they are jQuery
objects... there's nothing special about that variable name.

If you wanted to do this *without* jQuery, it would be really similar. You would
use `this.modalBodyTarget` then `.getElementsByTagName()`, pass it `form`... and
use the `0` index off of that.

## Making the Ajax Call

Anyways, to make the Ajax call, we're going to need the data from all of the
fields in the form. Without jQuery, if you look at our `submit-confirm_controller`,
and scroll down to `submitForm()`, we learned that you can do this with a
combination of `URLSearchParams` and `FormData` passing it the `form` element,
which in this case was `this.element`.

We *could* do that same thing here. But since we're using jQuery, there's a
shortcut: `console.log($form.serialize())`.

Let's try that. Move over, refresh the page, open the model and fill in at least
one of the fields. Hit save. Nothing visually happened. Look at the log.

There it is! We get a long string, which is the format that we can use in the
Ajax call. If you look closely, the product name *is* set to "shoelaces".

To make the Ajax call, we  need three things... and we already have the first one.
We need the form field data - we have that with `$form.serialize()` and also the
URL to submit to and the `method` to use. We can get those last two directly from
the `form` element.

Say `$.ajax()` and this time I'm going to pass it the options format where even
the URL is an option. Set that to `$form.`... now you might expect me to read
the `action` attribute off of this. Instead, say `.prop('action')`.

That's *slightly* different... and bit smarter: this would return the right action
URL even if there *were* no `action` attribute... which means that a form should
submit back to the current URL.

If you look back at `submit-confirm_controller`, *that* time we used
`this.element.action`... which is a property that exists on all `form` elements.
In jQuery, we're asking it to give us that same property.

Do the same thing for `method` set to `$form.prop('method')`.

Finally, for the data, we can say `data` set to `$form.serialize()`?

So there you have it: a jQuery version of how you can submit a form via Ajax.

Now, this *will* submit the form... but we want to also *do* something with the
response.

If you look in `ProductAdminController`, if we submit to this URL, and the form
fails validation, it will automatically re-render our `_form.html.twig` template,
but *this* time the form will render with errors.

So... great! This means that after the Ajax call is finished, we want to put
the returned HTML *back* onto `this.modalBodyTarget`. Copy that and say
`this.modalBodyTarget.innerHTML` equals await `$.ajax()`. And then make `submitForm`
*async*.

## Debugging the Ajax Error

Moment of truth! Spin over, refresh the page, click the button, but leave the
form blank this time. Hit save and... ah! There's an error!

What happened? Let's find out next... and make sure that our form - no matter
*how* or where it's submitted - will work properly.
