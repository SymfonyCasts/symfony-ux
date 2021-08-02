# Redirecting the Full Page from a Frame

We just did something pretty custom. Normally, if you submit a form into a frame,
if that frame redirects, the new content will be loaded *into* the frame only. The
URL in the address bar won't change and the rest of the page won't be affected.
That's usually what you want!

But sometimes, we *do* want to navigate the entire page, like in a modal. Or,
imagine that you have a sidebar with a form. When you submit and fail validation,
you *do* want that to show in the sidebar. But once the form is successful, you
want to navigate the *entire* window to a confirmation page.

So let's make our frame-redirecting system something that we can use *anywhere*.
Here's the plan: if a `turbo-frame` - like the `turbo-frame` in `_modal.html.twig` -
has a `data-turbo-form-redirect="true"` attribute - which I *totally* just invented -
then we will redirect the whole page if we detect a redirect in that frame.

[[[ code('0e28166ba0') ]]]

## Moving Code to turbo-helper

Because this new redirect behavior will be something that will work *anywhere*
on our site, we need to move the logic *out* of our `modal-form` controller and
into `turbo-helper` where the rest of our global Turbo stuff lives.

Copy the `beforeFetchResponse()` method and delete it. Then, in `turbo-helper`,
paste this at the bottom. Cool.

[[[ code('c479057d74') ]]]

Back in `modal-form_controller`, we don't need the `disconnect()` method anymore.
We're going to register this listener just *once* inside of `turbo-helper`. Copy
part of `connect()`, delete the rest... and we can also remove the Turbo import.

[[[ code('c0e19e76f2') ]]]

Over in `turbo-helper`, go up to the constructor - here it is - and paste. To
call the method, pass an arrow function with an event argument and call
`this.beforeFetchResponse(event)`.

[[[ code('7eed3fefea') ]]]

## Finding the "Active" Frame, if any, for a Request

Ok - go back down to that method. This is *not* going to work yet... because
it's still coded to work with a modal. To bring this to life, we need determine
three things. One: was the Ajax call redirected? Two: did this navigation happen
*inside* of a Turbo frame? And three: does that frame have the
`data-turbo-form-redirect` attribute?

The trickiest of these three is actually figuring out if this Ajax call is
happening *inside* of a turbo frame. This event doesn't give us any indication of
what *initiated* the Ajax call - like which link was clicked or which form was
submitted. But, we can use a trick. Remember: whenever a frame is loading, turbo
gives that frame a `busy` attribute. We can use that.

Create a new convenience method called `getCurrentFrame()`. This is going to return
the `turbo-frame` Element that is currently loading or null. And it's as simple as
return `document.querySelector()` looking for `turbo-frame[busy]`.

[[[ code('3e59075425') ]]]

It *is* theoretically possible that *two* frames could be loading at the same time.
But other than on initial page load if you had multiple lazy frames, I think that's
pretty unlikely.

Above, let's use this. Remove all of this modal stuff... and then move the
`event.preventDefault()` and `Turbo.visit()` to the end of the method... because
we're going to *reverse* the `if` logic to keep things clean. If the `fetchResponse`
did *not* succeed or it's *not* a redirect, then return and do nothing.

But if the response *was* successful and was a redirect, we need to see if we are
inside of a frame *and* make sure that the frame has our data attribute. If
not `this.getCurrentFrame()`, then return and do nothing. And if the current
frame does *not* have `.dataset.turboFormRedirect`, *also* do nothing.

[[[ code('502f59eb46') ]]]

At this point, we know that the Ajax call *did* happen inside of a frame with our
`data` attribute *and* that the Ajax call *did* redirect to another page. And so,
we prevent the frame from rendering and navigate the entire page.

Let's try it! Refresh, open the modal, fill in some info, submit and... got it!
I *know* that worked because the new product showed up thanks to the Turbo visit.

Yay! But... was that too easy? It... kind of was. There are two annoying bugs that
are hiding inside of our new system. Let's add one more turbo frame next that will
expose both of them. Don't worry, by the end, we're going to have a beautiful
bug-free way to force a frame to navigate the whole page.
