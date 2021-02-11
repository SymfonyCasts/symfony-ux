# Actions

Coming soon...

Making our mini controller more realistic instead of being able to click anywhere on
here to increment the count. Let's add a button, easy enough in a template. Well,
that's out of button. Okay.

`<button class="btn btn-primary btn-sm">`and then the excellent call to action. Click me

The line. Break the browser. Great.

No, how could we attach a click listener to just this element? Now you might be
thinking what we should do is you might be thinking, Hey, let's add a target to this
button like we did for the span. Then we can find that element inside of `connect()` and
add the event, listener to it instead of the entire element. And yeah, that will do
it, but that's way too much work after targets. The second biggest feature of
stimulus is actions. Second big. Anytime you need to listen to an event like click
submit key up anything. You can use an action instead. Here's how it works on the
button element. I'm going to break this onto multiple lines. 
Add `data-action="click->counter#increment"` This is another special syntax it's
`data-action=""`. Then the name of the event, like click submit or key up in
arrow. The name of the controller, a pound sign, and then the name of the method to
call on our controller. When this event happens, we'll create this increment method
in a second. Now, when I first used stimulus, I did not love this syntax. It's weird,
but it really does make life a lot nicer. And we'll simplify it a little bit in a
minute, over in the controller,

Add the method `increment()`,

Then move the inside of you. Click listener. Copy that and delete it down into this
method.

Then we can delete the event listener. Now entirely. I may not love the data. Dash
actions send texts, aren't template, but I do love this gorgeous. All right, let's
try it. I'll refresh. Now if I click somewhere else in the element and nothing
happens if I click on the button, it increments.

Woo.

But I did promise one simplification in the template. Take the word, `click` off and
the `->`  off. Try it again. It's still works fine. How stimulus has a default event name
for the most common elements? If you add a `data-action` to an anchor tag or a
button, the default event name is `click`. If you add one to a `<form>` tag, it defaults to
`submit`. If you add one to an `<input>` or `<textarea>`, it defaults to `input`, which is the,
which happens when the value of the field changes. So most of the time you don't need
to specify the event name.

Oh, and now to celebrate in the controller, we can remove the `connect()` method entirely
move this, `this.count =` to a normal property `count = 0`. That's pretty cool, huh? And
then remove the method. Let's make sure I didn't break anything. It still works. So
that is most of stimulus seriously, but I already love it. I mean, look how clean
this controller is. And I have nice clean HTML, uh, inside of my template. There are
several other things that I still want to talk about. Like the values API, but
stimulus really is a lean and mean library. Next as nice as our counterexample was,
let's do something real over the browser, click furniture and then click the
inflatable sofa. Some products come in multiple colors and you choose the color with
a colored dropped down boring. Let's enhance this by attorney, turning it into a
little color square selector widget.

