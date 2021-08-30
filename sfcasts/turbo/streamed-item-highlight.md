# Streamed Item Highlight

Coming soon...

Our review system is super cool. If any user submits a review that review will pop
onto the page of anyone else that is currently

Viewing this product

To make this a bit more obvious. I want to highlight that new review as soon as it
pops up. And this is pretty easy. Let's start over in assets styles, app that CSS

Add a new.streamed, new

Item class, and all of a sudden there's two very simply a background color of light
green. Next let's add this class to a new review if it's added via the stream. So we
can do is in reviews that stream that agent on site we'll specifically pass in a new
variable here called is new. True. And then over in this template,_reviewed at age 12
twig at the end of the class less, we'll just do a little Turner since exercise is
new and we'll default to this, to false. We're adding this just so that we won't get
an undefined variable. When is new isn't passed. So if his new is passed, then we
will add streamed new item, and that's all we need. If it's false, then it won't
print anything. Awesome. Let's check it out. I'll refresh both of my pages to get the
new CSS and then submit a newer view. Beautiful this green background, and does also
show up on anyone else's page like our incognito browser.

So this is cool,

But I want to make it fancier by showing this background for only five seconds and
then fading it out. Start again and add that CSS to handle the fading out part. We
need a new class that describes this transition. So I had a fade dash background
glass, and here I'll say,

Try and listen,

And we'll change us in the background color for 2000 milliseconds.

Before we try to use this somewhere directly, let's think if the goal is to remove

This background after seconds, then the only way to accomplish that is by writing
some custom JavaScript. In other words, we're going to need a stimulus controller in
the assets /controllers directory. Let's create a new file called how about streamed
item_controller.JS. I'll do our normal normal import export default class extends
controller, and then create a connect method. So before I fill this in, let's
actually go over to our_reviewed at age two months, wig, and let's use this. So I'm
going to break this onto multiple lines, cause this is getting kind of ugly here and
I'm copying my streamed to new item class and then kind of delete this. All right. So
here's the whole deal. I was just going to put a kind of normal if statement here. So
say if is new, I could false false.

And then, and if so, if we are new, what we're going to do is not add that class
where can actually render that stimulus control ourselves like {{ stimulus
controller, streamed item. And what I'm going to do here is actually pass in the
class as a new variable, as a new variable into this has any value into this called
class name, last name set to stream new item. Uh, I'm doing this because I'm going
and go. I'm going to make this stream to item controller, really flexible. Cause
we're going to use that later when we actually remove items for now, it's going to be
sort of unnecessarily, uh, flexible. So because I'm passing an a class name value
over in our controller, I had static values = and we're have one class name, which
will be a string. Cool. And then down at connect to do actually, uh, assign that
value. We'll say this element, that class list.add, and then this.class name value.
So if we stop right now, this is just a really fancy way to add the streamed new item
class to this element, as soon as it pops onto the page. All right. So let's go and
do our real work back in the controller. We'll use the set time out. So, so that we
can remove that class after 5,000

Seconds.

So when you say I'll copy the code from above this sentence, to me, this, that
element, that class let's remove this.class named value. If we just did this, then
after five seconds, the green background would suddenly disappear. It says, we want
to want it to fade out. I'm also going to say this, that element, that classless.add.
And we're going to add the fade dash of background class as well. This is a class
that we added that has the transition. If you want to be really fancy, you could
actually wait until the transition from finishes and then even remove this class. But
it's going to be fine if it just stays on there forever.

Anyways, let's

Try this. I'm going to refresh both of my tabs so that I get that new CSS fill in the
review. And yes, there is a green background over here. Here's a gray background and
if we wait, beautiful it, fade it. How it's. That is nice. All right. Next, we're
currently publishing updates to Merck here inside of our controller, but the mercury
turbo UX package we installed earlier makes it possible to publish updates like this
automatically. Whenever an entity is updated, added or removed, it's pretty
incredible.
