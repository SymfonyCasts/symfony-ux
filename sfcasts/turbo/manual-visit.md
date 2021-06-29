# Manual Visits with Turbo

Sometimes you need to trigger a Turbo visit programmatically... like after
running some custom JavaScript, you want to send the user to another page.

Head over to your code and open `assets/controllers/counter_controller.js`. This
very advanced Stimulus controller powers this high-tech "click for a chance to
win" area. Each time I click the button, the counter goes up. Amazing!

Let's pretend that, after 10 clicks, the user wins and we want to redirect them to
a "you won!" page. Let's first do this with normal JavaScript. Inside of the
`increment()` method - which is called each time we click - say, if `this.count`
equals `10`, then redirect using raw JavaScript: `window.location.href` equals
`/you-won`, which is a page I already created.

[[[ code('d0014f3439') ]]]

Let's make sure this works. Refresh the homepage... click a bunch of times and...
eureka! We're winners! But... that worked via a full page refresh, *not* via Turbo.

## Navigating with Turbo

Could we navigate *with* Turbo? Totally! Start by importing Turbo into this
file. This is the most complicated part because... the syntax looks a little
funny. It's `import * as Turbo from` and then the name of the library, which
is `@hotwired/turbo`. The `* as Turbo` is needed due to how that library exports
things.

Down in the method, instead of `window.location.href`, we can say `Turbo.visit()`
and pass in the URL.

[[[ code('4c63b84939') ]]]

Let's try it again! Go back to the homepage and do a full page refresh. Actually...
it did a full page refresh automatically because of the asset tracking we
created in the last chapter. Cool!

Time to click! Watch when we get to 10. Beautiful! That navigated with Turbo. We
can see the Ajax call right here. And... yea! It's just that easy.

But if you want to be more hipster, you can use de-structuring to *just* import the
`visit` function. It looks like this `import { visit } from '@hotwired/turbo'`. Then
below, literally call `visit()` as a function.

[[[ code('64b7d3afad') ]]]

This will work exactly the same as before.

## What if Turbo isn't Available?

There's one other tricky situation that you might run into when it comes to navigating
with Turbo: if you're writing JavaScript... but you are *not* in a file that's
parsed by Webpack. In other words, you're somewhere where you *can't* use the
`import` keyword.

This is probably not very common and, really, in a perfect world, 100% of our
JavaScript *will* be written in a Webpack-parsed file.

But just in case, let's see how we can navigate with Turbo from inside some
*inline* JavaScript on our page. Open up `templates/base.html.twig` and head to the
bottom. Right before the closing `</body>`, add a `<script>` tag. We're going to pretend
that when we click the logo... which has `id="logo-img"`...  that we want to go to
the cart page.

Do that by saying `document.getElementById()`, pass it, `logo-img`,
`.addEventListener('click')` and pass an arrow function with an `event` argument.
Inside, say `event.preventDefault()` so that it doesn't follow the link that the
image is inside of. Oh... yikes! I forgot my comma. That's better.

How can we fetch the Turbo object to trigger the visit? It turns out... it's already
available as a global variable! So we can immediately say: `Turbo.visit('/cart')`

[[[ code('072306d92f') ]]]

That's it! But... who *set* Turbo as a global object? I don't remember doing that!
Starting in Turbo 7 beta 6, when you import the `@hotwired/turbo` library, it
automatically sets itself as a global variable. So if you have Turbo working on
your site, there *is* a `Turbo` global variable, which is done to help with this
exact situation.

Anyways, if we go and do a full page refresh... then click the logo image, instead
of going to the homepage like it normally would, it navigates us - via Turbo - to
the cart page.

Next, we are now done with all the Turbo Drive tricky parts! Before we move onto
Turbo frames, let's try doing a few fun things. The first will be to experiment with
adding CSS transitions as we navigate between pages with Drive.
