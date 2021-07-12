# Read More Frame

Coming soon...

On the cart page, let's make this feature product sidebar a bit more useful by adding
the products description, except we probably don't want to add the whole description
right here, cause it's kind of long. So we'll just show a preview head over to
`templates/cart/_featuredSidebar.html.twig` and right down here before the
cartt controls, after the name and the price will add `{{ featuredProduct.description }}`
And then I can pipe this to a special `|u` filter, which leverages
`symfony/string` component and say `.truncate(25)`. And then I'll do a little extra
`|trim` on the end, just in case there's an extra space.

This you filter comes from a, a special twig extension, which we don't actually have
installed yet, but let's try it anyways. We ever refresh and nothing happens. But
down on the web, we have our two of our, you can see that an Ajax call fail. So we
all know errors happen, even though we're awesome programmers. But when you're
working with turbo frames, those errors are harder to see since it happens in a
background age call to see the real hair, you can go to the network tab, find it
right, click and hit open in new tab. There's the error. Or you can go use the web UI
toolbar to open the profiler for that request. And you can see the exception there
either way. the error, once we can see it is really clear. It says run 
`composer require twig/string-extra` I'll copy that. Find my terminal and paste.

```terminal-silent
composer require twig/string-extra
```

Once this finishes Volver closed the profiler refresh And nice.

Hold on. Let me put a little, uh, let's put a little `...` at the end of that there,
that looks a little better. Okay. So now let's make this even cooler says this
description is fairly short by adding a little, see more link that when we click
shows the entire description, now, of course we could do this in JavaScript by like
no before, before we implement that head over to `src/Controller/CartController.php`
and on `_cartFeaturedProduct()`, I'm going to re add the route that we had earlier. So
`@Route("/cart/_featured" name="_app_cart_product_featured")`
and I'll copy that route name then over in the main tea
cart template. So `cart.html.twig`, instead of using the `fragment_uri`
stuff that we used before, I'm going to go back to the saying `{{ path() }}` and then 
`_app_cart_product_featured`.

Now this is totally unnecessary. In fact, the only reason I'm doing that, the reason
I'm doing this has nothing to do with the feature whereabouts implement. The only
reason I'm doing this is because in a few minutes, it will make it easier to play
with the frame. Anyways, back over in `CartController`. Here's the idea if someone
requests this URL, but with a little `?description=1` a one on the end of
the URL. Then we'll render with the full description. Otherwise we'll render the
truncated description. So to figure that out, let's add a `Request` argument one from
HttpFoundation, and then I'm going to pass a new variable into the
template called `showDescription`, set to `$request->query->get('description')`
description. So as long as that's set to one or anything that will return true, that
will be trophy in the `_featuredSidebar.html.twig` 

If, if `showDescription` then we're actually going to run into the full description 
`featuredProduct.description` else. We will our render our little preview, but how do we
create that read more link. Now, remember we are inside of a turbo frame. And one of
the properties of turbo frames is that navigation stays inside that frame. In other
words, we can create a link tag to our featured product, a URL with 
`?description=1`, and that should just do it. So all we need to do is create a
link to the URL where you want to render, which is curly, curly `path()`, and then our
`_app_cart_product_featured` but with, oops, the `description: true` after the text down
here, my editors, there we go. I'll fix the highlighting. Say read more.

That is the beauty of turbo frames. Normally, if we wanted to be able to click a
link, make an Ajax call, then put the response back into the same DIB. We would need
to read some custom JavaScript. Well with a turbo frame, we don't need to, we don't,
but it won't work quite yet. In this case, if we refresh the page three more link
works great, but when you click it, the whole page changes as if we were not in a
turbo frame that happened because let me go back and actually you can see my page is
a little confused right now.

Sometimes it happens with turbo drive. When you access a page, that's not doesn't
have turbo drive. Um, do you remember when this happened? Because in `card.html.twig`
our turbo frame has a `target="_top"`. So that makes it behave kind of,
not like a frame, all link clicks and form submits to actually change the whole, uh,
applied to the whole page. But we now want this one link on the page, this read more
link to behave like a normal turbo frame by keeping navigation inside the frame. So
to override the `target="_top"` final link in `_featuredSidebar`. And let me actually pop
this on the multiple lines here pops into multiple lines and, and then I will add
`data-turbo-frame=""` and then the name of our frame

`cart-sidebar`. All right, try now, refresh and click. Beautiful. Which are that again?
I click this boom. It instantly right insulin. It makes an agent called loads and
this was done completely by making just small tweaks to PHP and Twiggle. And there's
one other thing I want to kind of show you here with terminal frames. And this is
pretty cool. So this turbo frame here, the way that you notice that when you click
the link, it actually changed the source to the new URL so that you were all with
`?description=1`, and that's actually the way that turbo frames
work to real friends are actually watching it's `src` attribute and whatever its
source attribute changes it, navigates to that URL. Now, normally this changes
because you click a link inside of here and that automatically updates the URL. But
if you want, you can actually change this by hand.

I'm gonna take out the `?description=1` and check that out. It
made an Ajax request for that. You noticed that change made it a request agent,
professor, that URL in loaded and in there, I felt like the read more link. Again, it
changes that and makes another call. So it's just a really cool conceptual thing to
realize the turbo frames. They really do work like I frames. And if this `src`
attribute changes for any reason, it's going to notice that and it's going to follow
that next. Let's make this frame a little bit fancier by adding some loading
animation between the time that we click the read more link. And when the description
shows.

