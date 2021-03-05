# Ajax-Powered HTML Updates & a CSS Transition

Time to make the Ajax call. In our new stimulus controller - `cart-list` -
add a *value* called `cartRefreshUrl`, which will be a `String`. We're doing
this, like we've done before, to avoid hardcoding the URL to the endpoint.

## Fetching the Fresh HTML

Copy `cartRefreshUrl`, go to `cart.html.twig`, add a second argument to
`stimulus_controller()` and set `cartRefreshUrl` to `path()` and the name of
our route: `_app_cart_list`.

Making the Ajax call is probably the easiest part. Down in `removeItem()`, say
`const response = await fetch(this.cartRefreshUrlValue)`. And, of course, as soon
as we use `await`, we need to make the method `async`. Finish by replacing
the entire HTML of this element with the response text:
`this.element.innerHTML = await response.text()`.

We're done! Testing time. Oh, but an empty cart is no fun... let's add a few more
items. And... excellent! Remove the red sofa, confirm and... oh! That was
awesome! We get the entire, no-full-page-refresh experience with *zero*
duplication and *minimal* JavaScript. I mean, check out how big the controller
is! It's teenie tiny!

## Stimulus Re-Initialized on the new HTML

And a super important, amazing thing just happened automatically. We add new HTML
to the page. In fact, *all* of the HTML inside of this element is brand new.
*Normally*, with JavaScript, that's a problem: any event listeners that we need
on the elements - like a submit listener that opens a dialog - need to be manually
reattached to the new elements.

But with Stimulus, it all... just works! We talked about this earlier. As *soon*
as Stimulus saw these two new `data-controller="submit-confirm"` elements on the
page, it instantiated two fresh new `submit-confirm` controllers. And everything
behaves perfectly. Watch: if we click remove... that still works! *We* don't
need to think about *anything*.

## A Simple CSS Transition

I'm *so* excited about this that I want to add one *last* tiny extra detail to
make it really smooth. I want to make the row fade out before it disappears.
We can do this with a CSS transition.

Open up `assets/styles/app.css` and scroll down a bit: I'm looking for `cart-item`.
Here it is. This is the class that's around each cart row. Add
`transition: opacity 500ms`

That doesn't *actually* make it transition. This just says: *if* the opacity ever
changes... for *any* reason, I want you to change the `opacity` *gradually* to the
new value over 500 milliseconds.

Below this, add another `.cart .cart-item` with `.removing` and set `opacity: 0`.
This says, if the `cart-item` element *also* has a `removing` class, change the
opacity to zero. Thanks to the `transition`, that change will happen gradually.

And where does this `removing` class come from? Good question! *We* are going
to add it.

Back in the controller, right at the beginning, add `event.currentTarget`. That
will get us the element that's around the entire row: this element here...
which has the `cart-item` class on it. Then `.classList.add('removing')`.

Try it! Refresh. Let's delete the blue sofa. Watch closely. Yes! It was quick,
but it faded out before it was replaced. Remove the last one. That's *so* cool.

If your server is *super* fast, the fading out might not finish before the HTML
reloads. If you care enough, you could delay the Ajax call a few milliseconds with
`setTimeout()` or get super fancy with some extra promises.

Later, we'll talk about how to add CSS transitions in a different, more
robust way. But this was easy and works nicely!

Next, we've talked a lot about Stimulus. But isn't this also a tutorial about
Symfony UX? What is that? And how does it fit in? Let's find out by adding a
JavaScript-powered chart to our page... by only writing PHP code.
