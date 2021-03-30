# Reuse Controller

Coming soon...

The whole point of our new reusable li reload content controller is to make an Ajax
call and put the HTML from the agents, call into a content target. Whenever, when
someone, whenever someone calls this refresh content method, we're already using it
on our product admin list page. After the new product modal form is submitted
successfully in our template, we listened to a custom modal form success method and
trigger the refresh content method. So that, so that the, so that the product list
reloads.

Okay,

What this controller does is super similar to a controller. We created earlier carte
list controller. In fact, they're basically identical. This is used on the cart page.
After we remove an item, let's actually add a couple of items So we can play with
this. Here's the plan. I want to eliminate some custom code by also reusing the
reload content controller here on the cart page. So let's start by deleting cart list
controller bye-bye Next, open the template for the cart so we can see how that was
used. That's templates, cart, cart, that HTML that twig. Okay, here it is. Cart list
controller is on the div that's around the cart table, Change this to reload content,
and then the reload content controller, instead of having a cart refresh value, it
just has a URL value. So we'll also change this to just URL.

Great.

We need something to call the refresh content, that method on the controller. How was
this working before the old controller? Let's dive a little deeper into the included
cart list that HTML, that twig template. Let's see. Ah, here it is. I remember now
after we remove an item from the cart. So over here, after we actually hit yes,
remove it, Submit confirmed controller that handles this dispatch dispatches a custom
event. Submit, confirm. Async submit it before we were listening to that in calling
remove item on the cart list controller. So all we need to do now is

Okay.

Use the reload content controller in call the refresh content method. Sweet. I think
we're done. Let's try it. I head over refresh, remove an item and uh, Oh, let's see.
Err, missing target element, reload, content dot content. Ah, in the reload content
controller, we reload the content into a target called content. We forgot to add that
target to the cart page. That's kind of an awesome thing about targets. If you create
a controller and that controller requires a target to be defined, you get a pretty
clear error. Alright. In car dot HTML, twig let's think which element do we need to
reload the content into actually it's this diff right here, the same one. We have
data dash controller on. So we should add a new target to this element we could, but
I have a better idea. What if we make the content target optional?

If it, if it is set, we'll put the HTML into it. But if it's not set, we will assume
that that the you, that we want the HTML to be put into the top level, this, that
element. How can we do that? Check this out. Say constant target = this.has content
target. I mentioned this has thing earlier when we first introduced targets, but we
haven't really used it yet. This is a safe way to check whether or not there is a
single, uh, content target, um, available. So if there is one, then of course we'll
use this.content target else. We'll use this.element. Now down here, we can use
target in those three other places instead of this.content target, try it now,
refresh, remove an item and Oh, it's still doesn't work

Or refresh it, remove and got it.

Same functionality with less code. Next earlier in the tutorial we built a search
preview controller.

Ooh,

It works really nicely, but it would look even better if we get add CSS transitions
so that the element fades in and out. Is that as easy as just adding or removing a
class in this case? No, but we already have a trick up our sleeves to add a
transition simply and beautifully.

