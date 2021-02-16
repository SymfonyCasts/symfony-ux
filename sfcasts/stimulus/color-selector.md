# Bootstrapping a "Color Selector" Form Element

Let's work on an even more interesting and complex example. Some products come in
multiple colors. To select a color and quantity, I built a nice, boring Symfony
form that renders a select element. If you're curious, you can see this form over
in `src/Form/AddItemToCartForm`. The `color` is an `EntityType` to a `Color` entity,
which means it renders as a dropdown.

But... the design team isn't happy, and I don't blame them! They want something
more interesting where users canto actually *see* what the color looks like that
they're choosing.

So here's the goal: replace this `select` element with small "color square" boxes
where we choose the color by clicking on the box. The strategy we're using here
is called progressive enhancement. Our page already works just fine. But we're
going to choose to "enhance it" with JavaScript.

Open up the template for this page: it's down here in
`templates/product_cart_add_controls.html.twig`. If you're wondering, I extracted
the form rendering code into this template "partial" because it's also used on
the checkout sidebar.

For the most part, this is a normal, boring Symfony form that uses the Symfony
form rendering functions.

## Building the Color Boxes

Ok: to build the color boxes here, should we add that HTML in Twig or in a Stimulus
controller? The answer is almost always: in Twig. Because Stimulus is *all* about
rendering HTML on the server and adding *behavior* in JavaScript.

Let me explain how this form is setup. Not all products come in multiple colors,
and this if statement checks to see if our form even *has* a `color` field. For
products that *do*, each product comes in *different* colors.

If you want to follow the entity structure, our form is bound to a `CartItem`
object. We can go from `CartItem` to `Product`... and then once we have the
`Product`, it has a `colors` property which holds a collection of `Color` objects.
This is what we need to loop over to create the color boxes.

To do this, we'll use a little form trick: `{% for color in addToCartForm.vars.data`,
to get to the `CartItem` object, then `.product.colors`. And an `endfor`, then
inside, we can say `{{ color.hexColor }}` - that's one of the properties on the
`Color` object.

Let's try that. Move over and... ah! It works, but it's *ugly*.

To turn this into some schwweet color boxes, change the div to a span, clear out
its contents entirely and give it a `class="color-square`: that's a CSS class that
I've already created to make this a small square with rounded corners. The only
special thing *we* need to do is set the background color.

Do that with `style="background-color: "` then I'll use the `rgb()` syntax, passing
`color.red`, `color.green` and `color.blue` - three *more* properties, or more
accurately, getter methods on my `Color` class.

Awesome! Check it out now. Hey! 3 cute little squares!

## Adding the Stimulus Controller

*Now* we need to make these little suckers *functional*. And *that* means we need
a Stimulus controller.

Up in the `assets/controllers/` directory, add a new file. Let's call it, how
about, `color-square_controller.js`.

Notice the naming convention here: the only thing that *really* matters is that
we end in `_controller.js`. For the rest of the name, it could be `color-square`
or `color_square` - it doesn't really matter, as long as it's all lowercases
because the name is used in HTML attributes. Because I'm using dashes, the
controller's name will be `color-square`.

Inside the file, we always start the same way:
`import { Controller } from 'stimulus'` and `export default class extends Controller`.

Then I usually like to add a `connect()` method to make sure I've got everything
hooked up correctly. Let's `console.log(this.element.innerHTML).`.

Now, go activate this in the template. But let's think: we need the controller
to go around the three color boxes. But it also needs to go around the `select`
element itself so that we can set its value whenever the user clicks a color box.
The `select` element is being rendered by `form_widget()`.

So let's add a new `<div data-controller="color-square">`, put that around
everything and indent.

Sweet! Let's take this puppy for a walk. Move over, refresh, and open up the
console. Yes! Our controller is connected!

## Adding the Action

Of course the goal of our Stimulus controller is not just to log something: it's
to *do* something when we click each color square. So what we need is an action.
In the template, on the color square span, add `data-action=""`. Remember, then
syntax for an action is the name of the controller - `color-square` - a pound
sign, then name of the method that should be called on our controller when this
span is clicked. How about `selectColor`.

Now, over in the controller, replace the `connect()` method with `selectColor()`
and, just like normal JavaScript, this will be passed an event object. Let's
`console.log()` that `event` object to see what it looks.

Move over, refresh and... click! Uh... nothing happens. The action is *not* working!

This is one of the trickier things about Stimulus: often, if you have a slight
mistake - like you misspell your controller name in an action attribute - you
won't get an error, it just won't work. There are reasons for that, but it can
be tricky. Watch your spelling closely.

But in this case, the mistake it something else. I added the action to a `span`
element. Remember, things like `a` tags, `buttons`, `forms` and form elements all
have a *default* action like `click` or `submit`. A `span` does not... which makes
sense... because a `span` doesn't *do* anything in normal JavaScript.

This means that if we want to add an action to a `span`, we need to specify it.
Do this by adding `click->` in front of the rest of the action syntax.

Now when I click... it works! And we can see that we're passed a normal event
object.

## Stay Semantic

But... we're making our life harder than it needs to be! Why not just make these
color squares *buttons* instead? That'll simplify the action syntax *and*... it's
just more correct: these really *are* little buttons.

Change the `span` to a `button`. And then add `type="button"`.

That will make sure that this button doesn't cause the form around it to
*submit* when we click it. And then we, do *not* need the `click` anymore: that
is the default action on a button.

By the way, since our button doesn't have any text in it, to make this more
accessible, we should add a `aria-label` to it for screen readers, like
`aria-label="choose the color red"`.

Anyways, let's try this! Refresh, click and... woohoo!

Now that things are set up, let's actually... yea know, *do* something on click.
First, we need to add a border to whichever square the user clicks so that it
looks "selected". And second, we need to set the value on this `select` element
so that the correct color is submitted with the form.

Let's work on that next!
