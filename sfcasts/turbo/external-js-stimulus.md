# Reliably Load External JS with Stimulus

Thanks to the turbo-frame system, we're now lazily-loading just *part* of the
weather page down in the footer. And... notice that this *is* working... which
actually proves something: `script` tags inside frames *are* executed.

## script Tags in Frames Are Executed

Let me find that frame... here it is. Ok, so no surprise: if you have a `<script>`
tag that's included in a `turbo-frame`, Turbo *does* execute that... *exactly* like
how Turbo *Drive* executes any `script` tags found inside the *body* element.

That's great! But... we have a bug that's hiding. Well, sort of *two* bugs. Yikes!
To see the first, scroll to the top of the page, refresh but don't scroll down. Now
click to the weather page... and check out the console. Error!

> Uncaught reference error: `__weatherwidget_init()` is not a function

And it's coming from `turbo-helper`. Go open that file - `turbo/turbo-helper.js`
and scroll down to line 71. Here we are: `initializeWeatherWidget()`.

If you scroll back up, this `initializeWeatherWidget()` function is called when
the `turbo:render` event is dispatched. Its job is to *reinitialize* the weather
widget on the next page. The problem is that, in this case, the weather widget
JavaScript hasn't *quite* yet been loaded onto the page... because it didn't load
at all on the first page. And the *real* problem is that... well... I didn't code defensively.

Fix this by adding an if: if `typeof __weatherwidget_init === 'function'`, *then*
call this. Otherwise, it means the JavaScript hasn't been loaded... so no reason
to do anything.

## The Weather Widget JavaScript is not Always Reinitialized

So... this would fix *one* problem... but not our *bigger* problem. To see that one,
over on the product page, below the sidebar, I want to add a *second* weather widget.
Open the template for this page: `templates/product/index.html.twig`. Oh, but
actually, the sidebar is in `productBase.html.twig`.

Cool: right here, I'm going to add `<turbo-frame>` with `id="weather_widget"` -
to match the id that we've been using so far - and `src="{{ path('app_weather') }}"`.

Try it! Refresh and... bah! It works - but I put it in the wrong spot! I meant
to put it in the `<aside>`. Let's try that again. Refresh now and... beautiful.

*Now* scroll to the footer. It's busted! Hmm... the turbo frame did its
job - the HTML is here - but the JavaScript didn't initialize! What happened?

Let's remember how this is *supposed* to work... because it's getting kind of
complicated. On page load, or really, anytime that the weather JavaScript is first
executed, it adds a `<script>` tag to the page, which downloads an external
JavaScript file. *That* JavaScript finds any elements on the page with a
`weatherwidget-io` class and initializes the weather widget inside of them.

But... when we surf to another page, this external JavaScript file is *not*
re-executed... because this function is smart enough to not add the same script
tag multiple times. We hit this problem earlier. To fix it, back in `turbo-helper.js`,
we added this `__weatherwidget_init()` code, which is executed on `turbo:render`.
So basically, each time Turbo renders the page, we call `__weatherwidget_init()`
and *that* reinitializes the weather widget for that page.

This worked *great* when the *only* way that a weather widget tag could be added to
a page was as a result of a Turbo Drive navigation. But now, this tag is sometimes
loaded onto the page via Ajax by a Turbo Frame... and that does *not* trigger
the `turbo:render` event... because we're not rendering a full page. In other words,
when a Turbo frame loads, nothing is calling the `__weatherwidget_init()` function!

If you're watching *really* closely, you might be wondering how the weather widget
in this lazy frame was *ever* working... since we were *never* calling the
`__weatherwidget_init()` function after it loaded. It worked simply thanks to some
smart code that lives inside that function. If you looked at the external JavaScript
in detail - which we did a bit earlier - you would see that when you call the `__weatherwidget_init()` function, if it does *not* find any `weatherwidget-io`
elements on the page, it automatically recalls itself every 1.5 seconds *until* it
finds one. This... almost accidentally... made sure that once our lazy frame in
the footer loaded, the JavaScript was initialized within 1.5 seconds. But... it
wasn't a very robust solution, and it stopped working as soon as there was a
*second* widget on the page that loaded earlier.

So let's fix *all* of this and simplify our code a *bunch*... because it took
*way* too long to explain how this has been *barely* working.

How can we improve this? By creating a Stimulus controller! I know, this tutorial
is about Turbo... but since Turbo *really* works best when you have *no* inline
script tags, let's see how Stimulus could help us manage this external JavaScript.

## Creating the Stimulus Controller

Here's the idea: let's attach a Stimulus controller to the `weatherwidget-io` anchor
tag. By doing that, *whenever* this element appears on the page... no matter *how*
or *when* it appears, we can run some code... like `__weatherwidget_init()`.

In `assets/controllers/`, create a new file called, how about,
`weather-widget_controller.js`. I'm going to cheat... as usual... and steal the
code from another controller, paste... then clear everything out. Start with
a `connect()` function and `console.log('🌦')`.

Next, over in `weather/index.html.twig`, find the anchor tag and add
`data-controller=""` and the name of our new controller: `weather-widget`.

Okay! Let's make sure that's connected. Head over, scroll up... refresh the homepage
and check the console. Perfect! This log is coming from the weather widget on the
sidebar. Now watch what happens when we scroll down... a second emoji!

The next step is to move all of this JavaScript into our Stimulus controller.
Copy everything and delete the `<script>` tag entirely. In the controller, after
`connect()`, paste! That is *totally* invalid JavaScript... and my build system
*and* editor are freaking out. Let's turn this into a function called
`initializeScriptTag()`. Copy these three arguments and remove them. Cool.

Up in `connect()`, instead of logging a cloud, say `this.initializeScriptTag()`
and *pass* those three arguments.

So... this isn't perfect yet... but it's closer: each time Stimulus sees a matching
anchor tag, it's going to run this.

Let's try it. Scroll back up to the top, refresh and... awesome! The fact that this
loads means that our Stimulus controller *did* just execute and add the script
tag. If you look in the `head` of our page... there it is!

But... if we scroll to the bottom of the page... that still *doesn't* work. It's
ok, we expected that: we still need to move the `__weatherwidget_init()` code into
Stimulus.

Copy the entire if statement, delete the `initializeWeatherWidget()` function, scroll
up and remove the event listener entirely. Over in the `weather-widget` controller,
up in `connect()`, paste that and then move the `initializeScriptTag()` call,
which I *totally* misspelled... let me fix that - move that into the `else`.

So *if* the `__weatherwidget_init()` function already exists, just call
it! Else, run the code to add the original `script` tag to the page.

I think we're ready! Scroll back up to the top of page and refresh. The sidebar
works... the footer works... and, if we go to the weather page, that works too!

I *love* this approach. Even though our external JavaScript is *not* written in
Stimulus, we can still *use* Stimulus to activate this JavaScript *exactly*
when we want to. At this point, we can add this anchor tag *anywhere* on our site,
and it *will* instantly do the work to initialize itself.

Next: let's investigate the second-use case for Turbo Frames... and really the
*main* use case: the ability to keep navigation isolated to one section of the page.
