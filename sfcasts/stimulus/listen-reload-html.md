# Listening to An Event From Another Controller

Thanks to the `useDispatch` behavior, after the delete form submits via Ajax and
finishes, our `submit-confirm` controller dispatches a custom event called
`submit-confirm:async:submitted`. Copy that event name: we'll need it in a few
minutes.

This is awesome because we can *listen* to that event from our *other* controller
so that we can run code whenever an item is removed. At the top, we can remove that
`debug` flag now that we know the event *is* dispatching correctly.

## Custom Events are just Normal Events

So: how can we listen to the new event from inside `cart-list` controller? Well,
think about how a normal event works. If I click a link, for example, this checkout
link, my browser first calls any `click` listeners attached to the thing I actually
clicked. So, the `<a>` tag. Then, the event *bubbles up*. That's a fancy way of
saying that the browser *next* calls any `click` listeners on the element *above*
this: this `<div>`. Then... it calls any `click` listeners on the element above
that... and then above that... and then above that... all the way until it gets
to the `body`.

Whelp, our custom event is *no* different. When you call `dispatch()`, it
dispatches the event on the `element` attached to the controller, though that's
configurable.

For us, it means that the event is being dispatched on the `form` element. When
that happens, our browser first checks to see if there are any listeners to our
`submit-confirm:async:submitted` event on the `form` element. Then it bubbles up:
checking *each* element up the tree to see if each has any listeners to our custom
event.

This means two things for us. First, our custom event is no different than a
`click` event. So to listen to it, we can use a Stimulus action. And second, we
can *attach* that action to the `form` element *or* to any of its ancestors.

## Adding the Custom Event Action

So... where *should* we add the action? Over in the template, find the div that's
around the row for a single item: it's this `cart-item` element.

Add the action here. I'll pop things onto multiple lines... and then say
`data-action=""`, the name of our event - paste `submit-confirm:async:submitted` -
an `->`, the name of our controller - `cart-list` - a `#` sign, and finally the
name of the method to call when that event happens. How about `removeItem`.

Why am I adding the action to *this* exact div? Well, it won't matter at first.
But in a few minutes, it'll give us the ability to access this `div` and add
extra logic to make it fade out.

Over in the controller, rename `connect()` to `removeItem()`, give it an event
argument, and let's `console.log()` our very favorite `event.currentTarget`.

Ok team: let's find out if our controllers are communicating. Refresh, hit remove
and confirm. Over in the console... yes! It hit our new log and the `currentTarget`
is the `div` around the removed row.

## An Ajax Endpoint for "Partial" HTML

What we *really* want to do in this method is make an Ajax call to an endpoint
that will return the new HTML for the entire cart area. We can create that
endpoint with some clever organization.

Start in the template. Copy *all* of the HTML that's inside of our cart area. So
everything that's inside of the `cart-list` controller element: this `div`...
all the way down to the end. Yep, that looks right.

Now create a new template in `templates/cart/` called, how about
`_cartList.html.twig`, and paste.

Back in the original template, include that with
`{{ include('cart/_cartList.html.twig') }}`.

This won't change anything yet: our page still works like it did before.

But *now* we can add a route & controller that returns *just* this template partial.

Open `src/Controller/CartController.php`. This is the controller that renders the
shopping cart page. Right below that method, add another one:
`public function _shoppingCartList()`.

I'm keeping with the convention of prefixing my template partials - or even
controllers that return a "fragment" of HTML - with an underscore. Above this,
add `@Route()` and set the URL to be `/cart` - to match what's above - and then
`/_list`. Name the route `_app_cart_list`.

Beautiful! To render the new template, we need one variable: `cart`... which we
get via this `CartStorage` service that's custom to our project. Copy that
argument, paste it down here, and return `$this->render('cart/_cartList.html.twig')`
passing a `cart` variable set to `$cartStorage->getOrCreateCart()`.

We're done! Go try it in the browser by going directly here. So `/cart/_list`
and... got it! Hit back.

Next: let's update our controller to make an Ajax call to this endpoint and replace
the entire cart area with fresh HTML. After we do that, will we need to, somehow,
*re-initialize* our Stimulus controllers on the new HTML elements?

And, as a bonus, we'll add a basic CSS transition to *really* make things shine.
