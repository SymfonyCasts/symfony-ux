# Magic with Events, Properties & HTML from AJAX

To show off the power of this simple, controller-instance-bound-to-HTML-element
concept, let's count how many times each element is clicked and print that inside
the element.

## Adding a Controller Property

Head over to the controller. I'm going to start by inventing a new property called
count in `connect()`: `this.count = 0`.

That property isn't a `stimulus` thing... I'm just creating a property for our
*own* use and initializing it to zero.

Below this, attach a `click` listener to our element:
`this.element.addEventListener()`, `click` then a hipster arrow function. Oh, that's
mad because I forgot my comma!

Inside, say `this.count++` to increment and then
`this.element.innerHTML = this.count`.

## What about jQuery?

If you're not super familiar with using the native DOM Element object methods, like
`addEventListener()`, don't worry! I'm not using jQuery because, in a lot of cases,
it's really not needed. But if you're more comfortable with using jQuery, awesome!
You can *totally* still use it! Just install it - `yarn add jquery --dev` - then
import it into any file that needs it - `import $ from 'jquery'`. Oh, and if you're
migrating a legacy app where you have jquery already included via a `script` tag,
that's something we talk about in our [Encore tutorial](https://symfonycasts.com/screencast/webpack-encore/external-libs).

Anyways, if you *are* using jQuery, just use `$(this.element)` before you call any
methods - like `$(this.element).on('click')`. Though, we *are* going to learn a cooler
way to attach event listeners in a few minutes.

Ok: I think this is ready! Move over and refresh. Now... click. Boom! The count
increments and prints in the element. And, most importantly, we can see that each
element is working independently. This proves that there are two separate objects
with two separate `count` properties.

## When data-controller Elements are Loaded via AJAX

This isn't even my favorite part of Stimulus. Down in your browser's inspector,
right click on the div around one of our controllers and go to "Edit as HTML".
Copy the `<div data-controller>` and paste to hack in a new one right above it.

What I'm doing is imitating what happens when HTML is added to the page after
it's done loading, like via an AJAX call. This is a *classic* problem with JavaScript.
Suppose you have some jQuery code that attaches a `click` event listener to all
elements that have some class. Usually, that code runs when the page finishes
loading.

Now, what happens if you load *new* HTML onto the page later via AJAX and the
HTML contains an element with that class? Is the event listener automatically
attached to it? Nope! It's not... unless you go to the hassle of manually re-calling
your function that attaches the event listener.

So: can stimulus handle this? Can it somehow "notice" that a new element with
`data-controller` was added to the page? The answer is.... yup!

When I click off to add the new element to the page... it works! Behind the
scenes Stimulus actually *did* notice that a new element was added to the page and
instantiated a brand new controller object for it. That's incredible! It's
a game-changer! I can now write nice controller classes, return HTML via AJAX and
*not* have to worry about re-initializing behavior on that new HTML.

And, of course, the controller works exactly like the other ones: it increments as
I click.

If this were the end of Stimulus's features, I'd *absolutely* use it. But it's not!
Let's learn about "targets" next: an easy way to find elements inside of the
controller's main element.
