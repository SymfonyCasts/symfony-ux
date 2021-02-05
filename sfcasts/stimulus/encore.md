# Encore

Coming soon...

So let's get Webpack Encore installed so we can get a proper CSS and JavaScript

[inaudible]

Build system set up, find your terminal and do that with composer require Encore.
This really installs Webpack Encore bundle, which is great. We need that, but the
true super power is it's this bundles recipe

[inaudible]

After it finishes, I can run to get status and it actually modified several files and
added several new config files. The really important thing for us is it created a
Pakistan JSON file. I'll check that out over here with some basic dependencies,
including stimulus. We'll talk about that later. And a Webpack that config, that JS
file with a basic setup. There are a few have also created an assets directory. There
are a few things in here related to stimulus like these controllers things and this
bootstrap thing. But we'll talk about that in a few minutes. Install the new note
dependencies from Pakistan, JSON, by going back to your terminal and running yarn
installed, By the way, if you're totally new to Encore and why I start a course, we
have

[inaudible]

Right now. This is a pretty boring Webpack setup. We have one entry was which is this
assets app. That JS file.

Yeah,

It doesn't really do anything except that it imports styles /app that CSS, which
makes our background color gray

[inaudible]

To get the JavaScript. Once we actually have some from this file and the CSS from
this file

Loaded onto every page, head into templates /at base that HTML that twig, you can
see, I already have a couple of styles up here in the style sheets block, add curly
curly on port entry, link tags and pass it app, which is name of that entry file.
This will render the built version of the app, that CSS file plus any other CSS that
are JavaScript imports and actually for performance reasons, Webpack may split that
built app that CSS into multiple files. This function takes care of including all the
link tags we need did the same thing in the JavaScripts

That block

Currently currently on-court and treats script tags

App like what the styles

Will render the built a version of app dot JS plus any JavaScript it imports. And it
may also be split into multiple.

Oh,

And you're seeing a recent change in Symfonys recipe. The JavaScript life used to
live down here at the bottom of the page by default.

No,

It lives up in the head and a new project.

Bye.

Thanks to a new feature in Webpack Encore bundle, you can see it in config packages,
Webpack Encore.yaml. Here it is script attributes.

Okay.

Every script tag rendered by Encore will have a differ attribute.

That basically means

That that the, that the JavaScript isn't executed until after the page loads, very
similar to having the scripts on the bottom of the page,

Even though it lives up here,

I want to learn more about this change. You can check out a blog post I wrote on
Symfony.com.

All right, let's try this first.

We need Encore to build the assets. We can do that by going back to the terminal and
saying yarn watch,

Hm,

This will build the assets into a public, build a directory. You can see that here
And then sit and watch for more changes. So it can automatically build again if we
modify anything now. And when we move over and refresh,

While

We have a great background, which proves the CSS is working. If you view the page
source, you can see one link tag here for build /app that CSS. And you can actually
see the script tag is already being split in two, three different script tags. Again,
that's not something we need to worry about. It's just done for performance and the
Encore functions take care of rendering, all the files we need. Okay, let's move our
styles that are currently in public styles, that CSS into our new file. That's being
processed by Webpack. So I'll open that up, copy everything inside. Then I will go to
refactor,

Delete,

Then go to our new assets styles at that CSS. And I'll remove the old, the body
thing, and then paste that there In base at HTML twig, I can remove this old link tag
that pointed directly to the static file. When we reload. Now it works perfectly and
that's because over the terminal, you can see it rebuilt automatically when it saw
that we modified our app, that CSS file

While

We're here, we also have a link tag that points to bootstraps CDN, which is okay, but
we can now properly install bootstrap into our app. First, over at your terminal, I'm
going to open a new tab. So I kind of just let yarn watch, go in the background and
I'll write yarn, add bootstrap dash fashion dev the dash dash dev part is not that
important. Perfect. That downloaded bootstrap into our node modules directory delete
the old link tag, which temporarily would make our site super ugly and then go into
our app dot CSS file. And on top we can import this. I'll say pad import, and then
inside quotes say Kilday bootstrap, that's it. Webpack will magically load all the
bootstrap CSS and include it in the final built app. That CSS file the till day here
is a magic character that tells Webpack to look in node modules. So why don't we
refresh now? It looks much better though. If you view the source, you can now see
that even the CSS file is being split into two pieces.

Okay.

Again, that's nothing to worry about. It's done for performance reasons. I just want
to point it out. Oh, and these really long ugly names here, when you build Encore for
production, those will be very simple names. Usually a number like 100 dot J S it
won't expose all these details. Okay. Next let's figure out how and where stimulus is
installed and build our very first stimulus controller.

