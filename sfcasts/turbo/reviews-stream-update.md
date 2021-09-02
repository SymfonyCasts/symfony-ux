# Smartly Updating Elements for all Users

With the power to return a normal redirect from our controller *and* publish a Mercure
update to modify any part of *any* user's page, we can now *really* clean up our
review system. After a successful form submit, we redirect to a page that renders
`_reviews.html.twig`... which includes the reviews list on top and also the review
form down here. Then... we send this *same* thing to the user via the stream
update. The only reason we're doing this is so that the review list updates
for *all* users, not just the user that submitted the form.

So... you can see that there's some duplicated work going on. But worse, there's
a bug! Copy the URL and open this same page in an incognito window. Notice that
we are *not* logged in. Let's pretend that this tab represents a user in Argentina...
and the other tab is a user in Ukraine.

Let's refresh and have our Ukrainian friend submit a new review... this will be
review number 21. When we submit, it looks good here. On the *other* user's page,
the review shows up... but oh! It also shows a success message! So when our
Ukrainian user submitted a new review, our Argentinian friend suddenly saw a
success message!

That's... ya know... *not* what we want. But I can already see the problem: in
the turbo stream, we're sending the *entire* `_reviews.html.twig` template to
*all* users... which includes the reviews list... but also the flash message and
the form.

## Splitting the Reviews Frame and Stream

No worries: we just need to be a bit more careful. The entire `_reviews.html.twig`
template is surrounded by a `<turbo-frame>`. But we really only need the frame
to surround the *form*... because we can update the reviews *list* via the stream.

Check it out: at the bottom of the reviews list, close that `<turbo-frame>`.
Now, create a *new* `<turbo-frame>` with `id=""`, how about, `product-reviews-form`.
We don't need a closing tag... because we already have one.

[[[ code('3190932efe') ]]]

Oh, and in this case, we *don't* need to make the `id` dynamic for each product
because we're not going to update this with a Turbo Stream. So there's no risk of
affecting the wrong page.

With *just* this change, the form now lives in a different frame. And so, if we
were to refresh the page and submit the form... it now only affects this part of
the page.

The next step is to make sure that our stream update sends back the *list*, not the
list *and* the form. To do that, we need to isolate the list into its own
template. Copy that turbo frame and, inside `templates/product/`, create a new file
called, how about, `_reviews_list.html.twig`. Paste the frame here.

[[[ code('010642b78e') ]]]

Back in the other template, include this.

[[[ code('636954373c') ]]]

Nice. Oh, but in the new template, we don't actually need this to be a Turbo frame
anymore. Change this to be a `div`. Think about it: we're not using any Turbo
frame features with this... we just need an element that we can target from our turbo
stream. A `turbo-frame` *would* have worked... it just wasn't necessary.

[[[ code('21155ea2ec') ]]]

*Anyways*, stream this template instead: `_reviews_list.html.twig`.

Sweet! Testing time! Refresh both tabs... and let's post review number 22.

When I submit here... perfect! The review form area updated thanks to the frame.
Then the *stream* took care of adding the review here *and* updating the quick stats
area. In the other browser, the quick stats updated, we see the new review, but
it did *not* mess with the form area.

## Appending the New Review

Look back at `reviews.stream.html.twig`. Right now we're streaming and replacing
the *entire* reviews list. That's probably fine... but because we know that a single
new review was just added, we could, instead, send only the *new* review in the
stream instead of *everything*. We don't *have* to do this, but let's try it.

First, over in `_reviews.html.twig`, on the `id`, I'm going to add a `-list` to
the end. I'm doing this *just* to make its meaning more obvious: it's
a *list*, not a single review. Repeat this in the stream template.

[[[ code('85aeb003b3') ]]]

[[[ code('3f4d0ff967') ]]]

Now over in `_reviews_list.html.twig`, copy the `div` for a single review and
isolate it into *its* own template: `_review.html.twig`. Back in the list, include
that.

[[[ code('7a5e1d9803') ]]]

[[[ code('0266cffb60') ]]]

So no changes yet, just some reorganization. But now, in the stream, change the
action to `append`... and include the single review template.

That's nice! In `_review.html.twig`, this needs a `review` variable. In
`ProductController`... let's see: we're only passing a `product` variable right now.
Also pass a `newReview` variable set to the review... which is `$form->getData()`.

Back in the stream, pass in a `review` variable set to `newReview`.

[[[ code('5ad91236f2') ]]]

[[[ code('651ed3a58a') ]]]

Let's try the *whole* flow. Refresh both tabs. We're filling in review
number 23. Submit and... sweet! Three things just happened. First the form area
updated thanks to the Turbo frame system. Second, the new review was appended to
the list thanks to the stream. And finally, the quick stats area was updated *also*
thanks to the stream.

Over in the incognito tab, it's almost the same. The reviews list has the new review
and  the quick stats area updated... all *without* affecting the form area.

Next: let's celebrate by visually highlighting the new review the moment it pops
onto the page.
