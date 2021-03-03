# Multi Controller Dispatch

Coming soon...

When we confirm the delete form and now submits via Ajax. That's cool, but it graded
a problem. The row just sits there. And actually it's more complicated than just
removing that row. The total at the bottom has to change. And if this is the last
item in the cart, we need to show the, your cart is empty message. If I refresh
manually, now we see it. You might get the urge to start trying to do all of this in
JavaScript. Removing the row would be pretty easy. May add back a couple of items
here so we can get a more interesting cart.

Okay.

You might be tempted to make it the urge to start trying to do all of this in
JavaScript. Removing the row would be pretty easy though. We, we would need to move
the data controller from the form two around the entire row, but it would be doable,
but updating the total and worse printing, the, your cart is empty message without
duplicating the message we already have in twig. This is starting to look at kind of
annoying. The solution for this is delightfully refreshing, stop trying to do
everything in JavaScript and instead rely on your server side templates. So instead
of removing the row and changing the total and rendering the, your cart is empty
message. All in JavaScript, we could make a fresh Ajax call to an end point that
returns the contest, the HTML of this entire area, and replace that on the page. That
is what we're going to do. But wait a second. If we look over on our template right
now, our stimulus controller is on the individual form element. So each row has its
own stimulus controller. Does this mean that we're going to need to move that data
controller element to a Dave that's around the entire cart area, like moving onto
this div, because if we want to replace the HTML for this entire div, it needs to
live inside of our controller. The answer is no, we don't need to move the data
controller element. The element that data controller is on. Let me clarify. We could
move the data controller from our individual form up to, uh, the Dave that's around
the entire car area.

If we did that, we would need to do some refactoring in our controller specifically,
instead of referencing this.element to get the form, we would need to reference event
dot current targets. So that's kind of annoying, but not a huge deal. And it would
give us the ability to replace the entire HTML of the car area. After making that
Ajax call. The real reason I don't want to move the controller up to this top level
element is because, well, it doesn't really make sense for our submit confirm
controller to show a confirmation on submit and also make an Ajax call to refresh the
data in the cart area. Those are two very different jobs. And if we did smash that
code for making the Ajax call into this controller, we would not be able to reuse the
submit confirm controller for other forms in our site because they would hold code
specific to the cart area. So what's the better solution. So what's the better
solution to keep submit, confirm how it is. It does its small job perfectly. And
instead add the new functionality to a new second controller, check it out and assets
controllers create a new cart dash list,_controller that JS

I'll actually

Cheat and copy the top of my submit, confirm and paste it in there, but we don't need
sweet alert. Then I'll do my usual

Connect and we'll console that log, of course, a shopping cart.

The job of the controller will be to add any JavaScript to the cart list area. So
basically this div right here, the entire cart list area on a high level, that means
it's job is going to be to reload the cart HTML via Ajax. After one of the items is
removed in the template cart dot H Timo twig.

Fine.

That div up here. There it is. And let's add our controller to this Curly curly
stimulus controller and it's called cart dash list. All right, let's make sure that's
connected. I'll head over my console refresh and

Perfect. We're good. All right.

In stimulus, each controller acts independently. They're their own little independent
units of code. And while it is possible to make two controllers talk directly to each
other, you don't typically do

That. But in this

Case, we have a problem inside our new controller. We need to run some code,
specifically, make an Ajax call to get the fresh card HTML only after the other
controller has finished submitting the form via Ajax. Somehow submit, confirm
controller needs to notify carte list controller that this has happened, that that
AGS call has finished. How do we do that? Oh, it's so nice

By,

By doing exactly what, uh, Dom elements already do. Dispatching a custom email

On our,

From our form, from our form element and the stimulus use library we installed
earlier. I haven't stocks right here has a behavior for that. It's called use
dispatch.

Okay.

You can dispatch events without this behavior. This just makes it a lot easier.
Here's how it works. Start the normal way. So in summit, confirm patroller, we need
to import the behavior. So import

Use

Dispatch from stimulus use and then make a connect method and say, use dispatch.

This

Also add a debug option set to true. I'm adding the debug option temporarily all the
stimulus use behaviors support this option When it's enabled most behaviors, log
extra debug info to the console, which is pretty handy. We'll see that in a minute.
Now, head down to submit form. Here's the plan. If the form is submitting via Ajax
let's Oh, wait for it to finish. And then dispatch a custom event do that by setting
constant response = await,

And

Then we need to make the method async to dispatch the event. The use dispatch
behavior gives us a handy new dispatch method. So we can say this.dispatch and then
the name of our method. And this can be anything let's call it. How about async
submitted. And if you want, you can pass a second argument with any extra information
that you want to attach to the event. I'll add a response. We won't need that. But
thanks to this, the event object will have an extra response property set to the
response object, which might be handy in other cases. And that's it. It's a little
technical, but thanks to the async on submit form. Our submit form method still
returns a promise that resolves after this HF call finishes that's important because
we returned that promise in preconfirm, which is what tells sweet alert to stay open
with the loading icon until that desk call finishes.

If that didn't make total sense, it's not that big of a deal. It's a little specific
to promises and sweet alert. Anyways, let's try it. Spin over. Refresh, remove an
item and confirm. Yes. Check out this log down here. We just dispatched a Dom event
from our form called submit dash confirm colon async colon submitted by default that
use dispatch behavior will prefix any event with the name of our controller, which is
kinda nice. So next, let's listen to this in our other controller and use it to
reload the cart HTML as a bonus, we'll add a CSS transition to make things look
really smooth.

