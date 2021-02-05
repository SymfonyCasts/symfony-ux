# Controllers

Coming soon...

Okay. Time for stimulus. First stimulus is, Hey, JavaScript library. If you start in
a new project and install Encore fresh, like we did, then thanks to the recipe.
Stimulus is already inside of your package, that JSON file,

We also have an ad Symfony /stimulus bridge library. This ads super powers on top of
stimulus. I'll tell you exactly what they are as we go along. If you don't have these
packages, go ahead and install them with yarn. Add stimulus at Symfony /stimulus
bridge dash dash Def. Let me close a few tabs and then open up our assets /app dot JS
file. This imports, a bootstrap that JS file that the recipe also gave us. And you
will need this. If you don't have it in your project, you can get it from the code
block on this page. And this line here starts stimulus by telling it to look for
stimulus controllers. We'll learn all about those in this controllers directory,
which is literally assets controllers. Symfony gives us one dummy controller to
start, And that's the entire point of his file to say, Hey, stimulus, I have some
controllers in this controller's directory. I'll explain all this weird lazy
controller stuff a bit later, the best way to see how stimulus works is just to try
it. I'm going to delete this hello controller file and what great one from scratch.
Call it counter honors core controller digest. We're going to create a little element
that tracks how many times you click it for our simple example to start. And by the
way

That

Naming that name, naming convention is important. All of our controllers will always
be something_controller that JS. And you'll see why in a minute inside a controller
always starts the same way, import curly curly from stimulus that I'll go back and
fill in the curly curly. What we need is controller and then export default, a class
that extends controller Inside the class. We'll learn a lot about what we can put in
here, but to see how things work at a connect method.

Yeah,

With this.element that inner HTML = that I'll put a message here. You have clicked
the zero times And that's it for now to see what this actually does. We need to add a
matching element to one of our pages, open templates, product index dot age two
months. Wait, this is the template for our homepage Down a bit about right here, I'm
going to add a div data dash controller = counter, and then I can put something in
the dead for I'm just going to keep it blank. For our example, this connects the data
dash controller connects this element to the controller class that we just created.
That happens because we call the, our controller con counter_controller dot JS. Now
because we call the file counter on the score controller that JS it's named
internally in stimulus becomes counter stimulus strips off the_controller part.

So we connected with data dash controller = counter. All right, let's try this as a
reminder, I still have a yarn watch going over here. So it's been rebuilding every
time we make a change, I'm going to spend over click to the homepage and it works.
You can see it actually took that empty element and added this inside of it all
inspect elements. Yep. You can see data desk controller. And this is inside. This is
magic of stimulus. As soon as it sees an element with data, dash controller =
counter. It instantiates an instance of our controller Of our counter controller
object in calls this connect method. As you can see, the element that it's bound to
is available to us via this.element. So this, that element is the Dom element that
corresponds with this diff right here. And so we're able to set its inner HTML.

The

Beauty is that we can have as many of these elements on the page as we want. What's
that another one I'll copy this and

I'll

Add it up here inside the OSI.

Now move over and refresh two elements.

A really cool part is that each element is connected to a separate instance of our
object. This means we can write JavaScript code in a class. I control our class And
store information about that specific element as properties on this object.

Yep.

We get objects that are bound to individual HTML elements and are instantiated
automatically. When those elements appear on the page, I would use stimulus just for
that.

Okay.

What's that one more thing. Let's count the number of times, how many times each
element is clicked and then print that inside the item, head over to our controller.
I'm going to start by inventing a new property called count. I'll say this not count
= zero. That's not a stimulus thing. That's just me making up a property and setting
it to zero.

Then

Below this, I'm going to attach a click listener to our element. So this.element,
that add event listener

Quick,

And then I'm going to pass this an->function, the hipster->function.

Oh,

That's mad. Cause I forgot my comma. There we go. And inside here, we'll say
this.count plus, plus to increment it. And then I'm going to set this, that element
that inner HTML = this.count. I'm not using jQuery because in a lot of cases it's
really not needed, but if you're more comfortable with using jQuery, you can totally
still use it. You would just always use it. Dollar sign open brand sees this.element
before any methods you call on it like dollar sign, open parentheses of this.element
dot on click anyways, move over refresh. Okay. They look the same now, but now I can
click and boom. When I click up, it keeps track of it. And you can see they're
independent of each other. That proves they're two separate objects that are tracking
the value of that count property.

[inaudible]

This isn't even the best part of stimulus Down in my inspector for my browser. I'm
going to right click on this div here and go edit as HTML. And I'm going to copy that
dev data = controller, and I'm going to hack in a new one right above it. What I'm
doing is mimicking. What happens when HTML is loaded to the page after it's, when
HTML is added to the page after it's done loading like via Ajax, this is a classic
problem with JavaScript. If you attach event listeners to some, to all classes, to
all elements with some class on page load,

If you add, if you load new HTML later via Ajax, the event listeners, aren't
automatically attached to it, unless you go to the hassle of manually recalling your
function to reattach, uh, to, to, to add the event listeners to that element. So can
stimulus handle this? Yup. When I click off to add the new element to the page, it
worked behind the scenes. This is totally true. Stimulus noticed that a new element
was added to the page and instantiated a brand new control object. You can see it
right here. That's incredible. That is a game changer for me. And this controller
works exactly like the other ones increments as I click on it. So like I said, if
this were the end of stimuluses features, I've use it, but it's not. Let's learn
about targets next and easy way to find the elements inside of the main, uh, inside
your main controller element.

Yeah.

