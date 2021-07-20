# Frame-Powered Inline Editing

Make sure you're logged in... and then head over to any product page. We
already have a product admin section. And since we *are* an admin - lucky us - we
can use it to edit any product. To make life cooler for admin users, let's add an
edit link right on the public show page.

Easy enough: open the template for this page - `templates/product/show.html.twig` -
find the `h1` and move it onto multiple lines. Then add if `is_granted('ROLE_ADMIN')`
and `endif`. Inside, we can create a boring anchor tag that points to the edit page:
`path('product_admin_edit')` with `id` set to `product.id`.

Oh, but I'm going to put this onto multiple lines in a slightly different way...
so that we can cleanly give this a few classes. For the text, say "Edit".

Nothing magic yet. When we refresh, there's our link... a fantastically boring edit
link. Thanks to Turbo Drive, clicking it feels pretty good. And with a bit more work,
we could add a link back to the public show page. Heck, we could even attach a query
parameter when we click this edit button - like `?from=` - and use that on the admin
page to dynamically link back to the admin index page - like it is now - *or* back
to the product show page *if* that's where we originally came from. We could even
go further and also make the form *redirect* back to that page after success.
My point is, thanks to the smoothness of Turbo Drive, there are many ways that
we could make this process *even* smoother simply by writing a little Twig & PHP
code.

But instead of doing any of those, let's progressively enhance this in a different
way: by making the edit link load the form *right* onto the public show page.
That sounds like a job for a turbo frame!

## Adding the turbo-frame

Head back to the template and scroll to the top. Okay: we have a `col-4` and
a `col-8` - that's the left and right sides of this page. Our new mission is to
wrap that *entire* area in a `turbo-frame` so that it can be *replaced* by the
edit form. So basically, we need a frame right *inside* of this "row" div.

Say `<turbo-frame id=""` and call it, how about `product-info`. I'm also going to
add a `target="_top"` so that everything inside, at least for now, will behave
*completely* normally: as if there were *no* frame.

Take the `turbo-frame` closing tag and... put it all the way down here: I think
this is the right spot.

Let's see how things look so far. Refresh and... whoa! That *completely* messed
up our styling! Why? Inspect element on this area. The problem is that we added
an element *between* the row and the columns... and with CSS Flexbox, sometimes the
direct relationship between elements *is* important. By putting this `turbo-frame`
in the middle, we angered the Flexbox gods!

## Using turbo-frame as a Normal Element

So what can we do? One obvious idea is to move the `turbo-frame` *around* the
`row` div so that we don't interrupt the row-column relationship. That *would*
work.

But... `turbo-frame` is just a normal HTML element... so we could also *change*
the `row` element from a `div` to a `turbo-frame`!

Check it out: delete the `turbo-frame` closing tag. Then, on top, copy the guts
from the `turbo-frame`, change the `div` to a `turbo-frame` and re-add `id`
and `target`. Down on the closing tag, ah nice! PhpStorm already changed that
for me.

When we refresh now... it looks good again! But because our frame has
`target="_top"`... the frame doesn't *do* anything yet: the edit link still
navigates the *entire* page.

To fix that, find the link... which is down here... and make it target the frame:
`data-turbo-frame="product-info"`.

Will this work? Not *quite*... and you may remember why. Refresh and click Edit.
The whole area disappeared! And we see our favorite error in the console:

> Response has no matching `<turbo-frame id="product-info">` element.

Of course! The page that that we're navigating to - the product admin edit page -
*must* also have a `product-info` frame.

The template for that product admin edit page lives at
`templates/product_admin/edit.html.twig`. The actual *form* lives inside
`_form.html.twig`. So we *could* add the `turbo-frame` here around the form. But
I kind of *do* want the "edit product" `h1` and the "delete form" button to
*also* be loaded when we click "edit". So let's add the `turbo-frame`
right here.

After the back button - because we don't want to include that - add
`<turbo-frame id="product-info">`. I'm also going to add `target="_top"` here to
guarantee that, by default, any links or forms inside here continue to behave
like normal when we navigate directly to the product admin page.

Add the closing frame tag and indent everything.

That should do it! Refresh the page... and click edit. Sweet! We see the form
but we're *still* on the product show page!

So far, this has been pretty easy: a perfect use-case for Turbo Frames. Let's
take a victory lap!

Except... something isn't quite right. If we change the title and submit the
form... woh! That looked like a full page refresh! Let's find out what's going
on next, fix it, and complete our inline editing destiny!
