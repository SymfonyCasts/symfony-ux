# Forms

Coming soon...

As I mentioned, turbo drive also works for form submits to prevent head to the login
page and log in as shopper, at example.com password buy and use these handy links to
fill all that in for you. Okay, cool. Now had to be admin area. This is an area where
we can, uh, generated admin for the products on our site. Let's click to edit a
product and make it a bit more exciting with some exclamation points I'll hit, enter
to submit,

And it works

Redirects over to the list page. And there are my ex and I some points, but now let's
make a change that will fail our validation. So I'll take out the name and leave it
empty and go down to hit update. Uh, nothing happened. Okay. Check out our console.
Ooh, form responses. That must redirect to another location. Okay. For the most part,
what part of what makes turbo so great is that you get the single page app experience
without making any changes to your server code. But the one big exception to that
rule is forms. Don't worry. The changes we need to make are minor. They're really an
improvement on our code and they are especially easy and Symfony 5.3. All right,
let's go find the controller for this page. That is source controller, product admin
controller, and the edit action. Here we go. In short, if there's a validation error,
we need to return a four 22 status code instead of a 200 [inaudible]

Right now, both when the page originally loads. And when we have a validation error,
we return this error render, which returns a 200 status code using a four 22 status
code. When there's a validation, error is actually more correct. And it tells turbo
that the form submit failed and it should rerender the page with new HTML. Okay. So
how can we set the status code on the response that this error render creates the
easiest ways to actually pass a third argument to render the little known third
argument is a response object that the render function will put the template content
into here. We can say new response, get the one from HDB foundation asked no for
content, because that will be replaced in a second. And then for the status code, we
can't use four 22 all the time because we don't want that status code went in
originally renders, let me navigate to the page. Suis alternaria is index form.->is
submitted and form->is valid.

Or of course, I mean, if not form valid, then four 22, L's 200, that's it. Back over
to the browser. We don't even need to refresh. I'll hit update and util. Now we have
our validation error. Let's put the content back, I'll get rid of my expansion
points, hit enter again. And it works. This is awesome. Um, by the way, on success in
our country stroller, we are redirecting with a three oh two status code, which is
perfect. That's what you should do after a forms. And that redirecting is what you
should do after a successful form summit. And notice that turbo handled this
perfectly.

If you look at your network tools, what happens internally is here we go, miss edit
here. This is the post request that was made to the forum that post requests returned
at three oh two. And so the Ajax requests automatically filed all of that. The next
URL that it's redirecting to, which is this notice that it was that the re Ajax
request was redirected. Use the HTML from this page and ultimately change the URL to
what it should be. In other words, reader, redirects work perfectly with turbo out of
the box. Now the turbo documentation will tell you to return a three oh three status
code instead of three or two, but both work exactly the same. Okay. Back to this four
22 status code fix. If you're using Symfony 5.3 and I am then fixing this as even
easier, thanks to a new render form shortcut, here's how it works. Change render to
render form. Then we can remove the response object.

That's it? Well, that's almost it also remove form->creative view. The render form
method is identical to this era render, except that it loops over all of the
variables that we're passing into the template. If it finds any that are form
objects, it does two things. First it calls create for you, which is just really
nice. We don't have to call that anymore. And second, if that form has been submitted
and it's invalid, it changes the status code of four 22. So all we need to do now is
repeat this change everywhere else in our app. All right, let's do it. I'll copy
render form. And we'll go through this quickly. If you scroll up to the new action,
you can actually see that we did the four 22 logic in the first tutorial for a
completely different reason. So let's change this render form. I don't need to create
view, and we don't need this third argument at all, much nicer. Next I'll close these
controllers as like, oh, let's go to cart controller. There are two spots inside of
here. I'm going to search for create view as a shortcut. So form

[inaudible]

And take off the great view. And the next one down here, take out the great view and
render form.

[inaudible]

Perfect. Then over in checkout controller, there's one spot in here. There we go.
Render form

[inaudible]

And this time there are actually two forms. We've asked them to this. So we will take
the creative view off of both of those in product controller. This is for the front
end, there are two spots under form

[inaudible]

And then take the view off of these two forms down here. In fact, we can actually
simplify this now to just that sentence [inaudible] And then one more spot down at
the bottom render form. And same thing here. I'll take out the great view, but really
we can just use that syntax [inaudible] And finally, in registration controller, no,
not final. [inaudible] let me have this one spot. And finally review admin
controller.

We have two spots [inaudible]

So that was some work, but that was a manual work, but it was very straightforward.
Good, straightforward, boring work. The only form we didn't need to change was the
log-in form

[inaudible]

And that's simply because the login form works a bit different than other forms in
our site. It already redirects on. So if I put something incorrect in here, it
already works just fine. Okay. We now just a few tens of our code. We now have a
fully working. Our site is now fully working with Ajax submitted forms, which is just
awesome. Um, next, let's talk more about that snapshot functionality, the feature
that instantly shows you a page from cache when hitting the bag button, or when
navigating to a page that we've already been to, as awesome as that feature is, and
it really makes the site feel fast. Sometimes it can take a snapshot in a state that
we don't want.

