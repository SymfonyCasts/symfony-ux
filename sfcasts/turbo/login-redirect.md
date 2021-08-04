# Automatically Redirect Ajax Calls to /login

All sites that loads things via Ajax have one annoying problem: what happens if
the user gets logged out due to inactivity? Obviously if the user gets logged out
and clicks a link to navigate the *whole* page, that's no problem. They'll get
redirected to the login page.

But go to a product page and scroll down to the review section. Pretend that
I stop right here, go home for the day, eat a delicious dinner, watch Mystery
Science Theater 3000 and come back to my computer tomorrow. During that time,
my session has timed out. What would happen if I tried to submit this form - which
submits into a `turbo-frame` - without refreshing?

Well... let's try it! I'm going to imitate this situation by opening the site in
a new tab... and logging out. Back over in the first tab, clear the network requests
and submit. Uh, that was weird.

In the network tools, you can see that it *did* submit to the reviews page. But then
because I'm not logged in, it redirected to the login page. In the console, we see
our favorite error:

> response has no matching `<turbo-frame id="product-review">` element.

That makes sense! The Ajax request redirected to the login page. And so, the frame
system *followed* that redirect and then looked for a `product-review` `<turbo-frame>`
on that page... which it obviously doesn't have.

So the user experience here is not so great. But for any frames that have our
`data-turbo-form-redirect` attribute, this problem is already fixed thanks to the
system we just built!

Check it out. Refresh... log back in and head to the admin section. Remember: this
modal *does* have that attribute on it. So I'm going to repeat our experiment.
In the other tab, refresh, then log out. Back on the first tab, when we open
the modal, the `<turbo-frame>` will *try* to make a request to a page that
requires authentication. When we try it... awesome! It redirected the entire page
to `/login`! That's perfect!

## Wrapping the Redirect Response to /login

So this problem is fixed in some places... but not everywhere. But we *can* make
this work everywhere.

In `TurboFrameRedirectSubscriber`, look at `shouldWrapRedirect()`. Let's think:
if this response is a redirect to the *login* page *and* if the request is
happening inside a `Turbo-Frame` header, then we *definitely* know that we want
to wrap the redirect so that our JavaScript redirects the whole page.

Start by check to see if *not* `$request->headers->get('Turbo-Frame')`. In this
case, return `false`. Adding this check was redundant before... because if you
have the `Turbo-Frame-Redirect` header then you *definitely* have a `Turbo-Frame`
header. But now it's going to help us detect if we're in a frame *and* if the
response is redirecting to the login page.

Grab the redirect location by saying `$location = $response->headers->get('Location')`.
Instead of checking to see if this equals `/login`, let's be fancier and use the
URL generator to help us.

At the top of the class, add a `__construct()` function with a
`UrlGeneratorInterface` argument... which is just a more hipster way to get the
router service. I'll hit Alt + Enter and go to "Initialize properties" to create
that property and set it.

Back down in the method, if `$location` is equal to `$this->urlGenerator->generate()`,
passing this the name of our login route - `app_login` - then return `true`.

That's it! If the response is a redirect... and the request is happening inside of
a frame... and what we're redirecting to the login page. That's a problem. That's
going to break the frame. And so, we'll wrap the redirect with our fake redirect
so that our JavaScript can navigate the page.

Let's try it! Log back in... go back to a product page, scroll down to the reviews,
and then, in the other tab, refresh and log out.

Ok, back in tab number 1, try to submit the review form. Beautiful! We are smoothly
redirected to the login page! This problem just got solved for *any* `<turbo-frame>`
on our site.

Okay team! Enough with turbo frames! Next let's turn to part 3 of Turbo:
Turbo Streams. This feature is probably the smallest of the three, but probably
*the* most fun to work with.
