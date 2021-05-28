# How Drive Works

Coming soon...

This is turbo drive and yes, it looks like absolute magic. So let's break down how
this works first. We haven't actually written any JavaScript yet that says, Hey
turbo, please do your turbo drive thing. So how would this automatically start
working? That is thanks to the magic of the assets /controllers dot JS on file. This
is normally a mechanism in Symfony UX for third party libraries to add new stimulus
controllers to our app. Okay. And in this case, that's true, but it's kind of a
trick. Let's go find the file that's being referenced right here. You'll find that in
node modules at Symfony UX turbo, and then source turbo_controller dot JS.

Okay. Okay. Curious how I know that this Turbocor string here matches up with a
special key inside of the packets that JSON of that. So Turbocor is pointing to
turbo. Honors score can join jazz. Now, technically the one of the disc folders, the
one that's actually loaded, I'm loading the one in the source folder, just because
it's a little bit easier to read and yep. There's not much here. This exposes an
empty controller. And really the whole point of this file is to import turbo and set
it onto the window. Variable. This accomplishes two things first, simply by importing
turbo, somewhere in your app, turbo drive is now active across your entire site. More
on that in a minute. Okay.

We will talk about how to disable it globally or selectively a bit later. And second
turbo is set onto a window variable, which makes it a global variable. You may or may
not need that. It's useful if you need to programmatically visit a link, but from
outside a JavaScript file more on that later. Okay. So we now know how turbo got
activated, but how the heck does turbo does it work? It's a pretty simple idea. Turbo
watches, link clicks, and also form submits like this add to cart is actually a form
submit. Yeah, it intercepts. Those performs them in the background via Ajax calls,
which we can see here and then updates the HTML of the page from that response all
without a full refresh, but it does modify the URL.

He has a full, the full normal browser behavior like clicking back and forward.
Speaking of back and forward turbo drive has this feature called snapshots. Let me
refresh the page real quick. As you visit pages, it stores a snapshot of that page.
If you click back in your browser, it instantly loads that snapshot with no network
request. It does the same if you go forward. And if you revisit a page that you've
already been to like break room. So a page who's snapshot is already cacheed. Turbo
will give you a instant preview of that page from cache while it waits for the Ajax
call to finish, you can see how super fast these pages are that have already gone to
versus ones that I haven't gone to. Yeah.

Some of this is kind of hard to see because things are so fast. So in our editor,
let's open up public /index that PHP and just add a sleep for two seconds. All right.
Let's head over and refresh the page that takes two seconds to load. Then let's click
back to the homepage. Oh, this shows off the progress bar. If response takes it
longer than 500 milliseconds by default, then that progress bar shows. So now you can
see that each time we click around now, watch, I'm going to click off of office
supplies, wait for it to load.

And then I'm going to click back to office supplies. And when I do, you'll notice
that this page will instantly show up. Boom. Then it finishes loading the Ajax call.
That's the preview feature. It loads previous pages from the cache for an instant
experience, then waits for the real Ajax call to finish. And once it does, it takes
that new HTML and puts that on the page. You can't really see that new, uh, request
finished because the content is identical to what the preview, when it does happen.
And if you click backward and forward, as we mentioned earlier, that stuff loads
instantly with no request.

So let's go take out that sleep. Okay. But how does this really work? What is turbo
doing behind the scenes to make this all happen? Well, I'm going to go a step deeper
into understanding how this works. And this is important because to get turbo drive,
to work happily on your site, as the saying goes, the devil is in the details. We'll
spend the first part of this tutorial talking about potential problems that turbo
will drive can costs and how to fix them. Let me refresh the page again, because that
clears out all those, uh, snapshot caches. Okay. So when I click click a link turbo
intercepts that and makes an Ajax call, but of course those requests, and by the way,
these other requests you see here are for the web debug toolbar. The request that was
made when we click that returns a full HTML page, when turbo gets the full HTML
response, it merges the new head tag into the existing head tag and then replaces the
body with a new body. The way it merges the old and new head is smart and go over to
my elements and open up my head tag. Okay. Right?

First it finds anything other than JavaScript and CSS elements and removes those from
the current head. Then it looks in the new head for any non JavaScript and non CSS
elements and adds those. We can actually see this reload the page, and look back at
the head. I see two non JavaScript and CSS tags, a tag with the char set and the
title element. When I click to another page, these will be removed and then re added
at the bottom, check it out. Boom, you can see the new page has the same meta-tags,
but it was added to the bottom. And the new title of that page was added.

The new title happens to match because I was lazy and didn't update my titles for the
different, uh, categories, but that would represent the new title. Now, if the new
head tag does contain JavaScript or CSS tags, and it probably will, since we, since
after all, we are rendering full HTML pages, turbo checks to see if these elements
already exist in the head. Yeah. If they do like the next page, we click to also has
a script tag called build /runtime dot JS, then turbo ignores it. There's no reason
to add this same script or CSS multiple times, but if the CSS or JavaScript does not
exist, it will add that element. The result is well, exactly what we see as we click
around the title, changes on each page. They can see the log in page has a different
title. Okay. And if a page contained new JavaScript or CSS, that would be added
automatically. So this is amazing. Well, yes, it is amazing. But to get this
amazingness to work perfectly, there is a little bit of work that we need to do the
first bit involves making your JavaScript turbo friendly. Let's dive into that topic
next.

