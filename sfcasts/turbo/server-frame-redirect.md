# Smart Frame Redirecting with the Server

When an Ajax request happens via a `<turbo-frame>` *and* that frame has our
`data-turbo-form-redirect` attribute, we're now communicating that to Symfony
by sending a new header on the request called `Turbo-Frame-Redirect`. We're
now going to *use* that to *change* any redirect responses to, sort of, "fake
redirects" so that the `fetch()` function in JavaScript doesn't automatically
follow them.

## Creating the Event Subscriber

We're going to add this magic with an event subscriber. In the `src/` directory,
let's create a new `EventSubscriber/` directory... and inside, a new PHP class
called, how about, `TurboFrameRedirectSubscriber`. Make this implement `EventSubscriberInterface`... and then go to the "Code -> Generate" menu - or
"Command + N" a Mac - and select "Implement Methods" to generate the *one* method
we need: `getSubscribedEvents()`. Inside, return one event -
`ResponseEvent::class` - set to `onKernelResponse`.

`ResponseEvent` is one of the *last* events that happens during the
request-response process. It happens *after* our controller has been called...
so the `Response` object *has* already been created.

Above this, add the `public function onKernelResponse()` method with a
`ResponseEvent $event` argument.

Cool. So the logic inside of here will be fairly simple: if the request has the
`Turbo-Frame-Redirect` header *and* the response is a redirect, then we're going to
*change* the response to something else.

## Replacing the Response

To keep things organized, add a new private method called
`shouldWrapRedirect()`. This will need the `Request` object - so we can read the
header - and the `Response` object that the controller created. This will return a
`bool`.

Before we work on that method, back in `onKernelResponse()`, call this: if *not*
`$this->shouldWrapRedirect()`... passing `$event->getRequest()` and
`$event->getResponse()`. If we should *not* wrap the redirect, return and do nothing.

In a minute we'll add the logic down here to change the response.

But let's finish `shouldWrapRedirect()`. Start by checking to see if
the `$response` is *not* a redirection. If it's not, return false. The only responses
we need to change are *redirects*: we don't want to change normal frame loads
or frame form submits that are returning with validation errors.

The only other check we need is for the header. Copy the header name
from `turbo-helper.js`. Then `return $request->headers->get('Turbo-Frame-Redirect')`.
So if the header exists and is set to something "truthy" like 1, this method
will return true. Else, it will return false. Actually, I'm missing a tiny detail,
but I'll fix it in a minute.

Finally, back in `onKernelResponse()`, at this point, we know that this request
was made inside of a frame that has our `data-turbo-form-redirect` attribute *and*
we know that the controller returned a redirect.

And so, create a *new* response object: new `Response()`, passing `null` for the
content - we don't need to return anything - a `200` status code - so *not* a
redirect - and then an array of headers. Invent a new header called
`Turbo-Location` set to the URL that we *want* to redirect to. We can get that
from the original response: `$event->getResponse()->headers->get('Location')`.

Finally, to use this response instead of the original, say
`$event->setResponse($response)`.

Ok! That's all we need to do in Symfony: we're now replacing the redirect response
in this situation with something different.

## Reading the Response Header and Navigating

The last little piece of work is back in JavaScript. We already have a
`beforeFetchResponse()` method, which is currently looking to see if a request was
successful and redirected... and checking for the `turboFormRedirect` data
attribute.

We can simplify this a *lot*. All we need to do *now* is check to see if the response
has this `Turbo-Location` header. If it does, then we know that we should read
that header and navigate.

Remove most of the code on top and add `const redirectLocation =` set to
`fetchResponse.response.headers.get('Turbo-Location')`.

Then, if we do *not* have a `redirectLocation`, we know this is not a situation
where we need to do anything fancy. So, just return.

Then, the rest is perfect, except instead of `fetchResponse.location`. use
`redirectLocation`.

That's it. We don't even need our `getCurrentFrame()` method anymore. It took
more work inside of Symfony, but the JavaScript side of things is nice!

Oh, but before we try this, back in our subscriber, before the return statement,
add a `(bool)` type-cast. This will guarantee the method returns a boolean.

Ok, *now* let's try it: Go back to the cart page and refresh. Remember: the whole
goal is to be able to submit this form and have it *not* make duplicate requests
to the redirected page. If we accomplish that, we'll be rewarded by seeing the
success flash message. And... yes! There it is!

Look up here on the Ajax requests. We submitted the cart form here... and then there
was only *one* request for the product show page, not two. Mission accomplished!

Thanks to our new fancy system, we can also - easily - solve an annoying problem.
What happens if the user tries to open something in a `<turbo-frame>` - like a
modal - but they got logged out in the background... maybe after taking a really
long coffee break. Instead of just having this load broken, let's write about
10 lines of code to gracefully handle this everywhere.
