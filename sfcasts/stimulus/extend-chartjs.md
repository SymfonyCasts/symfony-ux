# Extending a UX Controller

When we use the chartjs controller from the UX package, we're build all the data
for that library in PHP then pass it directly into `chart.js`. The `Chart` object
we create is turned into JSON, rendered on this `data-view` attribute... which is
ultimately read from the core controller and passed right into chart.js.

So, for the most part, if you need to customize your chart, you can do that by
playing with the data in PHP. For example, click into the Chart.js library and
go to "Get Started".

## Configuring a Controller via data- Attributes

If you scroll down a bit, you can say that each chart has keys for `type`, `data`
and `options`. In our controller, we're setting the `data` key via a nice
`setData()` method. We can also set the `options` in a similar way.

For example, there is apparently a `scales`, `yAxes`, `ticks`, `beginAtZero` option.
Let's set that in PHP. Do that with `$chart->setOptions()`... and then we ust
need to match the options structure: `scales` set to an array, then `yAxes`, this
is set to two arrays - you can see that in their JavaScript version - then `ticks`
set to another array and `beginAtZero` set to `true`.

Cool! Go back to our site. Our chart's y axis actually *already* starts at zero...
so we won't see any difference. Oh, bah! A syntax error. I forgot my semicolon.
Refresh now and... got it! It doesn't look any different, but the important thing
is that, if you look at JSON on the `data-view` element, it *now* has our options
data... which we know is eventually passed into chart.js.

So this is great! We can do many things right inside of PHP.

## Adding a Second Controller to Extend the First

But... this can't handle every option. Go back to their docs, scroll down to
"Developers" and click on "Updating Charts". You might have a situation where
you need to update the data after it's rendered onto the page. This is easy in
JavaScript: as long as you have that Chart object: change its data and call
`chart.update()`.

But how could we do that in our situation? In the core controller, it *does*
create this `Chart` object... but we have no way to hook into the process and
access that. Or do we?

In the `connect()` method of the core Stimulus controller, it does something very
interesting: it dispatches an event! Actually two events: `cartjs:pre-connect` -
where it passes the `options` on the event - and `chartjs:connect` - where it passes
the `Chart` object itself.

How can we hook into these events? By creating a second custom controller that
listens to them. Open `assets/controllers/`. Let's create a new file called, how
about `admin-chartjs_controller.js`. We'll start the same way as always. In fact,
let's cheat: copy the `counter_controller.js` and paste. Inside, add our normal
`connect()` method with `console.log()` a chart.

## Multiple Controllers on an Element

Cool. Next, in the template, and a second controller to the element. But, hmm. The
`render_chart()` function is responsible for rendering this `<canvas>` element.
Now we need to pass a second `data-controller` to this. How can we do that?

Fortunately, `render_chart()` has an optional second argument: an array of
additional attributes to add to the element. Pass `data-controller` set to the
name of our controller, which is `admin-chartjs`. Oh, but I should probably write
Twig code here... not PHP.

Okay! Move over and hit refesh. The graph is still there and... yes! It looks
like our new controller is connected.

Inspect the chart again. Interesting. You can't have two `data-controller`
attributes on the same element. Fortunately, Stimulus *does* allow you to have
2 controllers on the same element by having *one* `data-controller` attribute
with each controller separated by a space. The `render_chart()` function took
care of doing that for us.

## Hooking into the Core Controller via JavaScript

So here's the goal. Let's pretend that, for some reason, we need our new Stimulus
controller to change some of the data on this chart and then re-render it. Maybe
we make an Ajax call every minute for fresh data.

This means we need the `Chart` object that's in the core controller. And *that*
means we need to listen to the `chartjs:connect` event.

How do we do that? We already know that custom events are no different than
normal events. And in this case, the event is being dispatched on `this.element`,
the `canvas` element. So we can add an action to that!

Over on `render_chart()`, I'll break this onto multiple lines. Add another
attribute: `data-action` set to the name of the event - I'll go copy that from
the core controller, `chartjs:connect`, an arrow, the name of our custom
controller - `admin-cartjs` - a pound sign and then the name of the method to
call when this event happens. How about `onChartConnect`?

Copy `onChartConnect` and head into our custom controller. Rename `connect()` to
`onChartConnect()`, give it an `event` object, and `console.log(event)`.

Alright! Let's go see if it works.! Refresh, check the console and... we got it!
There's the custom event! Expand it. I love this: it has a `detail` property,
which has the `chart` object inside.

Back in our controller, we have access to the `Chart` object! That means we are
*infinitely* dangerous. To keep things simple, let's see if we can let the chart
load, wait 5 seconds, then update some data.

Start by assigning the chart to a property so we can use it anywhere:
`this.chart = event.detail.chart`.

Then, at the bottom, and a new method that will, sort of, fake making an Ajax
request for the new data and updating the chart. I'll call it `setNewData()`.
Inside, say `this.chart.data.datasets[0].data[2] = 30` and then
`this.chart.update()`.

This first line might look a little crazy... but if you look over at their docs,
this is how you can access your `datasets`. So what I'm doing here is, if you
look at the data we created in our PHP controller, we have a single "dataset".
So we're finding the 0 index to that dataset, which is this stuff, finding the
`data`, finding the element with index 2, and changing it to 30. So that should
change the 5 up to 30.

Back in our controller, up in `onChartConnect()` call `setTimeout()`, pass that
an arrow function, wait 5 seconds and then call `this.setNewData()`.

Moment of truth. Head over, go back to our site and reload the page. Here's the
chart. Waiting... ha! It updated! March jumped up to 30!

This was all possible thanks to the fact that the chartjs core Stimulus controller
dispatches these events. That gives us 100% control over its behavior.

And this isn't something unique to this *one* controller. This is a pattern that
many of the UX libraries follow.

Next: if you've been wondering how things like React or Vue.js fit into Stimulus,
wait no longer! The answer is that, while you might choose to to use them less,
if you *do* want to build something in React or Vue, they work *beautifully* with
Stimulus.
