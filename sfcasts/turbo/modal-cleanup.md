# Lazy Modal & Big Cleanup

Our modal *is* now powered by a `turbo-frame`: the form was Ajax loaded by the
frame system. But when we submit, wah, wah. It submits to the whole page.

Let's see what's going on. Reopen the modal and inspect it. Hmm. Ah, look at the
`form`. It has `data-turbo-frame="_top"`! That's coming from `_form.html.twig`.
Remember: a few minutes ago, we set the `data-turbo-form` attribute to a
dynamic `formTarget` variable. The point of this was so that *if* the form is
being loaded into a frame, then we *target* that frame. Else, if the form
is being loaded via a normal page load, target `_top`.

The problem is that... we only set the variable for the *edit* page. Open
`src/Controller/ProductAdminController.php`. Right here - this is the `edit()`
action - we *did* pass in the `formTarget` variable that's set to the `Turbo-Frame`
request header. Go us! But... I did *not* do that for the `new` action. And
since that does *not* pass a `formTarget` variable, it defaulted to `_top`.

So let's pass that variable in for the new page as well. This is yet *another*
spot where, to get this turbo-frame-powered modal working, we're making things
simpler and more consistent.

Ok: refresh again, open the modal, submit and... oh, that is positively
heart-warming.

## Lazy Modal Loading

We still need to work on what happens when we submit the form *successfully*... but
before we do, let's do something cool. Refresh the page and inspect element on the
button. Dig a little to find the `turbo-frame` that contains the modal. Here it is.
If you expand this, you'll notice that Turbo *has* already made the Ajax request
for the form and put the HTML here. That happens as *soon* as the page loads.

But we don't *really* need to make that Ajax call until the modal opens. Could we
somehow *delay* that? Totally! And we did this earlier.

In `_modal.html.twig`, on the `turbo-frame`, add `loading="lazy"`.

Let's see how this looks. Refresh and inspect the frame. It still says "Loading":
it has *not* made the Ajax request yet. Open your network tools and watch the
Ajax requests. Click to open the modal! There's the Ajax call!

Remember: with `loading="lazy"`, the frame system won't make the Ajax request until
the frame becomes *visible* in the viewport. And... that works pretty awesomely
with modals which don't become visible until you open them.

## Big Ol' Cleanup

At this point, if you look at the `modal-form` controller, its only job is to...
open the modal! The `turbo-frame` inside handles the rest... and that's *pretty*
cool. Let's cleanup a few more things: we don't need `useDispatch` anymore: we're
not dispatching any events... whoops. And... we don't need to import `useDispatch`
or `jQuery`... and we can also delete the `formUrl` value.

Cool. In the template for the product index page, we still *do* need the
`modal-form` controller but we do *not* need to pass in the `formUrl` variable.

Above this, we have some fanciness with the `reload-content` controller. That
helped us reload the product list via Ajax after the modal closed. We're going to
completely replace that with something simpler in a few minutes. So delete *all*
of that stuff.

Finally, near the bottom, remove this target, which was for the `reload-content`
controller.

Honestly, I'm wondering if it might have been easier to start this feature from
scratch! Because most of the work we just did was deleting and simplifying.

Let's make sure we didn't break anything. Refresh, open the modal and submit the
form empty. That feels great!

But what happens on a *successful* form submit? Fill in a title, price and...
go! Woh. That's... interesting. It says "loading". Next, let's figure out what
just happened. And then, we'll code up the *real* solution: after a successful
form submit, we want to close the modal and reload the list behind us. We're
about to bend the frame system to our will!
