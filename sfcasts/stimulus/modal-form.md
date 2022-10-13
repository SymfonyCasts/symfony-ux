# Loading a Form into the Modal

We're going to load the new product form into the body of this modal. But the
header and buttons will still come from `_modal.html.twig`.

## Making `_modal.html.twig` Customizable

Let's customize those to make more sense. Up on the header, we can say:
"add a new product".

Wait, don't do that. I want to try to make this template as reusable as possible
for *other* modals. Instead, let's say `{{ modalTitle }}`:

[[[ code('4fd5f9967c') ]]]

In `index.html.twig`, add a second argument to `include()` and pass `modalTitle` 
set to "Add a new product":

[[[ code('5665c52543') ]]]

Very nice! For the body, use `{{ modalContent }}`. That's a new variable I'm
inventing. But pipe this into the `default` filter and say "loading...":

[[[ code('d2235d73b3') ]]]

In this case, we are *not* going to pass any modal content, but you could in
other situations. We'll replace the `loading...` in a minute after we make the
Ajax call.

For the buttons, hard-code those to some new text: "Cancel" and "Save". We can
always make them dynamic later.

[[[ code('c9b4b0605e') ]]]

Let's make sure we didn't break anything. When I click the button, very nice!

## Passing the Form Ajax URL to the Stimulus Controller

To get the new product form HTML, when the modal opens, we're going to make an Ajax
call to an endpoint that will *return* that HTML.

Head over to `src/Controller/ProductAdminController.php` and find the `new` action.
This is the endpoint that we're going to make our Ajax request to: we're going to
customize this so that it's able to return the *full* HTML page *or* just the
form *partial* under a specific condition. We'll do that in a minute.

Copy the route name. As you know, I don't like to hard-code Ajax URLs in my Stimulus
controllers... and I *really* don't want to do that in this case because I want
the controller to be reusable for *other* forms on our site.

And so, we'll do what we've done several times before: pass the URL into the
controller as a *value*. Add `static values = {}` and create a value called, how
about `formUrl`, which will be a `String`.

[[[ code('5cbd3972cd') ]]]

Then, down in `openModal`, `console.log(this.formUrlValue)`.

[[[ code('9950108bce') ]]]

In the template, on `stimulus_controller`, add a second argument so that we can
pass the `formUrl` value set to `path()` and the route name: `product_admin_new`.

[[[ code('94469740c5') ]]]

Try it: refresh, click and... got it! There's the URL.

## Installing & Importing jQuery

So far, we've been using `fetch()` to make Ajax calls, which I really like. I
also really like Axios. But I've gotten some questions about how it would look to
use jQuery inside of Stimulus. So instead of showing another example of using
`fetch()`, let's install and use jQuery.

At your terminal, install it with:

```terminal
yarn add jquery --dev
```

Once that finishes, we can import that into our controller with:
`import $ from 'jquery'`.

[[[ code('c0902dbf4e') ]]]

## Making the Ajax Call

Now, down in the method, remove the `console.log()` and make the Ajax call with
`$.ajax()` and pass it `this.formUrlValue`.

That *will* make the Ajax call... but will do absolutely *nothing* with the
result. What we need to do is take the HTML from the Ajax call and, if you look
at `_modal.html.twig`, put it inside the `modal-body` element. That means we
need a new target.

Right here, add `data-modal-form-target=` and let's call this one `modalBody`.

[[[ code('cfd3bc2460') ]]]

Copy that, go back to the controller, and set this up as a second target.

[[[ code('aa32d25469') ]]]

In `openModal()` use that: `this.modalBodyTarget.innerHTML` equals,
`await $.ajax()`... because jQuery's Ajax function returns a `Promise`. And, of
course, my Webpack build is mad because we need to make `openModal()` *async*.

[[[ code('aa32d25469') ]]]

Our Ajax call *is* still going to return the HTML for the *entire* page... but
let's at least see if it works.

Move over, refresh and... awesome! It looks *totally* wrong because the endpoint
returns the full page, but it *is* working!

Before we fix that, I want to handle one small detail. In our Stimulus
controller, at the very top of `openModal`, add `this.modalBodyTarget.innerHTML`
equals `Loading...`.

[[[ code('94960f6d81') ]]]

That's a minor thing: if we open the modal twice, this will clear the contents
before we start the Ajax call... so that we don't temporarily see an *old* form.

## The Form HTML Endpoint

Ok: our *last* job is to return *only* the form HTML instead of the entire page from
the Ajax endpoint.

Over in `ProductAdminController`, inside of the `new` action, to return the full
page, we render `new.html.twig`. To return *only* the form, we can
actually just render `_form.html.twig`: this renders the `form` element.
Yea! `make:crud` already generated the exact template partial we need!

Inside `new()`, we can say `$template =` and then, to figure out if this is an
Ajax request, use `$request->isXmHttpRequest()`. If it is, use `_form.html.twig`.
Else, use `new.html.twig`. Now, render `product_admin/` and then `$template`.

[[[ code('2aed286c75') ]]]

That's it! But I do have one warning. When I make an Ajax call for a partial, I
usually append a query parameter like `?form=1` or `?ajax=1`... or add some special
header. I do *not* usually rely on `isXmlHttpRequest()`. Why? Two reasons.
First, relying on a query parameter makes it really easy to try the URL in
your browser. And second, some Ajax clients - like `fetch()` - don't send the
headers that are needed for the `isXMLHttpRequest()` method to detect it. If we
were using `fetch()`, this would return `false`.

So, it's up to you: this works with jQuery's Ajax client and is easy. If you're
using `fetch()` you'll probably want to add a query parameter when you make the
Ajax call, which you can do pretty easily inside of the Stimulus controller. We
did that earlier with `URLSearchParams`.

*Anyways*, head back to the page, refresh, click and... oh, look at that! It's
beautiful!

Oh, but there are *two* sets of buttons. It's hard to see because it's unstyled,
but there's a save button down here.

We probably want to keep the buttons in the modal footer and hide the one
that's coming from the form partial. A really easy way to do this is with CSS.
Over in your editor, open `assets/styles/app.css`. All the way at the bottom, we're
going to hide any buttons that are inside of the modal body... which has this
`modal-body` class. Do that with `.modal-body button` and `display: none`.

[[[ code('51a8c9e749') ]]]

This will hide all the buttons for *all* of the modals on your site. If that's
a problem, add a custom class on your modal HTML so you can be more targeted.

When I refresh now... and click the button... it looks perfect!

Okay: we've got our form into the modal. Now we need to make it submit via *Ajax*
inside the modal. Lets do that next!
