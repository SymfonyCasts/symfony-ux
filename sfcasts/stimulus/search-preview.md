# Search Suggestions

One thing we haven't done yet in stimulus is handle Ajax. Let's do that by
"enhancing" the search on this page. The search here *does* work: it's a completely
normal form that submits via a `GET` request. In the controller -
`src/Controller/ProductController` - we read the `q` query parameter and use it
to filter the product list.

Now, our UX team wants to make it fancier: as the user types we will show the user
a "quick results" or "suggestions" list below the search box.

## Bootstrapping the Controller & Markup

Let's get to work. Start with the Stimulus controller. So in
`assets/controllers/` create a new file called, how about,
`search-preview_controller.js`.

It starts exactly like *every* other Stimulus controller ever:
`import { Controller } from 'stimulus'` and
`export default class extends Controller`.

I also like to have a `connect()` method so we can `console.log()` a message to
make sure everything is... um... connected.

The template for the homepage lives at `templates/product/index.html.twig`.
Scroll down a bit... perfect. Here is our search form.

So let's think: we could add the `data-controller` attribute directly
to the `<input>`. After all, we need to *do* something when that input changes.
But we're *also* going to need a place to put the new search suggestions HTML.

So let's add the controller on the `<div>` around the `input`. Break this
onto multiple lines and then add `{{ stimulus_controller() }}` then the
name of our controller: `search-preview`.

Let's check it! Find your browser, refresh and check the console. Connected!

## Taking Action as the User Types

Ok: each time the user types a character into the box, we need to make an Ajax
request to get a list of suggested, matching products. That means we need to
add an *action* to the input.

Do that down here with our actions syntax: `data-action=""`, the name of our
controller `search-preview#` and then the method to call. How about: `onSearchInput`.

I'm calling it `onSearchInput` because the default action for an input element
is actually called... `input`! That's a native DOM event that happens whenever
the value of this box changes. Basically, each time the user types a character.

Copy the method name then head into the controller. Let's replace the `connect()`
method with `onSearchInput()`. Give it an `event` argument and `console.log()` that.

Let's check it! Go refresh and... when we type... very nice!

## Generating URLs in Symfony & Passing to Stimulus with Values

Look back at the controller for this: `src/Controller/ProductController.php`,
the `index()` method.

This action is responsible for rendering the homepage that we're currently
looking at. And it *also* contains the logic in to *filter* the products based
on the `q` search query parameter. That's because, if you look in the template,
our form does *not* have an `action` attribute. That means it will submit right
back to the current URL... which means it's submitting back to the homepage, but
now with the `?q=` part.

So here's the plan: we're going to send the Ajax request from our Stimulus
controller to the homepage route and controller because it *already* has all of
the search logic we need. But we're *also* going to add a `preview` query parameter.
We'll use that here in a few minutes to either render the *full* HTML page like
*or* just the HTML for the search preview area *if* we see that query parameter.

The point is: we're going to make the Ajax request to the homepage route. So,
question: in our Stimulus controller, should we just hard-code that URL?

We *could* do that. But remember! Stimulus gives us a *super* easy way to pass
values *from* Symfony - like a URL we've properly generated - *into* our controller!
It's the values API.

Add a `static values = {}` an object, and create one called `url`. This will be
a `String`.

Down in the method, instead of logging the event, let's `console.log(this.urlValue)`.\

In the template, to pass this in, we can use the 2nd argument of `stimulus_controller`.
Pass `url` - that's the name of the value - and set it to the normal way that we
generate routes in Twig: the `path()` function and the name of the route, which
is, let me check... `app_homepage`. I'll paste that.

Let's check it! Move over and refresh. The first thing you can see, if you inspect
element, is that we have the new `data-search-preview-url-value="/"` attribute.
And as we type... uh beautiful. It correctly logs the that value.

Next: let's make the Ajax call! But... what should the Ajax endpoint return? JSON?
No way! Definitely HTML... but not a full page of HTML.
