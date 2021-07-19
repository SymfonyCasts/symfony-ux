# Globally Disable Buttons on Form Submit

Coming soon...

These are for them to log back in and then head back to the product page. So things
that work that we did earlier, when we submit the review form form, the opacity does
go lower while submitted, but it's a little bit subtle when we do it to make it more
obvious. What if we disabled this submit button while the frame was loading? Okay.
That wouldn't give us an even better loading indicated than we have now. And as a
bonus, it would help prevent double summits. Here's the best part. We can do this
across all forms on our site, by leveraging an event that turbo dispatches in your
editor, open up assets, turbo, turbo helper.JS, and somewhere up here in the

Constructor,

Listen to a new events or document that, add that listener. We're going to listen to
turbo:submit, start, pass us an error function with any event argument. Okay. And
inside let's console log, just the strings, submit that start. And then also log the
event. Object turbo triggers. This turbo submit event, whenever any form is submitted
with turbo, whether it's inside of a terrible frame or just a normal form that turbo
drive is handling. So let's try this. I'll move over, refresh submit, and then go to
my console. There it is. Nope. Some of turbos events will have a detail key inside of
the extra information. And this is one of those events that has a detailed key
specifically. It has a form submission key that holds all kinds of information about
the form submission that is, is about to start. Most importantly, for us, it has a
submitter key, which is set to a button element that is this submit button right
here.

That's awesome because

We can use that to add a disabled attribute to that button. So the key reluctant here
is detailed form submission submitter. So head back over to our code and replaced the
log with event that detail, that form submission that submitter. And then we would
say toggle attribute disabled. True. So the toggle attribute with the second
argument, true, basically says, I want you to add this attribute, but I don't need it
to be like disabled = something. It's just, I want you to add a disabled attribute.
All right. Let's try that. Refresh the page. I'm going to go back to my elements
here. Let's inspect the element on this button and watch this button when I click.
Yes. Perfect. So just for a moment, I had a disabled attribute once he has an even
more loading field here, and it's not clickable for a little while, while it's
loading. So our code added the disabled attribute. And then when the frame finished
loading, the entire contents of the frame were replaced with a new non disabled form.
So the effect is exactly what we want. Now go check out the registration form, go log
out and go back and go to the registration form and check out the same thing is going
to happen on this button, even though this is not inside of a terrible frame, when a
quick, beautiful disabled, while that frame is loading, that's a nice little effect
we can add to our entire site with just a couple of lines of code. Now there is

One possible edge case, okay,

With this. And that's, if you submitted click to submit, and then while the form was
loading, navigated away from the page to another page, if you did that, turbo would
take a snapshot while the button was in. It's a disabled state, which meant if they
then clicked back in the browser, they would have a button with the disabled. So this
is probably this wouldn't be such an aged care aged case that you don't even care,
but let's code for it. So back over into rural helper, I'm actually going to create a
new variable called constant submitter equals,

And then

Copy the event that detail about form submission dot, sir, that submitter and put it
there. And then we'll just call it submitted that toggle toggle attribute. And the
other thing I'm going to do is add a special class that basically to mark that we in
our custom code added the disabled button. So submitter dots, class list that ad
turbo submit disabled. So this class on its own, isn't going to do anything. I'm just
marking it so that we know this is a button that we disabled, because what we can do
here is on turbo before cache. So this is the callback that's called right before
turbo takes it, snapshot cache. We can find any buttons that have that class and
remove the disabled attributes. So they don't get cacheed in that state. So I'm kind
of the callback. I'm going to call them new function called this.re enable, submit
buttons.

I'll copy that method name. Okay. Then head all the way down here to the bottom.
Okay. And add that. And we're going to do here. It's just fine. Those buttons sort of
say document that query selector all so basically finding it, all things that have
that turf.Turmo submit disabled class that we just created, then we will forage over
them. This will pass us in the individual elements. I'll make that as a button
argument to the->function, then it's on here. We'll just say button.toggle attribute,
save old false, remove the disabled, uh, argument. And just to fully clean things up,
we can also say button.class list that removed provokes submit disabled.

It's pretty hard to actually repeat that edge case anyway. So I'm not going to try
it. Let's at least make sure that this works. I submit. Yes. Perfect. We can see that
it is disabled for just a second and come back just fine. Okay. Next. I want to find
some more places that we can just leverage turbo frames, do some really cool stuff.
So one idea I have is it would be really neat as an admin. If, when I'm on a product
page like this, I can just click an edit button right here. And the form, the edit
form for this would just pop asynchronously via Ajax right in this spot. Then I'm
like, save, it would come back to this. Let's use a turbo frame to do that next.

