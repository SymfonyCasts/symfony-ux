# Broadcast Remove

Coming soon...

In reviewing that stream, that age two months wave, we have the ability to publish
turbo streams automatically. Whenever a review is created, updated or removed, that's
pretty cool on unrelated to this. I haven't mentioned it yet, but our site has a
review admin area. You can get it by going to /admin /review. Here, we can create a
update or delete reviews. Do you see where this is going? Sometimes admin users tweak
reviews to make them more encouraging. Would it be cool if any changes that happen in
the admin will reflected on that review instantly on the front end,

Let's do that.

Start in_review that age two month twig. This is the template that holds renders a
single review. We're going to give us an ID so that we can target it from a turbo
streams. How about ID = product best dash {{ review.id. I'll copy that value. And now
in review stream, not aged on a twig. When the review is updated, let's add a new
turbo stream. So turbo stream with action = replace, and we're going to target that
ID. We just did, which is product attribute dash reviewed at ID, but in this template
review is the entity variable. So I'll change that to entity ID. Now, inside of here,
we'll do our normal template element. And then inside of that, we'll just include
that product slash_view, that age limit twig

Template. Now

That template inside that template, we need a review variable instead of here. It's
called [inaudible]. So pass the second argument so we can pass in their review
variable set to entity. That's it. When I review is updated, it will try to replace
any elements on the page with the updated content. All right. So let's go over and
make sure you refresh the front end.

Perfect. And

Over in the backend, we will put a very important dinosaur emojis. All right. Hit
update. Okay. I think that worked. Let me double check. Yep. I worked on the back
end, check out the front end. That's amazing with no refreshing anybody that is
viewing ha has that review on their page at this moment. Just had it updated. That's
awesome. We could also update the quick stats area as well. In case maybe we change
the number on the review, but I'll leave that to you. What about removing a review?
Like on the back end here, we can actually delete this review.

Could we automatically remove this element from the front end? Absolutely. Okay.
Inside the remove block, let's create a turbo string. This is one I have action =
remove, which we haven't used yet and target equals. And we're just going to target
this same thing up here. So product dash review dash. Now here, you might expect me
to say entity ID, but by the time we're past the entity, when it's being removed,
it's already been deleted from the database. And so the ID is empty. Fortunately it
also passes us just an ID variable that we can use inside of here. And in this case,
the troublesome is going to be empty. It's just going to give instructions to remove
that element. This is easy to test. I'll refresh again on the front end, just to be
sure

On the backend, delete this

And on the front end, S it's gone want to make it fancier by fading it out like slack
does when you delete a message. Sure. We're doing all kinds of crazy stuff up in
styles /app, that CSS, I'm going to add a new stream removed item, uh, style here,
and we're going to set the background color to like coral. That's a pretty sounding
name, okay. Over and reviewed us stream that age on a twig. It's a little bit
trickier. We don't actually want to remove the element anymore because that doesn't
give us control over, fading it out. Instead, what we're going to do is we're going
to actually update the element just so we ended up here and then with a little extra
JavaScript, we'll fade it out and remove it. Okay.

Change the action to replace. And then actually what we're going to do and on here is
just copying base that entire template, same as above, but I'm also going to pass a
second. New variable in here called is removed true, which we'll use inside of that
template to do some custom stuff. All right. So let's head into this template under
score reviewed at HR twig. Now when a new items added, we're passing an ease new
variable in a rendering, our stimulus controller to kind of fade it in. We're going
to do something very similar. In this case, we're going to say, if is removed, then
we're going to run that same stimulus controller. But this time we're going to pass
the class name called streamed removed item. You can see why we were making things
dynamic. I'm also going to pass in a new, uh, value here called remove element, true
to kind of signal to that controller that we actually want to fade this element out
finally, inside of our controller.

So streamed item controller for Sam going to do is add that remove element value,
which is a boolean. And then I'm not going to go into too much details here. But in
the first tutorial about stimulus, we actually created a helper in our application
called add fade, transition, which I can import here. And then inside the connect
method to kind of initialize that on here, you call, add, fade, transition, pass it,
this object, this, that element, which is the element we want to fade out. And in
this case, because the item is going to start fading in, then transition out. We need
to pass transitioned. True. You want to know more about how this all works behind the
scenes, check out our stimulus controller, a tutorial then down here. And if set time
out where I should want to check for this remove elements and do something different
based on if we're removing an element or adding a new element. So if this.remove
element value, then we're going to do one thing else. We're going to do kind of what
we were doing before now for removing an element. I can say this, that leave. That's
the method. That's a, the spade transition thing gives me, and this will just fade
out the item.

Okay. Let's try that on the front end. Let's see. Let's go back and find this review.
Here it is. Let me refresh the front end to get fresh to CSS

And

Delete. Okay. Go check the front end. Awesome. There it is. It's red. It's going to
wait for five seconds, which is maybe a little bit long and then fade out. Whew.
Okay. Dean. There's just one more thing. I want to try using turbo streams to pop up
toast notifications on the front end. That's next

