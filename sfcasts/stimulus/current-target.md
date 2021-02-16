# Current Target

Coming soon...

When we click a square, we need to add a border around the square to show which one
is currently selected. I've already created a CSS class for this. I'll hack it in
here so you can and see it's called select it. And it even comes with a nice little
CSS transition on there. Ooh. Okay. So over the controller, in the select color
method, can we figure out which of the color squares, which just quick, quick, the
answer is always is event dot current target. So try this event, not current target
dot classless dot add select it. Let's make sure it works before we chat about it.
It's been over refreshed and beautiful and currently select multiple colors. So
that's not ideal, but we'll fix that soon. Okay. There are two important things about
this line. First, when you listen to an event or action in stimulus, the event object
always has two similar properties event.target end event dot current target.
Sometimes these are the same elements. Sometimes they're not take this example.

I'll just some dummy code inside of our temple over here. Imagine you have a button
like this with an action on it. We'll pretend that this is our data dash goals,
color, square, and inside. There is some texts and some of the texts for whatever
reason we put in a split. No, I'll actually temporarily coming up a selective class
on a console.log( event, got target and event dot current target. So you can see what
this looks like. Now, if we go over and refresh, there's our button, I'm going to go
to the console. If we, now I'm going to inspect the element, just remember, see what
it looks like Erin is right there. She wouldn't do that. Yeah, let's just do this.

Nope. If the user clicks the text part, which is choose or, and this, you can see
that both elements and this.L current current targeted current to target are the
button element. But if the user clicks this word color here, which is actually the
span, then suddenly the target is the span and the current target. But the current
target is still the button. This is not a stimulus thing. This is just how Dom events
work event that target will always be, will always receive the actual element that
received the action. Like the click event dot current target will be the element that
we actually add our listener or action to. So that's a long way of saying that event,
doctor current target is your friend, because this will return the element that we've
actually attached the listener to. So we always know what it's going to be.

Let me remove that weird extra button and then put our code back in the controller.
The other interesting thing here is class list. This is a method on the native
element objects. So we get the element object, call class list on it. And as you can
see, it's just an easy way to add and remove classes, no jQuery or any other fancy
tools needed. So this works great except for the problem that we can select multiple
colors. We need to make sure that only one color has the selected class at, at, at
once. So let's think when you click a square, we basically need to find all the other
squares and remove the selected class. If they have one, one way to do this would be
to look for the selected class. Like we could say this.element dot, get elements by
class name selected. Basically find that element. If there is one, then remove the
class from it. But another way more stimulus way is to use a target so that we can
easily find all of these, uh, color square elements. So check this out in the
controller, let's define a target. I will say targets = and I am making a mistake,
right? Target = an array with color squared. And I am making a mistake here, but
we'll fix soon.

I noticed the naming of my target. It's going to be in lower camel case. So I'm not
using a color dash square or something here because remember the name of the target
becomes a property. So we want it to be colored square. And then down here, let's
console that log and use this.color square hard gets. Remember I put an S on the end,
we can find the array of all of the matching targets. Finally, up in our template.
We're going to add a day, uh, a target to our button. So remember the syntax for that
is data dash the name of the controller. So color it dash square, the word target
equals, and then the name of our target. So color square, and that name you can
mention does take a little bit of getting used to all right. Let's try it. Refresh,
click and Oh, undefined. Okay. You probably, you may have seen my mistake back on the
controller, make this static targets. I made that mistake because I have made that
mistake several times before. It's a good beginner's mistake. If you don't have
these, it must be static. And if you don't have a static, there's no huge air. It's
just not going to work. Now, when we move over and refresh, yes, you can see we get
the three button elements.

So let's loop over these inside of our function. We can say this.color square targets
dot for each, and this will get an element object. And then is that a fair? It's very
simple. We have an element object. So we can say element. We can use that class list
thing again, and this time it will be removed selected. So if it has that class, it
will get removed. All right. Let's test this refresh. Okay. And, uh, it was works
beautifully even with our little CSS transitions. Next let's put the finishing
touches on our widget by finding an updating the select element. Whenever we click a
square, then we'll finally hide the select elements that this is a beautiful, uh,
fully functioning element.

