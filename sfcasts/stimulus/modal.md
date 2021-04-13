# Opening a Modal

Our current goal is to be able to add a new product - completely without
leaving or reloading this page. To do that, we need an "Add" button!

## Creating the modal-form Controller

Open the template for this page - `templates/product_admin/index.html.twig` - and
wrap the `h1` in a `div` with `class="d-flex flex-row"`. Also give the
`h1` an `me-3` so it has some margin between it and the button:

[[[ code('81c06215cf') ]]]

Add that `button`, text "Add" with classes `btn`, `btn-primary` and `btn-sm`.

[[[ code('ebc9f522ad') ]]]

Cool! Because clicking the button will open a modal via JavaScript, let's immediately
attach a Stimulus `data-controller` attribute. But instead of adding it to the
`button` directly, wrap the button in a div and add it there:
`{{ stimulus_controller()` }} and let's call it `modal-form`... because we're
going to make this controller able to open *any* form we want in a modal.

[[[ code('947f8f05a4') ]]]

Why are we attaching the controller to the `div` instead of the `button`? It
won't make any difference right now... but it *will* come in handy in a few minutes
when we need to add a modal *template* that we can access from our controller.

Speaking of the controller, let's go add that! In `assets/controllers/`, create
a new file called `modal-form_controller.js`. Go steal the starting code from another
controller... paste, and do our usual `connect()` method with `console.log()`
coffee.

[[[ code('82b235398b') ]]]

Ok! Refresh the page to make sure everything is connected and... it is! There's
our tiny, delicious beverage.

## Attaching the Button Action

Step two: on click, we want to open a modal. This means we need to add an *action*
to the button.

Over in our template, add `data-action=` the name of our controller -
`modal-form` - pound sign, and let's call the method `openModal`.

[[[ code('f975f551b4') ]]]

Copy that, head into the controller, rename `connect()` to `openModal()` and add
the event argument in case we need it. Inside, `console.log(event)`.

[[[ code('8e40c6c938') ]]]

If we refresh now... and click. We're on a roll!

## Importing the Modal Object

So how do we open the modal? One of the nice things about Bootstrap is that it has
standalone JavaScript utilities, including one that opens a modal. In Bootstrap 5,
we can import it by saying `import { Modal } from 'bootstrap'`.

[[[ code('e9a0bdddb1') ]]]

## Fixing the Missing @popperjs/core Peer Dependency

But... oh! As soon as we do that, we have a failing build! Head over to your
terminal and go to the tab that's running Encore:

> Bootstrap contains a reference to the file `@popperjs/core`. This file cannot
> be found.

Ah! Earlier, we talked about peer dependencies: we saw a warning about them
when we installed Bootstrap. Many of Bootstrap's JavaScript tools depend on another
library called `popperjs`. For good, but somewhat technical reasons, instead of
Bootstrap listing `popperjs` as its *own* dependency so it's downloaded automatically,
it's listed as a "peer dependency"... which means that it's *our* responsibility
to install it directly.

No problem! Copy that `@popperjs/core` string, head to our other terminal and
install it with:

```terminal
yarn add @popperjs/core --dev
```

When this finishes... beautiful! Our build is instantly happy.

## Adding the Modal Template

Okay: we've imported `Modal`. Now what?

The modal system works like this: we create a bunch of HTML that represents our
modal, put it on the page, but hide it by default. When we want to open that modal,
we point Bootstrap's Modal object *at* that element and say, "show that modal"!

This means that we need to add some modal HTML onto our page. To do that, and to
hopefully make this HTML reusable for other modals, in the `templates/` directory,
create a new file called `_modal.html.twig`.

Inside, I'll paste a basic modal structure. There's no magic here: you can find and
copy a bunch of different modal examples from the Bootstrap docs. This has a
header, a body, which is basically empty, and a footer with some buttons.

[[[ code('277a67a16f') ]]]

Now go back to `index.html.twig`. Right after the button, include the modal:
`include('_modal.html.twig')`.

[[[ code('b904019d38') ]]]

Why are we including it right there?  You'll see why in a minute. But first, go
refresh the page... and inspect element on the button. Ok good: the modal HTML
*is* on the page but, as you can see, it's hidden. This element basically serves
as a *template* for what we want our modal to look like.

## Opening the Modal

Head back to our controller and remove the `console.log()`. Now say:
`const modal = new Modal()`.

What we need to pass here is the DOM element that *holds* the modal template. In
other words, this element right here. How can we find that from inside of our
controller? By using a target of course!

Back in `_modal.html.twig`, all the way up on the top level element, add a target:
`data-` - the name of our controller - `modal-form` - `-target=` and call
the new target `modal`.

[[[ code('2233705a78') ]]]

This *does* make the modal template a *bit* specific to this *one* Stimulus
controller. But I'm okay with that. If we need to make this same element a target
for a *different* controller in the future... we can totally do that! We can add
as many target attributes as we need.

Copy the target name and head back to the controller. Declare it with
`static targets = []` an array with `modal` inside. Careful with the typing on
`targets`: I'll regret that mistake.

[[[ code('c07517bba9') ]]]

Anyways, now we can say `new Modal(this.modalTarget)`.

[[[ code('b19133f65c') ]]]

That creates a new `Modal` object... but doesn't actually *open* it yet. To do
that, say `modal.show()`.

[[[ code('adb972391e') ]]]

Time to take it for a test drive! Move over, refresh and click. Ah! An error!

> Cannot read property `classList` of undefined.

It's coming from Bootstrap. It's not perfectly clear what's happening... but
the "undefined" is very telling. It makes me wonder if my target isn't being
seen correctly.

Ah, yup! My bad: `static targets`. Misspelling this doesn't cause an error
directly from Stimulus... because having a property called `target` *is* legal!
But, of course, that caused the target to not work, which meant it was undefined.

Move over and try it again. This time... got it! And the "close" and X buttons
already work!

But... there's no form inside yet. So next, let's make an Ajax call to load
the new product form *right* into the modal. When we do that, we're going to
be careful to make sure that our new modal system can be re-used for *any* form
on our site, not just this one.
