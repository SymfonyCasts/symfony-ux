# Smart Frame Redirecting with the Server

Coming soon...

So we're now communicating the Symfony when we have this situation. Oh, we hear dangerous. So
now let's turn to the Symfony side of things. We're going to need an event subscriber
that can transform a redirect response to a different response. So in the `src/`
directory, let's create an `EventSubscriber/` directory, okay. And inside there,
a new PHP class called how about `TurboFrameRedirectSubscriber` make this implement
the event, `EventSubscriberInterface`, and then I'll go to "Code -> Generate" or
"Command + N" a Mac, you can go to "Implement Methods" to generate the one. We need
to get subscribed events here. I'm going to return one event that we want to listen
to, um, which is called `ResponseEvent::class` and set this to `onKernelResponse`.
So response event is the one of the last events that happens during the request.
It happens after our controllers called. So we already have a `Response` object there.
It's gonna allow us to read the response object that the controller created and change
it. So the on response method, let's actually make that up here, `public function
onKernelResponse()`, and that will receive a `ResponseEvent $event` argument.

Cool. So the logic inside of here is fairly simple. We're going to be looking for
that `turbo-frame-redirect` header. If that's not there, we're going to do nothing. So
in fact, I'm going to start down here by putting a new private function called
`shouldWrapRedirect()`. This is going to determine whether we're in this situation where we
want to change the redirect to be a fake redirect. We're going to need the `Request`
object here so that we can read the header. We're also going to need the `Response`
object that our controller created well, and you'll see why in a second, and this is
going to return a bullion.

Now, before we filmed that logic up here, we're going to say, if not,
`$this->shouldWrapRedirect()`
pass the request, which is `$event->getRequest()`, and won't pass the
response, which is `$event->getResponse()`. Then just return and down here in a second,
we're actually going to do our logic of changing the, uh, response in a moment, but
let's turn to `$this->shouldWrapRedirect()`. So there's a number of things that we
need to look for here. So for us to remember where the idea here is that if our
controller returns a response in this situation, then we're going to change the
response to something else. So if this is, if the `$response` is not a redirection, then
this is not a situation we need to deal with. This might just be a, that a frame is
loading like normal, okay.

Or it could be that, um, a, uh, a form of submitted, but it was unsuccessful. So it's
re rendering next we can check to see next. The only other thing we need to check is
to make sure it has that `turbo-frame-redirect`. Sure. Job copy from `turbo-helper`. So
you can basically say `return $request->headers->get('turbo-frame-redirect')`
 So the idea here is that if that, if we have a header and it's set to
something like one, then this method is going to return true. If that is not there,
then this method will return false.

So finally, up at this point, at the end of oncoming response, at this point, we know
that our con that this, there was a request made inside of a frame. That frame has
our `data-turbo-form-redirect` attribute, and the controller return a response. So
what we want to do is transform that response to not actually be a redirect. So we're
gonna paint a new response object set to a new `Response()` to the one from HD
foundation. Now I'm passing `null` for the content. We don't need to return anything.
I'll give a `200` status codes was not redirect. And then we're going to store the URL
that it would've redirected to on a new header. So, third argument here is the
headers array. I'm going to invent a new one called `Turbo-Location`. Now to
figure out what you were out. This was going to redirect to, we can say `$event->getResponse()`
response to get the original response from the controller.

And the redirected URL is always stored on a `headers` called `Location` so we can read
low location header. That's it finally to use this response instead of the original,
we say `$event->setResponse($response)`, all right, that is it for the work in
Symfony. We are now intercepting the response and redirect responses in this
situation and returning something different. The last little piece of work is back in
JavaScript over here, we already have a `beforeFetchResponse()` method, which is
currently looking to see if a frame was successful and was redirected and checking
for our `turboFrameRedirect`. And if so, it's a canceling the rendering and then
visiting that page via turbo, we can simplify this a lot. Um, all we need to do now
is check to see if the response has this `Turbo-Location` header. If it does, then we
know that we're inside of a turbo frame that has our `data-turbo-frame-redirect`
attribute, check us out on, remove all of this code here. And we're gonna say
const redirectLocation = no. Actually let me keep the `fetchResponse` variable, delete the
rest and say constantly `redirectLocation`. And we're going to use that federal response
to say `fetchResponse.response` that gives you the actual raw `fetchResponse`,
`.headers` that gets, and then read our `turbo-location`. Okay.

And very simply, if we do not have a `redirectLocation`, then we know this is not a
situation where we need to do anything fancy. This could be a normal page load. This
could be a, uh, normal frame load, or even a form submitting inside of a frame, but
it's not a situation where we want to redirect the entire page. So we're just going
to return. And then the only thing we need to change on here is what `event.preventDefault()`
`Turbo.clearCache()`. And then instead of `fetchResponse.location` we'll
use our new `redirectLocation` and that's it. We don't even need our `getCurrentFrame()`
method anymore. That's actually not being used. So it took a little more work instead
of Symfony, but the JavaScript side of things is actually a little bit cleaner. So
let's try it. Remember the whole goal here is for us to be able to go to the cart
page, I'd go back and click the cart page. You can actually see that my page fully
refreshed. That's thanks to the asset tracking we did earlier, but I'll refresh
again, just in case. Remember the whole goal is for us to be able to submit this form
and have it not make duplicate requests that we see the flash message. So it's
changed the blue and yes, there is our flash message and prove it. If you look up
here on the Ajax requests, you can see


That we submitted to the cart page here. And then there was only one request for the
product show page, not multiple. Awesome. All right, next, we can leverage our new
system to solve an annoying problem. What happens if the user tries to open something
in a terrible frame, like a modal, but they got logged out in the background maybe
after taking too long of a coffee break. And instead of just having this load broken,
let's read about 10 lines of code to gracefully handle this everywhere.
