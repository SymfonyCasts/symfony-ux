# Setting up Webpack Encore

So let's get Webpack Encore installed so we can get a proper CSS and JavaScript
build system set up.

## Installing Encore

Find your terminal and run:

```terminal
composer require "encore:^1.14"
```

***TIP
If you're using version 2 or higher of `symfony/webpack-encore-bundle`, be sure to also
run:

```terminal skip-ci
composer require symfony/stimulus-bundle
```

The recipe needed to integrate the Symfony UX libraries was moved to this
new bundle.
***

This *really* installs WebpackEncoreBundle, which will give us a few Twig helper
functions. But its *true* superpower is its recipe.

## The Encore Recipe Files

After Composer finishes, run:

```terminal
git status
```

Ok: it modified several files, most of which make sense. The `.gitignore` file
is now ignoring the `node_modules/` directory and the bundle was automatically
enabled in `config/bundles.php`.

There are also several new config files, which mostly aren't too important. What
*is* important is that it created a `package.json` file. Let's go check that out.
This defines several things that our Webpack Encore setup needs, like Encore
itself *and* `stimulus`, which we'll talk more about later.

The other hugely important file the recipe added was `webpack.config.js` - with a
basic setup. The recipe also added this `assets/` directory. There *are* a few
things in here related to Stimulus: the "controllers" stuff and `bootstrap.js`.
We'll talk about those soon.

To download the dependencies described in `package.json`, find your terminal and
run:

```terminal
yarn install
```

Oh, and if you're *totally* new to Encore and want to dig into it deeper, check
out our [Webpack Encore: Frontend like a Pro!](https://symfonycasts.com/screencast/webpack-encore)
course.

## The Entry Files and First Build

Right now, our app has a pretty standard Webpack Encore setup. We have one entry
which is this `assets/app.js` file. That means that when we build our assets in
a few minutes, Webpack will *only* look at this file to figure out *all* the
JavaScript and CSS needed in the project. It will then output the final, built
CSS and JavaScript files into a `public/build/` directory.

Of course, when it reads this file, it will follow any imports, like this import
of `./styles/app.css`... which is a dummy file that sets the `background-color`
to gray.

So, once we *have* executed Encore and built the assets - which we'll do in a minute -
we're going to need `script` and `link` tags that *point* to those new files.

## encore_entry_link_tags() & encore_entry_script_tags()

Let's add those to our base layout: `templates/base.html.twig`.

I already have a couple of link tags up here in the `stylesheets` block. Add
`{{ encore_entry_link_tags() }}` and pass it `app`, which is name of that entry.

[[[ code('cfa774c9aa') ]]]

This will render the built version of the `app.css` file plus any other CSS that
our JavaScript imports. And actually, for performance reasons, Webpack may split
that built `app.css` into multiple files: we'll see that in a few minutes. This
function takes care of including *all* the link tags we need.

Do the same thing in the `javascripts` block: `{{ encore_entry_script_tags() }}`
and pass `app`.

[[[ code('89159607b0') ]]]

Like with the styles, this will render the built version of `app.js` plus any
JavaScript it imports. And it may *also* be split into multiple files.

## The New `<script>` defer Attribute

Oh, and you're seeing a recent change in Symfony's TwigBundle recipe for
`base.html.twig`. The `javascripts` block *used* to live down here at the bottom
of the page. Now it lives up in the `head` in new projects. *Normally*... that would
not be a good idea, because when your browser sees a `script` tag, it stops rendering
the page until it can download and execute that JavaScript.

But thanks to a new feature in WebpackEncoreBundle - you can see it in
`config/packages/webpack_encore.yaml`, here it is `script_attributes` - every
script tag rendered by Encore will have a `defer` attribute. That basically means
that the JavaScript isn't executed until after the page loads, very similar to
having the `scripts` at the bottom of the page. But with the new setup, our browser
*does* start downloading the files *slightly* earlier. And this plays nicer with
Turbo - the topic of our next tutorial.

If you want to learn more about this change, check out a
[blog post I wrote on Symfony.com](https://symfony.com/blog/moving-script-inside-head-and-the-defer-attribute).

Next: let's use Encore to build our assets, see "code splitting" in action and
import a third-party CSS package.
