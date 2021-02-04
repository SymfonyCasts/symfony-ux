# Symfony UX

Hey friends! I *sure* am glad you're here for part 1 of our 2 part Symfony UX
series.

## HTML Build on the Server or in JavaScript

But what *is* this Symfony UX thing anyways? That requires... a short - and I promise
*thrilling* - history lesson. Until a few years ago, the web worked one way: your
server - like our Symfony app - created HTML and sent it to the user. Then, we
usually sprinkled some JavaScript on top.

Then, frontend frameworks came along with a totally new paradigm where your
server returns JSON and the HTML is built on the *client* side in JavaScript. Taken
to the extreme, you get single page applications.

And so, it seemed that the world was destined to keep moving in this direction: to
use frontend frameworks to generate HTML. After all, isn't the user experience of
a single page app way better? With its lack of page refreshes and instant updates?

## Hello Hotwire

But a storm was growing - a movement in the other direction. One, that's *all*
about doing what we did 5 years ago: generating HTML on the server.

This exploded in 2020 when [DHH](https://twitter.com/dhh) and company - creator
of Ruby on Rails and Basecamp - built a new email service entirely with server-side
generated HTML. Their claim was bold: that they could get a single page application
experience by building a *traditional* app.

They called the idea [Hotwire](https://hotwire.dev/). Symfony UX is built on *top*
of this concept.

## Hotwire: Stimulus & Turbo

Ok: so what does this mean for us? Having a beautiful & interactive user interface
requires two things. First, we need a way to write professional JavaScript... not
1000 line long jQuery files, or one-off scripts. And we need that JavaScript to
work... *even* if we load some HTML via AJAX. That's a classic problem with JavaScript:
behavior isn't necessarily applied to HTML elements that arrives later.

This is all solved with Stimulus: a tiny JavaScript library *and* the topic of
this tutorial.

The second part of a rich user interface is being able to avoid full page refreshes.
That's handled by Turbo: the topic of the next tutorial.

Put Stimulus and Turbo together and suddenly you can write clean JavaScript that
always works *and* have a site where every link click and form submit loads via
AJAX. So basically: the single page application experience... without the hassle of
building a single page application.

And if you're wondering: does this kill frontend frameworks like React or Vue?
Nope! Though, you'll probably use them less, if ever. Later in the tutorial we'll
render a React component inside of Stimulus and talk about when and why you might
do that.

## Project Setup

So... let's get this party started! This stuff is super fun so I recommend
coding along with me. Download the course code from this page and unzip it. After
you do, you'll find a `start/` directory with the same code that you see here.
Check out the `README.md` file for *all* the details you'll need to get the project
running. This *is* a tutorial about JavaScript, but we're using a fully-featured
Symfony app with a database.

The last step in the README will be to find a terminal, move into the project and
start up the flux capacitor by running:

```terminal
symfony serve -d
```

Don't worry: I programmed the time machine to *not* let us go back to 2020. This
starts a local web server in the background. To see the site, we can run:

```terminal
symfony open:local
```

Or, if you're less lazy, go to `https://127.0.0.1:8000` and say hello to...
MVP office supplies! Our office furniture startup for *other* startups that
embrace the minimum viable product mentality. All of our products are guaranteed
to be *barely* viable at best.

This is the same project we built in our Vue tutorial if you want to compare them.

Right now, the site *is* styled but it has absolutely *zero* JavaScript. It's a
completely traditional app with full page reloads. Adding things to the cart
like this is done by submitting a normal, boring form.

Even the CSS itself is just a static file that lives in `public/styles/`: it's not
being processed in any way.

So next: let's install Webpack Encore and use it to get a basic JavaScript
and CSS setup that will form the baseline for using Stimulus.
