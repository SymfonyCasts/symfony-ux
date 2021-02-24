# HTML-Returning AJAX Endpoint

Coming soon...

And you can see this really is the full HTML of the page, which isn't
really what we want. We need just a little fragment of HTML that would represent the
search results preview that would display below this box.

If you're using something like reactor view, you would probably return JSON from this
Ajax end point and then build the HTML and JavaScript. But this is, it's all about
building AIDS to file on the server. So that's what we're going to do. But instead of
returning the whole page, we'll return just part of the page or in the controller B
for the return statement, add an if statement, if `$request->query->get()`,
and look for that `preview` query parameter. So if it has a query parameter and that's
set up something like one, then we'll hit this if statement and here and let's return
a new template. So all of a sudden return `$this->render()` and call this
`product/_searchPreview.html.twig`. Now this can be called anything. It doesn't
matter. I put the_here, cause it's a common convention to prefix a template name
with `_` when that template only renders part of a page instead of an entire page. So
it's just there for clarity inside of here. We're just going to need a pass in the
`products`, which will already be the filter products. All right, let's go create that
template product. And I'll hit new file `_searchPreview.html.twig`
And we're not going to extend anything because we don't want to base layout.
We're just going to start putting some content here. So I'm going to
add a `<div class="list-group">` to give this a little bit of a sum list markup, and then
`{% for product in products %}{% endfor %}`

Then insider, I'm going to add an, `<a>` tag and for the `href=""` we'll use `path()`. And in
the name of the route to the product page on our site is `app_product`. And it uses
actually the `id`. So I'll say `id` set to `product.id`. And actually I'm going to put
this ATF on a multiple lines for clarity, and I'm also going to add a
`class="list-group-item list-group-item-action"`
Those Are just classes that bootstrap uses to make a nice list of format. Then inside the a
for now, let's keep it simple. We'll say `{{ product.name }}`. Oh, and to be extra
fancy, we'll even add an `{% else %}`  for. So if there are no results, we can add a
`<div class="list-group-item">`

With No results Found. Awesome to see you

This, we can go back to our page and add a little `?preview=1` to the
URL and Oh, variable products does not exist because I, my controller, I forgot my S
now much better. It looks terrible here because we have no styling on this page, but
it is working. So I have a back to the homepage. The last step in stimulus is just to
dump that return to HTML onto the page to control exactly where it goes. Let's add a
new target element. So an index that aged about twig. I want the content to go right
after the input.

So here, I'm going to add a `<div>`. I once again, use multiple lines for this can give
us a `class="search-preview"`. And I that's another class that I already have some
CSS for to make this feature look a little bit nice. And then for the target, I'm
going to add `data-search-preview-target`, and let's have this be a new
target called result. There we go. And I actually don't need any constant in here.
We're going to fill that in via stimulus or in stimulus. Let's add this target. So
I'll say `static targets = []` an array and our one new target called `result`. Then we'll
set the inner HTML on this. So I'll copy the await response dot text. And then, so
we'll say `this.resultTarget.innerHTML` equals, and then `await response.text()`
done. Let's go check it. I'm actually going to click back to
the homepage just to clear a search entirely and beautiful as I type. If I type
something crazy, I got the no results found it's alive. So let's celebrate by making
this even a little bit less, a little bit prettier.

Back over in the template, our `_searchPreview.html.twig`, instead of the name I'm
going to paste some markup here, which you can get from the code block on this page.

You can get that from the code block on this page, but it's pretty basic. Now move
over. Don't even need to refresh, but I'll do it anyways. And Oh, that is gorgeous. I
mean, you click on any of these to go there.

That's gorgeous. And we're looking at our controller. This whole feature took about
15 lines of JavaScript, only a couple of lines of PHP and a very simple template to
actually render the results. Exactly like we want it to. So next one problem with
this new feature is that when we type and then click off of this, it doesn't go away.
Really. We really need that to close. How could we do that? These easiest way is by
leveraging a third party library that I love that's full of tools for stimulus.
