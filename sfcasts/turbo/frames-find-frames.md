# Turbo Frames Look for & Load the Matching Frame

This is a *super* important detail of Turbo frames. When a frame makes an Ajax call,
it looks in the response for a `<turbo-frame>` element that has the same *id* as
itself and uses *its* content only. If it does *not* find a matching `<turbo-frame>`,
in the response, then you get this error.

Ok, but... why? If you look in the network tools, the response from the Ajax call
contains the *exact* HTML we want. Why doesn't it just take the *entire* HTML from
the response and put it into the frame?

Well, we're not leveraging it in this example, but one of the super powers of the
frame system is that you can point a frame at a URL that returns an *entire*, full
HTML page. So if you pretend that this returns an entire full page of HTML,
the frame system is smart enough to only find and use the matching frame. This
allows you to create full, normal pages and then *reuse* those full normal pages
to power your frames. If this doesn't make sense yet, don't worry. Our next
example will illustrate this.

## Adding the turbo-frame in the Response

Anyways, what we need to do is make sure that the response contains a `<turbo-frame>`
with `id="cart-sidebar"`. I'll copy that from `cart.html.twig`, open
`_featuredSidebar.html.twig`, add that inside of here... and indent everything.

Notice that we don't have a `src=""` on *this* frame: this is *not* a lazy frame...
it's just a frame that already has its content.

Ok: let's try it again. Refresh and... yes! It works! It looked in the response
for the `<turbo-frame>` with the id, found it and used its HTML. If you inspect the
element on the `turbo-frame`, you can see the `src=""` attribute is still there,
but now it's filled with content.

At this point, if you click any links or submitted the form on the sidebar...
it might not work like you expect because the frame will keep any navigation
*inside* the frame. That's the first use-case for Turbo Frames - and we'll come
back in a few minutes to address this.

## Using fragment_uri()

Oh, and by the way, if you're using Symfony 5.3 and you create a controller like
this that just renders part of a page, you don't *have* to give this a route.
There's another option. Remove this route.

Now, in `cart.html.twig`, instead of `{{ path() }}`, use `{{ fragment_uri() }}`
and then `controller()` and *then* the name of our controller:
`App\\Controller\\CartController::` and then the method name... which is
`_featuredProduct`.

This is a bit longer - and those double slashes are ugly and needed because backslash
is an escape character. Behind the scenes, this will generate a signed URL - called
a fragment URL - that renders our controller. To get this to work, make sure that
you have the fragment system activated: that's in `config/packages/framework.yaml`.
Uncomment `fragments: true`.

Let's try this. Move over, refresh the page and cool! It still works! If you look
at the `turbo-frame`, the `src=""` is now set to a long, weird looking `_fragments`
URL.

Next: let's look at a second lazy frame example. But this time, instead of creating
a controller that renders *just* the frame, we're going to populate a frame by
*reusing* an existing, full HTML page.
