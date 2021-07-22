# Close the Modal after turbo-frame Success

We just submitted the form in the modal *successfully* and... well, this happened.
Weird. If you refresh, the submit *did* work: this is our new product on top. Inspect
element on the frame so we can see what's going on... it's interesting and...
subtle. Dig a little to find the frame.

Ok, the `src` starts set to `/admin/product/new`, which means that when we open the
modal, we see the contents of the `turbo-frame` from that page. Fill in some data
and then submit.

hmm, the `src` changed to `/admin/product`. Well, that *does* make sense:
if you look `ProductAdminController`, after success, the controller redirects
to `/admin/product` - this is inside of the `new` action.

So we submit to `/admin/product/new` and it redirects to `/admin/product`. When
that happens, the frame system does *two* things. First, it makes a second request
to the redirected URL - `/admin/product`. We've seen that before. And second, it
updates the `src` attribute to match the redirected URL.

This is all perfectly expected. Open the network tab. The second to last request
is the POST request to `/admin/product/new`. That's the form submit. And the
*last* request is Turbo following the redirect to `/admin/product/`.

Look at the response for that request... let's actually look at the raw HTML.
Let's see if we can dig and find the `turbo-frame`. There it is! Yup, it contains
nothing more than "Loading...". *That* is what we're seeing in the modal.

Remember: when the frame system finds a matching `turbo-frame`, it only takes
the frames *HTML*: it does *not* also use the new frame's `src` attribute or
anything else. So even though this frame has `src="/admin/product/new`,
that is *not* used. It grabs the "Loading..." text and... that's it!

So once again, Turbo is behaving exactly like it should... but not necessarily
how we want!

Speaking of that... how *do* we want this to work? If we wanted the modal to
*stay* open but show a new, empty form, we could simply change the controller
to redirect *back* to the new product page. Done.

## Doing Something After a Form Submit

But I want to do something different: after a successful form submit, I want to
*close* the modal. How can we do something *after* a turbo frame navigates?

We already know that Turbo triggers a bunch of events... but there aren't
any events *specific* to turbo frames. There's no, `turbo:frame-start` or
anything like that. *However*, Turbo *does* trigger an event right before
and after a form submits.

In `modal-form_controller.js`, add a `connect()` method. Until now, we've
listened to all of our turbo events inside of `assets/turbo/turbo-helper.js`. The
reason is that all of this code represents *global* behaviors: stuff that we
we're adding to the *entire* page.

## turbo:submit-end in Stimulus

But in this case, we want to listen to an event *only* when a specific controller
is active... so w can run some custom code that affect *just* that controller. Say
`this.element.addEventListener()` and listen to an event called
`turbo:submit-end`. Pass this an arrow function with an `event` argument.

Earlier we listened to `turbo:submit-start`. As you can see, there is *also*
a `turbo:submit-end` event, which happens after the submit Ajax call has finished.
Let's `console.log(event)` to see what it looks like.

Oh, and you probably noticed one big difference between this event and the other
events that we've listened to. Most Turbo events are dispatched directly on
`document`. But the form events - like `turbo:submit-start` and `turbo:submit-end` -
are actually dispatched on the `form` element. Then, they bubble up.

This means that you *can* attach a listener to `document`... or *any* element that
*contains* the form, including the form itself. By attaching the event listener to
`this.element`, our callback will only be executed when a form is submitted
*inside* of this: so inside of the modal. That's... pretty awesome.

Ok, let's see what this event looks like. Move over, refresh the page, open the modal
and submit. Go check the console. There it is! Like other events, this has a
`detail` key with a `formSubmission` inside. Oh, but there's also a `success`
key set to `false`! That would be true if this was a *successful* form submit.
*That's* handy: we can use it to know if the submit was successful and then close
the modal.

Let's go do it! If `event.detail.success`, then `this.modal.hide()`.

Cool. Refresh, open the modal, fill in some details and submit. Go team!

Next: even though we closed the modal, the frame system *still* followed the
redirect and updated the HTML in the modal. In this case, that's not a problem.
In other cases, it could cause an error. Let's find out when and dive even
deeper into the event system to fix it.
