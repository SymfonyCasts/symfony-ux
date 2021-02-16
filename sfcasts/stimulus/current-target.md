# Actions & currentTarget

When we click a square, we need to add a border around the square to show that
it's currently selected. I've already created a CSS class for this... I'll hack
it into the page so you can see it. It's called `selected`. It even comes with a
nice little CSS transition. Ooooo.

Over the controller, in the `selectColor()` method, how can we figure out *which*
of the color squares was just clicked? The answer is always: `event.currentTarget`.

Try this: `event.currentTarget.classList.add('selected')`.

Before we chat about this, let's make sure it works. Refresh, click and... beautiful!
We can currently select *multiple* colors... which isn't ideal, bu we'll fix that
soon.

## currentTarget versus target

There are two important things about this line. First, when you listen to an event -
or "action" in stimulus - the event object always has two similar properties:
`event.target` and `event.currentTarget`. Sometimes these are the same element
and sometimes they're *not*.

Let me show you an example with some dummy code in the template. Imagine you have
a button with an action on it - I'll reuse our existing `data-action`. Inside the
button we have some text... but some of that text is inside another element. Or,
a more realistic example might be that you have an image or FontAwesome icon inside.

In the controller, I'll temporarily comment-out our code and add instead
`console.log()` first `event.target` and then `event.currentTarget`.

Go refresh the page. There's our *stunning* button. Open up the console.

First, click the text that's *directly* in the button. Nice! *both* `event.target`
and `event.currentTarget` are the same thing: the `button` element.

Now click the the *span* that's inside the button. Woh! This time they're different!
The `target` is the `span` but `currentTarget` is *still* the button!

This is not a Stimulus thing: this is just how DOM events work. `event.target`
will always be the actual element that *received* the action, like the click.
`event.currentTarget` will be the element that we added the listener or action to.

So that's a long way of saying that `event.currentTarget` is your friend, because
it will always return the element that we've attached our action to. So we *always*
know what it's going to be. `event.target` *could* be that element... or it could
be a child element.

Let me remove that weird extra button and then put the code back in our controller.

## Element.classList

The other interesting thing on the line in our controller is `classList`. This is
a method on the native Element object and... as you can see, it's just an easy
way to add or remove class. No jQuery or other fancy tools needed.

## Only Allowing One Selector

So... our color selector works great so far. Oh, except for the problem that we
can select *multiple* colors. We need to make sure that only *one* color has the
selected class at a time.

Let's think about how to solve this. One option would be to look for an element
with the `selected` class inside `this.element`. And if we find one, remove that
class.

Another option is to use a *target*. We could make each color square a target,
then, on click, loop over *all* of them and remove the `selected` class before
re-adding it to the one that was just clicked.

Let's do that. First, define the target with `targets = []` and let's call the
target `colorSquare`. It *did* just make a mistake - see if you can spot it.

Oh, and notice the naming of the target: it's lower camel case. I'm not using
`color-square` because the name of the target becomes a property.

Down in the method, let's `console.log(this.colorSquareTargets)`.

I put an "s" on the end on purpose: this will return an array of *all* matching
targets.

Finally, in the template, we'll add the target to the button. Remember: the
syntax for that is `data-` the name of the controller - so `color-square` - the
word `target` equals, then the name of the target: `colorSquare`.

Yes, you *do* need to write a few targets before you remember that by heart.

Let's try it. Refresh, click and... oh! Undefined.

Hopefully you saw my mistake. Back in the controller, make this *static* targets.
I made that mistake because... in the real world... I've made that mistake more
than a few times before. This must be static and... if you forget, there's no
huge error, it's just *not* going to add your magic target properties for you.

Try it now. Refresh, click and... yes! We see the 3 `button` elements.

So let's loop over these inside of our method: `this.colorSquareTargets.forEach()`,
the function will receive an `element`. Inside, remove the `selected` class...
even though at most only one will have it: `element.classList.remove('selected')`.

Let's try this one last time. Now when we click... yes! It works!

Next: let's put the finishing touches on our color selector widget by finding
and updating the `select` element's value whenever the user chooses a square.
Then we'll finally *hide* the select element and let our color squares take
center stage.
