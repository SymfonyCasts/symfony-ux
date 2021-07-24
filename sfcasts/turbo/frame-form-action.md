# Frames & Form "action" Attributes

Something isn't right. We *can* click this "edit" link to inline-load the product
form into the Turbo Frame. But when we save, something weird happens.
Watch the console closely down here. Whoa! It was fast, but it looked the Ajax
request failed! And then, the whole page reloaded?

Time to put on our detective hats! Let's start by getting more information
about why the form submit failed. Click any link on the web debug toolbar to jump
into the profiler... and then click the "last 10" link to see the last 10 requests.

Ah, here! A 405 error. Open the profiler for that page:

> No route found for POST /product/1: Method not allowed

Wait: look at the URL. That is *not* the right URL! The form should submit to the
product admin area, which... if you navigate there, looks like this:
`/admin/product/12/edit`. But the form *actually* submitted to the public product show
page. Why?

Close this tab and hit edit again. Actually, refresh, hit edit and inspect
element on the form. Ah ha! The form element does *not* have an `action` attribute.
Normally this is fine! If you go to the product admin page and click to edit a
product, the form doesn't have an `action` attribute here either. That's ok because
when a form doesn't have an `action` attribute, it tells your browser to submit
to the URL that it's currently on. For this page, that's perfect.

But when we're on the public product show page... and we load the same form,
having that missing `action` attribute is *not* okay: our browser incorrectly thinks
it should submit to `/product/1`.

Here's the takeaway: if you're planning to load a form into a `turbo-frame`, that
form *does* need an `action` attribute. We can't be lazy like we normally are.

## Setting the Form action

We can set the action attribute in a few places, but I like to do it in the
controller where we create the form. Open the controller for the product admin area:
`src/Controller/ProductAdminController.php`. Right now we're only dealing with
the edit page, but I'll set the action on both the new *and* edit actions to be
safe. Add a third argument to `createForm()` and pass an option called `action`
set to the URL to *this* action: `$this->generateUrl('product_admin_new')`.

Now scroll down to the one that we really care about: the edit action.
Same thing here: pass a third argument with `action` set to
`$this->generateUrl('product_admin_edit')`... but this needs an `id` wildcard
set to `$product->getId()`.

[[[ code('07ef85d1c7') ]]]

Time to give this a try! Refresh the page, click edit, change the title and
submit the form. Very nice... kind of. If you scroll down to find this product...
yes! It *did* update the title!

But, as we can see, it redirected to the product admin list page, not the product
show page. When we click this "edit" button, that *does* load the form into the
Turbo frame. But then, because the frame has `target="_top"`, when we submit the
form, it submits to the *whole* page and *navigates* the whole page. That's why
hitting save redirects us to a totally different page.

## Redirecting to the Product Show Page

And that's maybe okay: this is already a better experience than when we started.
But we could make it a bit more awesome by redirecting back to the public
product show page. Let's try that: I'll do it in just the edit action. On
success, change the index route to `app_product` - the route for the show page -
and pass this the `id` wildcard that it needs.

[[[ code('aabc2ed2a5') ]]]

Let's see how this feels. Open up the floppy disk public show page, hit edit, change
the title and submit. That's very nice!

Edit the product again, but empty the title so that we fail validation. When
we submit now, this navigate us away from the show page and puts us in the admin
section. That makes complete sense: we know that the form is *still* submitting
to the full page, not to the frame. And so, again, this is probably okay! We
should probably stop and say "good enough!".

## Submitting the Form in the Frame

Or... we could *also* make the form *submit* in the frame.

To do this, we have two options. Over in `show.html.twig`, we have
`target="_top"` on the `turbo-frame`. The first way that we could make the form
submit to the frame would be to remove this target so that *everything* navigates
inside the frame. Of course, if we did that, we would need to make sure to add
`data-turbo-frame="_top"` to any links or forms that *should* target the full
page.

The other option is to leave the `target="_top"` and then, on *just* the product
form, add `data-turbo-frame="product-info"`.

For me, the *best* option is still... not totally clear. Is it better to add
`target="_top"` on the frame and then target the frame on individual links and
forms? Or should we leave `target="_top"` *off* the frame and add `target="_top"`
to the individual links and forms that need it?

I don't have a perfect answer. But my rule of thumb is to determine this based on
the *main* purpose of a frame. In this case, I would expect *most* links to
navigate the whole page, so the `target="_top"` on the *frame* feels safer.

So let's go change the target of *just* the form. The edit page template is
`edit.html.twig`, but the form lives in `_form.html.twig`. Pass a second argument
to `form_start` with an `attr` variable set to an object. Inside *that*, set
`data-turbo-frame` to `product-info`.

[[[ code('2a150f20ba') ]]]

Let's try the flow! Refresh. We have a `turbo-frame` with `target="_top"`...
but inside, an edit link that specifically targets the frame. When we click this,
the new form is *still* in the frame with `target="_top"`... but it *also* targets
the `product-info` frame.

Thanks to this, if we empty the title and submit... woohoo! That keeps us
on the page! That submitted *into* the frame. And if we put the title back,
change it and submit. Beautiful!

Next: when we submit a form inside a frame... and that request redirects to
*another* page, what happens? Does that redirect the entire page and change the
URL in the address bar? Or does it *only* update the frame? Let's find out and
fix a related bug with our new inline edit frame system.
