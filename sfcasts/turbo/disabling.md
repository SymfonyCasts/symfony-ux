# The "defer" Attribute & Conditionally Activating Turbo

in just one minute, by the
way, if you inspect, if you go to the inspect element and go to the head tag, You
notice that all of our script elements are placed up here in the head with a differ
attribute that's on purpose. And this defer attribute comes from our configuration
in Webpack Encore, config packages, Webpack Encore,.yaml, script attitudes differ


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
