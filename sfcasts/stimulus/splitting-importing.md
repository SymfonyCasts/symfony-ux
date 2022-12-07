# encore watch & Code Splitting

Let's get Encore to build our assets! Do that by going back to the
terminal and saying:

```terminal
yarn watch
```

This reads our `app.js` file and outputs the final files into a `public/build`
directory - you can see that here. Once it's done, it sits and watches for more
changes. If we modify any files, it will rebuild the assets.

Let's try this! Find your browser and refresh! Woo! We have a gray background.
That proves the CSS is being processed!

## Seeing the Code Splitting

If you view the page source, you'll see one `link` tag for `build/app.css`
and... cool! The JavaScript is already being split into *three* different files.
The Twig function knows to render all of them. This is not something you really
need to think or worry about - Webpack does this to help your users download
the files faster and cache them better.

## Moving our Styles

Before we keep going, let's move our *real* CSS - it's currently in
`public/styles/app.css` - into the new `app.css` file that's being processed by
Webpack. Open that up, copy everything inside, then delete the file entirely.

Now go to the new `assets/styles/app.css`, remove the gray background and paste!

[[[ code('8221768d9b') ]]]

In `base.html.twig`, delete the `link` tag that pointed to the old static file.

Now... when we refresh, it still looks good! That's because, over at the terminal,
Encore noticed that we changed the `app.css` file and automatically rebuilt things.

## Installing Bootstrap CSS

While we're here, we also have a `link` tag that points to a Bootstrap CDN. That's
okay, but we can now *properly* install `bootstrap` into our app. First, over at
your terminal, open a new tab so that `yarn watch` can keep doing its thing in the
background. Run:

```terminal
yarn add 'bootstrap@^4.6' --dev
```

Two things. First, if you searched npmjs.com, you'd learn that the name of the
package that gives you Bootstrap is... `bootstrap`! And second, that `--dev`
flag isn't really important - it would be fine if you didn't include that.

This adds `bootstrap` to our `package.json` file and downloads it into the
`node_modules/` directory. Delete the old `link` tag... which temporarily will make
our site look *terrible*.

Then go into `app.css`. On top, we can import this: `import` then `~bootstrap`.

[[[ code('7ceeb7b703') ]]]

That's it. Webpack will magically load all the bootstrap CSS and include it in
the final, built `app.css` file. The tilde is a magic character that tells
Webpack to look for a package in the `node_modules/` directory. This is a special
syntax that you *only* use inside CSS files.

Let's check it! Refresh now and... all better! View the source again. Woh! Even
the CSS is being split into two files. I normally don't even think about this,
but I wanted you to see it.

Oh, and these really long ugly filenames? When you build Encore for production,
those will be very simple names, usually a number - like `55.js`. It won't expose
all these path details.

Next let's figure out how and where stimulus is installed and build our very
first stimulus controller!
