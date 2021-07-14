# Targeting Links in or out of the Frame

Head to the cart page and click the feature product to go to its page. Whoa. It
disappeared! And... we're still on the cart page. Head to the console. Ah, that's
a familiar error!

Response has no matching `<turbo-frame id="cart-sidebar">`. This shows off the true
main property of a `<turbo-frame>`: any navigation inside of a frame - whether you
click a link or fill out a form - will *stay* inside that frame.

Refresh. When we click this link, it *does* make an Ajax request to the "inflatable
sofa" product page: you can see it down here in the network tools. It *then* looked
for a `cart-sidebar` turbo frame on that page because it wants to find which *part*
of this page it should render inside of the `cart-sidebar` frame.

But... in this case, that is *not* what we wanted! We wanted to leverage the nice,
lazy-loading coolness of the turbo frame... but after that... we kind of want all
its links and forms to navigate like normal.

## target="_top" For "Normal" Navigation

No problem. Open the template for the cart page: `templates/cart/cart.html.twig`.
On the `<turbo-frame>`, add `target="_top"`.

[[[ code('d745d4e898') ]]]

That's it! The `_top` means that any links or forms inside of this frame should
target the *main* page. You can also change the target on just a *specific* link
or form instead of the *entire* frame... and we'll see how later.

Anyways, if we refresh now... and click. It's back to normal. If you go back to
the shopping cart and click to add the item to your cart, this *also* works.
That just submitted a form... which was *also* broken a minute ago before we added
`target="_top"`.

## Adding Attributes on the Initial Frame or Ajax-Loaded Frame?

But... wait a second. We just added `target="_top"` to the turbo frame in
`cart.html.twig`. But what about the `turbo-frame` over here in
`_featuredSidebar.html.twig`? This is the frame that's actually loaded via Ajax.

Let's talk about a small - but important - detail about turbo frames. When we
initially load the cart page, all its HTML comes from `cart.html.twig`. This means
that what we're originally loading on the page is a `turbo-frame` with a `src`
attribute and a `target` attribute.

But what happens after it makes the Ajax request? Does the `turbo-frame` from the
Ajax request *replace* the existing one that loaded on the page originally? Or...
does it keep the original `turbo-frame` tag and only use the new frames *inner*
HTML?

The answer is that a turbo frame only uses the *inner* HTML. So whatever attributes
your frame *starts* with - like `src` and `target` - it will keep those, regardless
of the attributes on any `turbo-frame` that it loads later via Ajax. Well, the
the `src` attribute changes to the new URL, but that's it.

We can see this over in our browser. Inspect this frame: this `turbo-frame` has
`src` and `target="_top"`. So, when the new frame loaded via Ajax, that frame
didn't *replace* this one: we know that because only the *original* frame has
`target="_top"`.

*Anyways*, this is why we added `target="_top"` to the frame in `cart.html.twig`:
our *original* frame.

But... in `_featuredSidebar.html.twig`, I'm *also* going to add `target="_top"`
here. 

[[[ code('768d838823') ]]]

Why? Well, functionally-speaking, it makes no difference. But conceptually,
if you look at this frame in isolation, its links - like this link and the form
down here - are not designed to navigate in the frame. Both are really meant to
target the main page. Adding `target="_top"` here makes that clear.

And also, if we ever simply use Twig's `include()` function to include this template
directly on a page, the frame would already have the `target="_top"` that it needs.
Though, an even *better* way to guarantee that a link has the right target is to
add it *to* the link itself - which we'll see soon.

So now that we've made this turbo frame *not* to keep its navigation inside of itself,
let's see a real example of when keeping the normal turbo-frame behavior is awesome.
