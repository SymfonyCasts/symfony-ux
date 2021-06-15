# Form 422 Status & renderForm()

We already know that Turbo Drive also works for form submits. To prove it, head
to the login page and log in as `shopper@example.com` password `buy`... using
these handy cheating links that are powered by a Stimulus controller.

Submit and... yep! That loaded via Turbo! Now head to the admin area. This is
a generated CRUD for creating, editing and deleting products. Click to edit a product
and... make it look a bit more exciting with some exclamation points. Hit enter
to submit and... that worked too! It submitted via Ajax and redirected back to
the list page. There are my exclamation points!

## Failing Validation... Doesn't Work?

But now, let's make a change that will fail *validation*: clear out the name field
and... hit Update. Uh... nothing happened? Check out the console. Ooh.

> Form responses must redirect to another location.

Okay. Part of what makes Turbo so cool is that you get the single page app experience
without making *any* changes to your server code. But the *one* big exception to
that rule is forms. Don't worry: the change we need is minor... it's really
an *improvement* on our code. And the change is *especially* easy in Symfony 5.3.

## The 422 Status Code

Let's go find the controller for this page: it's in
`src/Controller/ProductAdminController.php`... and `edit` action. Here we go. In
short, if the form has a validation error, we need to return a 422 status code
instead of a 200 status code.

[[[ code('8fbf4ee9b1') ]]]

Right now, both when the page originally loads *and* when we have a validation error,
we return `$this->render()`, which sets a 200 status code. Using a 422 status
code when there's a validation error is actually more correct. And it tells Turbo
that the form submit failed and it should re-render the page with the new HTML.

So how can we set the status code on the response that `$this->render()` creates?
The easiest way is by passing the little-known third argument: a `Response` object
that the render function will put the template content *into*. Say
`new Response()` - get the one from `HttpFoundation` and pass `null` for the content,
because that will be replaced by the template HTML. For the status code, we can't
use 422 all the time because we don't want that status code when we simply navigate
to this page. So use the ternary syntax: if `$form->isSubmitted()` and
`$form->isValid()`, I mean if *not* `$form->isValid()`, then use 422. Else use 200.

[[[ code('2c4b55466d') ]]]

That's it! Back over at the browser, we don't even need to refresh. Hit update
and... voilà! We see the validation error! Let's put the content back...remove my
exclamation points, hit enter again and... it works.

## Turbo Handles Redirects too

By the way, on success, in our controller, we are redirecting with a 302 status
code, which is perfect! That *is* what you should do after a successful form
submit.

The interesting thing is that... Turbo correctly handled this!

Check out your network tools. Let's look closely at what happened when we submitted
the form. This request is the `POST` request from the submit. It returned
a 302 redirect. When an Ajax request returns a redirect, your browser automatically
*follows* it. What I mean is: in this case, our browser made a *second* Ajax
request to the redirect URL - which is the product list page.

At this point, Turbo did something really smart: it *detected* that this 2nd Ajax
request happened due to a redirect. It then used the HTML from that Ajax call
to update the page like normal *and* it changed the URL in our browser to match
the redirected URL. In other words, redirects work *perfectly* with Turbo Drive
out of the box.

Now if you look at the Turbo documentation, they will tell you to return a 303 status
code instead of 302 when redirecting after a form submit. But both work *exactly*
the same. 303 is... *technically* a little bit more correct... and so more
hipster... but it really doesn't matter.

## Symfony 5.3's renderForm() Shortcut

Okay, back to this 422 status code fix. If you're using Symfony 5.3 - and I am -
then fixing this is even easier thanks to a new `renderForm()` controller shortcut.
Here's how it works: change `render()` to `renderForm()`. Then, remove the
Response object.

That's it! Well, that's *almost* it. Also remove the `createView()` call on the
form.

[[[ code('deb52f4788') ]]]

Let's break this down. The `renderForm()` method is *identical* to `$this->render()`
except that it loops over all of the variables that we pass into the template.
If any of them are a `Form` object, it does two things. First, it calls
`createView()`, which is just a really kind thing for it to do: we don't have to
call that ourselves anymore. Second, if the `Form` has been submitted and it's
invalid, it changes the status code to 422.

So all we need to do now is repeat this change everywhere else in our app... which
is kind of boring, but simple! Copy `renderForm()` and scroll up to the
`new` action. You can actually see that we did the 422 logic in the first tutorial
because we wrote some custom JavaScript that - like Turbo - needed to know if a
form was simply rendering or if it had a validation error.

Change this to `renderForm()`, we don't need `createView()`... and we don't need
the third argument at all. Much nicer.

[[[ code('4bcacbdbf8') ]]]

Let's clear the tabs and go to `CartController`. There are two spots inside here.
I'll search for `createView()`.

[[[ code('a760e674d7') ]]]

Cool: `renderForm()`, then take off `createView()`. For the next one... it's exactly
the same. I'll take a big sip of coffee... and speed through the rest of the
controllers: `CheckoutController` has one spot, `ProductController` has two spots,
one of which renders two forms including a conditional `reviewForm` that can be
simplified, `RegistrationController` has one spot... and `ReviewAdminController`
has two spots.

[[[ code('e8bae87367') ]]]

[[[ code('a232e916d6') ]]]

[[[ code('161df1af5c') ]]]

[[[ code('61442c74aa') ]]]

Phew! Good, straightforward, boring work. The only form we *didn't* need to change
was the login form. That's because the login form works a bit differently than other
forms on our site. On failure, it redirects and stores the error in the session.
So if we put some bad info and submit... it already works fine.

Hey! With a few small changes to our code, our site now has fully-functional Ajax
submitted forms! That's just... incredible.

Next, let's talk more about that snapshot functionality: the feature that instantly
shows you a page from cache when hitting the back button or when navigating to a
page that we've already been to. As *awesome* as that feature is - and it really
makes the site feel fast - sometimes it can take a snapshot when the page is in
a "state" that we don't want.
