# Dispatching an Event from modal-form

Earlier, when we were working on the cart page, we learned that if you need two
controllers to talk to each other, like the `modal-form_controller` and the
`reload-content_controller`, a great way to do that is to dispatch an event.
Specifically, `reload-content_controller` needs to know when it should refresh the
content. To help it know that, we're going to dispatch a custom event from
`modal-form` after the form submits successfully.

So since we did this before, let's jump straight in. First, at the top,
`import { useDispatch } from 'stimulus-use'`. Then activate that with a new
`connect()` method: `useDispatch(this)`. Temporarily pass this
`debug: true` so we can see the event being dispatched in the log.

Now, down after success, say `this.dispatch('success')`.

Try it: reload... and try selling some rotted, I mean, reclaimed wood at our store.
Submit and... awesome! That *did* just dispatch an event... and its name is
`modal-form:success`.

# Listening to the Custom Event

Copy that. Now that we've seen the event name, go back over and remove the `debug`
option. Here's the last magic piece to make this work. In `index.html.twig`, up on
the `reload-content` controller `<div>`, add an action for the new event:
`data-action=""` the name of the event - `modal-form:success` - arrow, the name of
the controller - `reload-content` - a pound sign and the name of the method:
`refreshContent`.

That's it! When the `modal-form:success` event is dispatched, it will bubble up to
this element and we will call `refreshContent()`. Then... that'll take care of
the rest!

Let's test it. Reload the page, open the modal and let's sell some avocado peels.
Submit. Ah! It... kinda seems like it worked? Except that the Ajax endpoint apparently
returned the entire page, *not* just the template partial.

## Using a Query Param instead of isXmlHttpRequest()

Let's go look at the controller. Ah... I warned about this and then did it anyways!
We're using `fetch()` to make the Ajax call... and `fetch()` does *not* send the
header needed for `isXmlHttpRequest()` to work. And so, this *always* renders
`index.html.twig`.

That's okay! Let's just add a query parameter to the end of the URL. I like that
better anyways.

Replace this code with `$request->query->get('ajax')`. So, we'll be looking for a
`?ajax=1` on the end of the URL.

In the template - `index.html.twig` - add that to the URL by passing extra params
with `ajax` set to `1`.

Try the form again. Refresh! We'll sell some salsa to go with those avocados... and
this time... perfect! The section reloaded. We're done!

If we want to make this a bit fancier, we could even add some classes and use those
to force CSS transitions. Or we can do an even simpler trick. When it first starts
loading, let's say `this.contentTarget.style.opacity = .5`.

Copy that, and then, *after* it finishes loading, set the opacity back to 1.

Add one more product: this time, a mystery box of donuts. Watch the table closely
when I hit save. Yes! It was quick, but the table had less transparency for *just*
a moment while it reloaded.

Now that we've got this cool new re-usable `reload-content_controller`, let's use
it to completely replace our custom `cart-list` controller. Yay for less code!
That's next.
