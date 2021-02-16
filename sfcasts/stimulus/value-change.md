# Value Change

Coming soon...

Now that we've created a color ID value. We can pass that in from the server into
simulate and read it in stimulus as this.color ID value. Let's use this to pre-select
a color square, thanks to our organization. This is going to be no problem. And by
the way, by the end, my end of this chapter, our controller is going to be about half
the length you placed the log with if this, uh, color ID value, just in case maybe we
reuse this color. Sometimes we don't set an initial color, say this.sets, select a
color ID, this.color ID value.

Let's try it. When I move over, it doesn't work in my console. I have a giant air.
Can I read property class lists of undefined coming from our set selected color
method. So let's go check that out. So I think it's coming from right here. So for
some reason, our finest selected color square is suddenly returning. No, which is
odd. If you look down here, the problem is actually our stronger types element.data
set dot color ID, which just uses the normal dataset functionality of your browser is
always going to be a string. But this, that selected color ID is now going to be a
number. Because if I scroll up here, we are passing in our color ID value, which
stimulus normalizes to a number. So our stronger types here actually caused us a
problem. I'm going to fix this by just changing this to a double equals.

Now in refresh util, you can see it preselected the green number, and if your inspect
element and unhide are select element yep. That updated to green as well. So there's
one last feature about the values API that we haven't talked about yet. It is really
going to help us here. In fact, it's going to make our, it's going to allow us to
remove a lot of code from our controller. It's called a change callback. Very simply.
We can tell stimulus to automatically call a function whenever a value changes like
when our color ID value changes, how what they especially named method, check us out,
make a new method called color ID value changed

And inside of here. Okay.

I'm actually just going to go steal my coat earlier. Just say this, that sets like
the caller ID, caller ID value, and now it can actually remove the code inside of
connect. Okay. So on low, the, the on load, the color ID value should be red from our
data attribute that will change the color ID value and cause our callback to be
changed. What's real. Then you run the rest of the normal magic. So let's try it. And
yeah, it did. It was really fascinating. Let me ask you click read to make it more
obvious. Onload it goes back to green. I love that feature. All right. Ready to have
your mind blown. Check this out. Find your data controller element two is the ID of
the green item. Let's change it to one, which is red. It changed. Yeah. Our callback
is even co executed at one. The data attribute is updated. That's bonkers. If you
look back at our controller now I'm kind of wondering something. Do we really need to
have both a selected color ID property that stores the current selected color ID and
a color ID value?

Nope.

A value is basically a property with extra superpowers, with the ability to read an
initial value from your data attribute, if you want to, and the ability to have a
change callback. So check this out inside the color ID value changed. Let's add all
the logic. We need to get this to work on its own. In other words, replace this, that
selected color ID with code that does the same thing. So for example, we'll say
this.selected target that value = this.color ID value. That's the Lexi element. And
the only other thing that we need to do inside of here is a loop over the color
squares and make sure that only the S and set these selected class correctly. So we
can do that by saying this.color square targets dot for each.

And that will receive an element Auburn,

Because on a multiple lines, then instead of here, we can use an if statement cause
to figure out if we should be adding the class or not. Instead of here, we can say if
element

That dataset dot color ID

= = this.color ID value,

Then we know

That this element has just been selected.

So we can say element dot class thought, add selected else.

This is not selected. So we're actually going to remove that selective class and
select color. We don't have to call the set selected color method anymore. We could
simply say, copy the event, current target spot, and replaces with just this.color ID
value = event. That current target, that data set, that color ID. That's it. That's
all we need to do. So when we click select how color will be called, all we need to
do is change the color ID value, and that should trigger our color ID value changed.
Let me try it on load. It works when we click it works again. I did lose my ability
to like click a second time in it,

But I'll fix that in a second. Okay.

Right now let's celebrate by removing odd ton of code. We don't need set, selected
caller ID anymore or find selected color square. Awesome. And if you want to get back
the ability to click again and unselect it, then we can just do a little of extra
logic and select color

Cons quick color equals

Event. The event I current target code. And I'll say, if I clicked color = this.color
ID value And election and say color, this.caller ID value = no,

And

I'll hit return after that. Otherwise we'll set it to

The eclipse color

Refresh and everything still works. Just make sure I'm going to unselect my slight
hell element here. Perfect. Quick enclave change, click. It is beautiful. This is the
final version of our controller. Actually, I can also remove the property, right?

Fair.

Check it out. It's about 30 lines of code and it's incredibly readable even with this
extra complication in the middle here and selecting the color on click

Next. Okay.

If you flip over and go to the homepage, we have a functional search here, but it's
incredibly boring. It just server-side submit. Let's make it. Let's add an Ajax
powered quick search that shows results under the search box. As we typed and
updates, as we type.

