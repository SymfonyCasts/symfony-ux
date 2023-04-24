# Async/Dynamic import()

One of the bigger items in the analyzer - though not the biggest, we'll get to
those later - is `react-dom`. It's... kind of unfortunate that the user needs to
download this immediately on every page... only to render a *pretty* unimportant
spot on the footer... or a sidebar that exists on just *one* page. Can we...
improve that situation somehow?

We can! And we have two ways!

The first is called an async or dynamic import and this strategy can be used inside
*any* JavaScript file: it has nothing to do with Stimulus. I'll show that one first.
But in the next video, we're going to learn about a trick that's special to
Stimulus and Symfony... a trick that I'm *super* excited to show you.

## Using import() As a Function

Over in the project - let me close a few files - head up to `assets/controllers/`,
open `featured-product-react_controller.js` and also `made-with-love_controller.js`.
These are the two files that use `react-dom`.

*Remove* the `import` on top for `react-dom`. Now, inside of `connect()`,
`re-add` the import, but use it like a function: `import('react-dom')`. Add a
`.then()` to the end of this with an arrow function that receives the `ReactDOM`
module as an argument. Move the `ReactDOM.render()` stuff into this function...
and the last thing we need to do is add `.default`.

***TIP
In React 18, the code to render a component is slightly different:

```
import('react-dom/client').then((ReactDOM) => {
    ReactDOM.createRoot(this.element).render(<MadeWithLove/>)
});
```
***


[[[ code('070e2813db') ]]]

That's it! This is called an async or dynamic import and we've talked about this
a few times before on Symfonycasts. This allows Webpack to isolate the `react-dom`
code into its own file. Then, that code won't be downloaded *until* this import
line is executed. Yup, it'll basically be downloaded as an Ajax call... which is
why we have the `.then()`: the `import()`function returns a `Promise`. We could
have also used `await`. Oh, and when you use a dynamic import, if you're importing
the "default" module from a file... you need to add the `.default`. It's kind of
weird, but simple enough.

Anyways, here's the big point! Thanks to this, the `react-dom` JavaScript code
won't be downloaded on initial page load. But the *moment* that a
`data-controller` element appears on the page for this controller, the `connect()`
will be called, `react-dom` will be downloaded and the component will render.
That's *amazing*.

The only downside is that there may be a slight delay before this component
renders. If that's a problem, you can add a loading animation in your controller
element's HTML... which will get replaced as soon as the component renders.

Let's repeat this in the other controller so that `react-dom` isn't imported by
*any* modules on page load. Copy this entire block, go to the other file,
remove the `import` on top, paste the new code below... then bring up our original
`ReactDOM.render()` code. Don't forget to add the `.default` and... we don't need
this `import`: PhpStorm added that when I pasted the code.

[[[ code('8a4cce4ae3') ]]]

Beautiful! First, let's make sure we didn't break anything. Head back to your
terminal. You can see that the `webpack-bundle-analyzer` server is still running.
Stop that with Ctrl+C and then run our usual:

```terminal
yarn watch
```

Over at the browser - I'll wait for that to finish... done! Refresh
the page and... yes! I saw a slight delay, but it works!

## Seeing Async Imports in your Network Tools

Go check out your browser's network tools - filter this to only show JavaScript
files. Let's see: the page downloaded `runtime.js`, a long `vendors` filename and
`app.js`. Those are the *only* three JavaScript files that are being initially
loaded on the page. I know that because of the "Initiator" column. It says
"register" - that's the URL for this page. That tells me that these are actual
`script` tags in my HTML.

But for the last one - this `vendors-node_modules_react-dom.js` file - its
initiator is something called "load script". That's a deep function from inside
Webpack itself! This tells me that *Webpack* downloaded this script file
asynchronously *after* the page loaded!

We can see this on the Waterfall timeline on the right: this file *started*
downloading *way* after the other ones. It didn't start downloading until the
`connect()` method on our controller was called.

## Analyzing our Async Import Improvements

This is awesome! But to prove that we've made good improvements, let's rerun our
analyze commands. Back at the terminal, hit Ctrl+C to stop Encore. Then run the
`yarn build` command from earlier to get a fresh `stats.json` file:

```terminal-silent
yarn run --silent build --json > stats.json
```

Since we're doing a production build... this will take a lot more time than
a normal build. Once it finishes, start the analyzer:

```terminal-silent
yarn webpack-bundle-analyzer stats.json public/build
```

And... ooh! Let me close the sidebar. Ah, this is nice! Notice that `react-dom`
*is* now isolated into its own file. Close a few tabs... then view the source of
the page. Actually let me refresh first... then view source.

Once again, the page has 3 script tags. This tells us that `runtime.js`, `643.js`
and `app.js` are the *only* files that are being downloaded on page load.

That proves that the `react-dom` file is *not* being immediately downloaded! The
amount of code that the user needs to download before the page starts working
*just* got a bit smaller... which makes our site load faster.

So... async imports are *super* fun. They're a native Webpack feature that you
can use in any file, even outside of a Stimulus controller.

But I want to go further. Could we make an entire *controller* lazy? What I mean
is: could we avoid downloading *all* of the code from a controller *and* the code
from *all* of its dependencies... until an element for that controller appears
on the page?

And what about the controllers from Symfony UX? Like the one for `chartjs`?
That controller imports the `chart.js` library... which is *huge* and only used on
the admin page. Could we make that entire controller load lazily?

You can probably guess that the answer is... yes! A *huge* yes! Let's learn
how next.
