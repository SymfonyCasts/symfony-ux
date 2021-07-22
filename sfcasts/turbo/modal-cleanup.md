# Lazy Modal & Big Cleanup

Coming soon...

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
anything. So I'll open up the modal. It loads. I can submit it empty. So let's add a
brand new product, all the title, price it's saved and oh, that's interesting. It
says loading next. Let's figure out what's going on here and then code up the real
solution that we want, which is after the forms submit successfully, we want to close
the modal and make sure we reload the list behind us.
