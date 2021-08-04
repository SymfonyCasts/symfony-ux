# Login Redirect

Coming soon...

Any site that loads things via Ajax has one annoying problem to fix. What happens if
the user gets logged out due to inactivity? Obviously if the user gets logged out due
to inactivity and clicks a link to navigate the whole page, that's no problem.
They'll get redirected to the login page, but what happens if I go to a product page
and then scroll down to the review section now? So pretend like I stopped right here,
went home for the day, came back tomorrow. And during that time, my session timed
out, what would happen if I then tried to submit this form, which submits into a
turbo front? Well, let's try it. I'm going to imitate this by opening the site in a
new tab, logging out. Okay. Now back over in my first tab, let's

Clear our network tools here and summit. Uh, that was weird.

So down here, you can see that it did submit to the reviews page, but then because
I'm not logged in it redirected to the login page and our console, we see our
favorite air response has no matching `<turbo-frame id="product-review">` element. That
makes sense the age as requests as we saw redirected to the login page. And so the
frame system followed that redirect and then look for a product review turbo frame on
that page, which it obviously doesn't have. So the user experience here is not so
great, but for any frames that have art `data-turbo-form-redirect` attribute, this
problem is already fixed by the system. We just built check it out. Let's log back in
I'll refresh log-in page login, head to the admin section. Remember this modal has
that attribute on it. So I'm going to repeat our experiment and the other tab I'll
refresh, then hit log out, go back to our initial tab and open the model, which is
going to load a page that requires authentication when

We hit it. Yes. It redirected us to the

Log-in page as exactly what we want. So this is fixed in some places, but not
everywhere, but we can make this work everywhere in `TurboFrameRedirectSubscriber`
down here, where we're determining, if we should wrap the redirect we can detect. If
this response is a redirect to the log in page. And if the request has a `Turbo-Frame`
header, then we definitely know it's safe to do our redirect trick. [inaudible] so
check it out. First thing I'm gonna do down here is actually add a little spot that
says, if not `$request->headers->get('Turbo-Frame')`, then `return false`. That
was kind of redundant before, because you can only have the turbo frame or redirect
header if you have a terrible frame header, but now it's going to help us detect if
we're in a frame for the, if we're in a frame and being redirected to the log-in page
to determine if the redirect is for the log-in page, [inaudible], Let's grab the
location here by saying 

`$location = $response->headers->get('Location')`

just like we did before. And what we basically
want to do is just check to see if that's equal to `/login`. Now, instead of checking,
if it's exactly equal to that URL, I'm actually going to check to see I'm going to
use the use of the router to help me figure this out. Some of the top of the page
make a `__construct()` function, and then Ottawa, R `UrlGeneratorInterface`, which is
just a fancy way to get the router. So I'll do my trick up here of hitting Alt + Enter
and go to "Initialize properties" to create that property and set it cool. Now
all the way down here, we can say, if the `$location` is equal to 
`$this->urlGenerator->generate()`, and then pass it, the name of our logging route `app_login`, then
return true. That's it. So thinking about this, we're basically saying, okay, if the
response is a redirection and it's happening inside of a frame and what we're
redirecting to is the login page. That's a problem. That's a situation where we do
want to wrap the redirect. So we do want to basically replace the redirect response
with a fake redirect response so that our frontend can detect that and do a real
redirect. So let's try it. Let's log back in [inaudible]

And back to a product page, scroll down through reviews, and then over my other tab,
I'll refresh here. Let's see we're logged in that's log out and then go back to our
original tab. All right. Try to submit the review form. Beautiful. We are smoothly
redirected to the login page. This problem just got solved for any turbo frame on our
site. Okay. Deem enough with turbo frames. Next let's turn to part three of turbo
turbo streams. This feature is probably the smallest of the three, but it's at least
as much fun and impressive.

