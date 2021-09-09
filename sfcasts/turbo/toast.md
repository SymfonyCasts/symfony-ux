# Toast Notifications

We've made it to the *last* topic of the tutorial... so let's do something fun,
like making it *super* easy to open "toast" notifications.

Toast notifications are those little messages that "pop up" like toast on the
bottom - or top - of your screen. And Bootstrap has support for them. Our goal is
simple but bold! I want to be able to trigger a toast notification from *any*
template or from a Turbo Stream.

## Creating the toast.html.twig Template

Start by creating a new template partial: `_toast.html.twig`. I'll paste in
a structure that's from Bootstrap's documentation. Then let's make a few parts of
this dynamic like `{{ title }}` - that's a variable we'll pass in... `{{ when }}`
that defaults to `just now` and... for the body, `{{ body }}`.

[[[ code('4a4c1754fa') ]]]

Next, open up `product/_reviews.html.twig`. After submitting a new review, we render
a flash message. *Now* I want this to be a toast notification! Cool! Include
*that* template instead... and pass in a couple of variables like `title` set to
`Success` and `body` set to the actual flash message content.

[[[ code('46d4f10fa1') ]]]

## The Toast Stimulus Controller

If we stopped now... congratulations! Absolutely nothing would happen. These toast
elements are *invisible* until you execute some JavaScript that opens them on the
page. To do *that*, we need a Stimulus controller!

Up in the `assets/controllers/` directory, create a new file called, how about,
`toast_controller.js`. Inside, give this the normal structure where we import
`Controller` from `stimulus`, export *our* controller... and have a `connect()`
method that, of course, logs a loaf of bread.

[[[ code('2a96fe0878') ]]]

Over in `_toast.html.twig`, I want to activate this controller *whenever* this toast
element appears on the page. No problemo: on the outer element, add
`{{ stimulus_controller('toast') }}`.

[[[ code('e397073cbe') ]]]

Our controller doesn't do anything yet, but let's at *least* make sure that
it's connected. Head over to our site, refresh the page... make sure that your
console is open... and then go fill out a new review. When we submit... got it!
As soon as the toast HTML was rendered onto the page, our controller
was initialized. Though... like I mentioned, you can't actually *see* the toast
element yet. It's taking up some space... but it's invisible.

Let's fix that! Back in the controller, import `toast` from `bootstrap`. Below
add `const toast = new Toast()` and pass it `this.element`. To *open* the toast,
say `toast.show()`.

[[[ code('633d61261b') ]]]

That's it! Refresh again and add another review. This time... that's super
cool! And it means that we can, from *anywhere*, render the `_toast.html.twig`
template and it will activate this behavior.

## Grouping all the Toasts into One Container

Though... the positioning isn't what I was imagining. Before it disappeared, it
was open... right in the middle of the page. I was hoping to put it in the
top right corner of the screen.

To do that, we just need to add a few classes to the toast element. Except...
there's one other minor problem. If you think about it, it's possible that a user
could see *multiple* toast notifications at the same time. The toast system
*totally* supports this.... it stacks them on top of each other. But for that
to work, we need to have a single global "toast container" element on our page
that all individual toasts live *inside* of.

This might be easier to show. Open up `templates/base.html.twig`. Really, anywhere,
but I'll go to the bottom, add a `<div>` with `id="toast-container`. That
could be anything: we'll use this `id` to find this element in JavaScript.

Also add `class="toast-container"` and a few other classes. `toast-container`
helps Bootstrap *stack* any toasts inside of this... and everything else
puts the toast in the upper right corner of the screen.

[[[ code('bd12d0ca4c') ]]]

Now, in order for this to work, we need all the toast notifications to physically
live *inside* of this `toast-container` element. So basically, we need to render
`_toast.html.twig`... and somehow get that HTML *inside* of the container.

But... I don't want to do that! I want to keep the flexibility of being able to
render `_toast.html.twig` from... *wherever* and have it work. And we can *still*
have this with a little help from our Stimulus controller.

Check it out: at the top of `connect()`, add `const toastContainer = `
`document.getElementById()` and pass it `toast-container` to find the element
that lives at the bottom of the page. Then... let's move *ourselves* *into*
that: `toastContainer.appendChild(this.element)`.

And now that it lives inside the container, we open it like normal!

Though... there is one subtle "catch". When the toast HTML initially loads, it
will live here in the middle of the page. Naturally, Stimulus *notices* this
element, instantiates a new controller instance and calls `connect()`. Yay!
But when we move `this.element` into `toast-container`, Stimulus destroys
the original controller instance, creates a new one, and calls `connect()`
a *second* time.

In other words, the `connect()` method will be called twice: once when we originally
render our toast element onto the page and again after we move into
`toast-container`. Right now, that's going to cause an infinite loop where we
call `appendChild()` over and over again.

To avoid that, add, if `this.element.parentNode` does not equal `toastContainer`.
So only if the element has *not* been moved yet, move it... and then return.
The first time this executes, it will move the element and exit. The second time
it executes, it will skip all of this and pop open the toast.

[[[ code('8e0d347de1') ]]]

Let's try this thing! Refresh the page, add another review and... beautiful! If you
quickly inspect the toast element... yup! It lives down inside of `toast-container`.

## Publishing a Toast through Mercure to All Users

Ok, I have one last micro-challenge: whenever a new review is added to a product,
I want to open a toast notification on *every* user's screen that's currently
viewing the product. Something that says:

> Hey! This product has a new review!

Over in `Review.stream.html.twig`, in the `create` block, add another turbo stream
with `action="append"` and `target=""`... well... leave that empty for a minute.
Give this the `template` element, include `_toast.html.twig` and pass in a few
variables: `title` set to `New Review` and `body` set to

> A new review was just posted for this product.

[[[ code('6887115818') ]]]

Very nice! But... what should the `target` be? We could use `toast-container`.
That would append it to this element. But... then the message would show up on *every*
page. We only want this message to show up if you're viewing *this* specific product.

To do that, we need to target an element that *only* exists on *this* specific
product's page. Open `show.html.twig`. Right inside of the `product_body` block,
let's add an empty `div` with `id="product-{{ product.id }}-toasts"`

[[[ code('39530becad') ]]]

A little empty element *just* for our toasts to go into. Copy this and, in
`Review.stream.html.twig`, target it. Except that we need `entity.product.id`.

Let's check it out! Refresh the page... and then open the same product in another
tab to "mimic" what a *different* user would see. Scroll down, fill in a review
and... submit. Awesome! We have two toasts over here and... the other user sees
the *one* toast! The *two* toast notifications in our first tab *is* a bit weird,
but I'll leave it for now.

And... we're done! Woh! Congrats to you! You deserve a nice crisp high five... and
maybe a short vacation for making it through this *huge* tutorial. It was huge
because... well... Turbo has a lot to offer. I hope you're as excited about the
possibilities of Stimulus and Turbo as I am.

Let us know what you're building. And, as always, if you have any questions, we're
here for you down in the comments section.

All right, friends. See you next time!
