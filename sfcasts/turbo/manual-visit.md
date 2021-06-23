# Manual Visit

Coming soon...

Sometimes you might need to visit trigger a turbo visit programmatically. This is
common. If you ever need to redirect from JavaScript, for example, head over to your
code and open assets controllers, counter controller. This very advanced to stimulus
controller power is this high tech quick for a chance to

Win area. Every time I click the button, the counter goes up

And updates right here. Amazing. Let's pretend that after 10 clicks, the user wins
and we want to redirect them to a you one page first, let's do this with normal
JavaScript. So inside this increment method, which is called each time we click, I'll
say, if this.count is equal to 10, then the way that we re redirect with normal
JavaScript is window dot location that H ref equals. And then you dash one. So you
want as a page that I've created to congratulate our winners. All right, so let's
refresh the homepage, click a bunch of times and every go Eureka where winners, but
of course, that worked via eight full page refresh, not via a turbo. Could we
navigate with everyone's dead, totally start by importing turbo into this file. This
is actually the most complicated part because it looks a little funny. It's important
star as turbo from, and then the name of the library, which is pat hot, wired it
/turbo. The star has turbo is just how you need to do it based on how they're
exporting things from that file. Now down here, instead of window dot location at
HRF, we can say turbo dot visit and then pass in the URL.

Let's try it again. Let me go back to the homepage, do a full page refresh. Actually
it did a full page refresh automatically for me because of the asset, uh, versioning.
We did it a second ago. Um, and now let's kick 10 times and watch as I get to 10,
beautiful that navigated with turbo. We can see the Ajax call right here. So yeah,
it's just that easy. And if you want to be more hipster, you can use de-structuring
to just import this visit function that looks like this import visit from at Hotwire
/turbo. And then down here, it's just literally visit, but that will work exactly the
same as before. There is one other tricky situation that you might run into when it
comes to navigating with turbo. And that's, if you're inside of JavaScript, that is
not inside of a file that's parsed by Webpack. In other words, you're inside of a
file where you can't use the import keyword. This is probably not very common and
really in a perfect world. 100% of our JavaScript will be written in a Webpack parsed
file. And that's what I recommend you do. Okay.

But just in case, let's see how we can navigate with turbo from some inline
JavaScript on our page. So let's open up templates based at HTML twig, head to the
bottom. And right before the antibody, we'll add a script tag. And we're going to
pretend that when we click this logo here, which has the class logo dash or the ID
logo dash image that we want to go to the cart page. So do that. We'll say document
dot, get element by ID, pass it, logo dash image dot add event, listener, click, and
then we'll pass an->function with any event argument. And here we'll say event dot
prevent default. So it doesn't actually follow the link. That's that images inside
of, oh man, holy cow. Forgot my comma over there. Things are mad at me. There we go.
And now it's a visit with turbo. We can just say turbo dot visit cart.

Yep. Touro is available as a global object. How, who said it as a global object?
Well, starting in turbo seven, beta six, when you import the turbo module to even get
turbo set up on your site, it automatically sets itself as a global variable exactly.
To help with this use case. The point is, if we go and do a full page, refresh and
click the logo image, instead of going to the homepage where we would normally go, it
navigates us with turbo to the cart page. Next we are now done with all the turbo
drive tricky parts. Before we move onto turbo frames, let's try doing, do a few fun
things. The first will be some experiments with CSS transitions as we navigate
between pages with drive. Okay.

