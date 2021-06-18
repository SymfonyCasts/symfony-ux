# 3rd Party JavaScript Widgets

Coming soon...

Earlier, we talked about how in a perfect world, all your JavaScript would be written
in stimulus, and you would have no JavaScript in your body element. But what about
externally hosted, JavaScript talking about a third party service that you sign up
for, then you're supposed to copy some JavaScript from their site, paste it onto your
page, and suddenly you get a feedback button or a share on Twitter button, or maybe
it's analytics. JavaScript. These bits of JavaScript will definitely not be written
stimulus in often funny things start to happen when you use them. So let's see an
example. Let's integrate a third-party weather widget onto our site. Head over to
weather, widget.io, handy, little widget, iPhone. That's perfect for demoing and
click this get code button. So this is pretty common. You sign up and then you get
some code that looks like this. So let's copy that. And then I'm going to head over
and go to templates and base .html.twig. And let's just pop this in our flair. So
right before the closing body tag, that doesn't really matter where we put this.

I'll paste it in there. So there's an, a tag here which just says New York weather.
And then probably when this JavaScript runs, it's going to kind of transform that
into the, you know, kind of cooler weather widget that you see down here. All right.
So let's try it. I'll go do a whole page refresh, scroll all the way down and tie
weather widget on the footer. Let's see. Let's navigate to another page and it's
broken. It's just that original anchor tag. Okay. Where do our cool little widget go?
[inaudible]

So the JavaScript code that we paste it here is pretty impossible to read. So I'm
going to select it and then go to code reformat code. There we go. It's still a
little hard to read, but that's doable. So we have here is we have a function which
calls itself and passes in these three arguments. Basically when this JavaScript is
executed, it adds a new script tag to the head of our page, to the head element of
our page, that points to this widget.men J S on their site. But this function is
smart. It gives the script tag in ID set to weather widget. I O J S and before it
adds the script tag, it checks to see if it's already on the page. And if it is it
avoids, uh, adding it twice. So if you go back over to our browser and go and open
the head tag, you will see, yep.

There's a script tag with ID = weather widget IO JS that points to widget.man,
men.JS. So here's, what's happening in our kicks when the page first loads like right
now, okay. This JavaScript function executes and the new widget.men.JS is added to
our page. Our browser downloads that then my guess is that when this JavaScript
executes, it looks for elements with a wedge weather widget IO class on it, and
transforms them into the nice fancy weather widget that you see here. Why inspect
element on this sip? You can see that there is our anchor tag, but inside of it now a
big I-frame has been added to it.

But then when we navigate to another page, that means the entire body tag is replaced
by a new body tag. The weather widget that was added to the old anchor tag is now
gone. And the new anchor dag is just the original boring, a tag that says New York
weather. Okay. However, turbo, uh, does see the script tag that's inside of the body.
So the script tag that we have here down at the bottom of base, that H twig, and it
does reexecute these lines, but this time, since the script with ID weather widget,
IO JS already exists up here in my head tag.

It does not re

Add it to the page. And so no JavaScript ever runs that re initializes the widget
back onto our weather widget, I O element. Okay. So now that we kind of understand
what's going on, shouldn't we just, you know, tweak the JavaScript here to always
insert the script tag. Let's try it. I'll cheat and kind of temporarily add a or true
to the if statement so that the, if statement always executes and always adds that
element. All right, let's now here on page one, the weather widget works. And if I
can go over to my cart, the weather widget works problems solved, and don't worry.
The script tag isn't downloaded multiple times. Your browser is smart enough to pull
it from cache after it downloads it the first time, but this might not be the best
solution for two reasons. Look at the head element of our page. We have two script
elements, and if I kind of keep going to new pages,

Each time we navigate, we get another script element that might be okay, but it looks
kind of crazy. Eventually there could be like 50 script tAJAX in here, and actually
that's how some external JavaScript works. Some external JavaScript doesn't have
this, if statement here. And so one of the problems is that it does add more and more
and more script tAJAX when you use turbo and you don't want it to the second problem
is that whether or not executing this script file over and over again is a good idea.
Sort of depends on what that script tag does. If it's simply reinitialize as the
weather widget. Cool. That sounds safe. But if it, for example, adds any event
listener to the document, each time it's executed, then each time we load that script
tag, we're going to add a second, third, fourth, or fifth listener to it. So suddenly
if you click the page, you that JavaScript widget might do whatever it does. Five
times. My point is you need to be careful with these third party widgets. Let's pull
back this, if statement the way that it originally was

So in this case, re executing this widget, that meant that JS script tag after each
visit is probably okay. It does just seem to reinitialize the weather widget on this
element, but I would love to do that without duplicating the script tag and ending up
with 50 of them in my head tech, how can we do that by removing the previous script
tag right before the page renders and how do we do that via a new event listener.
Let's talk about that next and discuss the proper way to handle analytics code
without double counting visits.
