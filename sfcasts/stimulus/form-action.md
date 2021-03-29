# Correcting the Form Action & Preventing Default

Submitting the form via Ajax just exploded! We can see the error down here: the
POST request failed with a 405 error. Open that link in a new tab so we can see
what happened.

Ah:

> No route found for POST /admin/product: method not allowed, allowed get

This is *not* the URL that we expected: we expected this to submit to
`/admin/product/new`

What happened? I was trying to be *really* responsible by reading the `action` and
the `method` directly from the form. The *problem* is, if you inspect the form...
there *is* no `action` attribute! That's normally fine: it just means that the
form should submit back to the current URL.

When we render this form on the *actual* new product page - `/admin/product/new` -
the empty `action` is ok because it will submit to this URL. But over here
on the list page, the missing `action` attribute makes it look like it should submit
right back to *this* URL. That's not what we want!

To fix this, instead of `$form.prop('action')`, use the `formUrl` value:
`this.formUrlValue`. This assumes that the same URL that you use to *fetch* the
form is also the URL that should be used to *submit* the form.

Let's try that again. Refresh the page, hit add, and Save. Yes! Look at that!
We instantly see validation errors! If I fill out one field and submit again,
it goes away!

## Preventing the Full Form Submit

But there is one *tiny* problem. If I focus on a field and hit enter...
oh... actually let me fill in a price. Now hit enter. Woh! The form submitted! But
*not* via Ajax.

No problem. In addition to executing the `submitForm()` method when we *click* the
"Save" button, we *also* need to execute that method if the form is *submitted*.

But wait: does this mean that we need to add a Stimulus `data-action` attribute to
the `form` element... inside this `form_start()` function? I thought we were trying
to avoid needing to add stuff to this template!

Fortunately, we do *not* need to do that. In `_modal.html.twig`, break the
`modal-body` element onto multiple lines for readability. Now add
`data-action=` the name of the event - `submit`, `->`, the name of our
controller - `modal-form` - `#` sign and then `submitForm`.

Remember: events bubble up. The `submit` event is *first* dispatched on this `form`
element. But then it bubbles up to the `modal-body` div. That means that this
element will *also* receive the `submit` event.

To *prevent* the form from actually *submitting*... so that we can make the
Ajax call, add an `event` argument to `submitForm()` and say `event.preventDefault()`.

When we hit enter on the form, that will prevent the submit. When we click the save
button, this will have *no* effect... because clicking a button outside of a
form doesn't *do* anything by default. So there's nothing to prevent.

Try it out. Reload, open up the modal and hit enter. Bah! HTML 5 validation is
keeping me honest. Fill in the two required fields... but put a negative price
so we hit a validation error. Hit enter now. Gorgeous!

Next: this is working *awesome* when there's a validation error. But if the submit
is successful, we need to do something different: close the modal.

To do that, we need to create a systematic way for our controller to communicate
whether or not the form submit was successful. This new idea will serve us *super*
well in the next tutorial when we Ajaxify more form submits with Turbo.
