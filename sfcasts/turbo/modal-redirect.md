# Preventing turbo-frame Navigation

Coming soon...

I'm going to complicate
things a little bit because I really want us to understand the how to get the most
out of France had ever would you `ProductAdminController` as we just talked about
this redirects to the `product_admin_index` page. Let's pretend that
for some reason, we want to redirect this to the reviews page for the new product. So
that's a `app_product_reviews`, and then we can pass the `id` to the new ID
`$product->getId()`. This change won't apply to our modal. You think about right now, once the
modal is successful, we're simply closing the modal

And staying on the page

And we're completely ignoring the frame. So this new redirected would only affect us
if we want directly to the `/new` page and submitted the form outside of a frame. So
since this won't affect us, it shouldn't cause any issues. I'll refresh. We opened
the modal, put some details and he'd say, oh, the modal did close, but ice and air in
the console response has no matching `<turbo-frame id="product-info">` element. Ah,
the problem is that the turbo frame it's still followed the redirect to the product
review page. And then it looked for a `<turbo-frame>` with ID Eagles, probably invoke on
that page, which it doesn't have. Okay. So what we really want to do is just close
the modal and tell turbo to not the redirect and just stop doing anything with the
frame. Unfortunately, the `turbo:submit-end` event is too late to tell turbo
to do that.

Can, we could ignore this air showing up here or even hack an empty turbo frame onto
that page, but let's fix this properly. All right. So here are the order of events
that are happening with turbo behind the saints. First turbo dispatches
`turbo:before-fetch-request` then `turbo:submit-start`, and then
`turbo:before-fetch-response` and finally `turbo:summit-end`.
And then the frame is rendered. So wait a second.

Why did, why is

`submit-end` too late, then this happens before the frame is rendered. So can't we tell
turbo to not run to the frame. The answer is maybe we should be able to, but turbo
doesn't make that possible. There's no way to cancel the rendering of the frame from
this event, but we can do it from one of those earlier events. So there is a plan
we're going to listen to an earlier event. And then if the form is successful, we
will actually cancel the rendering. So that no matter where we use this modal system,
we're not going to end up with annoying errors like this

So the event that we're going to need to listen to is `turbo:before-fetch-response`. So
that was right after the Ajax call finishes. So technically at this point it has
redirected and the Ajax call, uh, has made the second AGS call to the redirect, but
it hasn't rendered yet. And this time we actually do need to attach this to `document`
because this event is dispatched on the `document`. And for now I'm just going to not
hide the mole just for simplicity. All right. So let's see what this event looks
like.

So I'll refresh

Although some new details, so we can see a successful form submit on this. It saved
and cool. So you actually said there's two of these events. One of them happened
right before it was loading the original form into the modal. And then the second one
happened when we submitted, we open this up and look in detail and as a
`fetchResponse` object inside of it with awesome, a `succeeded` key, and also a `redirected`
keys. So it tells us if it was successful. And also if it was redirected, because
here's what we can do. If this, if when this event happens, if a modal was open and
the AGS call was successful and the angel has caught a redirected, then we can hide
the modal. Yeah.

So check this out. It doesn't make sense quite yet. Don't worry about it. This is a
bit complicated. So I'm going to delete my code here. And I'm just going to say,
okay, if we do not have `this.modal`, which we set down here, that means certainly the
modals modal is not open. So we don't need to do anything. Or if not,
`this.modal._isShown` kind of an internal way to detect whether a modal isn't visible or not.
Either of those things happen, we don't need to do anything. We're just going to
`return`. The modal is not open, but if it is open, say constant `fetchResponse =` and
say `event.detail.fetchResponse` as the object that we were just looking at
over a year, a second ago, then if `fetchResponse.succeeded` and `fetchResponse.redirected`
that we know it's redirecting to another page. And we're, we're going to
choose to do, at least for the moment is just hide the modal.

Now so far, if we did this, this would have the exact same effect as before it would
hide the modal. But then it was still try to render the frame of the redirector page
and give us that annoying air. The key difference between doing this in this event
and the turbo before submit is that this event allows you to cancel the rendering.
The other event did not. So this, in this event, we can say `event.preventDefault()`
Normally we use something like this to prevent form submits or, or prevent
link clicks. In this case, it's actually going to communicate to turbo, to stop
rendering this response. All right. So let's refresh and try it put in some details
and submit beautiful. It closed in this time. They did it without the error, but you
notice the page. It didn't like reload or anything. So you don't see the new product
here

