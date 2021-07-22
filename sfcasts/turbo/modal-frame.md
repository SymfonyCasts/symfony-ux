# turbo-frame inside a Modal

Let's do one more big thing with the frame system. Go to the product admin page and
click to add a new product. In the last tutorial, we used Stimulus to open this in
a modal, make this form *submit* via Ajax inside the modal, make the modal close
on success and then reload the list with Ajax. So, an entire experience with no
full page refreshes.

The stimulus controller for this lives at
`assets/controllers/modal-form_controller.js`. This `openModal()` is called when
we click to add a new product: it opens the modal and makes an Ajax call to populate
that modal with the form HTML. The `submitForm()` is called when the form is
submitted and its job is to Ajax-submit the form and handle closing the modal
on success.

We're revisiting this example because, by leveraging Turbo frames, I think we can
simplify this... quite a lot. And you can probably guess how: we can use a turbo
frame to load the initial contents of the modal *and* to make the form submit
*stay* in the modal.

## Refactoring to a turbo-frame

The modal's markup lives in `templates/_modal.html.twig` and this is meant to
be reusaable for other modals. This `modal-body` element holds the actual
*content*.

Let's transform this into a `<turbo-frame>`. To keep things usable, set the frame's
`src=""` to a new `modalSrc` variable that we will pass *into* this template.

Now open the template for the product admin list page:
`templates/product_admin/index.html.twig`. There's a lot going on here: we activate
the `modal-form` Stimulus here. We also have a stimulus controller for
`reload-content`. It's job was to reload the product the list after the modal
closed. We're going to be removing *a lot* of this stuff in a minute.

What I want to focus on right now is down here where we *include* that modal.
Pass in that new `modalSrc` variable set to `{{ path('product_admin_new) }}`
because that's the page that holds the "new product form" that we want.

And before we try this, let's delete some code in `modal-form_controller.js`. In
`openModal()`, we don't need to set the `innerHTML` to "Loading" - that can live
directly in the frame - and we don't need to manually make an Ajax call at all!
That's going to happen automatically just because we're setting the `src` on the
`<turbo-frame>`.

Also `submitForm()`... yea, we're not going to need this at *all*. The turbo frame
will handle the form submit entirely. And thanks to these changes, one the targets
up on top - `modalBody` - isn't used anymore. So we can remove that as well.

Yup, the job of this controller is getting pretty simple!

Back in `_modal.html.twig`, to finish our cleanup, we don't need the `modalBody`
target anymore... and we also don't need the `data-action` that called the
`submitForm` method that we just deleted.

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

Over in `index.html.twig`, pass in that new variable `id` set to `product-info`.
That's the `id` we've been using... and it really could be *anything*, as long
as it matches a frame on the new product page.

Ok: let's keep trying. Refresh and add a new product. Error!

> Response has no matching `<turbo-frame id="product-info">` element.

Ah, I remember we. In `edit.html.twig`, we added a `<turbo-frame>` *there*... but
we never added the `<turbo-frame>` in `new.html.twig`. We could just move the
`turbo-frame` into `_form.html.twig`, because that's included on both pages. The
disadvantage is that we added the frame in `edit.html.twig` on purpose so that our
inline editing feature would include the "edit product" `h1` tag and the delete
button. So instead, let's just add the same `<turbo-frame>` over here in
`new.html.twig`

Attempt number 3! Refresh and click. We got it!

## Using Real Buttons vs Modal Footer

Now refresh and open. It works! But if try to submit this... error!

> Error invoking action `click->modal-form#submitForm`.

Ok, so something is *still* trying to call that `submitForm()` method that we
deleted a few minutes ago. In `_modal.html.twig`, this is coming from the
`modal-footer`. In this last tutorial, we added a button down here to submit
the form. But this button is actually *outside* of the form, which lives in
the `turbo-frame`. What we need to do, yet again, is simplify. Remove the
`modal-footer` entirely.

If you refresh to form now... those will be gone... but there is now *no* submit
button on the form! Well, there *is* one, but it's hiding: you can see it if you
inspect element and do some digging. Yup, we hid this button when it's inside
a modal via CSS so that the modal-footer buttons could take precedence. Now, we're
going to *undo* that so that our form is perfectly boring and normal: a form with
a button.

Open `assets/styles/app.css` and search for `modal-body`. Here we go: delete this
section.

Now if we try the modal again... it works! And it's *so* boring, I absolutely
love it. Try to submit the form. Um, well... that *did* work, but it submitted
the whole page! Next, let's fix this, make the modal load lazily and delete even
*more* code from the modal system.
