# Extend Chartjs

Coming soon...

When we build the chart JS controller from the UX package, we're basically building
all the data for that library and PHP, then passing it directly into tar chart dot
JS. Our chart object that we create here is turned into JSON and then rendered on
this data dash view attribute. This is then read from the core controller and the
payload is passed directly into the chart dot JSI, the chart object. So for the most
part, if you didn't customize your chart, you can do that by playing with the data in
PHP. For example, I, over to the docs quick into the chart dot JS library and go to
get started. If you scroll down a little bit, you can say that each chart has keys
for type data and options. In our controller. We're sending this data key via a nice
set data method. We can also set the options down here in the same way. You can see
that there is apparently a scales. Why access ticks begin at zero option. Let's set
that in PHP. So I'll say chart, arrow, set options, and then we'll just need to pass
it a bunch of options here. So there's scales.

That's such an array then Y axes, this is actually set to two arrays. If you like
over Y axes as an array and an object in P in JavaScript, then ticks that to another
Ray and then begin at zero set to true. Cool. That's great. Fresh go back to our site
and let's refresh our chart actually already starts at zero, so we won't see any
difference. And last we have a syntax error. Let me add my semi-colon. Of course our
refresh boom. There we go. It doesn't look any different. But the important thing is
that if you look at the options at the elements, you can see that there's not an
options. Key that's being passed directly in the target asked. So this is great. We
can do many, many things just inside of PHP and it's passed directly to charge. Yes,
but this can't handle every option in situation. If you go back to their docs and
scroll down to developers, click on updating charts, you might have a situation where
you need to update the data. After it's rendered onto the page. This is easy in
JavaScript. You just change. As long as you have that chart object and this chart
object, you can change the data on it and call update.

But how could we do that in our situation, in the core controller, it does great this
tart object, but we have no way to hook into the process and access that or do weight
in the connect method of this core stimulus controller. It does one very interesting
thing. It dispatches an event, actually two events charge S pre connect or patches,
the options on the event and chart JS connect, where it passes the chart object
itself. How can we hook into these events by creating a second custom controller that
listens to them? Open assets controllers, let's create a new file called how about
admin dash chart JS_controller dot JS. We'll start the same way you always do. In
fact, I'll cheat. Copy the counter controller paste that. And then inside here, let's
add our normal connect method with console.log(. This time we will put then in the
template and a second controller to the element. Now, as a reminder, render chart is
responsible for rendering this canvas element here. Now we need to pass a second data
controller to this. How do we do that? Render chart has an optional second argument,
which has any additional attributes that you want to pass the element. Let's fast
data dash controller set the name of our controller, which is admin dash chart JS.
Okay. Let's hit over refresh.

Uh

Oh, but make sure that you are writing your code with valid twig code there. All
right. Is that over? Refresh the graph still there and yes, in our log, we see the
graph. We are connected. Inspect the chart again. Interesting. You can't have two
data dash controller attributes on the same element. Fortunately stimulus does allow
you to have two controllers on the same element by having one data dash controller
attribute with two controllers, separated by a space. The rendered chart took care of
doing that for us. So here's the goal. Let's pretend that for some reason, we want
our new stimulus controller to change some of the data on this chart. And then
rerender it. Maybe we make an Ajax call every minute for fresh data. This means we
need the chart object. That's in the core controller, which means we need to listen
to the chart JS connect method, uh, event.

How do we do that? We already know custom events are no different than normal events.
And in this case, this event is being dispatched on the element. So we can use an
action on that same element over on the render chart function. Let me break this onto
multiple lines. Let's add another attribute called data dash action set to the name
of that event. So let me go copy that from the core controller chart, JS connect an
arrow, the name of our custom controller admin dash chart JS a pound sign. And then
the name of the method to call when this event happens. How about on chart connect?

I'll copy that method name, head into our custom controller. Let's rename connect to
on chart connect, given an event object, and then console.log(, that event object.
Alright, let's go save a works. However, refresh check the console. And yes, there's
our custom events open, open it up. Beautiful. There's a detailed property, which has
the chart object inside back in our controller. Let's use this to keep things simple.
Let's let the chart load, wait five seconds. Then update some data. Start by
assigning the chart to a property so we can use it anywhere. this.chart = event, that
detail that chart next at the bottom and a new method that will sort of fake making
an Ajax request for the new data and updating the chart. I'll call it set new data.
And inside of here, we'll say this.chart, that data, that data sets last square
bracket, zero, that data left square bracket two = 30.

And then I'll say this.chart that update. Now this first line might look a little
crazy, but if you look over at their docs, this is just how you can access your data
sets. So what I'm doing here is if you look at our data and our controller, we have a
single data set. So we're finding these zero index to the data set, which is this
stuff, finding the data, then finding the element with index two and changing it to
30. So that should change zero one to this five, right there, up to 30, Finally back
in our controller up and on chart connect. Let's call set time out, pass that
an->function. We'll wait five seconds and then we'll call this, that set new data.
Okay. Moment of truth. Head over, go back to our site, reload the page and perfect.
There it is. And wait.

Awesome.

Awesome. Five second delay. And then March got bumped up to 30. I love it.

[inaudible]

Thanks to the fact that the chart JS the core chart, jazz controller, dispatches
these events. We have 100% control over its behavior, and this isn't something unique
to this one controller. This is a pattern that many of the UX libraries follow-up
next. If you've been wondering how things like react or Vue JS fit into stimulus,
wait no longer the answer is that why you might need to use them less. If you do want
to build something in react or view, doing that works beautifully with stimulus.

