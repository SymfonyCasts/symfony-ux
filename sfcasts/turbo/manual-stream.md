# Processing Streams by Hand for Fun & Profit

As we've learned, each time Turbo makes an Ajax call, it listens to see if the
response has a content type of `text/vnd.turbo-stream.html`. If it *does*, then
the HTML is passed to the Turbo Stream system... and it works its magic. But in
theory, you could grab some Turbo Stream HTML from *anywhere* and tell the Stream
system to process it. And... it's kind of fun!

Head to the homepage. This counter area is fueled by a Stimulus controller: the one
at `assets/controllers/counter_controller.js`. It's pretty simple: click it, it
counts and updates the text with the new number. In addition to doing this, I want
to *invent* a Turbo Stream that adds a flash message to the top of the page.

## Adding the Stream Target to the Page

First, we need to be able to target the flash area so that we can put stuff into
it. In `templates/base.html.twig`, find the section - I'll search for flash - and
surround it: `<div>`  with and id set to, how about, `flash-container`. Pop the
closing tag on the other side.

## Manually Creating a Stream

Back in `counter_controller.js`, right after we update the count on the page, lets
invent a new variable: `const streamMessage` set to some ticks so we can easily
create a multi-line string. Inside, we're literally going to invent a new
`<turbo-stream>` with `action="update"` and `target="flash-container"`. Add the
`template` element and inside of that, create an alert success div: thanks for
clicking `${this.count}` times.

This is variable is a plain, boring string... but a string that has the
`<turbo-stream>` format.

So... how can we tell the Turbo Stream system to read this and follow its instructions?
At the top, we're already importing the `visit` function from `@hotwired/turbo`.
This library exports a *bunch* of other things, including a function called
`renderStreamMessage`.

Copy that. Down below, it's as simple as this: `renderStreamMessage()` and pass
it `streamMessage`.

Done! Let's try this thing. Head back over, refresh and click. Oh! That's *so*
cool. We now have a dead-simple way to mutate different elements on your page from
JavaScript. And more importantly, this shows off the fact that the stream handling
is a standalone system inside of Turbo. And, in theory, we could get this stream
HTML from *any* source, not just from an Ajax call. That will be important in the
next chapter.

## Removing an Element from the Snapshot Cache

Go back to the page and click this 10 times. Woo! The Stimulus controller navigates
us to the winning page! Click back to the homepage... but watch closely when you.
Did you see the flash message? It was there for *just* a moment and then disappeared.

That is *totally* unrelated to streams. This would happen with *any* flash message
in Turbo Drive, thanks to its preview system. But even though this has nothing
to do with Turbo Streams, while we're here, let's fix it... and learn something
new along the way!

One solution to this would be to go into `assets/turbo/turbo-helper.js` and *remove*
any flash messages before the snapshot is taken. We already have logic for that:
we listen to `turbo:before-cache` and cleanup several elements.

But starting in Turbo 7 Beta 8, there's a *new* attribute that you can add to any
HTML element that you do *not* want to include in your snapshot. If you think about
it, we *never* want a flash message to be in a snapshot: you want the flash message
to show once... but *not* be there if you navigate away and then back again.

So, in `base.html.twig`, it's really simple: on `flash-container` - which will contain
all any flash messages - add a new `data-turbo-cache="false"`.

That's it! Thanks to this, this entire element - and anything inside - will *not*
be included in the snapshot. Check it out:refresh the homepage... click 10 times
and go back. Beautiful! No flash message.

Next: we know we can return Turbo Streams as the response from a controller. That's
what we've been doing so far in the reviews action. But there's also *another*...
more *powerful* way to send streams to your users.
