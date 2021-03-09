# Stimulus Bridge

Coming soon...

In the last video we installed a PHP package Symfony /UX chart dot JS it's recipe
added a new package to our package. That JSON file, which actually points to a
directory in that bundle by running yarn installed dash dash force. That directory
was actually copied into our node modules directory, which means it works like any
normal JavaScript package. So that was cool because with basically one command, we
got both a new bundle and a new JavaScript package, which contains a stimulus
controller.

Okay.

Then just by writing a little bit of PHP code and then calling a twig function, boom,
we magically had a JavaScript power to chart. How the heck did that all work? Inspect
the element on the graph? Interesting. All that render chart twig function actually
did was render a canvas element with two important attributes data dash controller
set to some long Symfony UX chart, JS sharp string, and a data view attribute set to
the JSON that we actually built inside of PHP. In our controller, you kind of look
over, back in our controller. You can see that that matches the data that we built
here.

Look back at the UX chart, JS library and node modules. Okay. And the source
directory opened up this controller dot JS. This is the controller that's being used
to render that chart on our page. And it's beautifully simple. It reads that data
dash view attribute then instantiate a new chart object. This comes from chart J the
chart JS library, and ultimately that renders the chart into our element. So simple,
but there is still one missing piece to explain how this all works. Go open assets
/app dot JS. The built version of this file is included on every page in our site and
our site. It Lowe's bootstrap dot JS, which we looked at at the very beginning of the
tutorial. The code inside his file looks a little weird, but its job is simple and
important. It reads all of the files in our controllers directory and registers them
with stimulus as controllers. This is where the naming convention comes into play.
When stimulus sees a file called_controller, it registers that controller under the
name counter. Then when a data dash controller = counter element appears on the page,
it knows which controller to use.

If we ever

Added a data dash controller element to our page with a controller name that stimulus
does not know about like data dash controller = eat, pizza stimulus will do nothing.
The point is 100% of the controllers that stimulus is aware come from this line here,
which means they come from the files in our assets /controllers directory. So, so
then if you look back at our browser, this rendered a data dash control = Symfony UX
chart JS chart.

And

We do not have a file called at Symfony UX chart JS sharp_controller dot JS in this
directory. So how the heck did that? So how the heck did the controller from our
stimulus, our U S UX chart JS JavaScript library get registered as a stimulus
controller with this name. The answer to that question lives in a file that we
haven't really looked at yet assets /controllers that JSON,

Okay,

This file was also automatically updated by the recipe. When we installed Symfony /UX
chart JS, it was basically empty before when we first installed the Webpack Encore,
our package that JSON file came prefilled with a couple of libraries. One of them is
called at Symfony /stimulus bridge. If you look back at bootstrap dot JS,

Wow.

Use this to import a function called a start stimulus app. In reality, when we use
that down here, that function does two things. First, it does what we already know.
It finds all the files in our controllers, directory and registers each as a stimulus
controller. Second, it reads our controllers that JSON file and also registers any
controllers here as a stimulus controller. How does that work

Work? When these start stimulus app sees

At Symfony /UX chart, J S key, it finds that package down here and opens. It's
packaged that JSON file and for a special key called Symfony and then control Uses
that to find out exactly, uh,

Next

It reads this chart key below that and uses that to find out exactly where this
controller file actually lives. It's under this main key. We'll talk about this eager
stuff later,

And that's it

To name the controller. It takes the package name at Symfony /UX chart dot JS, then
the sort of controller name chart, and normalizes it into the long string that we see
in our library. The /becomes a dash dash

cache.

If you don't care about too much about the details of how this all works here is what
this means. Each time we install a Symfony UX package, we instantly without doing
anything other than composer require and yarn install have access to a new, uh,
stimulus controller in our application. That's incredibly powerful. For example, it
doesn't exist yet, but you could imagine being able to install an entity
auto-complete package in your app, which would give you a new Symfony form type that
would replace a boring select element with an auto completable field for selecting an
entity. The possibilities are huge.

Next,

Let's start a bit more of a, how we can, could control the behavior of this chart
controller. It uses a really interesting pattern that will allow us to make big
changes if we need to.

