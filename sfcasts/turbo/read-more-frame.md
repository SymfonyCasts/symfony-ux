# Adding a "Read More" Ajax Frame

On the cart page, let's make this feature product sidebar a bit more useful by adding
the product's description. Except... we probably don't want to add the whole
description because it's kind of long. So we'll just show a preview.

## Using & Installing twig/string-extra

Head over to `templates/cart/_featuredSidebar.html.twig` and, down here, right before
the cart controls, add `{{ featuredProduct.description }}`. To show only *part*
of the description, pipe this to a special `|u` filter and say `.truncate(25)`.
I'm also going to add `|trim` on the end.

This `u` filter comes from a Twig extension library, which... we don't actually have
installed yet. But, pff, let's try it anyways. When we refresh.... nothing happens!
But down on the web debug toolbar, you can see that an Ajax call failed!

So... we all know that - despite our awesomeness - errors happen. When you
work with turbo frames, these errors are harder to *see* since everything happens in
a background Ajax call. To see it, you can go to the network tab, find the request,
right click and hit "open in new tab". There it is. *Or* you can go use the web
debug toolbar to open the profiler for that request... which opens straight to the
exception.

Either way, once we can *see* the error, it's really clear! It says run
`composer require twig/string-extra`. Ok! Copy that, find your terminal and
paste:

```terminal-silent
composer require twig/string-extra
```

Once this finishes... move back over, close the profiler, refresh and nice! Wait,
hmm... I meant to put a little `...` at the end of the shortened description.
And... yea: that looks better.

## Replacing our Featured Sidebar Route

But *now*, let's make this *way* cooler by adding a "read more" link after
the description that, on click, will show the *entire* description. We're going
to do that with *zero* JavaScript thanks to Turbo frames.

But before we implement that, head over to `src/Controller/CartController.php`.
On `_cartFeaturedProduct()`, I'm going to *re-add* the route that we had earlier:
`@Route("/cart/_featured", name="_app_cart_product_featured")`. Copy the
route name then, over in the cart template - so `cart.html.twig` - instead
of using the `fragment_uri()` function, go back to using `{{ path() }}` and
then `_app_cart_product_featured`.

Doing this is *totally* unnecessary to accomplish our new goal. The reason
I'm doing this is because, in a few minutes, it'll make it easier to play with
the frame's URL.

## Setting up the Target Endpoint

*Now* let's get to work. Back over in `CartController`, here's the idea: if someone
requests this URL - but with a `?description=1` on the *end* of that URL - then
we'll render the *full* description. Otherwise, we'll render the truncated
description like we are now.

To do that, add a `Request` argument - the one from HttpFoundation - and then
pass a new variable into the template called `showDescription` set to
`$request->query->get('description')`.

Next, in `_featuredSidebar.html.twig`, if `showDescription`, then render
the full description: `featuredProduct.description`. Else, render the preview.

Now here's the *big* question: how do we create that "read more" link?
Remember that we're *inside* of a turbo-frame... and one of the properties of
a turbo frame is that navigation stays *inside* that frame. So if we create a
link to a page, or page partial, that renders this frame, Turbo will handle all
the heavy lifting of making the Ajax request, finding the frame and putting
its content *right* here.

In other words, all we need to do is create a boring, normal link to `{{ path() }}`
`_app_cart_product_featured` *with* `description: true`.

Hmm... PhpStorm is confused, so I'll delete and re-add this quote to reset the
highlighting. Inside the link, say "(read more)".

Done... or done-ish. If we refresh the page... we have a link! But when we click
it... the whole page navigates as if we were *not* in a turbo frame! Click back.

This happened because, in `cart.html.twig`, our turbo frame has `target="_top"`.
That makes it behave, kind of *not* like a frame: all link clicks and form submits
apply to the *whole* page. But we now *want* this one link - this read more link -
to "yes" behave like a *normal* turbo frame: we want it to *keep* its navigation
inside the frame.

To override the `target="_top"`, find the link in `_featuredSidebar`. Let's
put this onto multiple lines. Add `data-turbo-frame=""` and then the name
of our frame: `cart-sidebar`.

That's it! We also could have done the opposite... which in some ways would have
been more natural. We could have left *off* the `target="_top"` - so that our
entire frame behaves like normal - and then added `data-turbo-frame="_top"` to
the link and form that *should* navigate the whole page.

Either way, the result would be the same. Refresh now... and click. Beautiful!
Let's try that again. Oh, that's nice *and* simple: an Ajax system entirely
powered by small changes in PHP and Twig only.

## Manually Changing the src Attribute

Ooh, and now that this is working, I want to show you something cool. Inspect
the turbo-frame. Notice that when you click a link, it *changes* the `src=`
attribute to the new URL.

This is actually the *way* that turbo frames work. Each turbo frame *watches*
its `src` attribute. When it changes, it *notices* that and makes an Ajax call
*to* that new URL. In a normal situation, you click a link inside a frame, *that*
changes the `src` attribute and *that* triggers the Ajax call.

But you can also change this by hand... it's kind of fun. Take out the
`?description=1` and... cool! It made an Ajax request for the URL and rendered it!
Our "read more" link is back! If we *click* that link, it makes another Ajax call
and loads back.

That's a really neat, conceptual, thing to realize about turbo frames: they really
*do* work like iframes.

Next: let's make this frame a little bit smoother by adding a loading animation
between the time that we click the link and when the description actually renders.
