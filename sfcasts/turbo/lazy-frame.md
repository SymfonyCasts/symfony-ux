# Lazy Frame

Coming soon...

Time to move on to part two of turbo turbo frames. This is a brand new feature to
turbo. It didn't exist in the old turbo links library. And in the simplest sense that
frames allow you to treat part of your page. Well, basically like an I-frame, like if
you imagine that this category is sidebar, when I-frame, you could actually click
these links or even submit forms and only in the frame itself, and only the frame
itself would reload with turbo drive frames. One of the cool things about frames that
works with very few changes to your server side code, but I do want you to keep
something in mind. Frames are super cool, but they're an extra feature turbo drives
give gives us these single page app experience frames, give you the ability to make
the user experience even better in some cases, but it does mean that you'll need to
write a little extra code to get them to work. Now, there are basically two use cases
for turbo frames. The first is kind of what I just talked about. You want
navigation and just one area of your page to happen without reloading rest of your
page. So one every page of acts independently. Yeah. The second use case is that you
want a part of your page to load laser only like on page load, it's actually empty.
Then the turbo frame makes an Ajax call to populate itself. We're actually going to
look at lazy frames first

But before we do, I'm gonna move into my terminal and run 

```terminal
yarn upgrade @hotwired/turbo
```

As a reminder `@hotwired/turbo` is just in line in our `package.json`
file. It was added automatically when we installed the `ux-turbo` library, but we
completely control this library. When I originally downloaded it, I got it at `beta.5`
The latest, as you can see over here for me is `beta.7`. No, a lot has
changed between those two versions, but there was one tweak to how JavaScript works
in frames that I want to get. Okay, head over to the cart page, see this featured
product sidebar.

Let's pretend that rendering that as kind of a heavy, if we could load it laser only,
then the rest of the cart page would load much faster. And that would be awesome to
little this, Leslie, we first need a route and controller that can render the
sidebar. We'll put up a template for this page, which is `templates/cart/cart.html.twig`
Here we go. This is where we render the featured sidebar. You can see
that it's already isolated into its own template. So all we need to do is create a
Robyn controller that renders this template. So let's do that in 
`src/Controller/CartController.php` And let's see here, this is the cart page right here. So what I can
actually do is close this function. This will look a little funny for a second.

I can do here is actually going to copy this entire cart function, paste it down
here. Let's call the method. `_cartFeaturedProduct()`. If you were out all the 
`/cart/_featured`, I like to use that `_` prefix when something only renders part of
the page, then down here instead of running `cart.html.twig` So we will render
`_featuredSidebar.html.twig`. And we don't need to pass the `cart` in
there. So I don't need this `CartStorage`. Cool, Alex, good open. Let me give us a new
name, `_app_cart_product_featured`
cool. Now up here on the cart page, this is where we're going to now allow us to load
faster because we don't need to make this cart, `addToCartForm` or `fetchTheFeaturedProduct`
anymore.

And I can even get rid of one of the arguments perfect. Because in card that age, do
you want to twig, we're not going to include this sidebar anymore. Instead we are
going to include a turbo frame. So a turbo frame is literally a custom element called
`<turbo-frame>`. And it always, at least has an `id` attribute that identifies it like
`id="cart-sidebar"`. Anyone knows a piece of from highlights. This, it doesn't
know what that HTML tag is, but it is a legitimate custom element that turbo ads,
lazy frames, which this is going to be a lazy frame.

Always have also a source attribute set to the URL that it should, um, request to get
its contents, which is gonna be `{{ path() }}` then `_app_cart_product_featured` inside here, we
can put some loading text, which will show up while the Ajax call is being made.
That's it with any luck, turbo will see the frame initiated the AGS call and pop the
response inside. Let's try it refresh and huh, watch closely for that loading is
there for just a second, then it disappears. And if you check the console air
response has no matching `<turbo-frame id="car-sidebar">` element. So it was looking for
a tuber friend that has the same ID that we have here. Why this is a super important
detail of turbo frames. When a frame makes an Ajax call, it looks in the response for
a turbo frame that has the same ID as itself, and it uses its content. Only if it
doesn't find a matching turbo frame, the response, then you get the error that you
see over here.

It doesn't do that. Why doesn't it just take the entire HTML from the response which
we can see inside of our network tools? Why does it just take this entire response
and put it into the frame? Well, we're not leveraging it in this example, but one of
the super powers of the frame system is that you can point a frame at a URL that
returns an entire HTML page. So you can pretend that this is returning an entire full
page of HTML and the frame system is smart enough to only find in, grab the matching
frame. This allows you to create full normal pages and then reuse those full normal
pages to power your frames. If this doesn't make sense. Yeah, don't worry. We're
going to see an example next anyways, what we need to do in this case is make sure
that the response contains a `<turbo-frame>` with `id="cart-sidebar"`. So I'm going to copy
that from `cart.html.twig` then going to `_featuredSidebar.html.twig`
and add that inside of here. And then I'll indent everything. And that was, we
don't have any `src=""` on this triple frame. This isn't, this itself is not a lazy
turbo frame. It's just a terrible frame and it has this content.

All right, let's try it again. Refresh and yes, it works. It looked. And the response
for that term turbo frame with the ID found it and used it's HTML. If you inspect the
element, I want to know if you inspect the element, there we go. On a terminal frame,
you can see source. He goes `cart-featured`. By the way, if you're using Symfony
5.3 and you create a controller like this, that just renders a, uh, a part of the
page. You don't even need to give this a route. If you don't want to pick it up,
let's remove this route, route the route, okay, this controller is no longer
accessible to the world, except that in a car that age to my twig, which now instead
of pat everybody's `{{ path() }}` of `{{ fragment_uri() }}`, and then `controller()`, and
then the name of our controller. It looks a little long here for me, 
`App\\Controller\\CartController::` and then the method name child copy `_featuredProduct`

He's double slashes aren't needed here, uh, because they're escape characters. This
is a bit longer to write, but it has the same effect. It will generate a signed URL
called a fragment URL that renders our control. But to get this to work, make sure
that you have the fragment system navel that's in `config/packages/framework.yaml` and
uncommon `fragments: true`. All right, let's try it had over refresh the page and yes,
it still works. And if you look at the triple frame here, you can see view long,
weird looking `_fragments`. You are okay. Right next, let's look at a second lazy frame
example, but this time, instead of creating a controller that renders just a little
frame part of our page, we're going to populate a frame by reusing an existing full
HTML page.

