# Actions: Listening to Events

I want to make our controller more realistic: instead of being able to click
anywhere on the element to increment the count, let's add a button.

Easy enough! In the template, add `<button class="btn btn-primary btn-sm">` and
then the excellent call to action: "Click me".

If we check it in the browser... amazing! It works! Ok, I'm kidding: of *course*
it worked. But that's the cool thing about Stimulus: we get to do *so* much of our
work in Twig where life *is* quick and easy.

Now: how could we attach a click listener to *just* this element? You might be
thinking:

> I know! Let's add a target to this button like we did for the span. Then we can
> find that button inside of `connect()` and add the event listener to *it* instead
> of to the entire element.

And yeah! That will *totally* do it! But that's *way* too much work.

## Adding a Click Action

After targets, the second big feature of Stimulus is actions. Anytime you need
to listen to an event, like `click`, `submit`, `keyup` or anything else, you can use
an action instead.

Here's how it works: on the `button` element, I'll break this onto multiple lines
for clarity. Now add `data-action="click->counter#increment"`.

This is another special syntax: `data-action=""`, then the name of the event, like
`click`, `submit` or `keyup`, and arrow, the name of the controller, a pound sign,
and then the name of the *method* to call on our controller when this event happens.
We'll create this `increment` method in a minute.

Now, when I first used Stimulus, I did not *love* this syntax. It's... a bit weird.
But it really *does* make life a *lot* nicer. And we'll simplify it a bit in a few
minutes.

Over in the controller, add the method `increment()`.

Copy the logic from the click callback, delete it, and paste it here.

And now we can delete the event listener entirely.

I may not *love* the `data-action` syntax in the template, but I *do* love the
result. This is gorgeous.

Let's try it. Refresh the page. Now, if I click somewhere else in the element,
nothing happens. If I click on the button, it increments! Woo!

## The Default Action Name

But I *did* promise one simplification in the template. Remove `click` and the
`->` after it.

Try it again: it's still works just fine! How? Stimulus has a *default* event name
for the most common elements. If you add a `data-action` to an `a` tag or a
`button`, the default event name is `click`. If you add one to a `<form>` tag, it
defaults to `submit`. If you add one to an `<input>` or `<textarea>`, it defaults
to `input`, which is the event that happens when the value of the field changes.

So most of the time, you don't need to specify the event name.

Oh, and now, to celebrate in the controller, we can remove the `connect()` method
entirely. Move this `this.count =` to a normal property: `count = 0`. Then delete
`connect()`.

Let's make sure I didn't break anything. Nope! All is good!

So that is *most* of Stimulus... seriously! But I already love it. I mean, look
how clean this controller is! And I get to render nice, clean HTML inside a
template.

There *are* a *bunch* of things that I still want to talk about, like the values
API, but Stimulus really is a lean and mean library.

Next: as nice as our counter example was, let's do something real. Over in the
browser, click the "Furniture" category and then click the "Inflatable Sofa".
Some products come in multiple colors and you choose the color with a color select
element. Boooooooring. Let's enhance this by turning it into a color square selector
widget.
