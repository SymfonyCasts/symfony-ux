# Toast Notifications

Coming soon...

You've made it to the last topic of the tutorial. One where we get to have a little
fun by adding toast notifications, toast notifications, refer to little notification
messages that pop up somewhere on your screen and bootstrap has support for them. Our
goal is simple. I want to be able to return some HTML, either from a controller or
inside of a turbo stream that causes a toasted suffocation to pop up, start breaking
a new template, partial called_toast, .html.twig I'll paste in a structure that's
from bootstraps documentation. What's make a few parts of this dynamic like {{ title.
That's a variable we'll pass in about a {{ when variable that we default to just now
and then down here for the body, a {{ body variable next open up product
slash_reviews .html.twig after submitting a new review, we render a flash message.
Now I want this to be a toast and notification. So very simply we only just include
that template include_score that age twig. And then we'll pass in a couple of
variables like for title we'll to say success and for body we'll set that to whatever
the actual flash message is that we want to render.

If we stop now, nothing would happen. These toast elements here are invisible until
you write some JavaScript that opens them onto the page to do that. We need a
stimulus controller. So up in the assets /controllers directory, let's create a new
file called toast,_controller.JS inside of here. We'll start our normal way by
importing controller, exporting our controller and adding a connect method, which for
now just concept a logs, a loaf of bread. Now over an_toast at age two months. Wait,
I want to activate this controller whenever this toast element appears.

So to do that, it's pretty easy. We'll just go on to this outer element and I'll say
{{ stimulus controller toast. Okay. Are going to do it. Doesn't do anything yet, but
let's at least make sure that it's connected. I'm going to head over to our site,
refresh the page, make sure that my console is open and then go fill out a new
review. Why did I submit? Yes. As soon as the toast aged him was rendered, our
controller was initialized. Um, but as I mentioned, you can actually see the toast
element and kind of see where it's taking up space, but it's not visible yet. So
let's fix that by opening up the toast, the tablet controller import toast from
bootstrap.

And then the way this works is we say con's toast = new toast and facet this.element,
which is our toast element. And then you say toast that show, that's it refresh again
and fill a, another view this time. Sweet. That's super cool. And it means that we
can have any time rendered this_toast that aged in twig template. And it will
activate this behavior. Nope, the positioning was off it disappeared. Uh, those
notifications automatically disappear after a few seconds, but it kind of loaded
right in the middle of the page. But I was thinking that it'd be better fit loaded at
the top right of the corner of the screen to do that. We just need to add a few
classes to the toast element. Except if you think about it, it's possible that a user
could see multiple toast notifications at the same time.

The tow system totally supports this. It just stacks them on top of each other, up in
whatever corner you have them. But in order for that to work, we need a single
global, global toast element on the page that all other, all individual toast
elements live inside of. If that doesn't make sense yet, don't worry. Go open up the
templates /base that HTML, that twig really anywhere in here, but I'll put it up,
bought them at a, at a div with ID equals. How about toast? Container? That could be
anything. We're just going to use that in JavaScript in a second. And then class =
toast container position fixed top zero and zero P dash three. So this toast
container class is, is going to tell bootstrap how to stack the toast notifications
inside of it. And then the rest of this just puts it at the upper right part of the
screen.

Now, in order to get this to work, we need all the toast notification to actually go
inside of this element. So basically we would need to render this, include this_toast
.html.twig, and somehow get that inside of here. But I want to, I want to keep the
flexibility of being able to render_toast, that agent from wherever I want, like
randomly from inside of this template template parcel, I don't want to have to make
sure that I'm rendering it into the toast container element to allow this let's have
our stimulus controller find and move any toast elements into this, uh, element
before popping it up.

Check this out in the controller. The top of connect at a constant toast container =
and say, document that get element by ID and pass it toast dash container. So that
we'll find that element inside of the footer. And then basically we're going to do is
as soon as the toast element is, uh, connected, we're going to instantly move it from
wherever it is now into this toast container. So I'll say toast container that a pen
child, this, that element, and now it will live inside of that element and we'll pop
up like normal. Now there's only one kind of catch to this. When we initially load
the toast element, we know it's going to load kind of like right here in the middle
of the page, that's then going to, as soon as that happens, it's going to call the
connect method inside of our controller.

And we're going to move it into the toast container element that's near the bottom of
the page when that happens. That's actually going to cause yeah, when that happens,
the original controller instance is going to be removed and a new controller instance
is going to be created. In other words, this connect method is going to be called
twice. Once when we originally render our toast element onto the page. And again,
after it's been moved into toast container and we need to make, and to avoid a
infinite loop here of constantly moving it into the toast container and having it
happen again, we need to make sure that we only do this moving twice. As we can say,
if this, that element that parent node does not equal toast container, that means it
has not been moved yet. And we need to move it. If it has been moved, we'll move it
and we'll just return.

So the first time is executes. It will move it into toast, container and exit. The
second time it's executes, it will go down here and pop open the toast element that
should do it. Let's refresh the page. It was to review and beautiful. If I quickly
kind of inspect that toast, you'll see that it's down inside of toast container. That
was one last thing that I want to do. Whenever a new review is posted to a product. I
want to open a toast notification on every user's screen that is currently viewed as
viewing that product. Something that says, Hey, this product has a new review over in
reviewed at stream that age tumor, that twig inside of the create block. Let's add a,
another turbo stream here with action = UPenn and target = well, actually leave that
target empty breath. Second, I'll talk about that in a minute. Then let's add the
template element. And of course we will just include our toast pass in a couple of
variables here, like title, how about new review? And then body say a new review was
just posted for this product.

Very nice, but what should this target be? We could use toast dash container, right?
That would append it to this element, but then the message would show up on every
page. But we only want this message to show up if you're viewing this specific,
right? So one way to handle this. We basically need an element that we need to put
this into an element that's specific to this product. For example, in show
.html.twig, right inside of the product body, I'm just going to add an empty div with
ID = product dash {{ product, not ID dash toasts empty. Dave, that's just waiting for
toast elements to go into it. That's specific to that product.

No, copy this and reviewed that stream to HTML twig. We'll use that except instead of
product it's of course going to be an entity, that product, that ID, all right,
testing time. Is that over? I'm going to refresh this page. And then I also wanted to
open that same product in another tab to kind of mimic a different user. All right.
So down here, fill, review and submit. Awesome. We have two toasts over here and as
the other user sees the toast as well. That's beautiful. The twos, the two toast
notifications, the first set is a bit weird, but I'll leave that for now. And we're
done. Congratulations for making it through this huge tutorial. It was huge because
well, turbo has a lot to offer. I hope you're as excited about the possibilities of
stimulus and turbo as I am, let us know what you're building. And as always, if you
have any questions, we're here for you in the comment section. All right, friends.
See you next time.
