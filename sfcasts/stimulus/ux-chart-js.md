# Symfony UX & Chart.js

We now know Stimulus pretty well, which is really just a nice JavaScript
library that has nothing to do with Symfony. So then... what exactly is Symfony UX?

To answer that, go to https://github.com/symfony/ux. At its most basic, Symfony
UX is a growing list of pre-built Stimulus controllers that you can install to
get free functionality. For example, one of them is a controller that integrates
[Chart.js](https://www.chartjs.org/): a completely independent, lovely
JavaScript-powered chart library.

This is perfect... because our sales are *really* starting to take off. People
*love* our junk, uh, minimalist products! So let's install and use this UX package
to build a sales graph on an admin page. I've already created that page at
"/admin"... but it's not very interesting yet... and none of these links go anywhere.

## Installing symfony/ux-chartjs

Ok: let's check out the docs for Symfony UX chartjs. Scroll down... Whoa.
Stimulus and Chart.js are both pure JavaScript libraries. So it's a little
interesting that the first step is to install a PHP package. Why are we doing
that? Let's find out! Copy the `composer require` line and head over to your terminal.

I committed all my changes before hitting record so I can easily see any
changes that the Flex recipe for this package might make. Paste and go:

```terminal
composer require symfony/ux-chartjs
```

While this loads, your Encore build may start failing: don't worry about that
for now. When it finishes, run:

```terminal
git status
```

Interesting! This modified `composer.json` and `composer.lock`, which is totally
normal. And the PHP package we just installed is actually a Symfony bundle, so
its Flex recipe auto-enabled that bundle in `config/bundles.php` and updated the
`symfony.lock` file.

## UX PHP Packages Come with a JavaScript Package

But here's where things get interesting, *really* interesting. The recipe *also*
updated our `package.json` file as well as some other file called
`assets/controllers.json`.

Let's see what changed in `package.json`:

```terminal
git diff package.json
```

Woh! It added a new package! But instead of a version number on the right, it's
pointing to a directory inside of `vendor/`. Let's go check out that path. It was
`vendor/symfony/ux-chartjs/` then `Resources/assets`. Cool! This directory is a
JavaScript package! How can I tell? It has a `package.json` file like all JavaScript
libraries and, in the `src/` directory, a Stimulus controller!

Well, in reality, when we use this package, the compiled controller in the `dist/`
directory is what will *truly* be used... but reading the one in `src/` is easier.

So: what does this mean for us? It means that each Symfony UX library - like
ux-chartjs - is actually *two* things: a PHP bundle *and* a JavaScript package.
There is also some magic related to *how* this controller is registered in our
app... but we'll talk about that in a few minutes.

Anyways, we have a new Symfony bundle in our `vendor/` directory *and* a new
JavaScript package registered in `package.json` - let me open that - which points
to a directory *in* that bundle.

## yarn install --force to Put the Package in node_modules/

Ok, back to the docs! The next step is to run `yarn install --force`. Copy that.

Normally, if you add a package to `package.json`, you need to run `yarn install` to
actually *download* that into the `node_modules/` directory. And actually, that's
exactly what just happened to us! The Flex recipe added a new package to our
`package.json` file.

Move over to the terminal that's running Encore, hit Ctrl+C to quit, then paste
this command:

```terminal
yarn install --force
```

This command forces yarn to reinstall our dependencies. The important part is
that it copies the directory from the bundle *into* `node_modules/` so that
it looks and acts like any normal package.

Let's go find that: let me close a couple of things, then open `node_modules/`,
and `@symfony/`. Cool! We of course have a `webpack-encore/` directory but we
*also* have a package called `ux-chartjs`.

Let's go re-build our assets:

```terminal
yarn watch
```

## Using the new Stimulus Controller

Ok: so how do we *use* the new Stimulus controller that lives in the package?

To answer that... let's keep following the docs! Scroll down. To build the
JavaScript-powered chart, we're... woh! We're going to write *PHP* code!

Copy the top half of this code then head over and open up our admin controller,
which is `src/Controller/AdminController.php`. Paste that code on top. And...
let's see, we also need this `ChartBuilderInterface` argument. Add
`ChartBuilderInterface $chartBuilder`. And... we need a `use` statement for this
`Chart` class. I'll delete the "t", re-type, and hit tab to get it on top.

[[[ code('49fe9e918a') ]]]

But wait: where did these classes come from? Remember: we *did* just install a new
bundle... and bundles give us new classes and services. Autowiring
`ChartBuilderInterface` will give us a new service that's really good at building
chart data in PHP. Pass the `$chart` variable into the template: `chart` set to
`$chart`.

[[[ code('4700f7122b') ]]]

Okay. How do we render the chart? Go back to the docs and scroll down one
more time. Ah ha! The bundle also gave us a new Twig helper. Copy that and go
find the template for this page: `templates/admin/dashboard.html.twig`.

Then... all the way at the bottom, paste.

[[[ code('87f33b4849') ]]]

This uses the new function `render_chart()` from the bundle to render our `chart`
variable.

I... guess we're done? Head back to our site and refresh the admin page. Whoa!
We *are* done! We have a JavaScript-powered graph! That's extra amazing since all
*we* did was `composer require` a PHP package, run `yarn install`, then write
some PHP code!

*That* is the power of Symfony UX.

But... how exactly does this all work and connect together? What does `render_chart()`
*actually* do? How is that Stimulus controller from the bundle being used? When
will I find my car keys?

Let's answer... most of these questions, next.
