# Turbo-Friendly JavaScript

The *biggest* gotcha with Turbo Drive is JavaScript. And that's for one simple
reason: suddenly there are *no* full page refreshes! And... a lot of JavaScript
is written to *expect* that behavior.

## How JavaScript in head Is Parsed

Let's see how some classic JavaScript behaves with Turbo. Open `assets/app.js`:
this file is loaded on every page. Let's use jQuery to run some code after the page
finishes loading. You might recognize this code.

Import `$` from `jquery` - I already have that installed. Then use
`$(document).ready()` and pass a function that should be called once the page is
fully loaded with `console.log('page is ready')`. After this block, also
`console.log('script is done')`.

[[[ code('34293bb4dd') ]]]

Cool. Go refresh... and check out the console. Yep! We see both logs: script is done
first, then "page is ready" shortly after. But when we click to another page, we
see nothing! And that makes sense! `app.js` is *not* re-executed... and the page
does *not* become "ready" again. This is a *big* difference compared to a
traditional web app. But, it's also what makes Turbo so fast: re-parsing all that
JavaScript over and over again on each page load takes time!

## The Problem of JavaScript in body

However, if you put JavaScript into the *body* of your page, then it *does* work
like normal. Open up `templates/base.html.twig` and - anywhere in the `body`,
I'll go to the bottom - add a `script` tag and `console.log('body executing!')`.

[[[ code('3b17786a93') ]]]

Refresh now. We see all three logs. Click to another page. Hey! The new log is
there! And... this *also* makes sense. Turbo replaces the old body with the new body.
And so, any script tags in the new body are parsed & executed.

But... this is *not* necessarily a good thing... for two reasons! First, re-parsing
the same JavaScript on every page is wasteful and can slow down your page. That's
what Turbo Drive helps us avoid.

Second, putting JavaScript into your body can... sometimes cause weird things to
happen. Watch closely: I'm going to clear my console.... then click back to a
page that I just visited a minute ago. Woh! There are *two* logs!

This logged once when the page *preview* was shown from cache and a second time when
the fresh HTML was rendered. This... might be okay? Logging two messages doesn't
hurt anything. But this might cause some big problems in other situations, like
double-counting page views in an analytics system. The topic of external JavaScript
is something we'll dive into a bit later.

Here's another issue. Suppose you - or some third-party JavaScript library - adds
an event listener to the entire document. Go back to `base.html.twig`. Use
the `document` variable. `document` basically represents the `html` tag, which
unlike the `body`, is *never* replaced by Turbo. Well, technically, `document`
is sort of like the *owner* of the `html` element... but that's not
important here.

Anyways, add an event listener to this: `document.addEventListener()` to listen
to the `click` event. On click, `console.log('document clicked')`.

[[[ code('bc26cc7db9') ]]]

We should be able to click *anywhere* to see this message. Refresh, go to
the console and... click. There it is! Click again and another log! Easy peasy.

Now clear the console and click to another page. Oh, let's clear the `console`
again. And... click. Ah! *Two* logs! That is *definitely* not what we want!

This happens because, each time we execute the script, it adds *another* listener
to the `document`. After 10 clicks, our function would be called 10 times!

Go remove the `script` tag and the jQuery loading code.

[[[ code('ba51eb9fc0') ]]]

[[[ code('aab9ad8dfa') ]]]

## Writing JavaScript that you (and Turbo) will Love

So... what *is* the best way to write JavaScript so it works nicely with Turbo Drive?
Well... Stimulus of course!

We already know from the first tutorial in this series that if a new
`data-controller` element appears on the page - like `data-controller="counter"`,
which powers this contest area up here, its Stimulus controller will *always*
work perfectly, even if that HTML is loaded via Ajax. *That* is the most
*powerful* part of Stimulus and it works *brilliantly* with Turbo.

One other lesson is that you should probably remove any JavaScript that you have
inside your `body` element... even though it mostly works. That's because of the
potential for the bad behavior that we saw a minute ago. In a little while,
we'll talk about external JavaScript - like widgets or analytics - which are often
supposed to be added to your body.

But let me be clear about one thing: I do *not* want you to think about all of
this like:

> Hey! Turbo is forcing me to write my JavaScript a certain way!

Nope: Turbo is forcing you to write *better* JavaScript: JavaScript that only needs
to be loaded and executed *once*... and then keeps on working forever, even as new
content is loaded onto the page.

So this whole JavaScript topic is definitely the biggest hurdle to using Turbo
Drive. Until you have all the JavaScript on your site written properly, things won't
work well. But you *can* fix the JavaScript for just *some* pages on your site and
activate Turbo Drive only for those. Let's see how next and also learn how we're
able to put all of our `script` tags into the `head` element *without* hurting
page-load performance.
