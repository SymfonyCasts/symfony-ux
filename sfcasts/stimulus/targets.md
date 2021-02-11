# Targets: Finding Elements

Each time stimulus sees a new `data-controller="counter"` on the page, it
instantiates a new instance of our controller class and calls this `connect()` method.
From here, we can do anything! We can attach event listeners, change HTML or whatever
else we dream up. And we can do it with or without jQuery: your choice. Heck we
can even render React or Vue components from inside here! That's something we'll
do later.

But there *are* a few things that we do *so* often in a controller that Stimulus
gives us some extra, optional, tools. The first tool helps us find elements
*inside* of the controller element.

Here's the new challenge: instead of replacing all of the HTML when we click, I
want to find *just* the number and change *it*.

## Moving the Starting HTML into the Template

Head into our template - `templates/product/index.html.twig` - and remove the
extra controller element: we don't need 2 anymore, that was just to test things out.

Inside the other one, let's put some HTML *inside* this element to begin with,
like: "I have been clicked" then `<span>` with `class="counter-count"`, put the
zero inside, and then say "times".

You might already know what I'm doing: I'm adding an element around the *exact*
thing we want to change with a class so that we can *use* that class to find this.

Oh, and by the way, this is the way we will normally work with Stimulus: we render
HTML in the template - like we've been doing for *years*. Then we add *behavior*
to some of that HTML in the controller. What I mean is, the controller isn't
usually responsible for rendering the full content of into the element, like you
would do with React or Vue. The HTML belongs in the template.

Over in the controller, this means that we don't need the `innerHTML` logic anymore:
that's already in the element.

## Finding an Element without Targets

To replace *just* the number when we click, we need to find the span we created.
Let's see - copy the `counter-count` class. To find this without jQuery, we can
say `const counterNumberElement = this.element` - we're *always* going to start
by looking *inside* the controller's element - the `.getElementsByClassName()`
and pass this `counter-count`. This will return an array of all the elements so
then we need to get the `[0]` index.

So... kind of easy... but not that easy to find the element. But it *is* enough
to get the job done. Inside the click callback, replace `this.element.innerHTML`
with `counterNumberElement.innerHTML = this.count`.

Sweet! Let's try it. Spin over, refresh and try it. Beautiful!

## Adding a "target"

But... finding that element *was* kind of ugly... especially because we're going
to do that kind of stuff *so* often. Thankfully, Stimulus has a feature to make
it *so* much nicer. It's called targets.

Here's how targets works. First, at the top of the class, add a *static* `targets`
property. You can do that with `static targets =` an array and add `count` inside.
`count` is the name of the new target and it could be *anything*: I just made that
up.

Now, in the template. replace the `class=""` with a special data attribute:
`data-counter-target="count"`.

So that is a *very* specifically named. It's `data-controller`, dash the *name*
of our controller, dash the word target, equals, and then the name of the target.
In this case, that's `count`.

Thanks to this, back inside the controller, we don't need to find the element
anymore: it's already available. Remove the `getElementsByClassName()` and down
inside the click callback all we need is: `this.countTarget.innerHTML = this.count`.

That's it. Before we chat, let's go try it! Refresh... and... it still works!

## Target Naming and Properties

So as *soon* as we have a target inside of our controller - in this case named
`count` - we magically get access to a property called `countTarget`. That's one
of the trickiest things about Stimulus: getting used to these naming conventions
like `countTarget` and also `data-controller-target="count"`.

Anyways, `this.countTarget` will either be an element or throw an error if no
matching target element was found. If there are multiple, it will return the first.

You can also use `this.countTargets`: that returns an array of *all* of the matching
targets. We'll use that later to find each "color square" in a color selector that
we'll build.

Finally,if you want to check to see if a target exists, you can use `this.hasTarget`.

So... you will *never* again need to search for an element inside your controller.
Targets have you covered. I love this feature.

## The Class Properties Syntax

Oh, and do want to say one thing about this `targets = []` part. This syntax -
where we set properties with an equal sign - is actually an experimental feature
in JavaScript. To use it, in your `webpack.config.js` file, you need to have this
`configureBabel()` line here: this adds a special plugin to Babel that adds
supports for the syntax. This comes automatically with the Encore recipe.

Next: there's one other *super* common thing that we always need to do in a
controller: listening to events, or, I guess you can think of this as "responding
to user actions". Stimulus gives us a great way to handle this.
