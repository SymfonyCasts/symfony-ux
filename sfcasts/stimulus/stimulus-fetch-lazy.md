# Making your Custom Controllers Lazy

Stimulus bridge has one more trick. On the analyzer tab, the 3 files
that are loaded on every page are now these 2 dark blue files and this 1 light
blue file. So the biggest JavaScript modules now are `sweetalert2`, Stimulus itself
and something called `core-js`.

`core-js` represents our *Polyfills*. For example, the `web.url.js` thing is
what gives us the `URLSearchParams` object that we've used a few times.

So other than the Polyfills and Stimulus, the biggest item in here is definitely
`sweetalert2`. And, again, that's kind of unfortunate... because that's only used
on the cart page. Could we do the same "lazy" trick with our own controllers?

## The stimulusFetch: lazy Comment

Totally! Open `assets/controllers/submit-confirm_controller.js`. This is the one
file that imports SweetAlert. Above the controller class, add a special *comment*:
`stimulusFetch` colon then `lazy` in quotes.

This is a special syntax and feature from `stimulus-bridge`. The text inside the
comments will be parsed as JSON, which is why we have the colon and quotes. The
result of this is that... yea! Our controller will be lazy - just like the `chartjs`
controller!

Head back to our terminal. Let's re-run our commands again to do one last
analyzer dump:

```terminal-silent
yarn run --silent dev --json > stats.json
```

When we run the analyzer:

```terminal-silent
yarn webpack-bundle-analyzer stats.json public/build
```

Yes! This time `sweetalert` is in its own file! The only reason we don't *also*
see our actual *controller* in this same file is that Webpack often splits vendor
code and *custom* code into separate files for caching reasons. If we look around
a bit... hmm... ah! Here it is! This new, *tiny* file contains our `submit-confirm`
controller code. This will *also* be loaded asynchronously as soon as it's needed.

Let's see this in action! Go refresh the homepage... I close out some old tabs...
then view the page source. The 3 files being loaded now are runtime, this long
vendors-stimulus-bridge and `app.js`.

If you look at the analyzer... the 3 files are this `app.js`, the tiny `runtime.js`
and... this `vendors` file. These are now *way* smaller than when we started.

Click into a product, then go back to the Network tools filtered to JavaScript
files. Add the item to the cart... then click to the cart page.

Woohoo! The SweetAlert code *and* our submit-confirm controller were both just
downloaded asynchronously! And everything still works! This *does* mean that, in
theory, someone could click "Remove" *so* fast that the JavaScript isn't loaded
on the page. If that's a problem, you should either avoid using fetch lazy, *or*
disable this button on page load and enable it in your Stimulus controller.

Okay, *enough* with that crazy laziness! Next, after chatting with some of you
lovely people over the past few weeks, I thought it might be nice to do a full
example of submitting a form via Ajax, complete with validation errors and
reloading part of the page after success. Oh, and we'll do all of this inside
a modal... to make it more interesting.
