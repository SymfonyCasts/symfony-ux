# Ajax Form Response Status Codes

Open the modal and fill out the name and price fields: the only two required fields.
I want to see what happens if we submit the form successfully.

And... whoa! That was weird. On success, our controller currently redirects back
to `/admin/product`. When jQuery's Ajax hits a redirect, it *follows* it and makes
a *second* Ajax request. We can see this in the Network tab: here's the original
request and here's the request from the redirect. And because that redirects to
the list page, it grabbed the *full* HTML for that page and jammed it into the modal.

That's... *probably* not what we want. Instead, I want to close the modal. But to
do that, we need to be able to determine - from JavaScript - whether a form submit
was successful *or* had a validation error.

But, at the very least, if we refresh the page, we *do* see the new product in
the list. So, yay! We know the save part is working.

## On Success: 204 Empty Response

Over in the controller, if the form is submitted via Ajax, instead of a redirect,
what *should* we return? How about an empty, successful response. We can do that
with if `$request->isXmlHttpRequest()` - or you might instead be looking for a
query parameter if you decided to use that strategy - then return
`new Response()` - the one from HttpFoundation - passing it `null` for the
content and `204` for the status code.

[[[ code('312d6c2a23') ]]]

204 is a special status code that means:

> This response was successful! Yay! But... I have no content that I want to return.

So: an empty - but successful - response. That'll fix the redirect issue.

## On Error: 422 Error Response

But there's still one problem. When the form has validation errors, we're
currently returning a 200 success status code: that's what `$this->render()`
uses by default.

In other words, whether the form submit is successful or not, *both* situations
return a successful status code. That... doesn't make our life very easy in JavaScript
where we need to figure out which situation we're in! Sure, we could look *exactly*
for a 200 versus 204 status code... but... there's a better way.

What is it? Return an *error* status code when the form fails validation. This will
not only make our life easier in JavaScript... it's also more correct! And it will
still work *fine* if you're submitting a form *normally* without JavaScript: the
error status code won't confuse old browsers or anything like that.

The third argument to `render()` is a `Response` object that the HTML from the
template will be put *into*. When this is *not* passed, a `Response` object with
a 200 status code is automatically created. Let's pass our *own*:
`new Response()` - the same one from HttpFoundation - passing `null` for the
for the content... because that's going to be replaced by the HTML from the template
anyways.

The really important thing is the status code. But this `render()` method
is called both when the form is *originally* loaded *and* when it's submitted with
invalid data. So we need to figure out which situation we're in. Use the ternary
syntax here to say: if `$form->isSubmitted()`, then use `422` else `200`.

[[[ code('311ded6cda') ]]]

This works because - if the form was submitted *and* was successful - we would already
be inside the first `if` statement... and we would never get down here. So if the
form is submitted, we definitely know it's an *invalid* submit.

The `422` status code means "unprocessable entity". And it's a pretty standard
status code for validation errors. As a bonus, doing this on your forms will play
*super* nicely with Stimulus's sister technology Turbo. Oh, and by the way, in
Symfony 5.3, there is a new `renderForm()` shortcut in your controller, which will
automatically set the `422` status code for you on error. That'll make this much
cleaner.

***NOTE
If using Symfony 6+, `renderForm()` has been deprecated/removed. Just use `render()` as
usual which now has this behaviour behind the scenes!
***

## Try/Catch the Ajax Call

Back in our Stimulus controller, now we have the info we need. When the form
submit fails validation, the 422 status code will cause the `await $.ajax()`
to throw an exception. So let's wrap this in a try catch block.

Say, `try`, `catch`:

[[[ code('f999e76b87') ]]]

and what we want to do is take out
`this.modalBodyTarget.innerHTML` because we *only* want to do that on error. In
the catch, say `this.modalBodyTarget.innerHTML` and the response text is available
on the error object as `e.responseText`.

[[[ code('5d13d8735f') ]]]

In the successful situation, for now, just `console.log('success')`.

[[[ code('7035b1c1c2') ]]]

Ok team - let's see what happens! Refresh. Open the modal and let's first submit
the form empty. Beautiful! We have errors! Fill in name and price... and submit
again. Okay.... nothing happened. But if we check the console... yes! There's our
log! All we need to do *now* is close the modal on success.

## Closing the Modal on Success

We can do that by calling `hide()` on this modal object. The only problem is that...
we don't have *access* to that object from down here in `submitForm()`! That's ok:
this is where having a controller object comes in handy! We can set that modal on
a property.

Up at the top of the class... we don't have to do this because JavaScript is friendly,
but I'm going to say `modal` equals `null` to initialize the property. Then down
in `connect()`, update to `this.modal` equals `new Modal()` and
`this.modal.show()`.

[[[ code('fa1a342734') ]]]

Now, after the Ajax call, replace `console.log('success')` with
`this.modal.hide()`.

[[[ code('c818596b08') ]]]

Let's try it now. Refresh - there's my awesome zip drive from the last submit - open
the modal, fill out the field and... submit! OMG! We have a fully-functioning
Ajax modal system. *And*... it's *completely* reusable!

The only *imperfect* thing is that we don't see the new item on the page, until
we refresh. No worries: we'll handle that in the next chapter.

## Listening to Bootstrap Modal Events

But before we do, search for "bootstrap 5 modal", click into its docs, and then go
down to the "Events" section at the bottom. As the modal opens and closes, the
modal *itself* dispatches some events. What if we needed to listen to those? Like,
what if we need to run some custom code whenever a modal is closed. How can we do
that? This is the beauty of Stimulus. We already know how! If something dispatches
an event, all *we* need to do is add an *action* for that event.

Check it out: over in `index.html.twig`, I'll break this
onto multiple lines and then add a `data-action=""`. What we're going to do is listen
to this `hidden.bs.modal` event, which happens after the modal is finished being
hidden.

Use that event name, then `->`, the name of our controller - `modal-form` - a
`#` sign and then call the new method `modalHidden`.

[[[ code('f172381d7f') ]]]

Now, the `hidden.bs.modal` event *won't* be dispatched directly on *this*
`<div>`. It will be dispatched on the modal element. But we already know that's okay!
The event will bubble *up* to *this* `<div>`.

Copy `modalHidden` and head into our Stimulus controller. At the bottom add that
method and let's just `console.log('it was hidden')`.

[[[ code('6dd39aff0e') ]]]

Try it out! Go back to our site, refresh, open the modal and hit cancel. There's
the log! Open it again, hit "X" and... *another* log. I love that.

Next: to make this a truly smooth user experience, after a successful form
submit, what we should *really* do is make another Ajax call to reload the product
list... so that the user can *see* the new product. Let's do that by, once again,
making a reusable Stimulus controller. This controller will be able to reload the
HTML for *any* element.
