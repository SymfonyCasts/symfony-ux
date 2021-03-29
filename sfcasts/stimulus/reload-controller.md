# Reload Controller

Coming soon...

If we add, if we add a new product with valid data, we're going to start selling
solar powered flashlights. It does work, but it's not very obvious. You don't see the
new product unless you reload the page. We could on success. Add some success message
to the top of the page. That's actually a really great idea, but even if we did, we
should also reload the product list. So the user can see the new item. If you think
about it, being able to make an Ajax call to a URL and use the HTML from that to
replace the HTML of an element is kind of a common thing to do. Heck heck, we
basically already do this on the cart page. After removing an item, we make an Ajax
call to get the fresh cart list. So here's the plan. Instead of adding more logic to
our modal form controller, which would make it less reusable, let's create a second
controller that will make an Ajax call to reload the product list area.

After a new product is successfully added to be extra cool. We're going to make this
new controller generic so we can reuse it anywhere like on the cart page, head into
`templates/product_admin/index.html.twig` the template for the product list
page. Let's see. The thing that we need to refresh after submit is really just this
table, but I'm going to add the new `data-controller` to this top level. Diff I'll
break it on the multiple lines. Find my super old at typo on container. No wonder it
doesn't look very good.

And then add curly curly `stimulus_controller()`, and let's call our new
controller. `reload-content`. Why are we adding it here? Well, in order for
this new controller to know when a form was submitted successfully, we're going to
use an old trick. We will dispatch it an event from `modal-form` controller to listen
to that event. Our second controller needs to live on an element that's around 
`modal-form` controller and around the `<table>` that needs to be updated. Let's go create this
controller and `assets/controllers/` create `reload-content_controller.js`
let's steal the entire `cart-list_controller` since it's so similar, copy that,
close it and paste, But then add a `connect()` and method with `console.log()`.

Are we on a refresh icon?

Let's try it. Refresh the page, check out the logs and got it. We are connected to be
able to refresh the contents of this table. We need an end point on our site that
returns just that table to do that. We need to isolate this into a template partial,
like we've done before. Copy the entire table, delete it. And then in this same
directory product admin create a new file called `_list.html.twig`
paste the table inside back in `index.html.twig` include that with
`{{ include('product_admin/_list.html.twig') }}`, if we refresh now so
far, so good, nothing changes just like we did with the new product form, the
simplest way to create the end point that we'll return just that table,

Okay.

Is to make the index action capable of returning a full page of HTML like it's doing
right now or just that `_list` template. Partial. Let's try the same trick
as, as before first, add a `Request` argument, the one from HttpFoundation, and then
we'll say `$template = $request->isXmlHttpRequest()`. If we are, then we will use 

`_list.html.twig` else. We'll use the template we're using now,
which is `index.html.twig` blow. We can replace the `index.html.twig`
with `$template`. Awesome. Now copy the route name to pass this into our controller.
We'll use a value start by defining that in our controller, um, set of `cartRefreshUrl`
let's just call this `url`. It will be a `string`. I'll also
remove the `connect()` method. Then pass that in via the template. So up at the top on a
second argument to reload controller, and we'll say `url:` set to `path()` and then
`product_admin_index`. Lovely. Before we use that to make the Ajax call. When that age
has called finishes, we're going to need to know where we should put the new HTML.
Let's wrap the table in a new div And make it a target `data-reload-content-target=""`
and let's call on your target `content`. Let's go set that
target up the top of the class as `static targets = []` with `content`
inside, and now make the Ajax call rename this method to `refreshContent` We're not
using this method to anywhere, but we will soon. And let's see, we don't need to add
this class. The value changed to `this.urlValue`. And instead of using
`this.element`, we'll use `this.contentTarget`

Phew,

Nobody is calling refresh content yet, but if we did it should automatically make the
ajax call and replace the table with the new HTML. How should we call this method?
Well, we need to call it

Are the modal form controller

Finishes submitting successfully. So next let's dispatch a custom event from 
`modal-form_controller` so that we can reload the content after it's successful. Once we're
done, we'll prove that our new controller is reusable by completely replacing the
`cart-list_controller` entirely by this new one.

