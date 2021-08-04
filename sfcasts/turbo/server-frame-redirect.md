# Server Frame Redirect

Coming soon...

Okay. So if we don't want to cheat and use the internal restore action, when we do,
when we do a terrible visit, how else can we solve our problem? Well, there's really
only one option right now after we submit the modal, actually that may reopen my
network tools before I do that. Okay. After we submit the model successfully, the
frame follows the redirect and makes an Ajax call to the page we want to redirect to.
Then we navigate to that page, which causes a second Ajax request. So if we can't
make turbo use the response from this first Ajax call, because we're not allowed to
use that restore option action, then it means that we need to somehow prevent that
first Ajax call entirely. And since the JavaScript fetch function always follows
redirects, the only real way to do this is to make Symfony not return a real redirect
after the form submits successfully.

It's kind of cool in Symfony, we could detect if this is a situation where a frame is
being submitted, and if that frame has the `data-turbo-form-redirect` attribute. If
so, if the response is a redirect from our controller, we could change it to not be a
redirect to return a 200 status code. Instead with the redirect URL on a header, we
could then prevent turbo from rendering that response read that you were alpha in the
header and navigate with turbo. Whoa. Right? Okay. So what step one, when a request
is made for a frame, turbo already adds a turbo frame header, which we can read and
Symfony. We can see this, for example, down on our post, when we submitted it all the
way near the bottom, there it is `turbo-frame: product-info`, but Symfony does not know
if that frame has art `data-turbo-form-redirect` attribute to fix this we'll hook into
turbo and add a new header. When this attribute is present on the frame in `turbo-helper`
we're to listen to another event. So let me go up to the `constructor()` here. It doesn't
really matter where, but right here, we'll add `document.` Actually let me cheat. I'm
going to steal the event listener from below and then changes to `before-fetch-request`


Remember, this is the event that's dispatched right before an any AGS call is made
inside. I'm going to call a new method called `this.beforeFetchRequest()` and pass the
`event`. And then we'll copy that method name, head down to before factories, head down
and pop that new method right before, `beforeFetchResponse`. So of course the order
doesn't matter for now, just `console.log(event)`. Okay. Back in our browser,
let's refresh. All right. So each time that we navigate, okay. Or a frame loads like
our weather frame. So this is why the frame loaded. And I think we down onto the
bottom, we'll see it load again. Our event is hit over on the cart page.

clear the console, then add an item to the cart.

Okay. So there were three events there, one for the submit, one for the navigation.
And another one when the weather was widget was loaded on the next page. So this
first one here is from us submitting that form into the frame, check out the event,
`event.detail`. It has a `fetchOptions` method on there inside. This has a `headers` key.
These are the options that will be passed to the `fetch()` function inside. It has
headers. And since we are in a frame, it has a `Turbo-Frame` header. We can use that to
figure out if that frame has our special `data-turbo-form-redirect` attribute. So
check this out over our method, we'll say constant `frameId`. And we're actually going
to read that header. That's about to be sent. So can say `event.detail.fetchOptions.headers`
and we're looking for a header called `Turbo-Frame`.
I'm using the square brackets here because the, I use the dot. It won't like my dash.

All right. So first thing is, if there is not a `frameId`, then this is not a request
that's happening inside of a frame. So we're going to do nothing now that we have a
frame ID though, we can actually look for that on the page. So we can say constant
`frame = document.querySelector()`. And then I use the ticks here so I can do pound sign and
then dollar sign, curly, curly, and say `frameId`. So we're literally going to go find
that frame on the page. Finally, if we couldn't find that frame, for some reason that
shouldn't happen, but just in case or the frame does not have the dataset of 
`turboFormRedirect`, then do nothing. So we're looking to make sure that we can find our
frame and that our frame has that `data-turbo-form-redirect`. Oh, sorry. Form
redirect, uh, attribute. As a reminder, I go back to the cart page and inspect
element on our frame. We do have that `data-turbo-form-redirect="true"` attribute.
And that's what we're reading here.

So at this point, we know that this is being submitted into a frame that has an
attribute. So we're going to add a new header. So same thing event that we're gonna
use that `event.detail.fetchOptions.headers` again, but this time
we're going to add a header that's saying left square bracket, and I'm just going to
invent a new header. How about `Turbo-Frame-Redirect = 1`. Cool.
That's it. Let's go check that out in the browser. So any normal request, even a
request inside of a frame like for our weather widget is not going to have it on
there. So here, whether this is here as for a frame, we scroll all the way down
because the `turbo-frame` is set, but there's no `turbo-frame-redirect` header.

But if you go back to the cart, I'll actually clear the log here and submit the card
form and scroll up to that request and scroll down. Yes. `turbo-frame-redirect`. So
we're now communicating the Symfony when we have this situation. Oh, we hear
dangerous. So now let's turn to the Symfony side of things. We're going to need an
event subscriber that can transform a redirect response to a different response. So
in the `src/` directory, let's create an `EventSubscriber/` directory, okay. And inside
there, a new PHP class called how about `TurboFrameRedirectSubscriber` make this
implement the event, `EventSubscriberInterface`, and then I'll go to "Code -> Generate"
or "Command + N" a Mac, you can go to "Implement Methods" to generate the one. We
need to get subscribed events here. I'm going to return one event that we want to
listen to, um, which is called `ResponseEvent::class` and set this to
`onKernelResponse`. So response event is the one of the last events that happens
during the request. It happens after our controllers called. So we already have a
`Response` object there. It's gonna allow us to read the response object that the
controller created and change it. So the on response method, let's actually make that
up here, `public function onKernelResponse()`, and that will receive a `ResponseEvent $event`
argument.

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

