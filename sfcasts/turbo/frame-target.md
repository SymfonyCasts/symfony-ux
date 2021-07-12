# Frame Target

Coming soon...

How did the cart page and click this feature product to go to it's page? Whoa. It
disappeared in where she's still on the cart page. If we open the console down here,
we can see an error that we know

Response has no matching `<turbo-frame id="cart-sidebar">`
This Shows off the true main property of a `<turbo-frame>`, any navigation inside of a frame,
whether you click a link or fill out a form will stay inside that frame.

So if I refresh, when we click this link,

It does make an Ajax request to the URL, the inflatable sofa product page.

You can see this down here in the network tools here,

But then it looks for a `cart-sidebar` turbo frame

On that page because

It wants to find which part of this page it should render inside of our `cart-sidebar`
frame. Unfortunately, in this case, that's not what we wanted. We wanted to leverage
the nice, lazy loading of the turbo frame.

Ooh, but once

It's here, we kind of just want it to navigate like normal.

No problem. Open the template for this cart. Page `templates/cart/cart.html.twig`
on the `<turbo-frame>` add

`target="_top"`. That's it? The `_top` means that any links or forms inside of this
frame should target the main page.

You can

Also change the target on just a specific link or form. And we'll see that later.
Anyways, if we refresh now

And click it's back to normal. If you go

Back to the shopping cart and click to add the item to your cart, this also works
that just submitted a form. And that was also broken before we added the 
`target="_top"`. But wait a second, we just added the `target="_top"` to the turbo frame
in `cart.html.twig` But what

About the turbo frame over here in `_featuredSidebar.html.twig`. This is the frame
that's actually loaded via Ajax. So here's a small but important detail about turbo
frames right now. When we initially load the cart page,

That's loading

`card.html.twig`, which means what we're originally loading on the page is a
turbo frame with a `src` attribute and a `target` attribute. But what happens after
the lazy frame, it makes the Ajax

Request. Does the

Turbo frame from the Ajax request replace the existing one that loaded on the page
originally? Or does it just use the new frames? Inner HTML? The answer is a turbo
frame only uses the inner HTML. So whatever attributes your frame starts with, like
`src` and a `target`, it will keep, regardless of if the frame loads via Ajax or you
click a link or you submit a form, we can see this over on our browser. If I inspect
this frame, you can see the turbo frame has `src` and a `target="_top"`. So on the new
frame loaded inside of here, it did not replace the frame. It just used the new
frames, inner HTML. So that is why we added the `target="_top"` two in `cart.html.twig`
where our original frame Lotes,

But in `_featuredSidebar.html.twig` I'm also going to add `target="_top"` here.

Why? Well, functionally

Right now it makes no difference. But conceptually, if you look at this frame in
isolation, it's links like this link here and the form that's down here. They're not
designed to navigate in the frame, both the link in the form of meant to be targeted
on the top of the main page. So adding `target="_top"` here

Makes that clear. And also

If we ever simply use twigs include function to include this template on a page,

The frame would already

Have the `target="_top"` that it needs. Okay. So now that we've made this turbo frame
not to keep its navigation inside of itself, let's see a real example of when keeping
the normal turbo frame behavior is awesome. By adding a, see more writing a
description down here where they see more link without adding any new end points.

