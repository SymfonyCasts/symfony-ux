# Broadcasting Frontend Changes on Entity Update/Remove

In `Review.stream.html.twig`, we have the ability to publish turbo streams
automatically whenever a review is created, updated or removed. That's pretty cool.
*Unrelated* to this, I haven't mentioned it yet, but our site has a review admin
area! You can get it by going to `/admin/review`. Here we can create, update or
delete reviews. Do you... see where this is going? Sometimes an admin user will
"tweak" a review to make it... um... more *encouraging*. Wouldn't it be cool if,
when an admin user did this, that review was instantly updated on the frontend
for all users?

Uh, yea! That *would* be cool! So let's do it.

## Publishing an "update" Update

Start in `_review.html.twig`. This is the template that renders a single `Review`.
Give this element an id so that we can target it from a turbo stream, how about
`id="product-review-{{ review.id }}"`.

[[[ code('db959b6ede') ]]]

Copy that value and, in `Review.stream.html.twig`, when the review is updated, let's
add a new turbo stream: `<turbo-stream>` with `action="replace"` and `target=""` set
to `product-review-{{ review.id }}`. Except that in *this* template, the variable
is called `entity`.

Inside, add the boring - but required - `template` element and inside of *that*,
include `product/_review.html.twig`. This template needs a `review` variable...
so make sure to pass that in: `review` set to `entity`.

[[[ code('ecfab1d24b') ]]]

That's it! When a review is updated, it will *replace* this review element with
the updated content.

Let's see it in action! Refresh the frontend. This is the review we'll update.
Over in the admin area, add a very important dinosaur emoji... and save. Okay. I
think that worked. Let me double check. Yep! Review updated.

*Now* check out the front end. That's amazing! This review just updated for
*every* user in the world that is currently viewing a page where this is rendered.
We could also update the quick stats area... but I'll leave that to you.

## Removing a Review on Delete

What about *removing* a review? In the admin area, we can actually *delete* a
review. Could we automatically *remove* this element from the frontend when that
happens? Absolutely!

Inside the `remove` block, create a `<turbo-stream>`. This will have a new
action - `action="remove"` - and will `target` the same element as our update.
Now, you *might* expect me to say `entity.id`. But... by the time this template
is rendered, the entity has *already* been deleted from the database. And so,
`entity.id` is empty.

*Fortunately*, the library *also* passes us an `id` variable that we can
use instead. Oh, and because we have `action="remove"`, the `turbo-stream`
element won't have anything inside: it's just an instruction to find this
element and *remove* it.

[[[ code('2de126c064') ]]]

Ok: refresh the frontend just to be safe... and in the admin area, delete this.
Now... deep breath... switch to the frontend. It's gone! Ok, this is getting
fun.

## Fading out on Remove

So let's get fancier. What if, when a review is deleted, instead of instantly
disappearing, the element turned red, then faded out. OoooOOOoo.

Start in `styles/app.css`. Add a new `streamed-removed-item` class that sets
the `background-color` to `coral`.

[[[ code('a64b3a40f9') ]]]

Back in `Review.stream.html.twig`, this will be a bit trickier. We don't
*actually* want to *remove* the element anymore... we want to *keep* it... but
trigger some JavaScript that will fade it out.

To do this, change the action to `replace`... and then copy the entire `template`
from `update`. But *this* time, pass in a new variable: `isRemoved` set to
`true`. We can use that in the template to do something special.

[[[ code('8dab1671ed') ]]]

Go open it up: `_review.html.twig`. If we pass in an `isNew` variable, we already
have code to activate a Stimulus controller that causes the item to get a green
background that fades out. We're going to do something similar.

If `isRemoved`, then initialize that *same* Stimulus controller. But this time
pass `className` set to `streamed-removed-item`. *This* is why we made that
controller dynamic. Also pass in *another* value called `removeElement` set to
`true`.

[[[ code('c41bc8b634') ]]]

This will signal to the controller that we want to fade out the element entirely.

Let's get to work in that file: `streamed-item_controller.js`.

Start by setting up the `removeElement` value, which will be a `Boolean`.

Then, import a helper function called `addFadeTransition`. This is a utility that
we created in the first tutorial to help us fade in or fade out an element.

To activate it, inside `connect()`, call `addFadeTransition()` and pass it `this`
object, `this.element` - the element that we're going to fade - and also an options
object with `transitioned` set to `true`. That's needed because our element will
*start* visible and then we want it to fade *out*. If you want to know more about
how this all works, check out our Stimulus tutorial.

Inside `setTimeout()`, check to see if `this.removeElementValue` is `true`.
If it is *not*, then keep the original code: this is where we fade out the
background color. But if it *is* true, call `this.leave()`. That will trigger
the entire element to fade out.

[[[ code('6fc993fee6') ]]]

Phew! Let's see this thing in action! Go back and find this review... here it is.
Refresh the frontend to get the fresh CSS... delete the review... and go to the
frontend! Yes! It's there but with a red background! And then... woohoo! It
faded out!

The big takeaway here? By combining Turbo Streams with Stimulus, you can do *much*
more than simply "update the HTML of an element". You can do... anything.

Okay team: there's just *one* more thing that I want to try: using Turbo Streams
to pop up "toast" notifications on the frontend, like after we do something awesome.
That's next.
