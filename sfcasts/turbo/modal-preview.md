# The Problem of Snapshots & JavaScript Popups

Let's go log in so we can access the product admin page. I'll click the cheating
links to fill in the fields and hit sign in. Now click "Admin" and then click
the "New" button.

## Snapshotting Pages with an Open Modal

This opens a Bootstrap 5 modal. Oh, and usually there is a dark gray backdrop...
behind this... which is missing right now. Refresh... then hit this button again.
*There* is the backdrop. Why was it missing the first time? It's actually a bug in
Bootstrap 5.0.1 when using Turbo. But don't worry, it's already fixed and will
be available in 5.0.2.

Anyways, now that I have this modal open with my backdrop, click the back button
in your browser and then revisit the admin page. Woh! The modal was still open
for just a moment and then closed. This is *very* similar to what happened with
our submitted form. The snapshot was taken when the modal was open. And so, when
the preview is rendered... it... still has a modal!

Do this flow again: click the button then hit "back" in your browser. But this time
hit the "forward" button in your browser. Whoa. The modal stays open! Which I guess
is okay: that *is* an accurate representation of the page's state. The only problem
is that... well... the modal is completely nonfunctional. I can click the "Cancel"
button until Symfony 10 comes out... and nothing will ever happen.

## Snapshotting: Event Listeners are Lost

There are a few important things we need to understand. As we talked about
a few minutes ago, the snapshot for a page is taken the moment you navigate *away*
from that page. And so, if a modal or a dropdown or anything else is currently visible
at that moment, well... it gets cached!

Also when Turbo takes a snapshot, it *clones* the `body` element using a method
called `cloneNode()`. That's important because it means that any JavaScript
listeners - like an "on click" listener for this cancel button - are *not* included
in that clone. When we're looking at a snapshot, it's not *really* the same `body`
from before: it's a clone with no JavaScript listeners attached.

*That* is why the modal doesn't work: it's the same HTML, but without any JavaScript
listeners. This was an intentional design decision inside Turbo. Cloning the
`body` element, which removes all of the listeners, helps keep Turbo fast by
avoiding memory leaks.

If you write all of your JavaScript with Stimulus, this is no problem. When the
snapshot is restored, a new Stimulus controller instance will be created
automatically and everyone is happy. But in this case, this is Bootstrap's modal...
so we can't exactly tell them to use Stimulus.

And, besides, even if this modal *was* functional, it would still show up and
then disappear when we navigate back to the admin page... which isn't a huge
deal, but it's not perfect.

## Listening to the turbo:before-cache Event

So what's the solution? Clean up the page *before* the snapshot is taken. Head over
to the Turbo documentation, click on Reference and go to Events. Turbo dispatches a
*bunch* of events when it does different things, like when we visit a page or
submit a form. Learning how to leverage these will be the difference between a
"nice" Turbo experience and an *awesome* one. Check out this `turbo:before-cache`
event:

> Fires before Turbo saves the current page to cache.

That sounds perfect! We could run code to close the modal! Copy that event name.

How do we use this? Open up `assets/app.js`. Usually when we want to add some
JavaScript, we write a Stimulus controller. But for Turbo events, we actually don't
need that. Instead, say `document.addEventListener()` - which is how you add an
event listener in normal JavaScript - then paste the event name. Pass an arrow
function with an `event` argument and, inside, `console.log(event)`.

[[[ code('86376cf890') ]]]

Turbo dispatches most of its events on the `html` tag itself. And, remember,
as we navigate around, the `html` element is never removed: this one `html`
element sticks around forever. That's nice because it means we can attach an event
listener to it just *one* time and it will always be there. And since `app.js` is
only executed *once* - on initial page load - the listener won't be added over and
over again as we navigate to new pages.

Oh, and like we talked about earlier, the `document` variable is kind of the
"parent" of the `html` element. You can attach the event to *it* - like we're doing -
or to the actual `html` element itself... which is `document.documentElement`.
It doesn't matter.

Anyways, let's see this in action. Go refresh the page and open the console. Now,
click to another page. There it is! The moment we navigated away from the product
admin page, a snapshot was taken. If you expand the `event` object that we logged,
often this `detail` key here will contain extra information that's relevant to this
event. There's nothing in this case... but we *will* see this with other events
later.

## Let's Hide the Modal!

So here's my thinking: we're using Bootstrap 5's modal system, and it has a
built-in method to hide a modal. So, in this function, *if* a modal is open, we'll
call that `hide()` method and... done! The page will cache with a hidden modal
and we can all take a snapshot of a group high-five.

To do that, import `{ Modal }` from `bootstrap`. Remove the `event` argument - we
won't need it - and the log. Now, if `document.body` - that's an easy way
to get the `body` element - `.classList.contains('modal-open')`, then we know that
there *is* a modal currently open.

[[[ code('15fcf3d435') ]]]

I'm using a bit of Bootstrap-specific knowledge here. Click over to the product
admin page and open the modal. Yup! When the modal is open, the `body` element gets
a `modal-open` class. We're using that as an easy way to check if the modal is open.

Inside of the if, now that we know that the modal *is* open, we can say
`const modal =` and use a nice method from Bootstrap to get that the modal instance
that's connected to our element: `Modal.getInstance()` and pass it the Element
that the modal is attached to. If you inspect element, it's always going to be this
element here: the one with the `modal` class. We can find that with
`document.querySelector('.modal')`.

If you're not very familiar with using native JavaScript without jQuery, that's fine.
You *can* use jQuery instead of native JavaScript if you want to. But this is about
as complicated as it gets. We're using `classList` to see if an element has a class
and then using the `querySelector()` method to find an element with a certain
class on it. Now that we have the Bootstrap modal instance, we can call its
`hide()` method: `modal.hide()`.

That's it! Testing time! Find your browser, refresh, open the modal, hit back, then
hit forward. Ummmm. It... kind of worked? The modal isn't there... but this gray
backdrop *is* there?

What happened? The problem is that Bootstrap's `hide()` method is asynchronous.
To say that a less-fancy way, when you hide a Bootstrap modal, it doesn't instantly
hide: it fades out over time with an animation. *After* that animation finishes,
it does the rest of its cleanup, like removing this backdrop. Unfortunately, the
snapshot is taken immediately, before the modal has finished doing all of its
cleanup.

This is one of the *trickiest* things with the preview feature: how to clean up
and play nice with third-party JavaScript. So next, let's find a way to solve
this both for Bootstrap's modal and also a Sweetalert modal that we have on a
different page. That will give us clean preview functionality across our entire
site whenever either of these are used.
