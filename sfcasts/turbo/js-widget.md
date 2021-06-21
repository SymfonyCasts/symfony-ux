# 3rd Party JavaScript Widgets

In a perfect world, all your JavaScript would be written in Stimulus and you would
have *zero* `script` elements in your `body` tag. With that ideal setup, your
JavaScript would always work - regardless of how or when new HTML was loaded -
and it would only be parsed and executed one time, on initial page load.

But what about externally-hosted JavaScript? I'm talking about a third party service
that you sign up for... then you're supposed to copy some JavaScript from their site,
paste it onto *your* site... and suddenly you get a "feedback" button or a "share on
Twitter" button... or maybe it's analytics JavaScript. These bits of JavaScript will
*definitely* not be written in Stimulus and, often, funny things start to happen
when you use them. Not, like "funny haha", more funny weird...

## Adding an External Weather Widget

Let's see an example. Let's integrate a third-party weather widget onto our site.
Head over to weatherwidget.io, which, as its name suggests, allows us to embed a
handy weather widget onto our site.

Click this "get code" button. So this is pretty common: you sign up for some service
and then they give you some JavaScript that you're supposed to paste onto your
site.

Let's do it: copy this... then go open `templates/base.html.twig`. Head to the
bottom and paste this in the footer: right before the closing `body` tag...
though you could put this anywhere.

Cool: this gives us an `a` tag... which just says "New York weather". Then,
my *guess* is that this JavaScript will execute and transform that `a` tag into
the cool weather widget that you see down here.

Let's find out! Do a whole page refresh, scroll all the way down and yes! We
have a weather widget! Now, navigate to another page and... it's broken! It's just
the original anchor tag. Where did our cool little widget go?

## What Happens When External JavaScript Executes

The JavaScript code that we pasted is pretty impossible to read. To help, select
it and then go to Code -> Reformat code. There we go! It's still a little hard to
read, but it's doable.

This is a function that calls itself and passes in these three arguments. Basically,
when this JavaScript is executed, it adds a new `script` tag to the `head` element
of our page that points to this `widget.min.js` script on their site. But this
function is smart: it gives the `script` tag an id set to `weatherwidget-io-js`.
And before it adds the `script` tag, it checks to see if it's already on the page.
If the `script` tag *does* already exist, it avoids adding it twice.

Back over at our browser, find and expand the `head` tag. Yup! There's the
`script` tag with `id="weatherwidget-io-js` that points to `widget.min.js`.

So here's what's going wrong in our case. When the page first loads, like right
now, this JavaScript function executes and the new `widget.min.js` script tag
is added to our page. Our browser downloads that file and then, my guess is that,
when that JavaScript executes, it looks for elements with a `weatherwidget-io`
class on it and transforms them into the fancy weather widget.

Inspect element on this. Yup! There's the anchor tag... but now with a big
`iframe` inside.

But *then*, when we navigate to another page, the entire `body` tag is replaced
by a new `body` tag. The weather widget that lives inside the original `anchor` tag
is now gone from the page, replaced by a new anchor tag that's just the original
boring one that says "New York weather".

However, Turbo *does* see the `script` tag that's inside of the new body - the script
tag that we have down at the bottom of `base.html.twig` - and it *does* re-execute
these lines. But this time, since the script with id `weatherwidget-io-js` already
exists up here in the `head` tag, it does not re-add it to our page. And so, no
JavaScript ever runs that *re-initializes* the widget into our new anchor tag.

## Add the script Element on Each Visit?

Okay, so now that we understand what's going on, shouldn't we just, you know, tweak
the JavaScript so that it *always* inserts the `script` tag? Let's try it. I'll cheat
and temporarily add `|| true` to the if statement so that it always executes and
adds that element.

All right. Refresh. On page one, the weather widget works. Click over to the cart
and... yea! The weather widget *still* works! Problem solved! And don't worry, the
`script` tag isn't downloaded multiple times: your browser is smart enough to pull
it from cache after it downloads it the first time.

## Having Many Duplicate script Tags on your Page

But... this might not be the best solution for two reasons. Look at the `head`
element of our page. Woh! We have two script tag!. And each time we navigate,
we would get yet *another* one.

That... might be ok? But it seems a bit crazy: eventually a user might have 50
identical `script` elements on their page.

And actually, that's precisely how some external JavaScript works. Some external
JavaScript snippets do *not* have this if statement here. And so, one of the problems
is that it *does* add more and more and more script tags when you using Turbo.

The second problem is that... whether or not executing this script file over and
over again is a good idea... sort of depends on what that `script` tag does! If
it simply reinitialize the weather widget, cool! That sounds safe. But if it, for
example, adds an event listener to the document each time it's executed, then each
time we load that script tag, we're going to add a second, third, fourth, or fifth
listener. Then, suddenly when you, for example, click the page, that JavaScript
widget's listener will execute 5 times and... do whatever it normally does *way* more
times than normal.

My point is: you need to be careful with third-party JavaScript. Let's put back
the if statement the way we found it.

So in this case, re-executing the `widget.min.js` script tag after each visit is
probably okay: it *does* seem to simply reinitialize the weather widget on this
element. But I would *love* to do that without duplicating the `script` tag and ending
up with 50 of them in my `head`. How can we do that? By removing the previous
`script` tag right before the page renders. And how can we do *that*? Via a new event
listener. Let's talk about that next and discuss the proper way to handle analytics
code so that you don't under-count or *over* count your visits.
