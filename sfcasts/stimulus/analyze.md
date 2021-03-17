# Webpack Bundle Analyzer

Each time we add a new Stimulus controller - either by adding a new file to
`assets/controllers`, or via the `controllers.json` file when we install a new
Symfony UX package, all the code from that controller - and any modules it imports -
are added to the set of JavaScript that's included and downloaded on every page.
For example, this `sweetalert2` code is currently only used on the cart page...
but it's downloaded on *every* page.

But you know how the old saying goes: premature optimization is the root of all
evil.

So this is not necessarily a problem. And the benefit of how this all works is
huge! We can write a controller, add a corresponding `data-controller` element
to any page - even via Ajax - and it *will* work. That is a game changer both
for reliability and simplicity.

However, it *may* not be a bad idea to look at what's inside our compiled
JavaScript files - the stuff that's in the `public/build/` directory. And we can
easily do that with Webpack.

## Dumping a Stats File from Webpack

Find your terminal, go to the tab that has Encore and hit Control+C. Now run:

```terminal
yarn run --silent build --json > stats.json
```

I know, that looks a little funny. The main command in there is `yarn run build`.
That executes the `build` script that's in our `package.json` file. So basically,
`yarn run build` is a shortcut to do a *production* build. We're doing a production
build because the point of this command - it's the `--json` flag that does it -
is to dump a *huge* amount of information into a new `stats.json` file about our
built files. And if we're going to optimize our files, it'll be more realistic
if we look at a production build.

## Installing and Using webpack-bundle-analyzer

But... this file... isn't exactly readable. Fortunately, instead of parsing
through this data by hand, we can use a visualization library. Install it with:

```terminal
yarn add webpack-bundle-analyzer --dev
```

This adds a new executable to our app. When it finishes, we can run
`yarn webpack-bundle-analyzer`, point it at the `stats.json` file and then tell
it where the built files are located: `public/build`.

```terminal-silent
yarn webpack-bundle-analyzer stats.json public/build
```

Hit it. And... woh! This starts a new, temporary web-server at http://127.0.0.1:8888
and opens our browser~ You can see this back on the terminal. If it didn't open
a browser for you, just go directly to that URL.

Oh, I can *feel* the power. This shows us every built file and what's inside of
it. For example, the biggest file is this `801.js` file... the filenames in a
production build are purposefully short and cryptic. An `app.js` file is also being
output and a tiny `runtime.js` file.

Looking at this... it's pretty obvious that the *biggest* thing is `chart.js`.
And, though this specific tool doesn't tell you, I know that `moment` is being
imported by `chart.js`.

By the way, as we talked about *way* at the start of the tutorial, the reason we
see 3 files is that Encore - via Webpack - automatically splits your code into
pieces. Head back to the homepage here... and view the HTML source. Yep! Our page
includes 3 script tags for the 3 files we saw a second ago. These represent the
built code for the *one* Webpack entry we have called `app`.

What's important for us is that the contents of these three files are being
loaded on *every* single page.

Can we somehow improve this situation so that our users need to download *less*
JavaScript for every page?

We can! Next, let's learn a native Webpack feature - async imports - that can
help us lazily load JavaScript in *any* file. After that, I'll show you a super
amazing trick that's special to Stimulus and Symfony.
