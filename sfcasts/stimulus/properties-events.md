# Magic with Events, Properties & "AJAX"

Okay.

What's that one more thing. Let's count the number of times, how many times each
element is clicked and then print that inside the item, head over to our controller.
I'm going to start by inventing a new property called count. I'll say this not count
= zero. That's not a stimulus thing. That's just me making up a property and setting
it to zero.

Then

Below this, I'm going to attach a click listener to our element. So this.element,
that add event listener

Quick,

And then I'm going to pass this an->function, the hipster->function.

Oh,

That's mad. Cause I forgot my comma. There we go. And inside here, we'll say
this.count plus, plus to increment it. And then I'm going to set this, that element
that inner HTML = this.count. I'm not using jQuery because in a lot of cases it's
really not needed, but if you're more comfortable with using jQuery, you can totally
still use it. You would just always use it. Dollar sign open brand sees this.element
before any methods you call on it like dollar sign, open parentheses of this.element
dot on click anyways, move over refresh. Okay. They look the same now, but now I can
click and boom. When I click up, it keeps track of it. And you can see they're
independent of each other. That proves they're two separate objects that are tracking
the value of that count property.

[inaudible]

This isn't even the best part of stimulus Down in my inspector for my browser. I'm
going to right click on this div here and go edit as HTML. And I'm going to copy that
dev data = controller, and I'm going to hack in a new one right above it. What I'm
doing is mimicking. What happens when HTML is loaded to the page after it's, when
HTML is added to the page after it's done loading like via Ajax, this is a classic
problem with JavaScript. If you attach event listeners to some, to all classes, to
all elements with some class on page load,

If you add, if you load new HTML later via Ajax, the event listeners, aren't
automatically attached to it, unless you go to the hassle of manually recalling your
function to reattach, uh, to, to, to add the event listeners to that element. So can
stimulus handle this? Yup. When I click off to add the new element to the page, it
worked behind the scenes. This is totally true. Stimulus noticed that a new element
was added to the page and instantiated a brand new control object. You can see it
right here. That's incredible. That is a game changer for me. And this controller
works exactly like the other ones increments as I click on it. So like I said, if
this were the end of stimuluses features, I've use it, but it's not. Let's learn
about targets next and easy way to find the elements inside of the main, uh, inside
your main controller element.