Right away.
Let's look at the log of the successful form. Submit again. Let's see here.
Look inside this `response`. Ooh, look at, we actually have a way to get
what URL was redirected to
And actually there's a different way we can do that. It's actually on the `fetchResponse`
itself that `fetchResponse.location`, which is a fancy object, but it's
just a different way that points to where this is redirected to. So the reason we're
looking at this is that what we really want to do is one, the M is, uh, get the URL
that the form submitted to. And then we can actually just navigate to that page with
turbo.  Check it out at the top of this, I'm going to
`import * as Turbo from '@hotwired/turbo'`

Then down here, I'll remove that `console.log`, we don't need that anymore. Now
we can say `Turbo.visit()` here. We can use `fetchResponse.location`

But for driving this there's a new one last little annoying detail.
when you submit the form successfully, that works.

No, at least in some cases, though, if you go back, you know, I'm also going to read
with you this time modal, that higher don't actually need that anymore for
redirecting to another page. There's no reason to close the modal. And in some cases
I've seen kind of that. Uh, yeah,

I won't say that.

All right. So I'm pretty happy with this. So let's clean things up a little bit. I'm
going to copy the, of this method, the lead them, and then down here, create a new
method called `beforeFetchresponse()` within `event` argument, just doing this for
readability. No I'm connect. We can call that. So we don't even need an error
function. We can say just `this.beforeFetchResponse`, are you sure you don't call it
here? Just point to the function, but be careful because there's a subtle problem
with this of her refresh. And actually I need to go back to the admin page and open
this up and some of the format. Yeah.

Actually let me refresh that again. Add a new product. So there's empty thoughts and
real data say it didn't work that time. And the area is not very obvious, but what
happened here is that the, this variable down here is no longer the, our controller
object. This happens when you pass a function directly to an event listener. And we
normally work around if I pass bypass using an arrow function. But if you do want to point
directly to method, you can by binding the method to this. So I want to do is create
a spread above this. I'll say `this.boundBeforeFetchResponse`. So I'm actually
creating a new property, `= this.beforeFetchResponse`, but don't call the method, just
say `.bind(this)`. And then below I'll point at the bound before the federal
response. So this creates a new property that points to this function. But when
that's called the, this object is going to be the controller object, no matter what,
this is not a stimulus problem. This is how Jarvis is sort of a JavaScript and event
listener problem. What this does it solve our issue.

I submit now we get the good behavior. Oh, but I do want to add one tiny detail over
here at a, `disconnect()` method. And then a copy of the `document.addEventListener`
listener, but do change it to `document.removeEventListener`. Why are we doing this?
If we add an event listener to a controller's element like `this.element` then if that
element is removed from the page, it's no big deal. Nothing can interact and trigger
events on that element anymore. Anyways, what if we add an event listener to the
`document`, then every time a new `data-controller="modal-form"` appears on the page,
Our connect method will be called

And we'll attach yet. We'll attach yet another event listener. So to be responsible,
let's clean up that in `disconnect()`, which is called when the element we're attached
to is removed and the page finally, to make our new modal field, the most awesome
head back to `ProductAdminController`. And let's actually read direct change the
reject back to the, uh, `product_admin_index`, which just makes more sense. So try the
entire process, have it admin area, and then I'll do a full refresh. If we clicked
the modal that was via the molded frame, we can save it. That saves via the mobile
frame. And if we fill in some new, real data, this is going to submit normally be at
a terrible frame. We'll detect that it was successful and boom, our item shows up. So
when we tag, when we detect successful, we close the modal. And then we navigated
back to this page. We redirected back to this page,

Which is why the new product showed up, but

Reality, it wasn't a full page refresh, redirect. We navigate it with turbo, which
made the whole thing feel really smooth. So next we just did something pretty custom.
We submitted a form into a turbo frame, but then navigating the entire page on
success is, is not something turbo does natively, but it's kind of handy sometimes.
So let's add a reusable way to do this whenever we want.
