# JavaScript

Coming soon...

The biggest. Gotcha. With turbo drive is JavaScript and that's for one simple reason,
suddenly there are no full page reloads on your site. And a lot of JavaScript is
written to expect that behavior. Let's see how some classic JavaScript behaves with
turbo open assets /app dot JS. This file is loaded on every page. Let's use jQuery to
run some code. After the page is loaded. You might recognize this code import that
are assigned from jQuery. I already have that installed. Then use dollar sign,
documented that ready And passes a function that should be called once the page has
finished loading. We'll say console, that log page is ready that around for this
block. Also console that log script is done

Cool.

When we refresh and check out our console,

[inaudible]

When we refresh and check out our console.

Wow.

And we refresh and check out our console.

[inaudible]

When we move over and refresh

And check the console.

Yep. We see both logs. Script is done as first, then pages ready right after a bit
after. But when we click, we see nothing. And that makes sense. Add that JS is not re
executed and also the page does not become ready again. However, if you put
JavaScript into the body of your page, then it does work like normal open up
templates /based at age two, that twig and anywhere in the body. But I'll do at the
bottom. Let's add a script tag console that log

Body executing.

Okay. Refresh now. And we see all three logs

Quick. Hey,

The new log is there and that makes sense. Turbo replaces the old body with the new
body. Any script tags and new body are also executed, but that's not necessarily a
good thing. Watch closely. I'm actually in the clear my console here, and I'm going
to go back to a page. I just visited a second ago.

There are two logs

Once because a page preview was shown from cache. We'll talk more about the preview
stuff later. And a second time when the fresh HTML is shown, that might be okay. Or
that might be really bad depending on your JavaScript. Here's another issue. What
have you, or some third-party JavaScript library adds an event listener to the entire
document. Check this out. Let's go over back to our template. We'll say document. And
what document represents basically is this HTML tag up here. That's the one element
that never goes away with turbo document dot add event listener

Quick

And on will console that log document clicked.

Okay.

I should be able to click anywhere to see this message. So let's refresh, I'll go to
my console and click there. There it is. Click here.

There's another one. Now clear the console. Okay.

I will click to another page or does that make some, one more document that quick?
Let me clear that again. Now click somewhere else to click up here. Two more. That is
definitely not what we want

It

That's because each time we execute this script is adding yet another listener to
that same document, let's remove the script tag and that JS remove our J query
loading code there. So what is the best way to write JavaScript? So it works with
terrible drive. Well stimulus of course, We already know from our first tutorial in
this series, that if a new data controller element is out of the page, like data,
database controller = counter, which powers this little header up here. If this is
added to the page, even if it's added via Ajax, this stimulus controller will still
work perfectly. It is made for turn

Turbo.

And the second lesson is that you should probably remove any JavaScript that you have
in your body tag, even though it mostly works. That's because of the potential for
the bad behavior that we just saw a minute ago in a little while. We'll talk about
external JavaScript, widgets, external JavaScript like widgets or analytics, which
are often added to your body. So this whole JavaScript topic is definitely the
biggest hurdle to using turbo drive. Until you have all your JavaScript, all the
JavaScript on your site, written properly, things won't work well. You can fix the
JavaScript for just some pages on your site and activate turbo drive only for those.
And I'll show you in just one minute, by the way, if you inspect, if you go to the
inspect element and go to the head tag, You notice that all of our script elements
are placed up here in the head with a differ attribute that's on purpose. And this
defer attribute comes from our configuration in Webpack Encore, config packages,
Webpack Encore,.yaml, script attitudes differ.

Okay.

We placed our script tags up in our head so that they won't be re executed on every
turbo visit

[inaudible].

But normally adding script tags to the head is bad for performance. When your browser
sees that screen, that tag, it freezes the page rendering until I can download the
file and execute it. But by adding differ, the file is downloaded in the background
And the page continues loading without waiting. Once the page finishes loading, then
the JavaScript is executed. Here's the big takeaway about using turbo drive and
JavaScript to get it to work reliably. You're going to need all of your JavaScript to
be written in stimulus, which doesn't mean that you need to completely rewrite it.
And our last tutorial, we showed some examples of using stimulus controllers To
simply wrap the existing logic that you might have. So sometimes it can be easy. It's
just taking a chunk of JavaScript and then throwing it into the connect method of a
stimulus controller. And if you can't or don't want to use stimulus,

You can also tweak your code so that it's executed on each page load. Like by
wrapping that code in a turbo event, that's fired on each visit instead of using the
jQuery document, that ready thing. We'll talk about turbo events later, by the way,
if you did need to disable turbo for a specific link or even for the entire section
of the page, you can do that with a special data dash turbo attribute, for example,
to completely disabled, terrible drive, head over to base that HTML twig, find your
body tag and add data. Dash turbo equals

False.

Now, any link clicks or form submits inside of this, which is everything we'll not
use turbo drive, check it out. I'll refresh the page and click around. We are back to
full page reloads,

Ooh,

To re reenable turbo on a link or section you can set the same attribute to true. For
example, let's activate turbo just for the links up in the navbar. So we'll do Is
fine down here. The UL classical's navbar and on that, we will add data dash. Okay.

Turbo = true refresh again. Okay.

And when I click a category, it's still pay full page reload. But if I clicked to go
to the cart that loaded with turbo, you can use this strategy to activate turbo on
only some parts of your site that are ready. Let's take both of these out.

Okay.

Next we've activated turbo drive and gotten this no page reload goodness. With zero
changes to our Symfony app. That's pretty amazing, but there is one tiny change that
you will need to make to any pages that have a form let's fix our form submits next.

