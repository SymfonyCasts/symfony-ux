# Targets

Coming soon...

Each time stimulus sees a new `data-controller="counter"` on the page. It
instantiates a new instance of our controller class and calls this `connect()` method.
From here. We can do anything. We can attach event listeners, change HTML or anything
else, and we can do it with, or without jQuery your choice. Heck we can even render
react or Vue components from inside of here. That's something we'll do later, but
there are a few things that we do so often in a controller that stimulus gives us
some extra optional tools. The first is finding an element inside of this controller
element, which right now we don't have anything because it's empty. Here's the new
challenge. Instead of replacing all of the HTML. When we click, I want to find just
the number and change it, head into our template `templates/product/index.html.twig`
and remove the extra controller element. We just don't need to anymore down
here. And the other one,

Let's put some HTML inside this element to begin with. Like I have been clicked, then
I'll do span. Then I'm going to create a `<span>` with `class="counter-count"` and
put the zero in there and then say times, and you probably know why I'm doing this.
I'm adding an element here with the class so that I can find this element and only
update the inner HTML of that specific element. And by the way, this is the way
you'll normally work with stimulus. We will add HTML in the template like we always
have. Then we will add behavior to it in the controller. What I mean is the
controller. Usually isn't responsible for rendering the contents of your controller
elements like with `innerHTML`, our template is usually responsible for that over the
controller. This means that we don't need the `innerHTML` element anymore. That's
already set to replace just the number when we click, we need to find that element.
So we gave it a `class="counter-count"`, which I will copy. I'm going to find that
element without jQuery. 

So I can say `const counterNumberElement = this.element`
So we're always going to start with the element for our controller,
and then we're going to search inside of it by saying, `.getElementsByClassName()`
and then pass it our `counter-count`. And that will return an array of all the
elements. So then we won't get the zero index. So easy kind of, but not that easy to
find that element, but it's enough to get the job done because now inside on click,
instead of `this.element.innerHTML`, we can say `counterNumberElement.innerHTML = this.count`
All right, let's try it. I'll spin over refresh and

Spin over refresh and try it beautiful. Since this was kind of ugly to do. Stimulus
has a nicer, has a feature to make it so much nicer. It's called targets. Here's how
it works. First at the top of the class, add a static targets property. You can do
that with `static targets =` and then this is an array and put `count` inside that target
name count could be anything. I totally just made that up then in the template
replace the `class=""` what a special data attribute `data-counter-target="count"`
So that is a very specifically named here. That is data dash. That is data
dash controller, name dash, the word target equals. And then the name of the target
in this case, count things to this back inside the controller, we don't need to find
the element anymore. It's already available so we can remove the get elements by
class name and down inside the click listener. It's simply,

`this.countTarget.innerHTML = this.count`. That's it. Let's go try that
refresh. And it still works. So as soon as you have a target inside of your
controller, in this case named `count`, you imagine that they have a property called
`countTarget`. That's one of the trickiest things about stimulus. Getting used to
these naming conventions like `countTarget` and also `data-controller-target="count"`

Okay.

Anyways, `this.count` target will either be an element or throw an error. If there was no
corresponding target element, if there are multiple, it will return the first one.
You can also use `this.countTargets` that would return an array of all of the matching
targets, which is sometimes handy. When you want to find when you have like a list of
something, Or if you want to check to see if a target exists, you can also use
`this.hasTarget()`.

So you will never again,

Need to search for an element inside your controller element targets. Have you
covered? I love this feature. Oh, and two things about this `targets = []` thing up here.
First, don't forget these `static`. I've done this. I've done that a few times. It just
doesn't work. It needs to be static. And second, this syntax of setting properties
with an equal sign like this is actually an experimental feature in JavaScript is
still to use it in your `webpack.config.js` file. You need to have this
configure babble line here that adds a special plug to babble that supports this
syntax. This comes automatically with the Encore recipe. Okay. The next most common
thing that we need to do in a controller is listening to events like our click event.
Stimulus gives us a way nicer way to do this.

Okay.

