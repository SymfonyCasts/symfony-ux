# Frame Redirect Cache

Coming soon...

Where else could we use our new turbo frame? Redirect,

Super system, go to the cart.

All right. Sierra sidebar. Over here,

We could,

With the add to cart controls, we can actually leverage a frame here. It works. Okay.
Right now this targets the whole page. Yeah. So when you add an item to it, it
redirects the whole page back into a page with a nice little flash message. Awesome.
That's exactly right, like that behavior. But if you go back and this time let's
change the blue and go into a negative quantity and it add, it's still redirects over
to the cart. It's still changes the page. And that is not quite as smooth. It's not a
huge deal, but I would be way cooler if we could just have this air show over in the
sidebar on the current page.

So let's do that.

The template for this cart, add a cart controls section lives over here at templates
product, add the cart cart, add controls. And this template is included on two pages.
As we've seen, it's included on the product show page and is also included over here
on the sidebar. When we submit this form, as we just saw, it's handled by the product
show pitch. This means that if we add a frame around all of this template, when we
submit it, the products show Pedro render, which will include this template and
include the new frame. Okay.

That needs to be a bit more clear. So let's do it

On the top of this was that turbo ID = turbo dash frame, and then ID equals, how
about add to cart controls then at the bottom, I'll add the closing frame. All right.
Just with that, let's refresh the car page, actually refresh the page, go to the cart
and let's submit with a negative quantity that is a much nicer. Now let's change to a
red change, the quantity to five and hits. Okay. Um, did it work? The color changed,
uh, your back degree and a quantity back to one and I don't see any errors, but that
was not very obvious. And I do not see the new item in the cart on until I refresh
the page.

So this makes sense because when we submit this form over here, that one over to the
product show page for that, which just showed a new empty form. So this can all be a
little bit better if we could revert the success functionality, if we could on
success, have this redirect to the product show page with the success message like it
was doing before. So fortunately it's exactly what our new frame system does. So
let's add the attribute that we created to this form frame. So I'm going to pop this
on and multiple lines cause it's getting kind of long and we'll add data dash turbo.
Gosh, that redirect = true. All right. So let's give that a try. I'll refresh the
cart page. And of course, if we submit a form on successfully that still stays right
here, but if we submit it successfully beautiful that redirected over to the product
show pitch. Okay.

But there are two weird things going on the first you may have noticed we're missing
a flash message. We'll talk about that in a second. The first, the other weird thing
is that if we keep adding items to our cart from this page, watch the shopping cart
header up here kind of jumps around little weird. Yikes. This is a result of the
preview system. When we submit this form for just a moment, it shows the preview of
the page, the cacheed preview of the page, which as of this moment, the cache
previous page is actually still the shopping cart 14th. So watched it from 14 up to
16. Now this is not normally a problem. If you submit a post form with turbo and have
a successful response, turbo automatically clears it's internal snapshot cache.
That's why we haven't seen weird things like this. It does that precisely to avoid
this problem, a successful forms and means something has changed. So we probably
shouldn't use any of the old cached pages. So the question is, why is it happening
now? The answer is because interval helper, we are calling events dot prevent default
on the turbo before fetch response event. I need to check that name. This tells it to
both prevent rendering the page, but it also has another side effect in that it
prevents it from clearing this snapshot calf, but it's no problem. Once we know that
we've Clare it manually by saying turbo dot cache. All right. So let's try this. Now.

I refresh as we click and add eyes-on items in here that con's up exactly like we
would expect. Awesome. By the way, there is still one spot where this little jumpy
cart thing happens. If you go to the cart page and then add an item. Now watch the
number. When I click back to the shopping cart, it's 21 right now and I'll click back
to the shopping cart. See that one from 20 back to 21.

This is due to

What I think is a fairly straightforward bug in turbo that I hope will be fixed in
the future. Here's the scoop. As we just talked about, when you successfully submit a
postform turbo clears, it's snapshot cache. And we even manually did that a minute
ago, but right after it clears the snapshot cache it then caches the current page
that's currently submitted. This means that

On our current page, the

Moment that when we hit add here, it actually caches the page, the shopping cart, 21,
right after it clears the cache.

It's a pretty rare

Situation by wanted you to know about it, to trigger this. You actually need a form
that submits to another page. And then it only happens when you click back to the
original page that will show the out-of-date preview. I'm just going to leave this
and kind of live with this right now, instead of adding more code to work around it.
Anyways, I mentioned a second ago that there were two weird issues with our, uh, new
add a cart system. The first one was that snapshot cache that we just fixed. The
second one is that when we add an item, it redirects, but there's no flash message.
This page actually does have a flash message. We saw it a few minutes ago. It should
be showing right here, but something unexpected is happening behind the scenes.
That's hiding it. Let's find out what next and then improve our system to prevent it.

