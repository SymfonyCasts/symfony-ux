# Turbo Stream

Coming soon...

The third and final part of turbo is turbo stream. Yes. In short, these are a way to
return instructions on updating any part, any element on the page. And there are two
main use cases. First you're submitting a form inside a frame on success. You need to
update something outside of the frame. And the second is as a way to get asynchronous
updates on the page without needing to repeatedly make Ajax requests every few
seconds. For example, imagine something like a chat app strings are a way to enhance
your page. So they're an extra feature that you should add on later, if you want it.
For example, go to a product page and scroll down. All right. See this review form
when we're logged in, it's in a frame and it works awesome. The frame surrounds both
the form and as long as both the form and the review list above it and see that if I
inspect element on that area, let me try inspecting that again.

There you go. The frame is around this whole area. This means that when we submit,
okay, and you review, we updated the form area, but it also updates the list. So we
can see that new item, but scroll up to the page to the product details up here, we
show the review count and the average number of stars. This did not update
automatically. When we submitted the review. Watch. If I refresh the page right now
at eight reviews, we'll go to nine reviews. This area lives outside of the reviews
turbo frame. So we can't update it via the frame, but we can update it using turbo
streams because they can update any element on the page. Here's how it works. Step
one is we're going to find the area on the page that we want to update and give it a
unique ID. So the template for this page lives in a templates product show that HTML
that twig let's see here, scrum alphabet. Perfect. This is the area right here. So we
can take this `<div>` right here and I'll say `id=""`. And how about we'll call this
`product-quick-stats`. That's just an idea. I just made up next, open up the 
`Controller/ProductController` and find the reviews action. So perfect. So this is the page that
we submit to when we rented the reviews. Now down here, instead of redirecting the
page, let's do something different. Let's render a new template. So I'm going to
leave this logic right here, but above this, oops,

I was like this awesome. Say there was a slight uptick in a second. Here. We have
live audio.

So we're going to return `$this->render()` like normal, and we're going to render a
template called `product/reviews.stream.html.twig`. We don't need to
pass us any variables, but I'm going to pass an empty second argument because we do
need to pass a third argument, a `new TurboStreamResponse()`. Okay. First see
the.stream and the template name. Yep. That hasn't no technical effect. That's just a
naming convention because this temple will have a special format. Second by passing a
turbo stream response. As the third argument, we're telling symphony to render the
template like normal, but to put the HTML into this response object instead of a
normal response object, I'll show you what that does in a minute. All right, let's go
create this template. So in `product/`, new file called `reviews.stream.html.twig`
twig stream templates have a very special HTML format where you described the exact
element that you want to update and how you want to update it. And the HTML that
should be used. So it looks like this.

We always start started the `<turbo-stream>` element, and then an `action=""` in this case,
it's going to be `update`. We'll talk more about those in a second and then a `target="""`.
And this is going to be set to the ID on the page that we're going to update. So I'll
copy it. `product-quick-stats` and paste that there inside the treble stream, we always
have a template element. You just need to have that doesn't really mean anything. And
inside of here, I'm just going to put some random HTML, uh, to start it simply says,
I want you to find the product quick stats element on the page and update its inner
HTML with the HTML that you see here. Okay, let's try this. The whole flow will make
more sense when we see it in action. So let's head back over, refresh that page,
scroll down

And add a review. Let's see. And nothing happens.

It looks like our form is kind of stuck, but scroll up to the quick stats

Area. Whoa,

There's my HTML. This is a turbo stream and action. Check out the network tools and
find our reviews post. It should be this one on the bottom here. Yep. As expected
when we submitted the form, it returned this special turbo stream HTML, but check out
the headers on the response. I scroll up a little bit actually. Yeah. As a content
type set to `text/vnd.turbo-stream.html` that's important. So
here's the whole flow of what has happened. Okay. In our controller, we rendered this
`reviews.stream.twig.html` that twig and put it into a special turbo stream
response object. That response object causes a special content type to be sent this
`text/vnd.turbo-stream.html`.

Okay. That's important because as soon as you set up turbo on your site, like the
first thing we did at the very beginning of this tutorial, turbo adds a listener to
the turbo before fats respond event. Remember that's one that we are also listening
to inside of our turbo helper right here. This is the event that's dispatched. After
any Ajax call that turbo makes has a finished inside of that core core listener. If
turbo detects that the response has the Tacs V and D turbo streamed at HTML content
type, then turbo handles that response via instream system.

So it passed our age team onto the stream system that process this and updated the
product quick stats area. But that's all it did our frame down here. Didn't actually
update. But we'll talk about that in a second. In addition to the append action,
there are a bunch of other actions that you can take in the journal docs. That's
actually going to reference and let's go to streams. So you can actually append an
element. Prebend an element, replace an element, update the HTML inside an element,
remove an element, but stop before after an element. And in addition to your
targeting with IDs, you can also target with elements with CSS selectors.

All right, next,

Let's make the stream update the quick stats area with, to the real new data. After
the new review, then we still need this reviews area to update. So we'll handle that
in the stream. Okay.

