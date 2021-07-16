# Frame Loading Animations

With Turbo Drive, when we click a link or submit a form, and that takes *longer*
than 500 milliseconds to load, we get a loading animation on the top of the
page... which we don't see here because this is all loading *fast*, but we saw it
earlier. It's a built-in, global loading indicator that we don't even need
to think about.

But the same thing does *not* happen for Turbo frames. When you click the read
more link, that loads pretty fast, but there *is* a slight delay when nothing happens.
And if clicking this loaded a heavier page.... it might *not* load so fast. It's
pretty normal to add a loading indicator in situations like this. Can we add
one with Turbo frames?

## The "busy" Attribute

Sure! And we already have what we need. Head over to
`src/Controller/CartController.php`. In `_cartFeaturedProduct()`, let's sleep
for three seconds to fake a slow page.

Back at the browser, inspect this `turbo-frame` and make sure it's highlighted.
Watch the element closely when I refresh. Look! It has a `busy` attribute!
Yup, whenever a `turbo-frame` is loading, it gets this attribute. If we click the
"read more" link, we'll see it again.

This simple attribute makes it possible to add all *sorts* of loading indicators.
For example, we could create two classes to help us hide or *show* an element
during loading.

## Hiding / Showing Elements During Loading

Open up `templates/cart/_featuredSidebar.html.twig`. Ok, let's pretend that we want
to hide the "read more" link once we click it. Add `class=""` and let's invent a
new class called `frame-loading-hide`. We'll add the CSS for this in a minute.
After this, add a `<span>` and give it a different, new, class -
`frame-loading-show`  - that will cause this element to only *show* when loading.
Also give this `fas fa-spinner fa-spin` to render a FontAwesome loading animation.

[[[ code('e42d4bb16b') ]]]

To add styling for these, open up `assets/styles/app.css`. Target the
`busy` attribute with `turbo-frame[busy]`. So *if* there's a turbo-frame element
that has a `busy` attribute, then for any elements inside with a `frame-loading-hide`
class, `display: none`.

For the *other* class - the `frame-loading-show` - we want this to *hide* by
default and then only *show* when loading. First, to hide it, copy the CSS selector,
paste, make it apply to *all* turbo-frame elements, and look for the
`frame-loading-show` class. So, hide these by default.

And, whoops!  That jumped a bit. Anyways, below this, *override* that: inside a
`turbo-frame[busy]` element, if you have a `frame-loading-show` class,
`display: inline-block`.

[[[ code('57c38c735a') ]]]

It's a little complicated, but that *should* get the job done and give us two
classes that we can reuse across our site. Let's try it! Find your browser, refresh
and... perfect! You can already see that my FontAwesome icon is *not* showing up
because it's hidden by default. Now click this link. Beautiful!

## Loading Opacity

And... that's it! You can leverage this `busy` attribute to do whatever you want.
For example, we can give *every* frame on our site loading behavior by lowering
their opacity. This is pretty easy. Copy the turbo-frame from above to say that
any `turbo-frame` with a `busy` attribute should have `opacity` set to .2. That's
an extreme level - but it'll be easy to see.

[[[ code('13b26b0d41') ]]]

When we refresh now, we should even see this during the *initial* load. And...
we do! When we click the "read more" link... uh... hmm. I did *not* see the lower
opacity. That's weird. Inspect the element... and hack a `busy` attribute
on the end of this.

## turbo-frame is an Inline Element by Default

Hmm. When I do this, our browser *does* see the correct opacity CSS... it just
doesn't seem to be doing anything! Hover over the element... let me scroll up a
bit. Check it out: it has no height! I see the arrow in the upper left... but
it's not highlighting the element. You'd expect it to go *around* the element
like this... but it's not!

So this is interesting. The problem is that `<turbo-frame>` is a custom HTML element.
And by default, your browser renders it as an *inline* element. You can see this
over in the computed CSS: it has `display: inline`. And so, when you put block
elements inside of it, it just... doesn't expand in the way you'd expect it to.
That's why it appears to have no height. And *that's* why nothing gets the lower
opacity.

To fix this, we can make this element `display: block`. As soon as I hack this in,
the opacity *does* take effect. To make this work everywhere, we can make our
turbo-frames `display: block` by default with `turbo-frame`, `display: block`.

[[[ code('92c6dedb13') ]]]

Try it now. The opacity on loading still works and when we click... that works too!

So now that this looks spectacular, let's go and make the opacity a little less
@dramatic... and over in `CartController`, take out the sleep.

[[[ code('a0cc2cebe7') ]]]

Let's go play with the page. That feels much more natural.

## Fixing the Checkout Page

Before we keep going and doing other cool Turbo frame stuff, we accidentally
broke the checkout page! It... was my fault.

> Variable `showDescription` does not exist

Coming from `_featuredSidebar.html.twig`. The template for this page lives at
`templates/checkout/checkout.html.twig`.

[[[ code('cf0574104e') ]]]

Ooooh. This page *also* has a featured product sidebar... and it is *still* using
the `include` directly. When we added our new `showDescription` variable, I didn't
realize this was being included directly and... well... now things are mad.

We could fix this by passing in the variable... or even coding defensively
inside `_featuredSidebar.html.twig`. But, pfff. We have a working, lazy Turbo
Frame! So let's just use that! In `cart.html.twig`, steal the lazy frame and
paste it inside `checkout.html.twig`.

[[[ code('61eb97fe15') ]]]

Celebrate by opening up the controller for this page, which is
`CheckoutController`, and removing some variables that we don't need anymore:
`addToCartForm` and `featuredProduct`... which means we can delete both variables...
and we don't need to inject this argument.

[[[ code('b63853824b') ]]]

Cool! Refresh now and... all good. The "read more", of course, even works here
because Turbo & Stimulus are awesome.

Next: below each product, if you're logged in, users can post a review. We can make
this a bit more awesome by leveraging a turbo frame.
