# Actions & currentTarget

When we click a square, we need to add a border around the square to show that
it's currently selected. I've already created a CSS class for this... I'll hack
it into the page so you can see it. It's called `selected`. It even comes with a
nice little CSS transition! Ooooo.

Over in the controller, in the `selectColor()` method, how can we figure out *which*
of the three color squares was just clicked? The answer is always:
`event.currentTarget`.

Try this: `event.currentTarget.classList.add('selected')`.

[[[ code('12365e06c3') ]]]

Before we chat about this, let's make sure it works. Refresh, click and... beautiful!
We can currently select *multiple* colors... which isn't ideal, but we'll fix that
soon.

## currentTarget versus target

There are two important things about this line. First, when you listen to an event -
or "action" in stimulus - the event object always has two similar properties:
`event.target` and `event.currentTarget`. Sometimes these are the same element...
and sometimes they're *not*.

Let me show you an example with some dummy code in the template. Imagine you have
a button with an action on it - I'll reuse our existing `data-action`. Inside the
button we have some text... but some of that text is inside *another* element. A
more realistic example might be that you have an image or FontAwesome icon inside.

In the controller, I'll temporarily comment-out our code and instead
`console.log()` `event.target` and also `event.currentTarget` so we can see
the difference.

Go refresh the page. There's our *stunning* button! Open up the console.

First, click the text that's *directly* in the button. Nice! *both* `event.target`
and `event.currentTarget` are the same thing: the `button` element.

Now click the the *span* that's inside the button. Woh! This time they're different!
The `target` is the `span` while `currentTarget` is *still* the button!

This is not a Stimulus thing: this is just how DOM events work. `event.target`
will always be the actual element that *received* the action. The second time
we clicked, we were *actually* clicking the `span`.

But `event.currentTarget` will always be the element that we added the listener
or action to.

So that's a long way of saying that `event.currentTarget` is your friend, because
it will return the element that we've attached our action to. So we *always*
know what it's going to be. `event.target` *could* be that element... or it could
be a child element.

Let me remove that weird extra button... and then put the code back in our controller.

## Element.classList

The other interesting thing on the line in our controller is `classList`. This is
a property on the native Element object and... as you can see, it's just an easy
way to add or remove class. No jQuery or other fancy tools needed.

## Only Allowing One Selector

So... our color selector works great so far. Except for the problem that we
can select *multiple* colors. We need to make sure that only *one* color has the
`selected` class at a time.

Let's think about how to solve this. One option would be to look for an element
with the `selected` class inside `this.element`. And if we find one, remove the
class.

Another option is to use a *target*. We could make each color square a target,
then, on click, loop over *all* of them and remove the `selected` class before
re-adding it to the one that was just clicked.

Let's do that. First, define the target with `targets = []`. Let's call the
new target, how about, `colorSquare`. I *did* just make a mistake: see if you can
spot it.

[[[ code('bf7e1111ef') ]]]

Oh, and notice the naming of the target: it's lower camel case. I'm not using
`color-square` because the name of the target becomes a property.

Down in the method, let's `console.log(this.colorSquareTargets)`.

[[[ code('30567415b4') ]]]

I put an "s" on the end on purpose: this will return an array of *all* matching
targets.

Finally, in the template, let's add the target to the `button`. Remember: the
syntax for that is `data-` the name of the controller - so `color-square` - the
word `target` equals, then the name of the target: `colorSquare`.

[[[ code('b350bda158') ]]]

Yes, you *do* need to write a few targets before you remember this syntax by heart.
But you'll get it.

Let's try this. Refresh, click and... oh! Undefined?

Hopefully... you saw my mistake. Back in the controller, make this *static* targets.
I made that mistake because... in the real world... I've made that mistake more
than a few times before. This must be static and... if you forget, there's no
huge error: it just *won't* add the magic target properties.

[[[ code('b481a31410') ]]]

Try it now. Refresh, click and... yes! We see the 3 `button` elements.

Let's loop over these inside of our method: `this.colorSquareTargets.forEach()`
and the function will receive an `element`. Inside, remove the `selected` class
from *all* of them for simplicity: `element.classList.remove('selected')`.

[[[ code('6c63d48912') ]]]

Let's try this one last time. Now when we click... yes! It works!

Next: let's put the finishing touches on our color selector widget by finding
and updating the `select` element's value whenever the user clicks a square.
Then we'll finally *hide* the select element and let our color squares take
center stage.
