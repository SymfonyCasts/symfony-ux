# Modal Frame

Coming soon...

Let's do one more thing with the frame system, go to the product admin page and click
to add a new product. In the last tutorial, we use stimulus to open this in a model
and to make the form submit via Ajax right in the modal, and to make the modal
clothes on success and reload the list behind this all with Ajax and no full-page
refreshes. The stimulus controller for this is `assets/controllers/modal-form_controller.js`
It's fairly basic. This `openModal()` is called on click. It reads makes an
Ajax call to populate a certain `<div>` inside the modal. And then the `submitForm()` is
called when the form is submitted and it Ajax summits the form, and does a few other
things. So with turbo frames, we can simplify this a lot and you can probably guess
how we can use a turbo frame to load the contents of the model and to keep this
submit, submit in the model the model is created and it templates `_modal.html.twig`
and this template is meant to be reused for other modals random of this.
You can see the `modal-body` that actually holds the muddle content.

Let's change this into a `<turbo-frame>`, and to keep things usable. We'll set the `src=""`
to a new `modalSrc` variable that we can pass into this template. Now open the
template for the product admin index page, which is `templates/product_admin/index.html.twig`
thaSo there's kind of a lot going on here. We have a stimulus controller
from modal form here. We also have a stimulus controller for reload content up here,
which is responsible for reloading the list after the middle closed. We're going to
be removing all a lot of that stuff in a second. What I want to focus on right now is
down here. This is where we include that model. And now we're going to pass in a new
`modalSrc` of variable and set this to `{{ path() }}` and then point to the product admin
index template, sorry, `product_admin_new` route, because that's the page that holds
the new product form that we want. Before we try this, let's delete some code over in
`modal-form_controller.js` First thing is in the `openModel()`.

We're not going to need to set the `innerHTML` with a loading, and we're not going to
need to make the Ajax call. That's going to happen automatically just because we're
setting the source on the `<turbo-frame>`, to what we want it to be, and also `submitForm()`
We're not actually going to need this at all. The turbo frame is going to
handle that entirely. And because of this one are a targets up here. You can see it's
called `modalBody`. We're not actually using that `modalBody` target anymore. So we
can remove that as well. And you can see that the job of this modal is getting this
controller is getting pretty simple. It's just to basically open the model now over
and `_modal.html.twig`, we don't need the `modalBody` target anymore. We just
deleted that. We also don't need the `data-action` that caused that submit form
either.

All right, let's try this. I'll refresh the page quick. And nothing happens in the
console. Whoa, fail to execute query selector on element `turbo-frame#` is not a
valid selector. Whoa, what is that? Well, it's not a great air internally. Something
is looking for a turbo frame with a certain ID. That's this pound part, but ah, our
frame doesn't have an ID. I come lately. Forgot that part. Whoops. All right, let's
give it one over and `_modal.html.twig` I want to keep this dynamic, cause it might not be
different for other models. So say `id="{{ }}"`, and then we'll just say `id`

Over in our `index.html.twig` let's pass in that new variable `id`. And
we're going to set this to `product-info`. That's the same ID that we've been
using, uh, in other places. All right, so let's just keep drying refresh and a new
air response has no matching `<turbo-frame id="product-info">` element. Ah, I remember we
in `edit.html.twig`. We added a `<turbo-frame>` there, but we never added the
`<turbo-frame>` in new diets. We have two options here. We could just move the turbo
frame into `_form.html.twig`, because that is included on both pages. The
disadvantage is that we did this on purpose so that our inline editing feature would
have the edit product h1 and the delete form button. So instead of doing that,
let's just add this same `<turbo-frame>` over here in `new.html.twig`

Now refresh and open. It works. But if you submit error, Aaron Woking action, click
modal form submit form. So we can't find the submit format method on our modal form
controller. And that's true because we just deleted that a second ago. And we did
that a purpose we wanted to remove that we don't need custom stimulus code to handle
the form submit. We can just let the turbo frame do that. The problem is that these
buttons down here are not actually coming from the form itself. These are coming from
the modal footer. So once again, we're gonna just kind of, kind of keeps simplifying
here. I'm going to delete the modal footer entirely.

