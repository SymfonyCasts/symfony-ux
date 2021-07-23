# Full Frame Redirect

Coming soon...

We just did something pretty custom. Normally if you submit a form and a frame, if
that frame redirects, the new content will be loaded into the frame, but the URL on
the top of the page will not change and it will not reload the content of the whole
page,

But sometimes this is what you want,

Like in the modal. Yeah. Or maybe if you have a sidebar and you want to fill out a
form that stays in the frame and on submit, shows errors in the frame, but on success
redirects the whole page. So let's make our modal, let's make our system, our modal
system of being able to redirect and having it the whole page from a frame, I need to
reword that a global thing. So here's the plan. If a turbo frame like the turbo frame
in_modal that aids two months away, if a turbo frame has a data dash turbo dash form
dash redirect = true attribute, which I totally just made up, then we will redirect
the whole page. Whenever this frame redirects, because of this new data term
performance, redirect behavior will be something that will work anywhere on our site.
We need to move the logic out of our modal form controller into turbo help or where
the rest of our global stuff is. So let me copy this before fetch response method
here, I'll delete that. Then go to terminal helper and let's paste this on the
bottom. Cool. Now go back to Maura form controller. We don't need the disconnect
method anymore. We're just going to register at once inside of turbo helper and then
we'll copy part of connect, but then the lead, the rest of it. And I can also delete
the import back over into her helper. Let's head up to the constructor here it is.

I'll paste. I'm going to say document data, add event listener turbo before fetch
response. And then I'll just actually do the simple way here. I'll just pass an error
function with the event argument and call this.before fetch response and pass it
event. Okay, cool. So if you look down at me for fetch response, it's not going to
quite work yet because it's still kind of hard-coded to work with a modal. The trick
is that we need to determine three things. One, this is called first was this
response a redirect, and we configure that out right here and VFS response, not
redirected. Second did this okay.

Was this navigation the results, uh, did this happen inside of a turbo frame? And
third, does that frame have the data for turbo form? Redirect attribute the trickiest
part about those? The trickiest, one of those three is actually figuring out if this
fetch call is happening inside of a turbo frame. This event doesn't give us any
indication if you logged it, uh, about who initiated the agent's call, like which
link or which form, but we can use a trick. Remember, whenever a frame is loading
turbo gives that frame a busy attribute. We can use that here.

So I'm gonna create a new function down here. Well, convenience function called get
current frame, and this is going to return the element B frame element that is
currently loading or no. And it's as simple as return the document that query
selector and then turbo dash frame left square bracket, Izzy, that's it? Yes. It's
theoretically possible that two frames could be loading at the same time, but other
than on page load, if you had multiple lazy frames, that's pretty unlikely op and
before fast response, we can use this. So remove all of this modal stuff here, and
then I'm actually going to take this event dot prevent default and turbine visit and
move this to the end of the function, uh, because what I'm going to do, what I'm
going to do with extra is the first reverse this if statement. So if the fetch
response did not succeed, or it's not a redirect, then we can return because we have
one other check that we need to do here. We need to see if we are inside of a frame
currently and make sure that that frame has our data attribute.

So that looks like if we are not in this.and get current frame, then return and do
nothings for not a frame or nothing. And actually we can also go further and say, or
if we are not in this correct, if our current frame, that data set that turbo frame
form, redirect then return. So basically the only way we down here is if it font
response is redirected and we are in a frame and that frame does have our turbo form
redirect date on it, all of those things are met. Then we will prevent defaults to
stop the rendering of a frame. And we will redirect the entire page with turbo dot
visit to that location. Let's try it.

All right, refresh the page, open the product. Do you have a, any product name and
hit save? Got it though, that hit our code. We can tell because it load up the new
product right here behind the scenes. All it really did was navigate the entire page
via turbidite visit right back to this URL. All right. Was that too easy though? It,
I actually kind of was, there are two annoying bugs that are hiding inside of our new
system. Let's add one more turbo frame to our system next that will expose both of
them. Don't worry. By the end, we're going to have a beautiful bug for you. Hey,
where we can tell frames to navigate the whole page.

