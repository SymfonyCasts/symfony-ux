# Adding a Custom Request Header Based on the Frame

Okay, so if we don't want to cheat and use the internal `restore` action with
a Turbo visit, how *else* can we solve our problem? Well, there's really only one
option. Let me reopen my network tools. Right now, when we successfully submit into
a `<turbo-frame>`, like this modal, the frame follows the redirect, meaning it
makes a *second* request to the redirected URL. *Then* we navigate to that page,
which causes a *second* Ajax request. Somehow, we need to avoid having *two*
requests.

So if we can't force Turbo directly use the response from this first Ajax call,
because we don't want to use the internal `restore` action, then our only choice
is to somehow *prevent* the first Ajax call from happening. But since the JavaScript
`fetch()` function *always* follows redirects, the only real way to do this is to
make Symfony *not* return a real redirect after the form submits successfully.

So here's the plan... it's kind of cool. In Symfony, we're going to *detect* if
a request is being sent via a `turbo-frame` *and* if that frame has the
`data-turbo-form-redirect` attribute. If both of these are true, then *if* the
`Response` from the controller is a redirect, we will change it to *not* be a
redirect. We'll return a normal 200 status codem but store the URL that we *want*
to redirect to on a response header. Then, we'll prevent Turbo from rendering
that response, like we already are, read the URL from the header, navigate with
Turbo and voilà! We redirect the page *without* the duplicate request.

## Sending data-turbo-form-redirect to the Server

Ok, so where do we start? Turbo already adds a `Turbo-Frame` header to any Ajax
requests that happen inside a frame. We can see this, for example, down on the
POST request. All the way near the bottom... there it is: `turbo-frame: product-info`.
We can read that in Symfony.

But what we *can't* yet read in Symfony is whether or not this frame has the
`data-turbo-form-redirect` attribute. To make that possible, let's hook into
Turbo and add that as a *new* request header.

In `turbo-helper.js`, we're going to listen to another event. Head up to the
`constructor()`... and say `document.`. Actually, let's cheat. Steal the event
listener code from below and change the event to `turbo:before-fetch-request`.

Remember: this is the event that's dispatched right before an any Ajax call is
made. Inside, call a new method - `this.beforeFetchRequest()` - and pass the
`event`.

Copy that method name, head down to the methods... and add that with an `event`
argument. Inside, `console.log(event)` so we can see what it looks like.

Back at our browser, refresh. Ok this logs *every* time that Turbo makes an Ajax
call, like when we navigate... or a frame loads. This is from the weather frame.
And I think if we go down to the bottom... yep! It fires again.

Head over on the cart page, clear the console, then add an item to the cart. Ooh,
the event triggered *three* times. One was for the submit, one for the navigation
to the next page and the last was for the weather widget that loaded on this page.

## Detecting if the Frame Request as data-turbo-form-redirect

So this first one is from submitting that form into the frame. Check out the event.
Ah, `event.detail` has a `fetchOptions` method! This is the array of options that
will be passed to the `fetch()` function. And it has a `headers` key with
`Turbo-Frame` inside.

That's no surprise... but we can use that in JavaScript to figure out if this frame
has the special `data-turbo-form-redirect` attribute.

Check it out: say `const frameId =` and *read* that header:
`event.detail.fetchOptions.headers`...  and we're looking for a header called
`Turbo-Frame`. I'm using square brackets here instead of `.` just because the key
has a dash in it.

Now, if there is *not* a `frameId`, then this request is *not* happening inside
a frame. So we do nothing.

But if we *do* have a `frameId`, we can *use* that to find the element on the
page: `const frame = document.querySelector()`... and then use the ticks so
we can look for `#` then `${frameId}`.

Yep, we're literally finding that `<turbo-frame>` element on the page! If we
can't find the frame for some reason - which shouldn't happen - *or* if the
frame does *not* have the dataset of  `turboFormRedirect`, then do nothing.

If you go back to the cart page and inspect element on the frame, as a reminder,
we *do* have the `data-turbo-form-redirect="true"` attribute. That's what we're
looking for.

At this point, we know that the request *is* happening in a frame with the
`data-turbo-form-redirect` attribute. And so, we're going to add a new header.
Use `event.detail.fetchOptions.headers` again but this time *add* a header.
I'll invent one called, how about, `Turbo-Frame-Redirect` set to `1`.

Cool! Let's go check it! At your browser, any normal request - even a request inside
a frame like for the weather widget - will *not* have the new header. Check
the weather frame request. All the way down... yep! It *does* have a `turbo-frame`
header but *not* `turbo-frame-redirect`.

But now go back to the cart and clear the requests. Submit the form... scroll
up to that request... and scroll down. There it is! `turbo-frame-redirect`.
We can now *detect* - from Symfony - when a request is going through this type
of a frame. Oh yes, we're dangerous.

Next, let's turn to the Symfony side of things where we'll use this header to
magically transform redirect responses into something that we can better handle
in JavaScript.
