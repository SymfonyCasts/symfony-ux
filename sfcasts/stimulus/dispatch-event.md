# Dispatch Event

Coming soon...

Earlier, when we were working on the cart page, we learned that if you need two
controllers to talk to each other, like the modal form controller and the reload
content controller, a great way to do that is to dispatch an event specifically
reload content controller needs to know when it should refresh the content to help,
to help it know that we're going to dispatch a custom event for modal form after the
form is successful. So since we did this before, let's jump straight in first at the
top import use dispatch from stimulus use. Then activate that with a new connect
method, use dispatch this and temporary temporarily pass it debug. True. So we can
see the event name now down here, right after success. Say this dot dispatch success,
try it reload. And let's add, let's try selling some rotted, uh, I mean, reclaimed
wood at our store and submit awesome. That did just dispatch an event and its name is
modal dash form colon success.

Name of the controller, call on the name of our event. I'll copy that. Okay. Now that
we've seen that we can go back over and I'll remove that debug. Here's the last magic
piece to make this work in index that HTML twig up on the reload content controller
div add an action for this new event. Data dash action equals the name of the event.
Modal form, colon success, arrow, the name of the controller, reload, a content pound
sign. The name of the method re fresh content. That's it. When the modal form success
event is dispatched, it will bubble up to this element and we will call refresh
content. Then that'll take care of everything. You know, what's great. Let's test it.
Reload the page, open the model and let's sell some avocado peels submit. Ah, yikes.
It seems to have worked, but the Ajax endpoint apparently returned the entire page,
not just the template partial. Well, let's go look at the controller. Uh, ha I warned
about this. We're using fetch to make the Ajax call and fetch does not send the
headers needed for his XML HTTP request to work. So it's always using index that
HTML, that twig that's okay. Let's just add a query parameter to the end of the URL.
I like that better anyways.

So replace this with request arrow, query arrow, get Ajax. So we'll be looking for a
question. Mark Ajax equals one on the end of the URL and the template index that age
them out the way we can add that to the end of the URL as an object with Ajax one.
Okay. Try the form again, refresh. We'll sell some salsa to go with those avocados
and this time perfect. The section reloaded. If we want to make this a bit fancier,
we could even add some classes and use those to force the CSS transitions, or you can
do an even simpler trick when it first starts loading. Let's say this back content
target that style dot opacity equals 0.5, a copy of that. And then after it finishes
loading, we'll set the opacity back to one, add one more product. This time, a
mystery box of donuts watch closely. When I hit watch closely on the table when I hit
safe. Yes, it was quick, but the table had less transparency for just a moment while
it reloaded. Now that we've got this cool new reload con re-usable reload content
controller, let's use it to replace our custom cart list controller entirely. Yay.
For less code that's next.

