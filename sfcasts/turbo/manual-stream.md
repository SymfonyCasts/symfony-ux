# Manual Stream

Coming soon...

As we've learned any time, Turbo makes an Ajax call. It listens to see if the
response has a content type of `text/vnd.turbo-stream.html`. If it
does, then the HTML is passed to the turbo stream system and it works its magic. But
in theory, you could grab some turbo stream HTML from anywhere and tell this terrible
stream to process it. Head to the homepage. This counter area here is fueled by
stimulus controller lives over and `assets/controllers/counter_controller.js`,
pretty simple on click let's invent a stream that adds a flash message to the top of
the page. First, we need to be able to target the flash area so we can put stuff into
it in `templates/base.html.twig`. Find the flash area I'll search your
flash. And let's add, I `<div>` with id equals. How about `flash-container` pop the
closing them on the other side. Okay.

Back `counter_controller.js`. Right after we update the account on page, I'm just
going to invent a new variable year called constant `streamMessage` equals, and I'll
set this to tick so I can use multiple lines and variables instead of here. I'm just
literally going to invent a new `<turbo-stream>`. So let's say `action="update"` and
`target="flash-container"` inside with our template element. And inside of that,
I'll just invent a new alert success div thanks for clicking `${this.count}` times. So
this is just a string, but it's a string that has the turbo stream format.

So the processes into the turbo stream, we can actually import a render stream
message of function from turbo. If you look up on top already importing visit from
[inaudible], we can also import `renderStreamMessage` a copy of that. And it's as
simple as this, the `renderStreamMessage()` and then pass it. `streamMessage` done. Cool.
Let's try this thing. Head back over refresh and click. Oh, that's super cool. A dead
simple way to mutate different elements on your page from JavaScript. And more
importantly, it shows off how these stream handling is just a standalone system
inside of turbo. And in theory, we can get this stream HTML from any source that will
be important in the next chapter. And if we go back in here and you click this 10
times, then our controller navigates us to our winning page, click back to the
homepage, but watch closely when I do it. Did you see that the flash message was
there for just a moment that is totally unrelated to streams? That one happened with
any flash message in turbo drive, thanks to its previous system, but while we're
here, let's fix it.

Now one way to fix this is inside of `assets/turbo/turbo-helper.js`. We could remove
this before the snapshot is taken. We already have logic for that. Um, up here, 
`turbo:before-cache`, we already do some cleanup, but starting in turbo, beta eight, there's
a new attribute that you can add to any HTML element that you do not want to include
in your snapshot. And if you think about it, you never want a flash message to be in
a snapshot. You want the flash message to show once, but if you ever go back to the
page for it to not be on that page. So over in base study general twig, it's really
simple on this flash container, which will contain all our class messages, add a new
`data-turbo-cache="false"` attribute. That's it. Thanks to this. Anything in this
element, not be included in the snapshot. So let's try it. I'll refresh the homepage.
Okay. Click this 10 times now, go back. Beautiful. Next we know we can return turbo
streams as the response for any controller. That's what we've been doing so far in
our reviews action, but there's also another more interesting way to send streams to
your users.

