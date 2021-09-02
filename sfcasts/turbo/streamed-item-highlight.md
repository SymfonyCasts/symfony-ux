# Visually Highlighting new Items that Pop onto the Page

Our review system is super cool: if *any* user submits a review, that review will
pop onto the page of anyone *else* that's currently viewing this product.

To make this a bit more obvious, I want to highlight the new review as soon as it
appears. And this is pretty easy. Start over in `assets/styles/app.css`.
Add a `.streamed-new-item` style with a `background-color` set to `lightgreen`.

[[[ code('caa0609ec4') ]]]

## Adding a Green Background to New Items

Let's *add* this class to a new review *if* it's added via a stream. We
can do this in `reviews.stream.html.twig`: pass a new variable into the template
called `isNew` set to `true`.

[[[ code('0a6b523fd8') ]]]

Now, over in *that* template - `_review.html.twig` - at the end of the class list,
use the ternary syntax: if `isNew` - and `default` this to `false` if the variable
is *not* passed in - then print `streamed-new-item`.

[[[ code('844fd7195b') ]]]

That's it. The "else" is automatic: if `isNew` is false, this will print nothing.

Let's check it out! Refresh both of the pages to get the new CSS... and then submit
a new review. Yay! The green background shows up here... *and* on the page of
*everyone* on the planet that happens to be viewing this page.

So... this is cool. But... we need more fancy! What if we show this background for
only five seconds and then fade it out. Start again in `app.css` to set up the
fading out part: we need a new class that describes this transition. Add a
`fade-background` class that declares that we want any `background-color` changes
to happen gradually over 2000 milliseconds.

[[[ code('46c3530807') ]]]

## A Stimulus Controller to Fade Out

Before we try to use this somewhere directly, let's stop and think. If the goal is
to remove this background after 5 seconds, then the only way to accomplish that is
by writing some custom JavaScript. In other words, we need a Stimulus controller!
In the `assets/controllers/` directory, create a new file called, how
about, `streamed-item_controller.js`. I'll paste in the normal structure, which
imports turbo, exports the controller and creates a `connect()` method.

[[[ code('6ba1308996') ]]]

Before we fill this in, go over to `_review.html.twig` and use this. I'll break this
onto multiple lines.. cause it's getting kind of ugly. Copy the class name,
but delete the custom logic. Replace it with a normal if statement: if
`isNew|default(false)`, then we want to *activate* that new Stimulus controller.
Do that with `{{ stimulus_controller('streamed-item') }}`. Oh, and pass a second
argument, I want to pass a variable *into* the controller called `className` set
to `streamed-new-item`.

[[[ code('1344fbe728') ]]]

I'm doing this for two reasons. First, it will now be the responsibility of the
*controller* to add this class to the element. We'll do that in a minute. And
second, while we don't need it now, making this class name dynamic will help us
reuse this controller later.

*Anyways*, head back to the controller and define the value: `static values = {}`
an object with `className` which will be a `String`.

Cool. Down in `connect()`, add that class to the element: `this.element.classList.add()`
and pass `this.classNameValue`.

[[[ code('4cbaccbb4d') ]]]

If we stopped right now... this would just be a really fancy way to add the
`streamed-new-item` class to the element as soon as it pops onto the page.

So let's do our *real* work. Use `setTimeout()` to wait 5 seconds... and then...
if I steal some code... *remove* `this.classNameValue`.

If we just did this, after five seconds, the green background would suddenly
disappear. To activate the transition when the background is removed, add
*another* class: `fade-background`.

[[[ code('83e3b20c31') ]]]

If you wanted to be really fancy, you could wait until the transition finishes
and then remove this class to clean things up. But this will work fine.

Let's try it! Refresh both tabs so that we get that new CSS... then go fill in
another review. When we submit... good! A green background here... *and*
in the other browser. If we wait... beautiful! It faded out! How nice is that?

Ok team, we're currently publishing updates to Mercure from inside of our
controller. But the Mercure Turbo UX package that we installed earlier makes it
possible to publish updates *automatically* whenever an entity is updated, added
or removed. It's pretty incredible, and it's our next topic.
