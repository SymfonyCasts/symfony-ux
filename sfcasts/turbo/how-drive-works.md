# How Turbo Drive Works

This is Turbo Drive. And yes, it feels like absolute magic. So let's break down
how this works.

## How Was Turbo Activated? The Magic Stimulus Controller

To start... we never wrote any JavaScript that said:

> Hey Turbo! Please activate your Drive functionality.

So... how would did this automatically start working? That is thanks to the magic
of the `assets/controllers.json` file. This is normally a mechanism in Symfony
UX for third party libraries to add new Stimulus controllers to our app. And
in this case, that's true... but it's kind of a trick.

Let's go find the file that's being referenced. It lives in
`node_modules/@symfony/ux-turbo` then `src/turbo_controller.js`. If you're wondering
how I knew to open this exact file... this `turbo-core` string here matches up with
a special key inside of the `package.json` file of this library. So `turbo-core`
points to `dist/turbo_controller.js`. So, technically the file in the `dist/`
folder is loaded... but I'm opening the original in `src/` because it's a bit
easier to read.

And... there's not much here! This exposes an *empty* controller. And really, the
*whole* point of this file is to import `Turbo` and set it onto the `window` variable.
This accomplishes two things. First, when you import `Turbo`, it automatically
activates Turbo Drive across your entire site. We'll talk about how to disable it
globally or selectively a bit later. And second, Turbo is set onto the `window`
variable, which makes it a *global* variable. You may or may not need this. It's
useful if you need to programmatically visit a link, but from outside a JavaScript
file. We'll see that later.

So we now know *who* activated Turbo. But... how the heck does Turbo Drive work?
It's a pretty simple idea. Turbo watches link clicks - and also form submits like
this add to cart form submit - and *intercepts* them. It then performs those
requests in the background via an Ajax call, which we can see here. When
that finishes, it updates the HTML of the page from the HTML in the response...
*all* without a full refresh. But, it *does* modify the URL, which gives us
normal browser behavior, like clicking back and forward.

## Snapshots & Previews

Speaking of back and forward, Turbo Drive has a feature called "snapshots". Let me
refresh the page real quick. As you navigate to a new page, it stores a "snapshot"
of the page you're leaving. Then, if you click back in your browser, it instantly
loads that snapshot with *no* network request. It does the same if you go forward.
And if you *revisit* a page that you've already been to, so, a page whose snapshot
has been stored, Turbo will give you an *instant* "preview" of that page while it
waits for the Ajax call for that page to finish. You can see how *super* fast the
pages are that we've already gone to versus ones that we have *not* gone to yet.
By the way, this snapshot cache isn't persistent: it clears when you refresh the
page.

Some of this preview & snapshot stuff is kind of hard to see because things are so
fast. So in your editor, open up `public/index.php` and add a `sleep()` for two
seconds.

[[[ code('92602b0191') ]]]

Now head back to your browser and refresh the page... which takes 2 seconds.
Click back to the homepage. Oh! This shows off the progress bar! If an Ajax
call takes longer than 500 milliseconds, the progress bar shows up, which
you can customize with CSS if you want. Because our site is slow, we see it each
time we click to a new page.

But now, let's click *back* to "Office Supplies", which we visited before. When
I do, watch closely: the page will show up instantly. Boom! *Then* it finishes
loading the Ajax call. This is the *preview* feature. When you navigate to a page
you've already been to, Turbo loads the page from cache for an instant experience.
But it *still* makes an Ajax call for the page. And when that finishes, it takes
the new HTML and renders it onto the page. Most of the time - like right now -
we don't really notice that Ajax call finishing... because the new HTML is identical
to the preview.

And if we click backward and forward, as we mentioned earlier, those pages load
instantly with *no* Ajax request. Let's go take out that `sleep`.

## Merging of the &lt;head&gt; Tag

Okay... but how does this all *really* work? What is Turbo doing behind the scenes
to make it all happen? Let's go a step deeper. This is important because, to get
Drive working happily on your site, as the saying goes, the devil is in the details.
We'll spend the first part of this tutorial talking about potential problems that
Turbo Drive can cause and how to fix them.

Let me refresh the page again to clear the snapshot cache.

Okay: when we click a link, Turbo intercepts that and makes an Ajax call instead.
Oh, by the way, these extra Ajax requests are for the web debug toolbar.

*Anyways*, the Ajax request that Turbo makes when we click returns a *full* HTML
page. When Turbo gets that full HTML response, it *merges* the *new* `head` tag
into the existing `head` tag and then *replaces* the `body` with the *new* `body`.

The way it merges the old and new `head` is smart. Go over to the Elements part
of the debugging tools and open up the `head` tag.

When the Ajax request finishes, Turbo first finds anything in the `head` *other*
than JavaScript and CSS elements and *removes* those. Then it looks in the *new*
`head` for any non JavaScript and non CSS elements and *adds* those.

We can actually see this. Reload the page and look back at the `head`. I see two
non-JavaScript and non-CSS tags: a `meta` tag with the `charset` and the `title`
element. When I click to go to another page, these will be removed. Then, any
elements from the *new* page's `head` will be added to the bottom. And... boom!
The new page happens to have the same two tags, but you can see that the original
ones were removed and the new ones added. I was lazy and didn't give each page a
unique title, but if the next page *did* have a new title, it *would* show up.

## How JavaScript & CSS is Handled

Let's talk about how JavaScript and CSS is handled. If the *new* `head` tag contains
JavaScript or CSS tags - and it probably will, since we're returning full HTML
pages - Turbo checks to see if these elements already exist in the *current* `head`.
If they do - like the next page we click *also* has a script tag for
`build/runtime.js` - then Turbo ignores it. There's no reason to add the same
`script` or CSS multiple times. But if the CSS or JavaScript element does *not*
exist on the current page, it *will* add it. This is actually a big reason why
Turbo Drive feels so fast: each time you navigate, your browser does *not* need
to re-parse all of your JavaScript and CSS like it *normally* would with a
traditional full page reload.

The result of all of this is... exactly what we see as we click around! The title
changes on each page - the login page has a different title - and *if* a page
contained new JavaScript or CSS, that would be added automatically.

So... this is amazing! Well, yes, it *is* amazing. But to get this amazingness to
work perfectly, there *is* a little bit of work that we need to do. The first bit
involves making your JavaScript Turbo-friendly. Let's dive into that topic next.
