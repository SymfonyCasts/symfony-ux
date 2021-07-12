# Turbo Frames: Lazy Frames

Time to move on to part two of Turbo: Turbo frames. This is a brand new feature -
it did *not* exist in the old Turbolinks library. To put it simply, Turbo
frames allow you to treat part of your page, well, basically like an `iframe`!
If you've never worked with iframes or IE6, I'm jealous. Turbo frames are a native,
non-weird way to get the goodness of iframes... without actually using iframes,
which are a pain in the butt.

Imagine that this category sidebar were inside a Turbo frame. If it were, you
could click these links or even submit forms and only the *frame's* content would
change: the rest of the page would be unaffected.

Frames are super cool, but I *do* want us to keep something in mind: they're an
"extra" feature. Turbo Drive gives us the single page app experience. Frames give
us the ability to make the user experience even *better*. But using frames *does*
mean that you'll need to write some extra code. Frames are form of progressive
enhancement... which basically means that you should get your site working first,
*then* come back to see where a tool like Turbo frames can *enhance* it further.

## The 2 Use Cases for Frames

Ok, so there are basically 2 use-cases for Turbo frames. The first is what we just
talked about: you want navigation in just *one* area of your page to happen
*inside* that area without affecting the rest of your page.

The second use-case is when you want a part of your page to load lazily. Literally,
an area of your site would be empty on page load... then that Turbo frame would make
an Ajax call to populate itself.

## Upgrading to the Latest Turbo

Before we jump into an example, I'm going to find my terminal and run:

```terminal
yarn upgrade @hotwired/turbo
```

As a reminder `@hotwired/turbo`, is a normal library and you can find it in the
`package.json` file.

[[[ code('81695c8dd7') ]]]

This line *was* added automatically when we installed the `symfony/ux-turbo`
PHP package, but we have *complete* control over managing its version. When I
originally downloaded it, I got version `beta.5`. The latest version at the
time of recording, which you can see over here, is `beta.7`. Not a lot has
changed between the two versions, but there *was* one tweak to how JavaScript works
in frames that I want to get.

## Setting up a Lazy Frame

Okay, at your browser, head to the cart page. We're going to talk about the
*second* use-case for Turbo frames first: lazy frames. See this featured
product sidebar? Let's pretend that rendering this is kind of a heavy. If we could
load it lazily - so via an Ajax call - then the rest of the cart page could load
*faster* because it wouldn't need to do the work of preparing and rendering that
section.

To lazily load this, we first need a route and controller that renders the sidebar.
Open the template for this page: `templates/cart/cart.html.twig`. Let's see...
this is where we render the featured sidebar. And you can see that it's *already*
isolated into its own template. So all *we* need to do is create a route & controller
that *render* this template.

[[[ code('bc945b7001') ]]]

Let's do that in `src/Controller/CartController.php`. This top method is the cart
page itself. Copy that, paste below, rename it to `_cartFeaturedProduct()`
and change the URL to `/cart/_featured`. I like to use that `_` prefix when
something only renders *part* of a page. Below, instead of rendering `cart.html.twig`,
render `_featuredSidebar.html.twig`. And... we don't need to pass the `cart`
variable... and so we don't need this `CartStorage`. Oh, and the route needs
a unique name, like `_app_cart_product_featured`.

[[[ code('c4deb5c239') ]]]

Cool. Now, up in the cart action, this will load faster because we can do
*less* work... because we don't need to prepare the `addToCartForm` or
fetch the `featuredProduct` anymore. We can even remove this argument.

## The Custom &lt;turbo-frame&gt; Element

We can do all of this because, in the template for this action - `cart.html.twig` -
we're not going to include this sidebar anymore. Instead, we're going to add
a Turbo Frame... which is... just a custom HTML element - `<turbo-frame>` - which
always has at *least* an `id` attribute that identifies it, like `id="cart-sidebar"`.

[[[ code('a969bfe789') ]]]

PhpStorm highlights this as an unknown tag, but the Turbo library *does* register
it as a custom element.

If we stopped here, this would render an empty `<turbo-frame>` element on the page...
and would do nothing. To make this a "lazy" frame, add a `src` attribute set to the
URL that it should request to get its contents. In this case, that's `{{ path() }}`
then `_app_cart_product_featured`. Inside the `turbo-frame`, we can put some loading
text: this will show on page load while the Ajax call is being made.

That's it! With any luck, Turbo will see the frame, initiate the Ajax call and pop
the response inside. Let's try it! Refresh and watch closely. Woh: the "Loading..."
was there for *just* a second, then it disappeared! Check the console. Error!

> Response has no matching `<turbo-frame id="cart-sidebar">` element.

Interesting: it made the Ajax call and then looked for a `turbo-frame` element in
the response with the same id as our frame. Why? The answer goes to the *core* of
how Turbo frames work. Let's dive into that next and get this thing working.
