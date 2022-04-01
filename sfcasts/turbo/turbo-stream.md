# Turbo Streams

The third and final part of Turbo is Turbo Streams. These are fun!

## Hello Streams

Turbo Streams are a way to return instructions on updating *any* element on the page.
And there are two main use cases. First: you're submitting a form inside a frame
and, on success, you want to update an element that lives *outside* of that frame.
Second: you need a way to update something on your page asynchronously but *without*
"polling" - where you make an Ajax call every few seconds to *constantly* check
for updates. For example, if you were building a chat app where you want a new
message to render as *soon* as the other person sends it, Turbo Streams can help.

Streams are another way to enhance your page. So they're an extra feature - like
frames - that you can choose to add whenever you want to do something special.

For example, go to a product page and scroll down. See this review form? It lives
in a frame and it works awesome. The frame surrounds both the form *and* the
list of reviews above it... we can see that if we inspect the element.

This means that, when we submit, both the form *and* the review list updates. That
gives us a fresh form *and* we see the new review. Awesome!

But scroll up to the product details where we show the number of reviews and the
average review. These details did *not* update when we submitted the review.
Watch: if I refresh the page, the 8 reviews... becomes 9.

This area lives *outside* of the `product-review` turbo frame. So we *can't* update
it via the frame. But we *can* update it using Turbo Streams... because... that's
their whole purpose! To update *any* element on the page from the server.

## Creating & Returning our First Stream

Here's how it works. Step one: find the area on the page that you want to update
and give it a unique ID. The template for this page lives in
`templates/product/show.html.twig`. Let's see... here are the details. On the
`<div>` around this, add, how about, `id="product-quick-stats"`.

[[[ code('6aac35fa32') ]]]

Now open `Controller/ProductController.php` and find the reviews action. This is
the page that we submit to when we post a new review. Down here, instead of
redirecting on success, let's do something different, let's render a new template.

I'll leave the old logic for now. But above this, return `$this->render()` to
render a template called `product/reviews.stream.html.twig`. We don't need to pass
any variables yet, but I'm going to pass an empty second argument because we *do*
need to pass a third argument: a `new TurboStreamResponse()`.

***TIP
In `symfony/ux-turbo` 2.1 and higher, this code has changed. The `TurboStreamResponse`
is no longer needed, but a `setRequestFormat()` call is:

```php
$request->setRequestFormat(TurboBundle::STREAM_FORMAT);

return $this->render('product/reviews.stream.html.twig');
```

***

[[[ code('e75b86c21e') ]]]

Okay first: see the `.stream` in the template name? Yep. That has *no* technical
effect. It's just a naming convention because this template will have a special
format. Second, by passing a `TurboStreamResponse` as the third argument, we're
telling Symfony to render the template like normal, but to put the HTML into *this*
response object instead of a normal response object. I'll show you what that does
in a minute.

Alright: let's go create the template. In `product/`, create the file:
`reviews.stream.html.twig`. These stream templates contain HTML, but... in a special
format that describes the element on the page that you want to change, *how*
you want to change it and the HTML to use.

It always starts with a `<turbo-stream>` element. This needs two attributes, the
first is `action=""` set to, in this case, `update`. We'll talk more about this
in a minute. The second is `target=""` set to the id of the element on the page
that should be updated. I'll copy `product-quick-stats` and paste that here.

Inside of the `<turbo-stream>`, we always have a `<template>` element. This...
doesn't really mean anything... you just always need it. Inside of *that*, put
the HTML. Start by hardcoding something.

[[[ code('0de8e623f4') ]]]

Ok, let's see this in action! Find your browser, refresh and scroll down. Add
a review and... submit!

Hmm. Nothing happened? It looks like the form is kind of stuck submitting. But
scroll up to the quick stats area. Woh! There's our new HTML!

## How a Turbo-Stream Works Under the Hood

*This* is a turbo stream in action. Check out the network tools and find the
POST Ajax request for the form submit - this one on the bottom. As expected, when
we submit the form, it now returns this special `<turbo-stream>` HTML. But check
out the headers on the response. There it is: the response has a
`Content-Type` header set to `text/vnd.turbo-stream.html`. That's important.

Here's the whole flow of what just happened. In our controller, we render the
`reviews.stream.html.twig` template and put it into a special `TurboStreamResponse`.
That response object causes a special `Content-Type` header to be set on the
response: `text/vnd.turbo-stream.html`.

*That's* important because, as soon as we set up Turbo on our site, like the
first thing we did at the *very* beginning of this tutorial, turbo added an event
listener to the `turbo:before-fetch-response` event. In `turbo-helper.js`, we
have our *own* listener for this event, which is dispatched after *any* Ajax
call that Turbo makes has finished.

*Anyways*, the moment you install Turbo, it adds a listener to this event that
looks at the response for *every* Ajax call and checks to see if the `Content-Type`
starts with `text/vnd.turbo-stream.html`. If it *does*, instead of handling the
response normally - like rendering it into the `turbo-frame` - the response is
passed to the Turbo Stream system... which reads this and updates the
`product-quick-stats` element.

But... that's *all* it did. The reviews frame, down here, did *not* update.
We'll talk about that in a minute.

## Other Stream "Actions"

In addition to the `update` action, there are a bunch of other actions that you can
use to update the page. In the Turbo docs, go to the Reference section and select
Streams. So you can `append` an element to the end of an existing element,
`prepend`, `replace` an entire element, `update` the HTML *inside* an element,
which is what we're doing, `remove` an element or even place an element `before`
or `after` another element.

You can also target using a CSS selector - like a class name - instead of an `id`.

Next: let's improve our stream so that it updates the quick stats area with the
*real* new information. And after submitting a new review, we *still* need
the reviews area - the form and list - to update. We can *also* handle that
inside the same stream.
