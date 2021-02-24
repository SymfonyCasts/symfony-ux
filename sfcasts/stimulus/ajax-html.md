# HTML-Returning AJAX Endpoint

When we type in the box, we *are* now making an AJAX request back to the server.
The response - as we can see in the log - is the *full* HTML of the homepage.
That's not what we want... but what *do* we want?

If you use something like React or Vue, your AJAX endpoints probably return JSON.
Then you use that to build the HTML in JavaScript. But we're using Stimulus! And
Stimulus is *all* about building HTML on the server.

So *that's* what we're going to do. But instead of an *full* page of HTML, we're
going to return an HTML *fragment*: *just* the HTML needed for the "search suggestion"
area.

Head over to `ProductController` and, before the return, add an if statement: if
`$request->query->get('preview)`, then we know this is the AJAX request. Inside,
return a new template: `return $this->render()` and call it
`product/_searchPreview.html.twig`.

The template could be called *anything*. The `_` at the front of the name is just
a convention: I like to use it for any templates that render only a *part* of a
page. These are sometimes called template partials.

Pass the template a `products` variable so we can render them. Be sure to add the
`s` at the end of `products`: I'll find my mistake in a minute.

Next, create the template. In `product`, add a new file: `_searchPreview.html.twig`.
But we're not going to extend anything because we *don't* want the base layout.
We're just going to start rendering content here. I'll add a
`<div class="list-group">` to give this some markup so it looks good in Bootstrap.
Then `{% for product in products %}` and `{% endfor %}`.

Inside, I want each result to be a link so use an `a` with `href=""` `{{ path() }}`
and the name of the route to the product page, which is `app_product`. This route
has an `id` wildcard. So set `id` to `product.id`. I'm also going to add a few more
classes for styling... then inside the a, start with the simple `{{ product.name }}`.

Oh, and to be *extra* fancy, add an `{% else %}`. If there are no results, render
a `<div class="list-group-item">` with "No results found".

I love that: the entire search preview HTML in a simple template.

To see this, go back to the homepage but add a `?preview=1` to the URL. And... oh!
Variable `products` does not exist. Because... in the controller, I forgot my "s".

Now... much better. I mean, it looks *terrible* here, but that's just because this
page doesn't have any CSS. Head back to the homepage.

## Adding the AJAX HTML to the a Target

The last step in Stimulus is to dump the HTML from the AJAX endpoint onto the page.
To control exactly where it goes, let's add a new target element. In
`index.html.twig`, I want the content to go right below the `input`. Add add a
`<div>`... with `class="search-preview"`. That's a class that already lives in our
CSS that will help style things.

To make this a target, add `data-search-preview-target=""` and... call the new target,
how about, `result`. We don't need any content.

Over in our Stimulus controller, setup the target: `static targets = []` an array
with `result` inside.

Then, below, set the inner HTML on this. Copy the `await response.text` and replace
it with `this.resultTarget.innerHTML` equals `await response.text()`.

Done! Let's go check it! I'll click to go back to the homepage just to clear the
search entirely. Moment of truth: type. Ha! It works! And if I type something
nutty, no results found. It's alive!

## Making the Search Preview Prettier

Let's celebrate by making this even prettier.

Back over in the template - `_searchPreview.html.twig` - instead of rendering just
that name, I'll paste in some markup. You can copy this from the code block on
this page... but it's basic stuff.

Move over and try it now. I actually didn't even need to refresh. Now type. Ah,
*gorgeous*! And you can click on any of these to go there.

Look back at the Stimjulus controller. This whole feature took about 15 lines of
JavaScript, only a couple lines of PHP and a very simple template to render the
results.

But it's not *perfect* yet. If I click off of the search area... it doesn't go
away! We really need that to close. How can we do that? The easiest way is by
leveraging a third party library that's full of behaviors for Stimulus, like
"click outside" and "debounce".
