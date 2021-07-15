# Reviews Frame

Coming soon...

Okay. We have a new mission, but before we get to it, let's log in. Use our
delightful cheating links. Now I had to do a product page and scroll down. Okay.
Every product has reviews and we can even at post review from right here, right now,
there is nothing fancy about this. This is a normal HTML form with no accent,
JavaScript and no turbo frames. And mostly it works great. Fill out the form and
submit it. Okay. Ooh, that's smooth. Just because turbo drive is awesome. But notice
that we are taken to a different page, a `/reviews` page, this is on purpose management
wants to show a review, wants to show the reviews below each product, but also wants
a dedicated reviews page for each product. And so we decided to make the review forms
submit to this page,

And this is working great, but it might be even better.

If when we submit a review from the product page, here we stay on that page. Instead
of going to a different page, this is a type of progressive enhancement that we are
going to choose to make. And it's going to require exactly two lines of code. The
template for this page is `templates/product/show.html.twig` at the bottom,
the reviews are actually loaded from this `_reviews.html.twig` that's right template,
open that template and scroll down to the form. By the way, this template is also
included by the main reviews page. It is also how it renders the same list of
reviews.

So anyways, if you think about it, if we wrap this entire template in a turbo frame,
I think that would do it. Wouldn't that do it? Wouldn't that make the form submit,
stay in the frame and just work for the shot at the top of the template, add 
`<turbo-frame id="">` equals. How about `product-review`, take the closing tag and put it
on the bottom. Okay. Testing time, refresh the page. And remember right now we are on
the product show page at the bottom, submit the form empty. Yes, that was perfect. We
S we see our validation areas, but we are still on the product show page. This is my
favorite example of yet of the power of turbo frames with two lines of code. The
entire review system is now self-contained behind the scenes. When we submit this
form, it does submit to these `/reviews` page. You can see down in the network tools
under the Ajax calls. Here we go. The summit was to `/reviews`.

If you look closely at the preview for this, this is rendering the full reviews page,
but our turbo frame is smart enough to find just the product review frame inside,
grab it and use it here. So let's finish our review, another five stars. And when we
submit, oh, gorgeous our new recipe, our new review even popped up right above the
form though. Hmm. There's no success message anywhere on this page, there was a six
months success message before on the top of the page, where did that go look back to
the latest request down in the network tools. There they are.

So our first POST request was right here as opposed to request a `/reviews`, the forms,
and it was successful. So it returned a `302` redirect that redirected back to the
same URL. And so it made his second request for `/reviews`. And this is what it used to
fill in the turbo frame. If you look at the preview for this closely checking out on
top, it does, it does have a success message. Thanks for you. Revert view. I like
you. And then way down below here is where we actually have our reviews. So do you
see the problem? The message, success message is being printed outside of our turbo
frame. And so we never see it. Fortunately, we can fix this pretty easily, open up a
controller that handles this page `src/Controller/ProductController`, and find the
review `productReviews()`. Action.

Let's see. All right. So if this is a `POST` request and it's successful, then as a
success flash message over here and at `templates/base.html.twig`, we already have
code that renders any success, flash messages near the top of the page, but because
our frame, when we really want to do now is make sure that that success message
renders inside of our frame. So back in the controller, change this to `review_success`
right now that won't render anywhere, but go into the template `_reviews.html.twig`
and above the form. Let's render it.

We can say for `flash` in `app.flashes('review_success')` and for
inside of here, very simply, we'll do a `<div class="alert alert-success">`, curly,
curly `flash`. And if you want it to be even a little fancier, you could isolate this
flash logic from base that HTML twig into its own template, then include it from here
and also include it from here, but this should work fine. Cool. Let's go review our
product one more time. I'm going to do a full page refresh just to be sure. Now five
submit and yes, this is perfect.

Now I'm back on the top of the page, click to log out. Cause there is one tiny little
detail left, go back to our product and scroll down to the reviews. So we're not
logged in. So now I have this log in to post your review. When I put that link that
didn't work. And if you look at the console, it's a familiar error response has no
matching, `<turbo-frame id="product-review">` element. Of course, let me refresh the
page to get that back. When we click this log in link, it's now inside of a turbo
frame. So what tries to navigate in that frame, it goes to the login page via Ajax
and tries to find the frame there. That is not what we want. We want this link to
target the whole page, and we now know how to do that. So over and_reviews that HTML
twig all the way on the bottom here is our link on that. Add `data-turbo-frame="_top"`
Now when we refresh and quick, we're good next what's that a
tiny little bonus feature to our site. Whenever any form submits on our site, for any
reason, let's automatically disabled the submit button to avoid double submits.

