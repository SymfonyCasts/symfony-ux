# Autocomplete with Transitions

Coming soon...

The great thing about the third party stimulus autocomplete controller is that we're
able to quickly build a fully functional search auto-complete feature with full
control over the HTML that you see this all required. Zero custom JavaScript. The
only bummer is that we lost our fade in fade out transition. How can we add that
back? When we originally added the transition, we did it by leveraging use transition
from the stimulus use library. We added the behavior in connect then called either
enter or leave to show or hide the element with the transition. But we can't exactly
go into the stimulus. Auto completes the source code and hack that stuff in, but we
can make this work. As long as this controller dispatches some events, the right
events. If you look at their docs and scroll down, you'll see they have an event.
They actually do trigger a bunch of events. And this toggle one looks perfect. Fire
is one of the results element is shown or hidden. We can use that to trigger our
transition. Before we jump in

To get this to work,

We need a poll request to be merged into this library, which is still at the time of
this recording, waiting to be merged rats. Since this is an merged, we'll use a fork
of this library, which contains the tweaks from this pull request

To do that. Open your package.JSON file.

Find the stimulus auto complete line and set its version To a specific branch on my
repository,

Right.

I'll paste in. So this is my username, the same norm, the name of the library, and
then the special branch called toggle event. Always a dist now at your terminal run
yarn install to download that version of the library. Okay. So here's the plan in
order to leverage the use transition behavior, we're going to need our own custom
controller and assets controllers create a new file called how about auto complete
transition_controller.JS. I'll go steal some code from another controller. And the
only thing we need right now is just a connect method and we'll counsel.log. I want
transitions now back in the template for this page, which is in templates, product
index, .html.twig, we're going to add two controllers to this same element. We can do
that with stimulus_controller. We just need to tweak the format a bit.

We pass an object to the first argument, and then each controller becomes a key in
the object assigned to the value. So let me actually, Eric, go finish it. So you see,
we're not passing an object. We're currently passing on one controller and here are
the values for that controller. Now we'll also pass it our new controller name, which
is going to be auto, complete dash transition, and we don't need to pass any values
to this. So we'll just set it to an empty object. Let's try it move over, back on our
site. I'll open my inspector, refresh the page, check the console and beautiful.
There's our log. If you inspect the element on this, you can see that the data desk
controller attribute now has both controllers on it to get this all to all work. We
need our new controller to be notified whenever the autocomplete results should be
shown or hidden so that we can trigger the transition.

This is where that toggle event comes in handy. The stimulus auto complete controller
dispatches all of its events on the main element that's registered for the
controller, which means it's going to be on this div right here. Let's add an action
for the toggle event. We'll do that with a new attribute called data dash action
equals. And then the name of the event we want to listen to, which is toggle an-> the
name of the controller that we want to call. That's going to be our new custom
controller auto complete bash transition. And then the name on that controller that
should be called. Let's just use the name toggle I'll copy that and head over to our
new controller. And we'll add a new toggle method with an event argument, and let's
just console that log, that event. So with any luck, whenever the complete results
are shown or hidden, we should see this line get hit. Let's see if that happens,
refresh the page. I'll go back to the console. And yes, you can see actually the
first time there's an event that's dispatched immediately when it's loaded. Uh, but
this down here as our custom event, when it was shown, and if you open that up and
look at the detail property, there's an action property set to open. When we click
off of this to close, we get a another event in this time, the detailed property, the
action is set to close

Back in our toggle method. We can use that info to either call this dot, enter, to
fade in the element or this.leave to fade it out. But in order to even have those
methods, we need to initialize the use transition behavior on this controller this
time, instead of putting all the code right here, I'm going to create a reusable
function so that we can add our fade, transition behavior more easily to other
controllers in the future in the assets directory, let's create a new directory
called how about util and inside that a new file called add dash transition.JS

[inaudible]

I'll paste. In some code, you can get that from the code block on this page, this
exports, a named function called add fade transition that will add the use transition
behavior to a controller. Most of what you see here is identical to what we had
before. When we originally leveraged the use behavior back in our controller,

Okay.

In the connect method, which is normally where we, uh, initialize behaviors, we can
use that, say, add, fade, transition, I'll hit tab. I'm going to do that. It's going
to add the import for me, which is awesome. And then we need to pass the controller,
which is this. And then we need to pass it, which element is going to be hidden or
shown, which we don't actually have access to yet for that we're going to use
this.results targets. So we're going to use a target that points the results. Of
course, this means we're going to need a static targets, = an array with results
inside.

And then in the template, we need to add a target to this device right here. Now
notice is we already have this as a target for the normal complete controller. We now
need to copy that and have another target for auto-complete transition. It is a
little weird to have this as a, you know, two different targets on the same element.
Uh, but this allows our two different controllers to work independently. They both
need access to that target. If you really didn't like that, you could actually just,
um, try to find an element that has this attribute and use that as your target. That
would totally work fine.

Now back in our Tala method, because we've initialized the youth transition behavior,
we have those enter and leave methods. So we can say if event that detail.action =
open, we know that we need to open. We do that with this, that enter else,
this.leave. Let's try it. Move over. Refresh and type Dai. Yes. There's our
transition. As a reminder, the details behind this transition, like the fact that
it's fading and the fact that it takes two seconds, which is way too long right now
it's only two seconds so that we can see more easily. All of these details live in
our app. That CSS file. If you search in here for fade so you can tweak these however
you want, But now I'm back at the browser. Let me bring that thing back up quick off
to close it. That happened instantly. There was no transition.

Okay.

The reason and I'll inspect element on here

Is that our

Dave now has a hidden attribute on it. This is here because the stimulus auto
complete controller adds this hidden attribute to the div. Whenever it needs to close
it, normally

That's

Great. That's why the results div normally goes away.

Normally it becomes,

Becomes invisible and we click off. But now that we are controlling the hiding and
showing with our transition behavior, we do not want this hidden attribute to be
added anymore. Fortunately, after my PR is merged, we can pass a value to disable
this behavior in the template above here on the auto complete library, passing new
value called skip hidden property set to true that literally says, please do not

Great

Set that hidden property. We are handling the hiding and showing it manually. Let's
try it out again. I'll type. We still get the nice fade in now. And we click off. We
get the nice fade out. And so we're done. I mean, the whole tutorial is done. I hope
you found this journey through stimulus, as refreshing as I did. And the next
tutorial in the series about turbo. I hope to show you that we can have an even more
dynamic app while writing even less custom JavaScript, let us know what cool stuff
your building. And as always, if you have any questions, we're here for you in the
comment section. Okay. Friends. See you next time.
