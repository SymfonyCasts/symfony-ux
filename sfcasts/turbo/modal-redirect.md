# Full Page Redirect from a Frame

Coming soon...

beautiful. It closed in this time. They did it without the error, but you notice
the page. It didn't like reload or anything. So you don't see the new product her


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

