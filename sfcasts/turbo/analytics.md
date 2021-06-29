# Fixing External JS + Analytics Code

Head back to the Turbo docs, specifically to Reference and then Events. We saw this
list of events earlier. Now we're going to hook into a *new* one:
`turbo:before-render`.

## The turbo:before-render Event

Here it is. This event triggers before Turbo renders a page, but *not* counting the
initial page load. In other words, it triggers when Turbo is *specifically* responsible
for rendering the page. We can use this to help our third party weather widget get
working right before the page renders.

Head over to `assets/turbo/turbo-helper.js` and, up here in the `constructor`...
say `document.addEventListener()` to listen to `turbo:before-render`. Pass this
an arrow function and then log "before render" so we can see *exactly* when this
does and doesn't execute.

[[[ code('6e598fda0d') ]]]

Cool. Let's test it!

Find your browser, refresh, and open the console. Okay. So nothing on initial page
load. But then, when we click to another page, there it is! Click to another page...
there's a second one. Click to the homepage, a third one. Awesome.

*Now*, clear out the console... and go back to a page we went to a second ago.
It logs twice! This is an important detail about this event. It fired twice because
*first* the preview was rendered and *then* the final page was rendered. Just keep
that fact in mind.

## Removing the Weather Script Tag Before Render

So here's the plan: right before the page is rendered, so inside of our new listener,
we're going to find and remove this `weatherwidget-io-js` script tag. Then, with
any luck, when the new page is loaded, the JavaScript from our base template will
execute, it will re-add that script tag and everything will work!

Let's check it! Replace the log with `document.querySelector()` and look for
`#weatherwidget-io-js`. Then say, `.remove()`. You can also code defensively
to make sure the element *exists* first before trying to call `remove()`... not
a bad idea.

[[[ code('53dea88fb2') ]]]

Ok: refresh. It works and... navigate to a different page. Yea! It *still* works!
If you look inside the `head` element, it accomplishes this *without* duplicating
the `script` tag.

## Calling the External Script Directly on Navigation

I like this solution. But if you're willing to do some digging, there *might* be
an alternate solution.

Copy the `widget.min.js` URL and open it in your browser. It's minified... so pretty
unreadable. Copy the source, close it, spin over to your editor and create a
new file anywhere, like `pizza.js`... we're not going to actually use this. Paste
the code, select it, then go back up to Code -> Reformat Code so we can at
least, *kind of* read it.

It's still not super clear, but... let's see. Ah! There's a function called
`__weatherwidget_init`... and it looks like *this* might be the key to re-initializing
the weather widget! In other words, instead of removing and re-adding the `script`
tag on each render, we might be able to just... call this function!

## The turbo:render Event

Let's do some experimenting! Start by changing the event from `turbo:before-render`
to `turbo:render`... that's another new event. Why are we switching to it? In order
for the `__weatherwidget_init` function to work, the new `weatherwidget-io` anchor
tag needs to actually live *on* the page.

But `turbo:before-render` is triggered too early: it's triggered *before*
the new body is on the page. Fortunately, `turbo:render` is called *after* it's on
the page. This means that, inside of the callback, we know that the *new* body
*will* be on the page. And so, we can call that `__weatherwidget_init` function.
Let me steal that name from the other file... and paste it here.

[[[ code('739bb961bf') ]]]

Testing time! Refresh! The first page works: no surprise. And when we go to a second
page... yes! It still works! No matter how many pages we go to, it keeps working.
I like this solution better, though, I also realize that we're sort of using an
"internal" function from that widget script... and it's possible they could change
their JavaScript some time in the future.

Now that we have this working, let's refactor this logic into a method for clarity.
Copy the `__weatherwidget_init` function, go to the bottom of the class and create
a new method, how about `initializeWeatherWidget`. Paste, then call that from up
here in our listener: `this.initializeWeatherWidget()`.

[[[ code('835bf6be19') ]]]

## Solving External Widgets with Stimulus?

By the way, there *is* a third way to solve this problem, and we'll talk about it
later. It's especially appropriate if you need to load an external widget -
like our weather widget - but that widget might be loaded onto the page at any time,
even via a custom, non-Turbo Drive Ajax call. This solution basically involves running
the same code that we have here, but leveraging a Stimulus controller.

## Handling Analytics Code

Before we move on, we do need to talk about *one* last type of external JavaScript:
analytics code. As an example, here's what Google analytics code looks like: this
is what you're supposed to paste into the `head` tag of your page.

It turns out that the key line that triggers the visit is this last one:
`gtag('config')`. If we pasted all of this onto our site, guess what would happen?
It would register the first visit... then the code would *never* execute again,
no matter how many pages the user visited. That's not great. Fortunately, single
page applications - like those written in Vue or React - have the same problem....
and you can often find docs that talk about how to integrate with *those*.

In this case, the solution would be to paste all this code - *except* for the
`gtag('config')` line - into your `head` like normal. For this last line, we need
to execute it on initial page load and then every Turbo "visit" after.

## The turbo:load Event & Analytics

Let me open a [GitHub issue](https://github.com/turbolinks/turbolinks/issues/73#issuecomment-812484452)
that talks about this with a really nice solution. As you can see here, `henrik`
is using a `turbo:load` event. That's yet *another* event that we haven't talked
about yet. `turbo:load` is nice because it's executed on initial page load and
*one* time for every visit: it avoids the "double dispatch" that happens with
the `turbo:before-render` and `turbo:render` events when you visit a page that shows
a preview. In other words, `turbo:load` is triggered *exactly* when you would want
your analytics code to trigger a visit.

Inside the callback, `henrik` calls `gtag('config')` to trigger that visit. This
`googleAnalyticsIDForScript` thing is just their way of referencing whatever your
Google Analytics ID is. The one special thing that you need to do with this function
is pass a little bit of extra data to make sure analytics knows what the actual
URL is that it should use.

Next: we already know that, with Turbo Drive, we download each CSS and JavaScript
file just *one* time. Then, as we navigate around, if Turbo sees a CSS or JS file
in the new page's `head` tag that already exists on the *current* page, it ignores
it.

But what happens if we deploy a new version of our site and the content of these
files has changed? How can we force the user to download the newest version of our
assets? That's an important question.... and one where the answer is refreshingly
simple.
