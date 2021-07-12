# Frame Loading

Coming soon...

Yeah, with turbo drive. If we click a link or submit a form, if that page takes
longer than 500 milliseconds to load, then we get a loading animation on the top of
the page, which we don't see here, because this is all loading fast, but it's sort of
a built in loading indicator that you don't even need to think about. But the same is
not true for terrible frames. When you click this, read more link, that loads pretty
fast, but there is a slight delay when nothing happens. And if clicking this we're
loading a heavier page, it might not load so fast. So can we add a loading indicator?
Sure. And we actually already have what we need head over to `src/Controller/CartController.php`
And in `_cartFeaturedProduct()`, let's sleep for three seconds to fake us
slow page back at the browser, inspect this turbo frame, make sure you're selecting
on it and then watch it as watched this element. When I refresh the page, there it
is. Look, it says a `busy`, yep. Whenever you're terrible frame is loading, it gets
this attribute. If we click the read more link, we'll see it again.

This simple Azure, it makes it possible to add all sorts of loading indicators. For
example, we could create two classes, one that will hide an element during loading in
one that will show an element only during loading. Let's do this over in our
`templates/cart/_featuredSidebar.html.twig`. So for example, this read more link. Let's
pretend we want that to hide once we click it. So we'll add `class=""` and I'll invent a
new class called `frame-loading-hide`. And then down after this, let's add a `<span>`. I'll
give this a class of `frame-loading-show`. So we want this to show only when we're
loading. Also give this `fas fas-spinner fa-spin` so that this is actually a
font awesome icon. All right now, for the styling for this head over to 
`assets/styles/app.css` and we can do here is we can say `turbo-frame[busy]`
So if there's a turbo frame element that has a busy attribute, then for the
`.frame-loading-hide` element, we want to say `display:none`

That's an element that should be hidden while we're loading for the other element,
the `frame-loading-show`. We want this to hide by default and then during loading. So
first actually to hide it, I'm going to actually copy this. Okay. Comma. And then
we're gonna say, is that on any `turbo-frame`? If you have `frame-loading-show`
then we also want you to have `display: none` because what we'll do is we'll override
that down here. Oh, jumped around a little bit down here. If we have `turbo-frame[busy]`
any element inside of it with `frame-loading-show` should have `display` and I'm
going to do `inline-block` because that's going to work well with my font. Awesome
icon. It's a little complicated, but if you stare at that, that's going to get things
to hide and show in just the right situations. All right. So let's try it over
refresh.

It's a little slow to load and perfect. So you can already see that my font awesome
icon is not showing up because it's hidden by default. Now I'll click this link.
Beautiful. Okay. And you can leverage this technique to pretty much do whatever you
want. For example, another approach which will instantly work for all frames across
your entire site is to lower the opacity of the frame while it's loading. So this is
pretty easy. We can just say, I'll copy the turbo frame from up. I'm going to say
`turbo-frame[busy]` `opacity` and temporarily I'll set this to `.2`. So it's
really noticeable. All right. So try it refresh and we should already see it while
it's doing the initial loading. And we do.

And when I click this link down here, we don't, that's weird to see what's going on
on the spec element on this. Let's hack a little busy attribute on the end of this.
So to say busy. All right. So when I do that, you can see, it does see the CSS over
here. It's just not actually making the element. It's just not actually applying to
the element. And here's the problem. If you hover over this, let me scroll up a
little bit, actually check it out. It has no height. You can kind of see the->in the
upper left is not really highlighting the element. You'd expect it to kind of go
around the element like this, but it's not going around the element. The problem
[inaudible].

So this is interesting. The problem is that `<turbo-frame>` is a custom HTML element. And
by default, your browser renders it as an inline element. You can see this over in
the computer `display: inline`. And so when you put block elements inside of it, it just
doesn't expand in the way you'd expect it to. That's why it appears to have no
height. And that's why it appears that nothing has the low capacity to fix this. We
can make this element `display: block`. You can see as soon as I do that, it takes
effect. So to make this work everywhere, you can actually make your turbo frames
display block by default. So `turbo-frame`  `display: block`. All right, try now refresh
the original one is still working and after it loads, we click that works too.

All right. So now that this looks good, let's go and make that, uh, opacity a little,
yeah, less dramatic and over in `CartController`, I'll take out that slate. So now if
we play with the page, it looks a little bit more natural. Perfect. Before we keep
doing, keep going in doing all of this triple frame stuff, we accidentally broke page
quick checkout, ah, variable `showDescription` does not exist coming from 
`_featuredSidebar.html.twig`. The template for this page lives, that 
`templates/checkout/checkout.html.twig`
ah, this also has a featured product sidebar and it is still using the
include directly. So when we added our new show description, variable, that doesn't
exist because we're including a directly. So obviously we could fix this by passing
that variable in, but we don't have to anymore. We can simply remove this and replace
it with a lazy turbo frame. So in `cart.html.twig`, let's steal our lazy frame
from there and paste it inside. `checkout.html.twig`. And if you even
wanted to not duplicate these three lines of code, you could put those in their own
twig template and include it.

Celebrate this open up a controller for this page, which is `CheckoutController`. And
we actually don't need a couple of variables anymore. We don't need to `$addToCartForm`
form or the `$featuredProduct` variable, which means we don't need to create this
variable or this variable. And we don't need to inject this, uh, arguing not anymore.
Okay. Refresh now and all good and are nice. Read more even works over here next
below each product. If you're logged in users can post a review. We can make this a
bit more awesome by leveraging a turbo frame.

