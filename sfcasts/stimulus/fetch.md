# Loading HTML over Ajax with fetch()

To make the Ajax request inside the controller, I'm going to use the `fetch()`
function.

## fetch() for AJAX

If you don't know, `fetch()` is a built-in function for making Ajax calls tha
*most* browsers support. So basically... pretty much all browsers except for IE 11.

If you *do* need to support IE 11, you have two options. First, the other library
I really like for making AJAX requests is called. Axios, which you can install
with `yarn add axios` - we use it in our Vue tutorial. It also has a few more
features than fetch.

But if you *do* want to use `fetch()`, you can "polyfill" it so it works on all
browsers. Github itself maintains a [polyfill for fetch](https://github.com/github/fetch).

It's pretty simply to set up: `yarn add whatwg-fetch` then, in `webpack.config.js`,
adapt your main entry to include it first:

```diff
// webpack.config.js

Encore
    // ...
-    .addEntry('app', './assets/app.js')
+    .addEntry('app', ['whatwg-fetch', './assets/app.js'])
```

## Using URLSearchParams() & its Polyfill

*Anyways*, let's make the AJAX call!

First, we need to do send 2 query parameters on the AJAX request: the value of
the search box as a `q` query parameter and another one called `preview` so that
our controller knows to render the preview HTML.

To help create the query string, say `const params = new URLSearchParams()`
and pass that an object. Inside set `q` to the value of the input, which we know
will be `event.currentTarget` - to get the input Element that we attached the
action to - then `.value`. Also pass `preview` set to 1.

This `URLSearchParams` object is a built-in JavaScript object that just helps you...
basically create query strings like this. It is *also* not supported in all
browsers... IE 11, cough. *But*, it will be polyfilled automatically by Babel,
as long as you have the default Encore configuration:

```js
// webpack.config.js

Encore
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
```

Yea, I know, it's a little confusing. The `@babel/preset-env` library that Encore
uses will automatically detect when you use a feature that's not supported by the
browsers that you want to support and add a polyfill. But... while Babel supports
*most* polyfills, it doesn't support all of them. `fetch()` is one notable example
of a polyfill it doesn't support... which is why you need to add it manually.

***TIP
If you want to see a list of all the polyfills added by Babel, you can add a
`debug` flag. A report will then be printed to the terminal when you build Encore:

```diff
// webpack.config.js

Encore
    .configureBabelPresetEnv((config) => {
        // ...
+        config.debug = true;
    })
```
***

Anyways, in this case, and in most cases, Babel has us covered.

## Making the AJAX Request with fetch()

Ok, let's make the AJAX call: say `fetch()` then pass it the fancy ticks so create
a dynamic string. We need `${this.urlValue}` then a question mark and another
`${}` with `params.toString()`. That builds the query string with the `&` symbols.

Like all Ajax libraries, `fetch()` returns a `Promise`. So if we want to get the
response, we have two options. First, we can use the `.then()` syntax and pass
a callback.

*Or*, we can use `await`... which I like because it looks a bit simpler. Remove
the `.then()` and instead say `const response = await fetch(...)`.

This will *wait* for the AJAX call to finish and set the result to the `response`
variable. But as *soon* as you use `await`, you must add `async` before whatever
function this is inside of. Yup, that makes my build happy.

The `async` is really just a marker that says that, thanks to the `await`, this
function will now *automatically* return a Promise. That doesn't really matter
unless you want to call this function directly and use its return value. If you
*were* doing that, you would *also* need to `await` for this function to finish.

But... the response itself, isn't that interesting. What we *really* want is the
response *body*. We can log that with `console.log(await response.text())`.

This shows off a... sort of weird thing about `fetch()`. When we make the Ajax
call, we - of course - need to await for it to finish. But even getting the
body of the response - with `response.text()` is an asynchronous operation that
returns a `Promise`. That's... kind of odd... but ok: we just need to `await` it.

Ok, testing time! Refresh and... type! Yes! I can already see new AJAX requests
in the web debug toolbar. *And* the console is dumping the *full* HTML.

The only problem is that this is the *full* HTML of the homepage... which isn't
really what we want. What we *really* want is a "fragment" of HTML that represents
the search suggestions. Let's get that hooked up next and render it onto the page!
