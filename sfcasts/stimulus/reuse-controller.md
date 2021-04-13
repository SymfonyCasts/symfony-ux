# Reusing the "Reload Content" Controller

The whole point of our new reusable `reload-content_controller` is to make an Ajax
call and put the HTML from that call into a `content` target whenever someone calls
this `refreshContent()` method.

We're already using it on our product admin list page. After the new product modal
form is submitted successfully, in our template, we listen to the `modal-form:success`
event and trigger the `refreshContent` method... so that the product list reloads.

What this controller does is *super* similar to a controller we created earlier:
`cart-list_controller`. In fact, they're basically identical! This is used on the
cart page after we remove an item. Let's actually add a couple of items so we can
play with this.

Here's the plan: I want to eliminate some custom code by reusing the
`reload-content_controller` *here* on the cart page. Start with the fun part: deleting
`cart-list_controller`. Bye bye!

Next, open the template for the cart so we can see how that was used:
`templates/cart/cart.html.twig`. Okay, here it is: the `cart-list` controller is
on the `div` that's around the cart table. Change this to `reload-content`... and
then, the `cartRefreshUrl` value is now called `url` in the new controller,
so change that here.

[[[ code('bf1a639deb') ]]]

Great!

Now, we need something to *call* the `refreshContent()` method on the controller.
How was this working before with the *old* controller? Let's dive a little deeper
into the included `_cartList.html.twig` template. Let's see. Ah, here it is, I
remember now. After we remove an item from the cart - so over here, after we
actually hit "yes, remove it" - the `submit-confirm` controller that handles
this dispatches a custom event: `submit-confirm:async:submitted`. Before, we were
listening to that and calling `removeItem` on the `cart-list` controller. All we
need to do *now* - since we're using the `reload-content` controller - is call
the `refreshContent` method.

[[[ code('8df2a553ad') ]]]

Sweet! I think we're done. Testing time!

## Making a Target Optional

Head over refresh, remove an item and... uh oh! Let's see:

> Error: missing target element `reload-content.content`

Ah... In `reload-content_controller`, we put the HTML into a target called `content`.
We forgot to *add* that target to the cart page! That's kind of an awesome thing
about targets: if you create a controller... and that controller requires a target
to be defined, you get a pretty clear error if you forget.

Ok: in `cart.html.twig`, let's think: which element do we need to reload the content
into? Actually it's this `<div>` right here: the same one that has the `data-controller`
attribute on it. So: we should add a new target to this element, right?

We could. But I have a better idea. What if we make the `content` target *optional*?

If it *is* set, we'll put the HTML into it. But if it is *not* set, we will assume
that the HTML should be put into the *top* level element: `this.element`.

How can we do that? Check this out. Say `const target = this.hasContentTarget`.
I mentioned this `has` thing earlier when we first introduced targets, but we
haven't really used it yet. This is a safe way to check whether or not there *is* a
`content` target defined in the HTML. If there *is* one, then of course we'll
use `this.contentTarget`. Else use `this.element`.

[[[ code('ee2b10e336') ]]]

Now, down here, use `target` in those three other places instead of
`this.contentTarget`.

[[[ code('bc2edce4c3') ]]]

Try it now! Refresh, remove an item and... got it! We get the same functionality
with *less* code!

Next: earlier in the tutorial we built a `search-preview` controller: oooOOOoo.
It works really nicely. But... it would look even *better* with some CSS fade in
and fade out transitions. Is that as easy as just... adding and removing a class
at the right time in our controller?

In this case... no. But don't worry: we already have a trick up our sleeve that
will allow us to add transitions simply and beautifully.
