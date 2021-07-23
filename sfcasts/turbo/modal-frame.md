# turbo-frame inside a Modal

Let's do one more big thing with the frame system. Go to the product admin page and
click to add a new product. In the last tutorial, we used Stimulus to open this in
a modal, make this form *submit* via Ajax inside the modal, make the modal close
on success and then reload the list with Ajax. An entire experience with no
full page refreshes.

The stimulus controller for this lives at
`assets/controllers/modal-form_controller.js`. This `openModal()` is called when
we click to add a new product: it opens the modal and makes an Ajax call to
*populate* that modal with the form HTML. The `submitForm()` is called when the
form is submitted and its job is to Ajax-submit the form and close the modal
on success.

We're revisiting this example because, by leveraging Turbo frames, I think we can
simplify this... like, a lot. And you can probably guess how: we can use a turbo
frame to load the initial contents of the modal *and* to make the form submit
*stay* in the modal.

## Refactoring to a turbo-frame

The modal's markup lives in `templates/_modal.html.twig` and this is meant to
be reusaable in multiple places. This `modal-body` element holds the actual
*content*.

Let's transform this into a `<turbo-frame>`. To keep things usable, set the frame's
`src=""` to a new `modalSrc` variable that we will pass *into* this template.

Now open the template for the product admin list page:
`templates/product_admin/index.html.twig`. There's a lot going on here: we activate
the `modal-form` Stimulus controller here. We also have a Stimulus controller for
`reload-content`. It's job was to reload the product list after the modal
closed successfully. We're going to be removing *a lot* of this stuff soon.

What I want to focus on right now is down here where we *include* that modal.
Pass in that new `modalSrc` variable set to `path('product_admin_new)`
because that's the page that holds the "new product form" that we want.

Before we try this, let's delete some code in `modal-form_controller.js`. In
`openModal()`, we don't need to set the `innerHTML` to "Loading" - that can live
directly in the frame - and... we don't need to manually make an Ajax call at all!
That's going to happen automatically just because we're setting the `src` attribute
on the `<turbo-frame>`.

Also `submitForm()`... yea, we're not going to need this at *all*. The turbo frame
will handle the form submit all on its own. And thanks to these changes, one of the
targets up on top - `modalBody` - is no longer used. So we can remove that too.

Yup, the job of this controller is getting... pretty simple!

Back in `_modal.html.twig`, to finish our cleanup, we don't need the `modalBody`
target... and we also don't need the `data-action` that called the `submitForm`
method that we just deleted.

## Forgetting the id Attribute

Ok team: let's try this! Refresh the page. Hmm, nothing happened. In the console,
whoa!

> Failed to execute `querySelector` on element: `turbo-frame#` is not a valid
> selector.

What is that? Well, it's not a great error, but something is looking for a
`turbo-frame` with a certain id - that's this `#` part. But oh! I forgot to give
our frame an id! Whoops.

Head back to `_modal.html.twig`. I want to keep this dynamic because different
modals may need different frame ids. So say `id="{{ id }}"`.

Over in `index.html.twig`, pass in the new `id` variable set to `product-info`.
That's the `id` we've been using... and it really could be *anything*, as long
as it matches a frame on the new product page.

Ok: let's keep trying. Refresh and add a new product. Error!

> Response has no matching `<turbo-frame id="product-info">` element.

Ah, I remember. In `edit.html.twig`, we added a `<turbo-frame>` *there*... but
we never added the `<turbo-frame>` in `new.html.twig`. We could just move the
`turbo-frame` into `_form.html.twig` because that's included on both pages. The
disadvantage is that we added the frame in `edit.html.twig` on purpose so that our
inline editing feature would include the "edit product" `h1` tag and the delete
button. So instead, let's just add the same `<turbo-frame>` over here in
`new.html.twig`.

Attempt number 3! Refresh and click. Got it!

## Using Real Buttons vs Modal Footer

But if we try to submit this... error!

> Error invoking action `click->modal-form#submitForm`.

Ok, so something is *still* trying to call the `submitForm()` method that we
deleted a few minutes ago. In `_modal.html.twig`, this is coming from the
`modal-footer`. In this last tutorial, we added a button down here to submit
the form. But this button is actually *outside* of the form, which lives in
the `turbo-frame`. What we need to do, yet again, is simplify. Remove the
`modal-footer` entirely.

If you refresh and open the form... the footer buttons are gone... but there is now
*no* submit button on the form! Well, there *is* one, but it's hiding: you can
see it if you inspect element and do some digging. Yup, we hid this button in
the last tutorial when it's inside a modal via CSS so that the modal-footer buttons
could take precedence. Now, we're going to *undo* that so that our form is perfectly
boring and normal: a form... with a button.

Open `assets/styles/app.css` and search for `modal-body`. Delete this section.

Try the modal again... and... it works! And it's *so* boring, I absolutely
love it. Try to submit the form. Um, well... that *did* work, but it submitted
the whole page! Next, let's fix this, make the modal load lazily and delete even
*more* code from the modal system.
