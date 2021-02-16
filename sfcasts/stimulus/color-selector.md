# Color Selector

Coming soon...

[inaudible]

Let's work on an even more interesting and complex example. Some products come in
multiple colors to select a color and quantity. We built a nice, boring Symfony form
that renders a select element. If you're curious, you can see this over in source
form, add item to cart form. Nice, boring form that builds a entity type for the
color, which is rendering as a dropdown, but the design team wants something more
interesting because users want to actually see what the color looks like that they're
choosing. So here's the goal troop to replace this select element with three, with
color square boxes that we can click to choose the color. The strategy we're using
here is called progressive enhancement. Our page already works just fine. We're just
going to choose to make it better to enhance it with JavaScript. Let's find the
template for this page. It's done here and templates, product cart, add controls that
age, Tim twig, I've extracted the add to cart controls into a template partial
because this is being re re-used somewhere else. And for the most part, this is a
normal, boring Symfony form using these Symfony form rendering functions. Okay. So to
build the color boxes here, should we build that HTML in twig or in a stimulus
controller? The answer almost always is in twig because we're all about putting the
HTML on the server. All right. So here's how this works. You can see that I have an
if statement up here to check, to see if this form even needs a color. If a product
does have a color different products come in different colors.

So I'm going to use a little form trick here to find the product object that we're
about to add to the cart and then loop over its color objects. So behind the scenes,
if you're following the kind of entity structure here, our form is bound to a cart
item object. We're going to go from cart item object to get the product is actually a
DTO class. And then from the product, once I get the product, it has a color's
property, which has all the colors that this product is available. And so we'll use
this and we'll ultimately loop over this array. So to do that, we can say for color
in add to cart, form.vars.data that will get us the cart item, object dot product,
that colors.

And now that we have this color object, it has a couple of methods on it. One of them
is color. One of them is hex color. All right. So let's try that when I move over,
it's totally ugly, but it is working sweet to turn this into color boxes. I'm
actually going to change this div to a span and then clear out the contents entirely.
Um, give us a class = color dash square as a CSS class that I've already defined that
will give it like a little square size with rounded colors. So the only thing that we
need to do here is add a background color. So I'll say style = background, color,
pound sign, and then curly, curly color dot hex color.

Then I use RGB here and I'll say curly, curly, colored.red, curly, curly,
colored.green, and finally curly, curly color.blue. Awesome. Now I try it. There are
three little color squares. Now we need to make this functional. So let's go
bootstrap, a new stimulus controller up in the assets. Controllers directory, make a
new file and let's call it. How about color of dashes square_controller dot JS. Now
notice the naming convention here. The only thing that really matters is that we end
in_controller dot J S over here. I could have make this color dash square
color_square. It doesn't matter. I like the dash naming. I like the dash. So the name
of our controller will be color dash square inside. We always start the same way.
Import controller from stimulus and export default class extends controller. And then
what I usually like to do is add a connect method, at least to start. This will help
me make sure that I have everything connected. Cause I'll console that log this.mint
dot inner HTML. So we'll make sure that that at least shows up on the page. Then
let's go activate this in the template. Let's think here we need the controller to go
around the three color boxes. And, but also these select element itself so that we
can set, which option is selected as we're clicking the boxes, these select elements
being rendered by this form widget thing. So let's add a div data. Dash controller =
color dash square, and I'll put that around everything and then indent it.

Okay.

Right now I move over. I'll refresh. I'm gonna open up my dev tools there. Okay.

Okay.

And Oh, it is not working.

There we go.

And yes, there it is. You can see the select element and our three spans.

So

Of course the goal of our controller, our stimulus controller is not just to log
something. It's to do something when we click each of these color squares. So what we
need is an action in the template. Let's add that on the span. I'm going to add data.
Dash action equals. Remember, this is the syntax for action. And then we do the name
of the controller color dash square, and then pound sign. And then the name of the
method that should be called on our controller when this span is clicked. So I'll say
select color. Now this is not actually going to work. I am missing something. If
you're wondering, we'll see it. We'll see what it is in a second. Now over in our
controller, I'll replace the connect method with select color and just like normal
JavaScript. This is going to get a pass, any event object. So let's console that log,
that event object to see what it looks When you move over and refresh and click,
nothing happens.

Yes,

The action is not working. The reason is that we just added our action to a span
element. Remember things like anchor tags, buttons forms, form elements. They all
have a default action like click or submit a span does not. Which means if you do
want to add something to a, an action to a span, you need to specify it. This means
you need to add click->in front of the rest of the action syntax. Now let me click it
works and awesome. Down here can see we get the normal event object from clicking
that element, but let's refactor this span to a button element anyways, that'll make
our life simpler and more correct. And we'll also get a little pointer icon when we
are hovering over the colors.

So

We'll change the span to a button. And then I'm actually going to say type = button.
That's not something you normally need to do, but we want to make it serve this. This
will, this will make it so that clicking this won't submit the form. And then we
don't need the click anymore because that is the default action on a button Refresh
now and, uh, much better. And we click it is working so next. Now that things are set
up, let's really, let's actually do something on click first. We need to add a border
to whichever square the user is clicking so they know which one is selected. And
second, we need to update this select element so that the form actually submits
correctly. And once we're done we'll of course hide this select element so that the
user only sees these boxes.

Okay.

