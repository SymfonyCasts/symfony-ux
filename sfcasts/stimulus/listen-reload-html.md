# Listen Reload Html

Coming soon...

Thanks to the use dispatch behavior and the dispatch method down here after the
delete form submits via Ajax and finishes our submit confirmed controller dispatches
a custom event called submit confirm colon async Cohen submitted. Oh, copy that event
name we'll need it in a few minutes. This is awesome because we can listen to this
event from our other controller to know when an item was just removed. Go ahead and
at the top, take out that debug flag now that we know the event is dispatching
correctly. So how can we listen to the new event from cart list controller? Well,
think about how a normal event works. If I click a link, for example, this checkout
link, my browser first calls, any click listeners attached to the thing I actually
clicked. So this tag, then the event bubbles up, that's a fancy way of saying that it
then calls any click listeners on the element above this, this div. Then it calls any
quick listeners on the element above that, and then above that, and then above that,
and then above that and so on until it gets to the body and even the document itself,
our custom event is no different.

When you use dispatch, it dispatches the event on the element attached to this
controller. For us, it means that the event is actually being dispatched on the form
element. When that happens, our browser first checks to see if there are any
listeners to our submit confirm async submitted event on this form tech, then it
bubbles up, checking every element to see if it has any listeners to that custom
event name all the way to the top. This means two things for us. First, our custom
event is no different than a click event. So to listen to it, we use a stimulus
action. And second, we can attach that action to the form element or to any of its
ancestors. So where should we add it over in the template, find the divots around the
row. So this current item div right here

And add it there,

Pop things on multiple lines, and then we'll add our data actions. Index data actions

Equals

The name of our events. All paste submit dash confirm colon async colon submitted an
arrow, the name of our controller. So this is going to be carp dash list pound sign.
And then the name of the method you call when that event happens. How about remove?
Okay.

Wow.

I'm putting it on this div. Well, it won't matter at first, but in a few minutes,
it'll give us the ability to get this div and add some extra logic to make it fade
out over in the controller, renamed connected to We move item, given any event
argument, and let's now console that log our very favorite event, that current
target.

Awesome.

Let's see if our controllers are communicating, I'll refresh. One of our rows has
gone from earlier.

It remove yes. Remove that problem

And over in the console. Yes, we can see it. It's logging and it's logging the entire
row around our element. Okay. What we really want to do in this method is make an
Ajax call to some end point that will return the new HTML for the entire cart area.

Okay.

You can create that end point with some clever organization. Start in the template,

Copy

All of the HTML that's inside of our cart area. So everything that's inside of our
cart list controller. So that's this div here all the way down to this end, if yep.
That looks right and then create a new template in templates cart called how
about_cart list dot HTML that twig

And paste there,

And the original template. We can include that with include Cart /honor score part
list at that age to month week, that won't change anything yet. Our page still works
just like it did before, but now we can build an end point that just returns that
template partial.

Okay.

Source controller card controller dot PHP. This is the controller that renders the
shopping cart right below it. Let's add another one. I'll call it public
function,_shopping cart list. I'm keeping with that convention of prefixing a
template partials or even, uh, endpoints that only return a fragment of HTML with
an_and above this. I will give it a route at route and let's have the URL be hibachi
/cart to kind of match what's above and then co slash_list. And I'll give us a name
equals. And how about_app_cart_list.

Beautiful. Now it actually render this actually rented this template. The one
variable that it's going to need is the cart object, which we get via this cart
storage service that is custom to our project. So I'll copy that argument, paste it
down here, and then we'll return this->render card slash_cart list that age not twig
passing a cart variable set to cart storage arrow, get or create the cart. All right,
that's it. Let's go try it in the browser by going directly to this URL. So head
over, go to /cart slash_list and got it.

Now go back time to make the Ajax call in our stimulus controller cart list
controller, add a new value called cart refresh URL, which will be a string. We're
doing this so that I don't have to hard code the URL to this end point. Then let's go
pass that in copy cart, refresh URL, go over to cart .html.twig at a second argument,
distinct as controller and set cart, refresh URL set to path. And then the name of
our route, which is_app cart list. Finally making Ajax call is pretty easy down in
remove item. I'll say constant response = await, fetch this.cart, refresh URL, and
value. And of course, as soon as we have a weight on here, we need to make the method
async and then all we need to do now is replace the entire HTML of this element with
that response text. So this.element that inner HTML = await response to that

Text. We're done

Testing time. First, let me go over and add a couple more things to our cart. So
things are a little bit more interesting. Beautiful. Now let's delete the red one
move confirm and Oh, that was awesome. We get the entire, no full page refresh
experience with zero duplication and minimal JavaScript. I mean, check out how big
this controller is. It's tiny, that's amazing and super important bonus. New HTML was
just added to our page. In fact, this entire, All of the HTML inside of this element
is brand new to the page. Normally with JavaScript, that's a problem. Any event
listeners that you want on these new elements must be reattached, but with stimulus,
as we talked about earlier, it's no problem. As soon as stimulus saw these two

New

Data dash controller = submit, confirm elements added to the page. It instantiated
two fresh new submit confirmed controllers and all the behavior works perfectly. What
I mean is if I click remove it still works. We don't need to think about anything.
I'm so excited about this. Let's add one last tiny thing to make it really shine. I
want to make the row fade out before it disappears. We can do this with CSS
transitions, open up assets styles, app that CSS and scroll down a bit. So I'm
looking for a cart item. Here we go. Cart item. This is the that's the class that's
around each cart row to have the transition say transition

Opacity 500 milliseconds that doesn't actually make it transition. That just says if
the opacity changes, I want you to change the opacity gradually over 500
milliseconds. Then below this, add that car, that car, that car, that car dash item,
and then that removing and set opacity to zero. So this says, if the cart item
element also has a removing class, it changed the opacity to zero. So what we'll do
is as soon as the item needs be removed, we'll add that class in the CSS. Transitions
should make it fade out. So back in the car controller, right at the beginning here,
we'll say event dot current park. That that will get us the element around the entire
row that we added this too. So as a reminder, that will give us this element here,
which has this cart item class on it.

Yeah.

Then that class list got, add,

Removing,

Try it refresh. Let's delete the blue one. And, And yes, you see it. It was quick,
but it faded out before the Roman goddess right on the last one here. Awesome. If
your super server is super fast, the fading out might, this might not finish fading
out before the HTML reloads. If you care enough, you could even delay the AGS, call a
few milliseconds with set Tam timeout later. We'll talk about adding CSS transitions
in a different, more robust way, but this was easy and in practice works. Awesome.
Next we've talked a lot about stimulus,

But

Isn't this also a tutorial about Symfony UX. How does that fit in? Let's find out by
adding a JavaScript powered chart to our page by only writing PHP code.

