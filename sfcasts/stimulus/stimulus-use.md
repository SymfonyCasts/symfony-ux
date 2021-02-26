# Stimulus Use

Coming soon...

We have a little itty bitty problem. When we click off of our search area, this isn't
the suggestion box. Just sticks there. We need that to close. Now, can we do this? We
could do it manually. Probably we would register a click listener on the documents
and then detect to see if any clicks were outside of our search element. But I have a
way better solution. Google for search forks stimulus use to find a GitHub page. This
is a library packed full of behaviors for your stimulus controllers. It's awesome.
I'll click down here into the documentation. So check this out. Suppose you want to
do something whenever your element appears in the viewport, like as the user is
scrolling or disappears from the viewport, you can do that with one of the behaviors
called use intersection.

Basically you set it up, give it some options if you want. And boom, the library will
call up here and appear method on your controller when your element enters the
viewport and it disappeared when it leaves the viewport. One of the other behaviors
use click outside is exactly what we want. So let's get this installed. I'll go over
to usage, actually getting started and let's see yarn add stimulus use. I'll copy
this. I've used a fancy copy to clipboard, spin over to my browser paste. And
actually let me add a little dash dash, actually, I'm going to recap that whole thing
just so I can add the dash dash and dev on the end. Not really needed, but that's
what I like to do. All right. While that's doing that, let's go over here and look at
the documentation.

Scroll down to the usage. All right. So step one is to activate the behavior on our
connect method. Cool. I'm going to copy this line right here. Let me make sure that
the library finished downloading it did. Then we'll go up to the top and import it
import curly, curly. And the way that you import things is is you just grab the one
behavior you need from the stimulus use library. So for us, it's going to be use
click outside and actually features arm just completed the rest of it for me.
Awesome. Then down to here, I'm going to add a connect method and paste that line
from before. Use, click outside this. All right. Step two. If you look at the docs is
to add a click outside method. All right, let's do this down here. How about on the
bottom click outside? And what we'll do is inside. This is when the user clicks
outside, we will set this.result, target that inner HTML = empty quotes and done back
at the browser over on our site, refresh the page type a little bit, to get some
search results And click off beautiful or a diamond type. Again, it's back. If I
click off, it's gone. That was like four lines of code. Since that was so quick,
let's do something else.

If I type

Really, really fast watch my little Ajax counter right here.

Yeah.

We're making an Ajax call for every single letter. No matter how fast we type that's
overkill. The fix for this is to wait for the user to pause maybe for 200
milliseconds before making an HS call. That's called D bouncing. And there's a
behavior for that use D bounce. All right, let's use it. So let's go up here to the
example. So we're going to import now use D bounce.

Yeah.

An application controller thing there, you don't need that. They're just kind of
mixing their examples. So at the top, I'll now import used amounts. And then if you
look down at the other example, it's the same thing. Use the balanced this inside
your connect method to activate that. So pretty simple. I'll add, semi-colons
obviously not needed, but I like them.

So

The way the D balance works is you add a static D balances property set to an array
of methods that should not be called until a slight delay. It's 200 milliseconds by
default. So for us, we want to D balance our on search input method. So I'll copy
that name then up here, I'm going to say static D balances = an array with on search
input inside. All right, let's try it back to the browser refresh type real fast. And
it exploded. The reason this exploded is due to a limitation in the D bouncing. Since
our browser is calling on search input, the D balancing behavior can't hook into it
properly. Debouncing only works for methods that we call ourselves. And that's no
problem. We just need to organize it a bit better. So let's try this. I'm going to
close up on search input early and create a new method called async search.

And we'll have this taken a query, uh, arguments. And again, we're making this async
here because we have a wait there, and then I've been on search input. We don't need
to have asynch anymore. And we're just going to call this.search and we'll pass it,
that value, which is going to be event dot current target dot value. And down there
we can price the queue with query. So really we're just refactoring things into a
more reusable method. Anyways. Now change the balance from on search input to search
testing, time, refresh type real fast and perfect. Only one AGS call. Once things
finish a backspace a bit. Yes, it is waiting next. Let's do something different.

