# State

Coming soon...

If we click a color multiple times, nothing happens. I want this to unselect that
color to accomplish this. We don't need to do anything special on click. We could
look at the current target to see if it already has the selected class to determine
if it's already clicked or we could read the value of the select target to see if it
already = the caller ID from the current target. What do these solutions mean? That
we're sort of storing our state, which color is currently selected in HTML elements,
like relying on the presence of this selected class to know that's okay. But people
stimulus gives us an object and we can store stuff on it. We did this earlier with
the current count on our counter controller. So on click let's start storing which
color ID is currently selected. Let's start at the top. I'm just going to invent a
new selected color ID = no property. I am totally just making that up Then down in
select color, I'm going to create a variable constant clicked color ID equals. And
then when we use the I'll go copy my event, that current target, that data set that
caller ID.

Okay.

And then right below this, I'll say this.selected a color ID = click the color ID. I
don't really need a variable yet. It's just going to make my life a little bit
easier. In a second, of course, down here at the bottom, instead of referencing the
event, we can say this, that selected color ID, this by itself doesn't really do
anything or help us, but now we can more easily use this property to figure out if
the color that's being clicked is all ready, selected. Check this out. Is that an if
statement right near the top, we'll say if clicked color ID = = = this.selected
caller ID, then we know that we're clicking on a color box that's already selected.
So we can do in this case is I'll actually copy my class list code from below this
time, it's going to event that car current target dot class list that remove
selected,

And

Then we'll set this.selected caller ID = no, and we need to make sure that we set the
target value. So this.select target, that value = empty quotes. And then we won't
return. So if we have quick one, we go into this. If we, if statement else we do the
normal logic down here. All right. So let's try that. I'll refresh. And I'm actually
going to inspect element here, find my select and temporarily just take off that the
nuns that we can see our select element. All right. So if I click red, it works,
click green works. If I click green again, perfect. It goes away and you can see that
the select updates beautiful. But before we keep going, why don't reorganize things
just a little bit in our controller, close up these select color method early and
move. Most of the logic into a new set, selected color method with a clicked color ID
argument. Then we can call this from above this.set, selected color. And then I'll
actually just steal the event that target value. And we don't need the variable at
all anymore.

Not going to quite work yet, but I want to explain why I'm doing this because you
don't have to, but I'd like to have as many re-usable methods in my controller as
possible. The nice thing about set selected color is that it's not dependent on the,
on the event before we reading event at that current target, that data set that color
ID alphabet. Now anybody could call this method from anywhere passing a caller ID,
and everything's just going to work well, it's going to work once we actually finish
this function here, because we do need to fix things specifically, event that current
target is not going to work anymore, but this is actually kind of cool. What we
really need to find here is what is the color box that is currently selected, right?
Because we're inside of this, if statement where we've determined that we're clicking
a box that is already selected. So we need to find the element, the color element
that was already selected so that we can remove the class from it. And we can do this
really easily. Now that we have these selected color ID on our class. So down at the
bottom, let's add a new fine selected color square method, because we're actually
going to reuse this.

And then inside, we can say return, and we're going to do is actually look inside of
our, this.color square targets array. And then I'm going to call find on it. And what
we're going to do is we're going to look through all the colors for our targets to
see which one has a data color ID that matches the current selected the selective
color ID. So this is going to get past an element, and then I'm doing a super fancy
single line, uh, statement here where we say element that data set that color ID = =
= this, that selected color ID. So this is either going to return the element. This
will return the element, if any, the color square element, if any, that is currently
selected, actually put some documentation above that to basically say that this is
going to return an element or

No.

Now we can use this above. So we will say this.find selected color, square clot, not
class dot removed selected. And we have one more spot down here. This is where we're
adding the selected element, fortunately, but we've already set the new selected
caller ID. So we can once again, use the, this.find, select the color square that
class let's not add select it. So the nice thing about storing these selected color
ID is that we can now have useful methods, like find selected color square. That's
going to use that. So we can use that at any time to find which color square is
selected. All right. Testing time,

Let's move over. Refresh. I'll click read, click it again.

And yes, we got it. So next there's one big feature of stimulus that we haven't
talked about and it's actually brand new to stimulus. It's the value's API.

