# Modal Form

Coming soon...

Okay, we're going to load it.

The new product form into the body of this modal over the.dot that part. But the
header in buttons will still come from our underscore modal that age to twig
template. So let's customize those to make more sense up on the

Header. We can say, add a new product. Wait, don't do that.

I want to try to make this template as reusable as possible for other models.
Instead, let's say curly, curly, modal titled An index that HTML that twig, we can
add a second argument to include and pass a modal title. So that to add a new
product,

Very nice for the body, let's say

Curly, curly, modal content. That's a new variable I'm inventing, but I'm going to
pipe that to the default filter and say loading.

So in this

Case, we're not going to pass a modal content variable, but you can, in other
situations instead, we'll just say loading, which will sit there, which will be
replaced once our Ajax call finishes

For the buttons.

I'll hard-code those to some new texts. So instead of close, I'll say cancel.

And

Instead of understood, I'll say safe. We can always make those dynamic later. If we
need to, let's make sure we didn't break anything. When I click the button, it looks
good to get the new product form HTML. When the muddle opens, we're going to make an
Ajax call to some endpoint that will return that HTML.

Okay.

I over to source controller, product admin controller and find the new app,

Okay,

This is the end point that we're going to make our Ajax requests to in a few minutes,
we're going to customize this so that it's able to return the form HTML under certain
conditions instead of returning the entire page of HTML, which it always does right
now.

Sorry.

Well, up here in copy this route name, as you know, I don't like to hard-code Ajax
URLs in my stimulus controllers, and I really don't want to do that in this case
because I want this controller to be reusable for other forms on our site.

And so we'll do what we've

Done several times before pass the URL into the controller as a value, add a static
values equals and create a value called how about form URL, which will be a string.
And then down here in open modal we'll counsel about log this dot form URL value.
Okay. In the template on our stimulus controller at a second argument. So you can
pass this in form URL set two, we'll use the toy path function and paste in our route
name, product admin, new, alright, refresh quick. And that didn't work. I bet I was
too fast to refresh again quick and

All right.

And there's the URL so far, we've been using a fetch to make Ajax calls, which I
really like. I also really like Axios, but I've gotten some questions about how it
would look to use J query inside of stimulus. So instead of showing another example
of using fetch let's install and use jQuery at your terminal, okay. At your terminal,
install it with yarn, add jQuery dash dash dev. Once that finishes, we can import
that into our controller with import dollar sign from jQuery.

Okay.

Now down in the method, let's remove the council of that log. This should be deleted,
Remove the console dot log and make the ads call with the dollar sign dot Ajax and
pass that this got form URL and value. Now that will make the Ajax call, but we'll do
absolutely nothing with the result. What we need to do is take the HTML from this
Ajax call. And if you look at underscore modal that HTML that twig, we want to put it
inside of this modal body element.

That

Means we need a new target at a right here, data dash modal VAT form dash target
equals. And let's call this one modal body. Copy that, go back to the controller and
add that as a second target on top,

Huh?

Now we can say down here, this dot modal body target dot inner HTML equals, and then
await dollar sign dot Ajax because jQuery Ajax function also returns. They promise,
and he can see my Webpack build is mad because we need to make it open modal. Async
our Ajax call is still going to be returning the entire HTML page, but let's at least
see if it works, move over refresh and awesome. It looks totally wrong because it's
including the entire page, but it's working before we make it return. Only the form
is one last little detail. I want to handle in our stimulus controller at the very
top of open model, let's say this dot modal body target that inner HTML equals. And
I'll just say loading. This is a minor detail. If we open the modal twice on the
page, it will clear the contents that was just there and load fresh content That we
won't temporarily see any old forms. Okay. Our last job is to only return the form
HTML instead of the entire page. From our end point, when we're making the AGS call
over in product admin controller inside of the new action,

We're basically I want to do is on a, for the full page. We want to render it new dot
HTML twig. But if we're only returning the form, we can actually just render this
underscore form that HTML twig, this is the form element. So the generated code
already has this isolated into a perfect form partial. So that means we can say
template equals and then to figure out if this is an Ajax request, we can say request
arrow is XML HTTP request. If it is use underscore form that HTML that twig
[inaudible] knew that HTML that twig now down here, while you render product admin
slash

And then template, that's it. But I do have one warning. I usually, when I make an
Ajax call for a partial, I usually append a query parameter like question Mark form
equals one or question Mark Ajax equals one. Instead of relying on the is XML HTTP
request. Why? Well, it's easier to test that partial page in the browser because you
can just add that query parameter to the URL. And some Ajax clients like fetch don't
send the right headers to be detected by the, by the is XML HTTP request thing. So if
you're using J query for Ajax, you can totally do this. If you're using fetch, you're
probably going to want to add a query parameter, um, when you make the AGS call,
which you can do pretty easily inside of our controller. We did that earlier with the
URL search warrant.

Yes.

Anyways, head back to the Bates refresh quick and Oh, look at that. It's beautiful.
The only problem is that there are two sets of buttons. I know it looks on style, but
there's actually a save button there. And our say buttons down here. We probably want
to keep the buttons in the modal footer in the hide, the ones that are coming from
the form partial and a really easy way to do this is just CSS. So over in my editor,
I'm going to go to assets styles, app that CSS and all the way at the bottom. What
you do is just hide any buttons that are inside of the modal body. So as a reminder,
a modal body has this modal dash body class. So we can say that model, body button,

None.

And if you want it to be, this will hide all the buttons for all of the modals on the
site. So you might need to be more targeted if you're using your, uh, modals and
otherwise, but this should work great for us. So when I refresh now and hit it, so
when I refresh now and hit it, ah, it looks perfect. Okay. We've got our form into
the modal. Now we need to make it submit via Ajax and the modal. If the form has
validation errors, we'll need to rerender though. We'll also need to re-render those
in the modal, but at the age it's called a successful, we're going to want to close
the modal. Let's get all that worked out next.

Okay.

