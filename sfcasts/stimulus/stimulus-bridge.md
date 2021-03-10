# stimulus-bridge: How UX Packages Work

In the last video we installed a PHP package - `symfony/ux-chartjs` and it's
recipe added a new JavaScript package to our `package.json` file, which points
to a directory *in* that bundle. By running `yarn install --force`, yarn copied
that into `node_modules/` so that the package works like any normal JavaScript
package.

So that was cool because, with basically one command, we got both a new bundle
*and* a new JavaScript package, which contains a Stimulus controller.

Then, just by writing a little PHP code and calling a Twig function, boom!
We magically had a JavaScript-powered chart. How the heck did that all work?

## render_chart() just renders a data-controller Element

Inspect element on the graph. Interesting. All the `render_chart` Twig function
actually did was render a `canvas` element with two important attributes:
`data-controller` set to some long `symfony--ux-chartjs--chart` name, and a
`data-view` attribute set to a JSON-version of the data that we built inside of
PHP. In our controller, you can see that this matches the data we built here.

Look back at the `ux-chartjs` package in `node_modules`. In `src/`, open up
`controller.js`. This is the controller that's being used to render that chart
on our page. And it's beautifully simple. It reads that `data-view` attribute
then instantiates a new `Chart` object. This comes from the `chart.js` package.
That, ultimately, renders the chart into our element. So simple!

## How Stimulus Controllers are Registered

But there is still one missing piece to explain how this all works. Open
`assets/app.js`. The built version of this file is included on every page on our
site. It loads `bootstrap.js`, which we opened up at the very beginning of the
tutorial. The code inside this file looks a little weird, but its job is simple and
important. It reads all of the files in our `controllers/` directory and registers
them with Stimulus as controllers. This is where the naming convention comes into
play. When Stimulus sees a file called `counter_controller`, it registers that
controller under the name `counter`. Then when a `data-controller="counter"`
element appears on the page, it knows which controller to use.

If we ever added a `data-controller` to our page with a controller name that
Stimulus does *not* know about like - `data-controller="eat-pizza"` - Stimulus
will do nothing.

The point is: 100% of the controllers that Stimulus is aware of come from this line
here, which means they come from the files in our `assets/controllers` directory.

## controllers.json: Automatically Registered Controllers

But... wait. If you look back at our browser, this rendered a
`data-controller` attribute set to `symfony--ux-chartjs--chart`. And... we do
*not* have a file called `symfony--ux-chartjs--chart_controller.js` in this
directory. So how the heck did the controller from our `ux-chartjs` package
get registered as a Stimulus controller with this name?

The answer to that question lives in a file that we haven't really looked at yet:
`assets/controllers.json`. This file was also automatically updated by the recipe
when we composer required `symfony/ux-chartjs`. It was basically empty before.

When we first installed Webpack Encore, our `package.json` file came pre-filled
with a few libraries. One of them is called `@symfony/stimulus-bridge`. If you
look back at `bootstrap.js`, we import a function called a `startStimulusApp`
*from* that package.

In reality, when we use that down here, that function does two things. First,
it does what we already know: it finds all the files in our `controllers/`,
directory and registers each as a Stimulus controller. Second, it reads our
`controllers.json` file and also registers any controllers here as Stimulus
controllers.

Let me show you how that works. When `startStimulusApp()` parses this files and
sees the `@symfony/ux-chartjs` key, it finds that package in `node_modules/` and
opens its `package.json`. Then it looks for a special key called `symfony` and
then `controllers`.

It then looks at the `chart` key... and uses that to find out *exactly* where this
controller file actually lives: the path under this `main` key. We'll talk about
the "eager" stuff later.

And that's it! For the controller name, it takes the package name -
`@symfony/ux-chartjs` - then this controller nick name - `chart` and normalizes
it into the long string that we see in the browser.

## tl;dr: UX Packages give you Stimulus Controllers

If you don't care about too much about the details of how this all works, here are
the cliff notes. Each time we install a Symfony UX package, we instantly - without
doing anything other than `composer require` and `yarn install` - have access to
a new Stimulus controller in our application. That's incredibly powerful.

For example, this doesn't exist yet, but you could imagine being able to install
a form entity type auto-complete package in your app, which would give you a new
Symfony form type that could replace the boring `select` element with an
auto-completable field for selecting an entity. The possibilities are huge.

Next, let's investigate how we could *control* the behavior of the third-party
Stimulus chart controller. It uses a really interesting pattern that will allow us
to make *big* changes if we need to.
