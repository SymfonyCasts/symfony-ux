# Frame Redirecting & Dynamic Frame Targets

It was subtle, but we just saw one important property of Turbo frames. When
we submitted this form successfully, it submitted to the edit action inside of
`ProductAdminController`. This code handled the form submit and, because it was
successful, it redirected to the public product show page.

It turns out, if you submit a form in a frame and that Ajax request *redirects* to
another page, Turbo does *not* follow the redirect and navigate the entire page.
Well, let me be more clear.

## Redirects Do Not Move the Entire Page

Check out the network tools. This POST request was for the *unsuccessful* form submit
we did a minute ago: the one that failed validation. This second request was
for our *successful* form submit. And you can see that it returned a 302 redirect.
When Turbo sees a redirect, it *does* follow it in a sense... it makes a second
Ajax call to the redirected URL: the product show page. This is also how Turbo
Drive works... but with one key difference: after making the second Ajax request,
a Turbo frame does *not* navigate the entire page and update the URL in our browser
to match the redirected URL.

Nope, because we submitted to a turbo frame, it reads the HTML of this redirected
page, finds the `product-info` frame and loads just *that* into the frame.

This is... kind of hard to see in *this* case, because it's redirecting back to
the URL that is *already* in our address bar. But this *is* the behavior: if
you submit a form inside a frame, even if that request redirects, all navigation
will *stay* inside the frame.

Actually, there is a *super* obvious place where we can see this. Go to the product
admin area and edit a product. Like with the show page, the frame is targeting
`_top` but the form is targeting `product-info`. If we clear out the title and
submit, it submits to the frame and looks fine.

But if we put the title back, change it and submit, watch what happens. Ah!
Frankenstein page! Half of the public product page just exploded onto this
admin page!

Unfortunately... the turbo frame is doing *exactly* what we're asking it to do.
Look at the network tools... and scroll up a bit. We submitted successfully to the
edit page and that redirected to the public show page. Then, because we're submitting
in a turbo-frame, the frame found the `product-info` frame *on* that page - which
is all this product info - grabbed it, and popped it right here.

In the admin area... this is *not* what we want. And things are getting a bit
complicated as a result of us *really* pushing for the best possible user experience.

So let's stop and think. When we load the form from the product show page and hit
edit, we *do* want this form to submit *into* the frame. But when we load that same
form in the product admin area, we kind of just want this to behave like *normal*,
by submitting *to* the entire page. Could we do that? Could we make the same form
behave *differently* based on the situation? Totally!

## The Turbo-Frame Request Header

Head to `ProductAdminController`'s edit action. Whenever turbo is navigating
inside a frame, it sends an extra header called `Turbo-Frame` with the name of the
frame. So when we click the edit link from the product show page, that Ajax request
*will* add a `Turbo-Frame` header. You can see it all the way down here under request
headers... there it is: `Turbo-Frame: product-info`.

But when navigate directly to the product admin area and look at *that* Ajax request,
down here, there is *no* `Turbo-Frame` header. This means we can detect whether
a request is being loaded inside a turbo frame from inside of Symfony!

Back in the controller, when we render the template, pass in a new variable called
`formTarget` set to `$request->headers->get('Turbo-Frame')`. If that header was
*not* sent, add a second argument to default this to `_top`.

[[[ code('df81a0897d') ]]]

Now in `_form.html.twig`, instead of setting the target to `product-info`, use
the `formTarget` variable. And because this template is *also* included on the
new product page... and we're *not* setting this variable there, code defensively
by defaulting it to `_top`.

[[[ code('2ac71f840b') ]]]

I *think* that's going to do it! Refresh the product admin page and hit save.
Beautiful! That submitted to the entire page and *redirected* the entire page.
Now click edit, empty the title and hit enter. Yes: this *still* navigates inside
the frame. If you inspect element on the form, you can see that it *does* have the
extra `data-turbo-frame` attribute set to `product-info`.

So, inline product admin form done! I included this example both because it's really
cool to load the form inline... but also because it shows a situation where turbo
frames *can* get a bit complex. It's up to you to balance the added complexity
with the user experience that you want.

Next: what about using a turbo frame inside of a modal? After all, you often want
navigation - like links and form submits inside of a modal - to *stay* inside
of that modal... which is what turbo frames are really good at. So let's
transform this modal into a turbo-frame powered modal.
