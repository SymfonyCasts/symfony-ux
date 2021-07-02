# External Js Stimulus

Coming soon...

Thank you. So the turbo frame system, we're now laser only loading, just part of the
weather page down here in the footer. As you can tell, just by the fact that this is
working means that if you have a,

If you have

A script tag, that's loaded into a terrible frame. Let me find, here you go. In turbo
frame and the script tag. If you have a script tag that's loaded into a terrible
frame, turbo does X you that exactly how turbo free turbo drive, executes your script
tags in the body. But we have a bug that's hiding well sort of two buckets to see the
first scroll to the top refresh don't click don't scroll down. Now click to another
page. Now click to the weather page and check out the console error, uncaught
reference error, weather widget, and it is not a fine coming from turbo helper. Let's
go over and open that file as that's turbo turbo health grant JS and scroll down to
line 71.

There we go. This initialize weather widget function. If I scroll back up this
initialize, whether budget function, if I scroll back up to our constructor is called
when turbo render is executed, its job is to reinitialize the weather widget on the
next page. The problem is that in this case now weather widget, JavaScript hasn't
quite yet been loaded onto the page since it didn't load on the first page. And we
didn't code defensively here. So an easy fix for this is just to say if, if type of a
copy of that weather widget in it = = a function. Yeah, let's call this otherwise. It
means the JavaScript hasn't been loaded. So no reason to call it and get an air. So
this would fix this problem, but not our other problem to see that problem over on
the product page below the sidebar. I want to add a second to weather widget. The
tempo for this is over in templates, product index that HTML that twig, but actually
the sidebar is in this product of base that HTML, that twig. So cool. Okay.

Right here. I'm going to add turbo frame with our ID = whether widget to match the ID
that we've been using so far and source = curly curly curly path app for weather.
Okay. We had a brand refresh the homepage that works oh, popped up in the wrong spots
because I meant to put this inside the aside. Let's try that again. Or you're fresh
now. Perfect. Right below the sidebar. Now scroll to the footer. It's broken. The
turbo frame did its job. The HTML is here, but the JavaScript, it didn't initialize.
What's going on. Let's remember how this is supposed to work because it's getting
kind of complicated on page load, or really anytime that the weather JavaScript is
first executed, it adds a script tag to the page that after being downloaded,
initializes, any elements with weather widget, I O on the page class on the page.

But when we serve to another page, this external JavaScript file is not re executed
because this function is smart enough to not add it multiple times. We hit this
problem earlier to fix it back in turbo helper dot JS. We added this weather widget
and NIC code, which is run on turbo render. So basically each time turbo render the
page, we would call it weather widget and it's, that would reinitialize the weather
widget for that page. This works great. When the only way that, that a weather widget
tag can be added to a page is as a result of a turbo drive navigation. But now it's
sometimes loaded later on the page via Ajax by a turbo frame, when this is loaded via
Ajax, that does not trigger the turbo render event listener because we're not
actually rendering a full page. And so the weather widget knit function is never
called and nothing ever initializes the weather widget down here.

By the way, you might be wondering how this weather widget in this lazy frame was
ever working. Since we were never calling the weather widget and net function after
it loaded, it works simply thanks to some smart code that lives in this weather,
widget and net function. So if you actually went and looked at what this JavaScript
file looked like, which we did a little bit earlier, you would see that the weather
with it widget function when you call that, if it does not find any matching weather
widget IO elements on the page, it automatically recalls itself every 1.5 seconds
until it finds one. So that kind of helped this work temporarily until,

But it's not a robust solution. So

Let's fix all of this and simplify our code. Cause it's getting a little hard to
follow how by creating a stimulus controller. I know this tutorial is about turbo,
but since turbo really works best when you have no inline JavaScript, let's see how
stimulus could accomplish this. Here's the idea. Let's attach a stimulus controller
to the weather widget. I O anchor tech by doing that, whenever this element appears
on the page, no matter how, or when it appears on the page, we can run some code like
the weather widget and it function up and assets controllers. That's created the new
file called how about weather widget,_controller dot JS. I'm going to cheat and steal
a little code from another controller, paste that, and then clear everything out.
Start with a connect function.

And let's just console that log BrainPOP. Now over in weather /index HTML, twig. So
this is the weather page. This is where we have all of our JavaScript and the anchor
tag at a data dash controller = and then match that name, whether dash widget. Okay.
Let's make sure that's connected. So I'm head over, scroll up and then refresh the
homepage and check the console. Okay, perfect. We have a log here and that's coming
from this weather widget down here. Now watch what happens when we scroll down a
second emoji, the next step is to move all of this JavaScript here into our stimulus
controller. So I'm gonna copy all of this and delete the script tag entirely and
weather widget controller after connect function. Let's just paste now. That is
totally invalid JavaScript. And my build system and editor are freaking out. Let's
turn this into a function. Now I'll call this initialize script tag and I'm going to
copy these three arguments down here

And remove [inaudible] and cool.

And then up and connect instead of logging a rain cloud, we'll say this.initialize, a
script tag and pass those three arguments. So this isn't quite perfect. Yet. Each
time stimulus sees a matching anchor tag, it's going to run this code down here. So
let's try it, scroll back up the top refresh and beautiful. The fact that this loads
means that our student's controller did just add that script tag. If you look in the
head of our page, there's the weather, which IO script tag right there. Of course, if
we scroll down to the bottom that still doesn't work, that's okay. We expected that
we still need to move this weather widget, a knit code into

Stimulus. So let's copy

This entirety of statement and I'll delete the initialize weather widget, scroll up
and delete the, uh, event listener entirely now over in weather widget controller, up
in the connect method, let's paste that and then move the initialize script tag,
which I totally misspelled that is realized. We fix that. Move that into the else. I
love this. So if the weather widget and knit function already exists, just call it.
L's run initial Esker pack to add the original script tag to the page. I think we're
ready. Let's scroll back up to the top of page and refresh sidebar works. Footer
works to go to the weather page that also works. I love this approach, even though
our external JavaScript is not written in stimulus. We can still use stimulus to
activate this JavaScript exactly what we want at this point. We can add this anchor
tag anywhere to our site, and it's going to initialize do instantly do the work it
needs to do to initialize itself. Next let's investigate the second use case for
turbo frames and really the main use case. The ability to keep navigation isolated to
one section of the page.

