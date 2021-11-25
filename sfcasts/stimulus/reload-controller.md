# Ajax Element Reloading Controller

If we add a new product with valid data - we're going to start selling solar powered
flashlights... I got a *great* deal on them - it *does* work... but it's not very
obvious. You don't see the new product until you reload the page. We could, on
success, add a message to the top of the page. That's actually a really great
idea. But even if we did, we should *also* reload the product list so the user
can see the new item.

If you think about it, being able to make an Ajax call to a URL... and then use
the HTML from that to replace the contents of an element is... kind of a common
thing to do. Heck, we basically already do this on the cart page! After removing
an item, we make an Ajax call to get the fresh cart list.

So here's the plan: instead of adding more logic to our `modal-form` controller,
which would make it less reusable, let's create a *second* controller that will make
an Ajax call to reload the product list area after a new product is successfully
added. To be *extra* cool - cause we *are* extra cool -  we're going to make this
new controller *generic* so we can reuse it *anywhere*... like on the cart page!

## Bootstrapping the New Controller

Head into `templates/product_admin/index.html.twig`: the template for the product
list page. Let's see. The area that we need to refresh after submit is really just
this table. But I'm going to add the new `data-controller` to the top level div.
Let's break it on multiple lines... fix my *super* old typo on `container` - no
wonder the page didn't look very good - then add `{{ stimulus_controller() }}`.
Call the new controller, how about, `reload-content`.

[[[ code('b64c982205') ]]]

Why are we adding it here... and not directly around the table? Well, in order
for the `reload-controller` to know when a form was submitted successfully,
we're going to use an old trick: we will dispatch an event from `modal-form`
controller. To listen to that event, `reload-controller` needs to live on an element
that is around both `modal-form` controller *and* the `<table>` that it needs to
update.

Let's go add the new file. In `assets/controllers/` create
`reload-content_controller.js`. Steal the entire `cart-list_controller` - since
it's so similar - close it and paste. Add a `connect()` method with
`console.log()` a refresh icon.

[[[ code('e93b5ee821') ]]]

Let's give it a go! Refresh the page, check out the logs and... we are connected!

## Creating the Partial Endpoint

To be able to refresh the content of this table, we need an endpoint that returns
*just* the table. To do that, we need to isolate the table into a template partial...
like we've done before. Copy the entire table, delete it and then, in this same
directory - `product_admin` - create a new file called `_list.html.twig`. Paste the
table here.

[[[ code('6d5c4c4aed') ]]]

Back in `index.html.twig` include that with
`{{ include('product_admin/_list.html.twig') }}`.

[[[ code('6e046b79c6') ]]]

If we refresh now... so far so good: nothing changes.

Like we did with the new product form, the simplest way to create an endpoint
that will return just the table is to make the `index` action capable of returning
a *full* page of HTML - like it's doing right now - *or* just the `_list` template
partial.

Let's try the same trick as before. First, add a `Request` argument - the one from
HttpFoundation - and then say `$template = $request->isXmlHttpRequest()`. If this
*is* an Ajax request, use `_list.html.twig`. Else, use the template we're using
now, `index.html.twig`. Below, we can replace the `index.html.twig` with `$template`.

[[[ code('90f51a038c') ]]]

Awesome! Now, copy the route name so we can pass this into our controller. We'll
use a value. Start by defining that in our controller. Or, really, just rename
`cartRefreshUrl` to just `url` and it will be a `String`. I'll also remove the
`connect()` method.

[[[ code('74a0576cec') ]]]

Pass the value in via the template. So up at the top, on a second argument to
`stimulus_controller()`, set `url` to `path('product_admin_index')`.

[[[ code('d993fe5572') ]]]

Lovely! Before we use that to make the Ajax call, when that call finishes,
we're going to need to know *where* we should put the new HTML. Let's wrap the table
in a new `div` and make it a target: `data-reload-content-target=""` and...
call it `content`.

[[[ code('a13f839fb4') ]]]

Go set up that target. At the top of the class, add `static targets = []` with
`content` inside.

[[[ code('951d23d04c') ]]]

*Now* let's make the Ajax call. Rename the method to `refreshContent()`. We're not
*using* this method anywhere... but we will soon. Let's see: we don't need to add
this class... the value changed to `this.urlValue`... and instead of using
`this.element`, use `this.contentTarget`

[[[ code('47da3b912d') ]]]

As I mentioned, nobody is *calling* `refreshContent()` yet. But if they did, it
*should* make the Ajax call and replace the table with the new HTML. So... how
*will* we call this method?

Well, we need to call it after the `modal-form` controller finishes submitting
successfully. So next: let's dispatch a custom event from `modal-form_controller`
and listen to it so that we can reload the content after it's successful.

Once we're done, we'll *prove* that our new controller is reusable by *completely*
replacing the `cart-list_controller` with the new one. Yay for less custom code!
