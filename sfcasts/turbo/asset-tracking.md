# Reloading When JS/CSS Changes

How does Turbo handle when a JavaScript or CSS file that's downloaded onto our page
changes? When we navigate, it's smart enough to merge any new CSS or JS into our
`head` element without *duplicating* anything that's already there.

But what about a CSS or JavaScript file whose contents just updated because we
deployed? This is really a problem specific to production because locally, if
we change a CSS or JS file in our editor, we just come back and trigger a full
page reload manually. But how is this handled on production?

Well... if you do nothing, it's pretty simple: your users will continue to surf around
with the old CSS and JavaScript... which is *not* something we want... especially
since they will be getting the *newest* HTML from our site... which may only work
with the newest CSS and JavaScript.

## Activating Asset Versioning

But a slightly different thing happens if we enable *versioning* on our assets. Head
to your editor and open up `webpack.config.js`. About halfway down this file...
you'll find `enableVersioning()`.

This tells Encore that, if we are doing a production build, each filename should
contain a hash that's unique to its contents. It's a great strategy to make sure
that when you deploy updates, each file gets a new file name... which forces users -
in a *non* Turbo universe - to download the latest version.

To see what happens *with* Turbo, let's activate this for dev builds also by
removing the `Encore.isProduction()` argument.

To make this take effect, find your terminal, go to the tab that's running Encore,
hit Control+C and then rerun:

```terminal
yarn watch
```

When that finishes... move over, refresh, and navigate around. If you check out the
`head` tag, we have versioned filenames! The `app.css` file is now `app.blahblah.css`,
and the `app.js` file also has a hash.

Let's go modify the `app.js` file - that's over at `assets/app.js`. At the bottom,
`console.log('new code')`.

Now, *without* refreshing your browser, navigate to a new page.. and look at the
console. Interesting... no log! And we have *two* `app.js` script tags on the page...
which is probably not what we want.

First, the new file wasn't executed because Webpack was smart enough to realize that
the `app` entry script *has* already been loaded. So even though the `script` tag
*was* added... and downloaded, Webpack prevented it from running: it knows that
something weird is going on.

And even if it *did* load, it would probably mean that we would have things like event
listeners registered twice on the page... which is *also* not what we want.

What we see in the `head` tag at least *does* make sense based on what we know about
Turbo. Because the `app.js` has a new filename, it looks like a *new* script file.
And so, Turbo added it to the `head`.

## Refreshing the Page with data-turbo-track="reload"

So... how do we fix this mess? Well, let's think. One of the huge benefits of
Turbo is that your JavaScript and CSS are downloaded and executed just *once* on
initial page load... and then are reused for every navigation after. It's a big reason
why Turbo is so fast. But if one of these files changes... we sort of *do* need to
hit the "reset" button. In other words, this is *one* case when the page
*should* do a full page reload so that our browser can download everything new.

Fortunately, there's an easy way to do this: by adding a special `data-turbo-track`
attribute to every CSS and JS tag. And, it turns out, adding that attribute is
super easy!

Open `config/packages/webpack_encore.yaml`. The `script_attributes` key allows us
to add an attribute to *every* `script` tag that Encore outputs. Add
`data-turbo-track` and set it to `reload`. We'll talk about what this does in
a second. Also uncomment `link_attributes` and set the same thing here.

With this simple change, every `script` and `link` tag that Encore renders
will now have that `data-turbo-track="reload"` attribute on it.

So here's how this works... it's pretty simple. When we navigate, Turbo finds all
of the elements with `data-turbo-track` on the *current* page and compares their
filenames to the `data-turbo-track` elements on the *new* page. If the total
collection of filenames on the old page does *not* exactly match the total collection
of filenames on the *new* page, Turbo will trigger a full page reload.

Watch: if we click around, we see a lot of nice, boring Turbo-powered visits.
But now go back to `assets/app.js` and remove that `console.log()`.

Behind the scenes, a new `app.js` file with a *new* filename was just output. You
can see it in the Encore terminal: before the filename was this, now the filename
is different.

Back at our browser, let's visit a new page. Watch carefully. Yes! That was a
full page reload! Turbo saw that the new page's "tracked" `script` and `link` tag
filenames did *not* exactly match the old page's tracked filenames, and so, it
triggered a normal, full-page-reload navigation. Problem solved!

Next: sometimes you may need to navigate to another page via custom JavaScript
code. Like, maybe you have some custom JavaScript... where, after an Ajax
call, you want to redirect to another URL. Could we use Turbo to do that visit
instead of triggering a full page reload? Absolutely.
