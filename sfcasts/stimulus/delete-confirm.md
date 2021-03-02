# Delete Confirm

Coming soon...

What's that

A couple of items to our cart, like the floppy disk, maybe also some awesome CDs and
then head to the cart page, a user can already remove an item from their cart. Let's
go check out the tempo for this page. That's templates, cart cart that HTML that
twig. If you scroll down a bit, here it is around line 50. Here is the remove button
it's actually inside of a form. When the user clicks it, it submits that form and
removes the item from the cart, super smooth and boring functionality. I would like
to enhance this when the user clicks the button, I want to pop up a modal. So the
user can confirm that they want to remove the item, and we're going to make this even
cooler.

We're going to make the new stimulus controller for this functionality, able to be
used to confirm, submit on any form across your entire application. So let's get to
work. Start by creating the stimulus controller. So an assets controller let's create
a new file called how bout submit dash confirm_controller dot JS. Again, I'm calling
this submit confirm because this'll be a controller that can be used reuse to confirm
the submit on any form. We'll start the normal way. Import controller from stimulus
and then export default class extends controller with our connect method to make sure
everything is hooked up constant out log. And of course, inside of here,

Well at a dinosaur

Next let's activate this in the template, adding it to the form tag should be fine.
So add curly curly stimulus controller submit dash confirmed. Let's make sure this
connected. I need to make sure my console is open refresh and it did roar. I noticed
there are two roars because there are two different controllers on this page to
create the actual model search for sweet alert two. I love this library. It's an easy
to use highly customizable alert system. If you scroll down a bit, let's see here. I
hear it as one of the examples is for a model that confirms deleting something. This
is basically exactly what we want. So let's go get this library installed, spin over
at your terminal, run yarn, add sweet alert to dash dash dev.

And before we actually use that, let's set up the action on our form because when we
submit this form, we are going to want to do something in the template on the form.
Add data dash action = then the name of our controller, submit dash confirm pound
sign, and let's have a call, a new ethical on submit a copy of that. Then head over
to our controller. We can rename connect to on summit, give it any event argument.
And first let's call it event that prevented the fault so that the form doesn't
submit immediately. And then let's console that log event just so we can see this
working okay. Back over refresh hit, remove and awesome. We can see the submit event
is being triggered so far. So good. Now let's bring in sweet alert back over in the
docs. Copy this entire delete example and in the controller, remove the log and paste
and for this SWA out, make sure to import sweet alert on top. So that is import SWA L
from sweet alert to cool. Let's try it. I'll go back to our site. Refresh here,
remove in Tata. So awesome. If we click cancel, nothing happens. And if we click yes,
delete it. Then we get this other message, but it's not actually deleting it yet to
do that.

Look back at the code. What happens here is if you then the dot then call back is
called, which is why, what we actually saw was it just, um, firing another sweet
alert. All we need to do is replace that sweet alert.fire with this.element, which we
know will be the form dot submit. That's it, by the way, if you're thinking that this
might cause an infinite loop where we call submit, and then that triggers our, uh,
submit action, which then calls this method again, it won't when you call submit like
this directly on an element, it does not trigger a submit event on that element. So
it won't call itself again. All right. Let's see if this works, refresh the page
click remove and this time confirm. Yeah, it worked. The form submitted the page,
reloaded on the item is gone, but I want to make this a more awesome how by making
this con controller configurable so we can reuse it across our app, after all saying
yes, delete it. Isn't exactly the right button text. When you're removing an item
from the cart, we'll also add an option to make the form submit via Ajax. That's all
next

[inaudible].

