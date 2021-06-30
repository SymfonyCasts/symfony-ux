# Prefetching the Next Page

I have a crazy idea. What if, when the user hovers over a link, we *prefetch*
that page via Ajax and saved it as a snapshot cache? Then, assuming the user *does*
click that link, Turbo would show the page *instantly* via its preview system.

Is that possible? Well, not *officially*. But thanks to some clever people on the
Internet, it is! Let's learn two different ways that can we can make the performance
of our site *even* faster... and the caveats that go with both - neither is perfect
out-of-the box. But both are *super* interesting.

## Prefetching on Hover

If you downloaded the course code, you should have a `tutorial/` directory
with a `prefetch.js` file inside. Copy that and paste it into `assets/turbo/`.

Ok: this is *not* my script: it comes from a gist that I attributed on top.
This script automatically makes an Ajax call whenever a user hovers over an anchor
tag and saves the response as a Turbo snapshot. Then, if the user *does* click that
link, the page will be displayed *instantly* thanks to the preview. To avoid
*totally* spamming the server with requests, this code waits for the user to hover
for 65 milliseconds before sending the Ajax request. The idea is to take advantage
of the brief pause between when a user starts to hover over a link and when they
actually *click* that link. This approach does have some downsides, but let's see
it in action before we chat about them.

Open up `app.js` and import this: `import './turbo/prefetch'`. That's enough to
activate the new behavior.

Also open up `styles/app.css` and comment-out the `opacity` transition that we added
before. The pages are going to be *so* fast that this won't be needed.

Moment of truth. At your browser, refresh. I'm going "casually" click on the
Furniture category. Woh - that was fast! All these pages are now loading as *if*
we've already visited them... because... we actually have! The perceived performance
of our site just took another huge step forward.

## The Downsides of the Hover Prefetch

But that was *too* easy! So what are the downsides? There are a few. The first is
that your site is going to get hit by a lot more requests. If you hover over a link
but never click it, that's an extra, unnecessary request! But worse, even if you
*do* click the link, *two* requests are made! Watch, I'll refresh, then clear my
network tools. I'll hover over "Office Supplies", then click. Check it out: *two*
requests were made for the same page! The prefetch script made the first request
to store the page as a snapshot for the preview. But then, like normal preview
functionality, after showing the preview, Turbo made a *second* request to load a
"fresh" version of the page. That's a bummer.

Another downside is that, if your page doesn't load fast enough, this won't make
any difference! For example, let me clear the network tools again. I'm going to
hover and then click "Breakroom" *really* fast. Watch: that time, the page did
*not* load instantly because the first prefetch request had *not* finished by the
time I clicked.

In fact, when you look at the second request that Turbo made, it "stalled": the
second request waited for the first. To be fully honest, I'm not actually sure
*why* my browser waits like this... but it means that if the user clicks before
the prefetch request finishes, it may actually be slowing *down* the experience.

The *last* problem is that the prefetch script will also try to prefetch links
that we don't want it to - like a "log out" link. Yup, right now, if we hovered
briefly over a log out link, that... would log us out.

In the script, search for `dataset`. You *can* add a `data-prefetch="false"`
attribute to any link to disable the behavior for that link. Or, by customizing
this line a little, you could *disable* the prefetch behavior by default and only
*enable* it if the link has `data-prefetch=true`. That would be a safe way to
enable this only on links that make sense to you.

## The "prefetch" link Hint

There's also another way to use this script, which you can see at the bottom. If
you add a `data-prefetch-with-link="true"` attribute, instead of making an Ajax
call, it will add a `<link rel="prefetch">` element to your `head` tag. What
does that do? It enables a really neat feature that's native to your browser.
Let's learn about it next.
