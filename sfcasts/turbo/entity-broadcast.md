# Entity Broadcast

There's one super cool feature of the Turbo Mercure UX package that we installed
earlier that we have *not* talked about. And it's this: the ability to publish a
Mercure update automatically whenever an entity is created, updated or removed.
It's a *powerful* idea.

For example, instead of publishing this update from inside of our controller,
what if we published this update *whenever* a review is added to the system,
regardless of how or *where* it's added? Or what if we could publish a Mercure
update whenever a review is changed... like if we changed a review in an admin area,
that review would automatically re-render on *anyone's* page that was currently
viewing it!

## The Broadcast Attribute/Annotation

That is *totally* possible. Go into the entity where you want to activate this
behavior. For us that's `src/Entity/Review.php`. Above the class, add
`@Broadcast`.

[[[ code('8c2a63ec1b') ]]]

If you're using PHP 8, you can also use `Broadcast` as an attribute. Next, open
`templates/products/_reviews.html.twig`. This is where we originally used
`turbo_stream_listen()` to *listen* to the `product-reviews` Mercure topic.

Copy that and, temporarily, *also* listen to a topic called `App\Entity\Review`.
We need the double slashes to avoid escaping problems. Oh, and not *reviews*,
just `Review`: the name of the class.

[[[ code('f69301387c') ]]]

Okay: whenever a `Review` is created, change or removed, an update will be sent to
the `App\Entity\Review` topic on our Mercure hub. And now we're *listening* to that
topic.

If this doesn't all make sense yet, don't worry: we're missing one important piece.
To find out what it is, let's fearlessly forge ahead and try this! Refresh the page
and check out the network tools. Let's see... here it is! We're listening to a new
stream URL. Open this in a new tab. Like with the other topic, our browser
spins and waits for updates.

## The Broadcast Template

Ok! Try to submit a new `Review`. And oh! A 500 error. Open the profiler for
that request to see what happened:

> Unable to find template `broadcast/Review.stream.html.twig`.

Okay. So here's the *whole* flow that we activated by adding the `@Broadcast`
annotation above the entity. First, we create, change or remove a `Review` from
the database. Second, the Turbo Mercure library *notices* this and tries to render
a template called `Review.stream.html.twig`. We will create this in a moment.
And third, whatever this template renders is published to Mercure... in a specific
way.

Let's go create that template. In the templates directory, add the `broadcast`
directory... and inside, the new file: `Review.stream.html.twig`.

These "broadcast" templates always look the same, and I'm going to paste in a
skeleton to show you. It's... kind of a cool use of blocks. If a `Review` is
created, the content in the `create` block is sent to Mercure. If a `Review` is
updated, the `update` block is used. And if we delete a `Review`, the contents of
the `remove` block are published to Mercure as an update.

[[[ code('f2a4ed3998') ]]]

We can see this immediately. Close the profiler tab, refresh this page... and
add another review. When we submit, nothing looks different *here* yet. But check
out the tab that's listening to Mercure. Yes! There it is! This published an update
and passed the contents from our `create` block as that update's *data*!

## Publishing turbo-stream Updates

*Now* we're dangerous. Go into the original `reviews.stream.html.twig` template,
copy both streams and paste them into our `create` block.

[[[ code('8893c049b3') ]]]

Boom, done! We can now completely delete `reviews.stream.html.twig`. And inside of
`ProductController`, we don't need to dispatch this update at *all* anymore. It
will happen automatically when we create the `Review`. So I'll delete the `Update`,
the `$mercureHub` argument... and if you want to get really crazy, you can clean
up the unused `use` statements on top.

Finally, in `_reviews.html.twig`, we no longer need to listen to our original
`product-reviews` topic.

## The "entity" Variable

Testing time! go back, refresh... and publish a new review. Ah! Another 500 error?
Let's check out what happened:

> Variable `product` does not exist coming from `Review.stream.html.twig`.

Ah! So Apparently there is *not* a product variable that's passed to this template...
which begs the question: what variables *are* passed to this template? When the
Mercure Turbo library renders this template, it passes several variables. The most
important - by far - is a variable called `entity`... which in this case will be
set to the `Review` object. We can use that to fix our template.

So instead of `product.id`, we need `entity.product.id`. Do that in both places.
And this template also needs a `product` variable... so pass that in set to
`entity.product`. And down here, `review` is now `entity`.

[[[ code('1bbfd7222b') ]]]

Hopefully that's everything. Close the error, refresh the page... and add a new
review. If all goes well, this will have the *same* behavior as before. Submit.
We got it! The new review loaded onto the page thanks to the stream! The quick
stats area *also* updated. In the other tab, yup! The new review streamed here
too!

The *big* difference now is that the stream update will be published no matter
*how* a review was created.

Next, let's also instantly update every user's page whenever a review is changed
or removed. I'll show you a review admin area that's been hiding on our site where
we can watch this in real time.
