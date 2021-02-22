# Bootstrapping a "Color Selector" Form Element

Let's work on an even more interesting and complex example. Some products come in
multiple colors. To select a color and quantity, I built a nice, boring Symfony
form. If you're curious, you can see this form in `src/Form/AddItemToCartForm.php`.
The `color` is an `EntityType` to a `Color` entity, which means it renders as a
dropdown.

But... the design team isn't happy, and I don't blame them! They want something
more interesting where users can actually *see* what the color looks when they
they choose it.

So here's the goal: replace this `select` element with small "color square" boxes
where we choose the color by clicking on the box. The strategy we're using here
is called progressive enhancement. That's a philosophy where we get the page
working first - with a nice boring, normal form - then make it better - "enhance
it" - with JavaScript when we need to.

Open up the template for this page: it's down here at
`templates/product/_cart_add_controls.html.twig`. If you're wondering, I extracted
the form rendering code into this template "partial" because it's also used on
the checkout sidebar.

Yup, this is a normal Symfony form that uses the normal Symfony form rendering
functions.

## Building the Color Boxes

Ok: to build the color boxes, should we add that HTML in Twig or in a Stimulus
controller? The answer is almost always: in Twig. Why? Because Stimulus is *all*
about rendering HTML on the server and adding *behavior* in JavaScript.

Since not all products come in multiple colors, this if statement checks to see
if our form even *has* a `color` field. For forms that *do* have this field,
each product has a *different* list of possible colors.

If you want to follow the entity structure, our form is bound to a `CartItem`
object. We can go from `CartItem` to `Product`... and then once we have the
`Product`, it has a `colors` property that holds a collection of `Color` objects
that this product is available in. *This* is what we need to loop over to create
the color boxes.

To do this, we'll use a little form trick: `{% for color in addToCartForm.vars.data` -
that will give us the `CartItem` object - then `.product.colors`. And an `endfor`,
then inside, we can say `{{ color.hexColor }}`: that's one of the properties on
the `Color` object.

[[[ code('7c2ef96883') ]]]

Let's try that. Move over and... ah! It works, but it's *ugly*.

To turn this into some schwweet color boxes, change the `div` to a `span`, clear
out its contents entirely and give it a `class="color-square"`: that's a CSS class
I already created to make this a small square with rounded corners. The only
special thing *we* need to do is set the background color.

Do that with `style="background-color: "` then I'll use the `rgb()` syntax, passing
`color.red`, `color.green` and `color.blue` - three *more* properties, or more
accurately, getter methods in my `Color` class.

[[[ code('74d9457c8a') ]]]

Awesome! Check it out now. They're so cute!

## Adding the Stimulus Controller

*Now* we need to make these little suckers *functional*. And *that* means we need
a Stimulus controller.

Up in the `assets/controllers/` directory, add a new file. Let's call it, how
about, `color-square_controller.js`.

Notice the naming convention: the only thing that *really* matters is that
we end in `_controller.js`. For the rest of the name, it could be `color-square`
or `color_square` - it doesn't really matter, as long as it's all lowercase
because the name is used in HTML attributes. Because I'm using dashes, the
controller's name will be `color-square`.

Inside the file, we always start the same way:
`import { Controller } from 'stimulus'` and `export default class extends Controller`.

I usually like to add a `connect()` method to make sure I've got everything
hooked up correctly. Let's `console.log(this.element.innerHTML).`.

[[[ code('1ebb14daf5') ]]]

Now, go activate this in the template. But let's think: we need the controller
to go around the three color boxes. But it also needs to go around the `select`
element itself so that we can set its value when the user clicks a color box.
The `select` element is rendered by `form_widget()`.

So let's add a new `<div data-controller="color-square">`, put that around
everything and indent.

[[[ code('96b5b859f1') ]]]

Sweet! Let's take this puppy for a walk. Move over, refresh, and open up the
console. Yes! Our controller is connected!

## Adding the Action

Of course the goal of our Stimulus controller is not just to log something: it's
to *do* something when we click each color square. So what we need is an action.
In the template, on the color square span, add `data-action=""`. Remember, the
syntax for an action is the name of the controller - `color-square` - a `#`, then
name of the method that should be called on our controller when this
action happens. How about `selectColor`.

[[[ code('bbdea10ff1') ]]]

Now, over in the controller, replace the `connect()` method with `selectColor()`
and, just like normal JavaScript, this will be passed an event object. Let's
`console.log()` that `event` to see what it looks like.

[[[ code('4204d03db4') ]]]

Move over, refresh and... click! Uh... nothing happens? The action is *not* working!

This is one of the trickier things about Stimulus: often, if you have a slight
mistake - like you misspell your controller name - you won't get an error... it
just won't work. There are reasons for why.... but it can be tricky. Watch your
spelling closely.

But in this case, the mistake is something else. I added the action to a `span`
element. Things like `a` tags, `buttons`, `forms` and form elements all
have a *default* action like `click` or `submit`. A `span`... does not... which
makes sense: a `span` doesn't *do* anything in normal HTML.

This means that if we want to add an action to a `span`, we need to specify it.
Do this by adding `click->` in front of the rest of the action syntax.

[[[ code('71ed322ab4') ]]]

Now when I click... it works! And we can see that we're passed a normal event
object.

## Stay Semantic

But... we're making our life harder than it needs to be! Why not just make these
squares *buttons* instead? That'll simplify the action syntax *and*... it's
just more correct: these really *are* buttons that the user will click.

Change the `span` to a `button`. And then add `type="button"`.

[[[ code('d8bdf6111f') ]]]

That will make sure that the button doesn't cause the form around it to
*submit* when we click. And then, we do *not* need the `click` anymore: that
is the default action for a button.

By the way, since our button doesn't have any text in it, to make this more
accessible, we should add an `aria-label` attribute for screen readers, like
`aria-label="choose the color red"`.

Anyways, let's try this! Refresh, click and... woohoo!

Now that things are set up, let's actually... yea know, *do* something on click.
First, we need to add a border to whichever square the user clicks so that it
looks "selected". And second, we need to set the value on this `select` element
so that the selected color is submitted with the form.

Let's work on the first part next and learn about the "current target" property
on events.
