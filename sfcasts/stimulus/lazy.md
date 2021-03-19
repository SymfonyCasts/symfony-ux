# Lazy

Coming soon...

If we look at the current analyzer, it's pretty obvious what our biggest problem is.
Sharp. J S church S is gigantic. That's included because the U X charge JS
controller, The UX Trinity has packaging includes a controller, which imports charge.
Yes. What a shame, especially since we only use this on an admin page, we somehow
only load this on that page.

Yeah.

I actually do something a bit better. We can automatically load the controller for
the chart JS only when there is a data dash controller, actually on that page. It's
magic. How in our assets /controllers that JSON file

Open assets controllers that JS

This assets /controllers dot JSON file. This file is automatically updated by Symfony
flex. Whenever we download a new UX package, but we are totally free to tweak the
configure. Like we could change enable to false. If we didn't need that control

Or we could change fetch to lazy, what does that do?

Inter-terminal stop the analyzer and let's dump a new on file because at this time
I'm actually going to change the bill to dev to a dev build. Just because it's a
little bit faster, even though it's not quite as accurate to our production bill,

When that finishes,

Which will still take a little bit of time,

I'll run my analyzer and here. Whoa,

Awesome. All the church dot JS stuff, including actually our controller that's this
file right here

Is in its own file.

And so the other files are now much smaller and you can see this react Dom one. We
know that that is actually an async file. Sealy files being

Close, a couple of tabs here, and then I'm going to go refresh,

Go click onto the homepage and then view the page source. If you check out the file
names here. Yeah. Charge as has not being included anymore. The only files that are
being included are runtime digest app digests. And this now pretty small long
vendors' file here. I can also see this when you go under network and go to
JavaScript, you can see that are that long, um, chart JS file is not in this list.

So that file is never downloaded, but now go to /admin. There's our chart. How the
heck did that just happen when you set fetch too lazy instead of actually importing
the real controller and its dependencies stimulus bridge creates a tiny fake
controller that fake controller waits for a data dash controller element matching the
controller name to be added to the page. As soon as it sees that it asynchronously
downloads the real controller and execute it. That's incredible. Let me show you how
amazing this is on this canvas element. I'm going to right click and go to copy outer
HTML. Now go back to the homepage. As we saw a minute ago, the chart JS file is not
loaded on this page at all. Let me clear out the network tab under JavaScript. Next
I'll inspect element on the H one that doesn't really matter where, and then I'm
going to edit this div is HTML and paste the data dash controller element onto the
page.

Ready,

Click off of this boom. We get a graph on the page. Our graph showed up and check out
the network tab down here for JavaScript to new JavaScript, files it asynchronously
loaded our charge JS. And it also for performance reasons, even split moment that JS
from that. So at asynchronously downloaded both of these files, this is the beauty of
the lazy fetch. You can still add a data dash controller element

To any page or even load it via Ajax, but you do not need to make the user download
all the JavaScript necessary for that on every page. But stimulus bridge has one more
trick. If you look back at our analyzer tab, the three files that are being loaded
now are these two dark blue ones and this one light blue on here, they're being
loaded on every page. So the biggest JavaScript module now is sweet alert. By the
way, if you're wondering what core JS is, those are your polyfills. For example, the
web URL, uh, these down here are what give us our search parameters that we're using
earlier. Anyways, the biggest one other than stimulus is sweet alert, which again is
kind of unfortunate because that's only used on the cart page. Could we do the same
lazy trick with our own controllers? Totally. I got a code open assets controllers,
submit, confirm controller that JS. This is the one that imports it. Sweet alert
above the controller class. Add this a comment with stimulus fetch set to lazy. So
inside this common, you're gonna kinda pretend like you're doing a JSON ki. It's
actually a JSON syntax and that's it. This syntax is special to stimulus bridge and
it will make this controller lazy, just like the chart JS controller, head back over
to our terminal and let's rerun our commands again to do one last analyzer dump.

When we run the white ant pack bundle analyzer. Yes. This time sweet alert isn't its
own file, including because Encore realized that it can now isolate that in this
case, you don't see our, our controller inside that same file only because a lot of
times Webpack will split well differentiate vendor files and user land code. So our
controller is actually probably split down into some tiny file somewhere else. There
it is right there. So it's its own a file that can also be loaded asynchronously.

So here's the cool part about this. Okay.

If we go and refresh the homepage, I close out my old tabs and view the page source.
You can see the three files are being loaded. Now our runtime, this long vendor's
stimulus bridge core thing and app dot JS. So over here that is apt digest right
there. That's tiny one time dot JS that's there. And I hear you out and here's the
last one right here. So these three files here are the only ones that have been
loaded on page load, which is so much smaller than it was when we started. And of
course, if we actually go to a cart page, actually go to network and go back over to
JavaScript. I'll add something to my cart

On the cart page.

Our sweet alert code is loaded asynchronously and everything still works. Now this
does mean that in theory, someone could try to click remove so fast before the
JavaScript was loaded on the page. If that's a problem for you, you could either
avoid using this fetch lazy, or you could disable this button on page load and enable
it in your STEM is controller. Okay. Enough with that crazy laziness next after
chatting with some of you lovely people over the past few weeks, I thought it might
be nice to do a full example of submitting a form via Ajax, complete with validation
errors and everything. We're also going to reload a list once that form submits and
render the entire form in a modal to make things extra complicated. Let's go.

