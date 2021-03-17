# Async import()

Coming soon...

One of the bigger items in the analyzer - though not the biggest, we'll get to
those later - is `react-dom`. It's kind of
unfortunate that the user needs to download this on every page only to render a
pretty unimportant spot on the footer as part here, and then a sidebar that exists on
just one page. Can we fix that somehow? Can we improve it? We can. And we have two
options. The first is called an async or lazy import, and it can be used inside any
JavaScript file. It has nothing to do with stimulus. I'll show you that one first,
but in the next video, I'm also going to you a trick that is special to stimulus and
Symfony, okay. Over in a project, let me close a few files, head up to assets
controllers, and let's open a feature product react controller, and also made with
love controller. These are the two files that use react dumb. What we're going to do
is actually remove this import up here for react on. And instead inside of connect,

We're

Going to use import as a function import open parentheses react dash Dom. When our
code hits that line, it's going to download that. Then do that then, and this will
get past that react down module

And

Then do an->function and move, react to that Dom inside of air. And the last thing we
need to do is say dot default,

That's it. This is called an async import. And we've talked about that a few times on
Symfony casts. This allows Webpack to isolate the react dash Dom code into its own
file. Then it won't actually be downloaded until this import line is executed. What
it is, it will be downloaded via Ajax, which is why we have the dot then here. So
after it's loaded via Ajax, then we will render our code. This default thing here is
just something you have to do when you use async imports. When what you're trying to
use is a default import. So, um,

Yeah,

Anyways, what this means is that as soon as this controller is actually seen on any
page at that moment, the downloadable start for react dash dump. The only downside is
that there may be a slight delay before this component renders. If you need to, you
can add some sort of loading animation. Let's repeat this in the other controller.
I'll actually copy this import, go and move to import on top. I'll paste this down
there and then I'll just bring up my react dot Dom, paste it inside and make sure we
have the

That's false.

Oh, and Garrett is extra important up here. That happened when I pasted the other
code in. Beautiful. All right, first, let's make sure we didn't break anything. Head
back to your terminal. And you can see that our terminal is running that Webpack. Why
back bundle analyzer server hit control CNET and run our usual yarn watch

Then over at your browser. I'm going to, for that to finish there it is. I'll refresh
the page and you can see a slight delay there, but it works and check out the network
tool. So now in here, make sure you're on network and make sure that you filter to
JavaScript like this. You can see that there's a one-time event, this long vendors
thing and app dot JS. Those are the three jobs for files are being loaded on the
page. I know that because the initiator says register. That's the URL for this page
that tells me that these are actually script tags in my HTML, but this last one here,
vendor node models, react to Dom, which you could tell by the name that holds react
on. It comes from a load script. There we go. That tells me that it was actually
loaded after the page load via JavaScript. And we can actually see it over here.
Here's the waterfall of when things were loaded by the auto-complete tonight, it was
loaded way later.

Okay.

So yeah, this didn't start downloading until the connect method in our controller was
actually called. So let's rerun our analyze to see what difference that made is the
fact that the terminal stop Encore. And then we're going to rerun our yarn run, build
to get a fresh Jace on file. I'm building this in production. You don't have to do
that, but it makes things a little bit more realistic. Production goals are slower.
So this'll take a little bit longer. Okay. Then run our yarn Webpack bundle analyzer.

Okay.

Let's see what it looks like. Ooh, close the sidebar here. Okay. This is really cool.
You can see over here, react. Dom is isolated into its own file. If you go back and
let me actually close these other tabs and view source on our page, actually let me
refresh first and resource. You can see that the script, there's only three script
tags right now. There's the runtime six 43 and app. So over here, runtime six 43 an
app. These are the three JavaScript files are being loaded on the page, which means
this is not being loaded on the page. As we saw a second ago, the three files that
are, are now smaller than they were before. Thanks to react on being eliminated. So a
sick imports are awesome. They're a native Webpack feature that you can use in any
file, even outside of a stimulus controller. But I want to go further. Could I make
an entire controller lazy?

Okay.

Like both all of its code and all of its dependencies.

Where are we wait

To load all of that code until the controller actually appears on a page. And what
about controllers from Symfony UX? Like our charge as controller that controller,
thanks to chart that controller, as you can see, because it imports the chart JS
library is huge and only used on the admin page. Can we make that lazy next? Let's
learn about one of the neatest tricks of stimulus bridge.

Okay.
