# HTML-Returning AJAX Endpoint

When we type in the box, we *are* now making an AJAX request back to the server.
The response - which we can see in the log - is the *full* HTML of the homepage.
That's... not what we want. But... what *do* we want?

If you use something like React or Vue, your AJAX endpoints probably return JSON.
You then use that JSON to build the HTML in JavaScript. But we're using Stimulus!
And Stimulus is *all* about building HTML on the server.

So *that's* what we're going to do. But instead of a *full* page of HTML, we're
going to return an HTML *fragment*: *just* the HTML needed for the "search
suggestions" area.

Head over to `ProductController` and, before the return, add an if statement: if
`$request->query->get('preview')`, then we know this is the suggestions AJAX request.
Inside, render a new template: `return $this->render()` and call it
`product/_searchPreview.html.twig`.

[[[ code('0db35afee1') ]]]

The template could be called *anything*. The `_` at the front of the name is just
a nice convention: I like to use it for any templates that render only *part* of a
page. These are sometimes called partials.

Pass the template a `products` variable so we can render them. Be sure to add the
`s` at the end of `products`... I'll find my mistake in a minute.

Now, create the template. In `product`, add a new file: `_searchPreview.html.twig`.
But we're not going to extend a base template because we *don't* want a base layout.
We're just going to start rendering content! I'll add a
`<div class="list-group">` to give this some markup that looks good in Bootstrap.
Then `{% for product in products %}` and `{% endfor %}`.

[[[ code('1ff1b6b948') ]]]

Inside, I want each result to be a link. Add an `a` with `href=""` `{{ path() }}`
and the name of the route to the product page, which is `app_product`. This route
has an `id` wildcard. So pass `id` set to `product.id`. I'm also going to add a
few more classes for styling... then inside the a, start with the simple
`{{ product.name }}`.

Oh, and to be *extra* fancy, add an `{% else %}`. If there are no results, render
a `<div class="list-group-item">` with "No results found".

[[[ code('b4b628437b') ]]]

I love that: the entire search preview HTML in a simple template.

To see this, go back to the homepage but add a `?preview=1` to the URL. And... oh!
Variable `products` does not exist. Because... in the controller, I forgot my "s".

Now... much better. I mean, it looks terrible *here*, but that's just because this
page doesn't have any CSS. Head back to the homepage.

## Adding the AJAX HTML to the a Target

The last step in Stimulus is to dump the HTML from the AJAX endpoint onto the page.
To control exactly where it goes, let's add a new target element. In
`index.html.twig`, I want the content to go right below the `input`. Add a
`<div>`... with `class="search-preview"`. That's a class that already lives in our
CSS that will help style things.

To make this a target, we need `data-search-preview-target=""` and... call the
new target, how about, `result`. It doesn't need any content by default.

[[[ code('87af1a9d84') ]]]

Over in our Stimulus controller, set up the target: `static targets = []` an array
with `result` inside.

[[[ code('2a7381817b') ]]]

Below, set the inner HTML on this. Copy the `await response.text` and replace
it with `this.resultTarget.innerHTML` equals `await response.text()`.

[[[ code('9047016e2f') ]]]

Done! Let's go try it! I'll click to go back to the homepage... just to clear the
search entirely. Moment of truth: type. Ha! We got it! And if I type something
nutty, no results found. It's alive!

## Making the Search Preview Prettier

Let's celebrate by making it prettier.

Back over in the template - `_searchPreview.html.twig` - instead of rendering just
the name, I'll paste in some markup. You can copy this from the code block on
this page... but it's pretty basic.

[[[ code('de2aba68d3') ]]]

Move over and try it again. I actually didn't even need to refresh. Now type. Ah!
*Gorgeous*! And you can click any of these to see that product.

Look back at the Stimulus controller. This whole feature took about 15 lines of
JavaScript, only a couple lines of PHP and a very simple template that renders
the results.

But it's not *perfect* yet. If I click off of the search area... it doesn't go
away! We really need that to close. How can we do that? The easiest way is by
leveraging a third party library that's full of behaviors for Stimulus, like
"click outside" and "debounce". That's next.