If you refresh to form, now those will be gone, but you'll notice that there is no
submit button on our form. It's not just cause it's hiding down here. There is no
submit button on our form inspect element and open that up and find the button. The
button is actually hidden with display none with some CSS. This is again something we
did in the last tutorial. We, since we have those Modo footer buttons, we decided to
add some CSS to hide any buttons that were inside of the middle body. Whereas you're
going to undo that now we're kind of making things more boring and more normal. So
this is coming from `app.css`. So assets styles at best CSS search for `modal-body`
Here we go. And the lead that CSS.

So at this, when I refresh, we have a really boring modal. I click this. It's letting
be the frame. There's nothing special happening. This is the normal button from the
form. Well it's summit. Let's try it summit and sort of, yeah, it did work, but it
submitted the full page. All right, let's see what's going on here. Reopen the modal
and let's inspect it. Let's see. Ah, look at the form here. It is `data-turbo-frame="_top"`
that is coming from the `_form.html.twig` template is a reminder.
A second ago, we set the `data-turbo-form` attribute to a dynamic form target variable.
And the point of this was so that if the form were being loaded in a frame, then it
would you, it would target that frame else. It would target the top of the page. The
problem is that we only set this up for the edit page. So you feel like at 
`src/Controller/ProductAdminController.php` right here, this is the `edit()` action. And we did
pass in this `formTarget` variable. That's set to the turbo frame request header, but
I did not do that for the new page. The new page does not have that variable. And so
it was just defaulting to `_top`. So let's pass that variable in for the new page as
well.

This is just yet another thing where we're kind of making things simpler and more
consistent to get this to work. All right, refresh again, open that up. Submit and oh
God, it's beautiful. We still need to work on what happens when we fill this out
successfully. But before we do, we can do something cool. Refresh the page and then
inspect element on the button. What I'm really looking for here is the turbo frame
that contains the modal. So let me open up a couple of things here. Turbo frame,
modal body. If you expand this, you'll notice that it is already made the Ajax
request for the form that happens as soon as the page load, as soon as the page
loads, it sees the `src` and it makes an AGS call to this URL. But if we want to, we
can make this lazy. We don't really need to make that age. I've called until the
modal is visible until it opens. We did this before by adding a `loading="lazy"`
attribute. So let's do that and `_modal.html.twig` let's add `loading="lazy"`.
All right. Let's refresh again. And awesome. Look at the element. It
still says loading right there, but we don't care because it's not actually visible.
If you go to your network tools and go to XR, watch what happens when you click this.
As soon as that frame becomes visible, then it actually makes the Ajax call to load,
which is pretty cool.

At this point, if you look at our `modal-form` controller, the only point of this
controller is just to open up the modal with bootstrap, which is pretty cool. So we
can clean up even more stuff. We don't need to, uh, uh, `useDispatch` anymore. That
helps us dispatch an event. Oops

And we don't need to import useDispatch or jQuery

We also don't need this `formUrl` value because the frame is handling all of
that for us. In the template for the product index page.

We still do need to have the `modal-form` controller up here, but we don't need to pass
in this form. You were out variable anymore. That's the one we just deleted. And
above this, we have some fanciness with the `reload-content` controller, uh, is there,
as I mentioned, that helped us reload the list via Ajax after the Myrtle closed,
we're going to replace that with something simpler in a second. So I'm just going to
delete all of that stuff up here.

And finally, down near the bottom, we had a specific, um, targets for that 
`reload-content` controller that we just deleted. So we can also remove that target. Honestly,
it may have been easier just to start this feature from scratch because we are
simplifying so much stuff. Alright, so refresh again. Let's make sure we didn't break
anything. So I'll open up the model. It loads. I can submit it empty. So let's add a
brand new product, all the title, price it's saved and oh, that's interesting. It
says loading next. Let's figure out what's going on here and then code up the real
solution that we want, which is after the forms submit successfully, we want to close
the model and make sure we reload the list behind us.

