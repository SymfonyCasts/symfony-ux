# Magic: Lazy-Loading UX Controllers

If we look at the current analyzer, it's pretty obvious what our biggest problem
is: `chart.js` is *gigantic*. That's included because the `ux-chartjs` controller
imports `chart.js`.

And... what a shame! We only use this controller on an *admin* page. Yet, *every*
user has to download it no matter *what* page they go to. Could we... somehow
*only* download that code if a user goes to the admin page?

Actually... we can do something even cooler. We can set things up so that the
JavaScript for the controller - including `chart.js` - is only downloaded - and
*automatically* downloaded - when a `data-controller` element for that controller
appears on the page. It's... magic.

## Making a UX Controller Lazy: fetch=lazy

Open `assets/controllers.json`. This file is automatically updated by Symfony
Flex whenever we download a new UX package. But we are *totally* free to tweak
this. For example, we could change `enable` to `false` if we didn't need that
controller. Or we could change fetch to `lazy`.

What does that do? At your terminal, hit Ctrl+C to stop the analyzer and let's
dump a new stats file. But this time, I'm going to switch to do a `dev` build...
just because it's a little bit faster... though less realistic than profiling
a `production` build:

```terminal-silent
yarn run --silent dev --json > stats.json
```

When that finishes, which will still take a little bit of time, run the analyzer
again:

```terminal-silent
yarn webpack-bundle-analyzer stats.json public/build
```

And... woh! Awesome! All the `chart.js` code - including the controller - now
lives in its own file! Thanks to this, the other files are much smaller.

Let's close a few tabs... click to go to the homepage and then view the page source.
Check out the file names on the `script` tags... Yeah! `chart.js` is *not*
being loading! The only files that *are* loaded are `runtime.js`, `app.js`
and this now, pretty small, long vendors filename. You can also see this if you
go to the "Network" tools and filter for JavaScript. The long `chart.js` filename
is *not* in this list! It is *not* being downloaded at all.

*Now* go to `/admin`. There's our chart! How the heck did that just happen? I
thought the `chart.js` code wasn't being downloaded!

## How does Lazy Fetching Work?

When you set `fetch` to `lazy`, instead of importing the real controller and its
dependencies, the `stimulus-bridge` library creates a tiny, "fake" controller. That
controller *waits* for a `data-controller` element matching the controller name
to be added to the page. As *soon* as it sees one, it asynchronously downloads
the *real* controller and executes it. That's bonkers!

Let me show you *how* amazing this is. Find the `canvas` element that holds the
`data-controller` attribute, right click and go to "copy outer HTML".

Head to the homepage. As we saw a minute ago, the `chart.js` code is *not* loaded
on this page at *all*. Let's clear out the Network tab under JavaScript. Now,
inspect element on the `h1` - though the location doesn't matter - edit this div's
HTML and paste the `data-controller` element onto the page.

Ready? Click outside of this to activate that. Boom! We see the graph! And check
out the "Network" tab. Woh! It asynchronously loaded our `chart.js` JavaScript
code! For performance reasons, it even split `moment.js` into a *second* file and
downloaded that at the same time!

*This* is the beauty of the `lazy` fetch. You can add a `data-controller` element
to any page - or even load that HTML via Ajax - and your Stimulus code *will* work.
If the controller code hasn't actually been downloaded yet because you decided to
make it lazy, no problem! It will be downloaded automatically.

But what about our own *custom* controller code? Back on the analyzer tab, one
of the biggest modules left is sweetalert... which we only use on *one* page for
our submit-confirm controller. Could we somehow only download that code when an
element with `data-controller="submit-confirm"` appears on the page? We can! Let's
find out how next.
