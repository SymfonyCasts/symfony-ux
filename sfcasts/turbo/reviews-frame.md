# Review this Product... in a turbo-frame!

We have a new mission. But before we jump in, we need to *log* in. Use the delightful
cheating links... then head over to a product page and scroll down.

Okay: every product has reviews and we can even *post* a review from right here.
There is *nothing* fancy about this: this is a normal HTML form with no custom
JavaScript and no turbo frame. And, mostly, it works great! Fill out the form...
and submit. Ooh, that's smooth... just because Turbo Drive is awesome.

But notice that we *are* taken to a different page, a `/reviews` page. This is on
purpose: management wants to show the reviews below each product... but they also
want a dedicated "reviews" *page* for each product. And so, we decided to make the
review form *submit* to this page.

This *is* working great... but it *could* be even better if, when we submit a review
*from* the product page, we *stayed* on the product page. This is a type of
progressive enhancement: everything is cool right now, but we're going to *choose*
to enhance things to a higher "coolness level". Doing this is going to require
two lines of code.

## Adding the Frame

The template for this page is `templates/product/show.html.twig`. At the bottom,
the reviews are rendered via this `_reviews.html.twig` template partial. Open
that and scroll down to the form. The reason all of this lives in its own partial
is that this is *also* included from the reviews page template - `reviews.html.twig`.
That lets us show the same list of reviews and form on both pages without duplication.

So let's think: when the "new review" form submits, we want the page to *not*
navigate away: we want everything to happen *in* this reviews area. Isn't that...
*exactly* what Turbo Frames are for? If we wrapped this entire template in a
`<turbo-frame>`... wouldn't that do it? I think it would!

At the top of the template, add `<turbo-frame id="">`, how about, `product-review`.
Take the closing tag and put it on the bottom.

[[[ code('04d19c9caf') ]]]

Those are the 2 lines! Testing time. Refresh, scroll to the bottom of the product
show page and submit the form empty. Yes! That was perfect! We see the validation
errors but we are *still* on the product show page. This is my favorite example
yet of the power of turbo frames. With two lines of code, the entire review system
is now self-contained.

Behind the scenes, when we submit this form, it *does* submit to the `/reviews` page.
You can see this down in the network tools under the Ajax calls. Here it is: this
was a POST request to `/reviews`.

If you look closely at the "preview" for this, it *did* render the *full* reviews
page - with header, footer and all. But our `turbo-frame` is smart enough to find
*just* the `product-review` frame *inside* this response, grab it and use it.

I love this product *so* much that I think we should publish another another 5
star review. When we submit... gorgeous! Our new review even popped up right above
the form!

## Changing The Flash Message to Render In the Frame

Though, hmm. There's no success message anywhere on the page. There *was*
one before... but now it's gone! What happened?

Look back at the network tools. There are *two* new requests.

The first is a POST request to `/reviews`. That processed our form, was successful,
and returned a 302 redirect *back* to the same URL. This caused a *second* AJAX
request to be made to `/reviews` and *this* is what was used to fill in the
`turbo-frame`.

Look at the preview for this request closely. Near the top - here! The page
*does* have a success message! Then, way below, we see the reviews. Can you
spot the problem? The success message is being printed *outside* of the turbo-frame.
And so, we never see it.

Fortunately, we can fix this pretty easily. Open up the controller that handles
the review form submit and renders the reviews page: `src/Controller/ProductController.php`. Here it is: `productReviews()`.

[[[ code('18eec279e2') ]]]

Let's see: if this is a `POST` request and it's successful, then we set a
`success` flash message. Over in `templates/base.html.twig`, we already have
code that renders any `success` flash messages near the top of the page.

Now that we're leveraging a frame, what we *really* want to do is render
the success message *inside* that frame. Back in the controller, change the
flash type from `success` to, how about, `review_success`.

[[[ code('4a88487126') ]]]

Right now, nothing is rendering `review_success` flash messages. But go into the
template - `_reviews.html.twig` - and, above the form, render it: for
`flash` in `app.flashes('review_success')`. Inside, and an alert div with
`alert-success` and print the `flash` variable.

[[[ code('56490ef3d5') ]]]

If you want to be fancier, you could isolate the flash logic from `base.html.twig`
into its own template and include it from both the base layout
*and* `_reviews.html.twig`. That'd be pretty sweet!

Let's go review our product one more time. Do a full page refresh just to be safe,
recommend this product to all your friends, submit and... that's *lovely*.

## Making One Link target="_top"

Back at the top of the page, click to log out... because there is one *tiny* little
detail left. Go back to the product and scroll down to the reviews. You need to
be logged in to post a review. But when we click the "log in" link... it's busted!

Check out the console, it's a familiar error:

> Response has no matching `<turbo-frame id="product-review">` element.

Of course. Refresh the page to reset things. When we click the "log in" link, it's
now *inside* of a turbo frame. And so, Turbo makes an Ajax call to the
login page and looks for a `product-review` frame *on* that page. That is...
*not* what we want. We want this link to target the *whole* page. And we know how
to do that!

Over in `_reviews.html.twig`, all the way on the bottom, find the link and add
`data-turbo-frame="_top"`.

[[[ code('eadaecd564') ]]]

Now when we refresh... and click... we're good!

Next: let's add a bonus feature to our site! Whenever *any* form is submitted on
our site for *any* reason, let's automatically disable the submit button to avoid
double submits.
