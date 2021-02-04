# Hello Symfony UX!

Hi friends!

I sure am glad you're here for part one of our two part Symfony UX series. Okay. So
what is this Symfony UX thing that requires a short and I promise exciting history
lesson until a few years ago, the web worked one way your server, like our Symfony
app created HTML and sent it to the user. Then we usually sprinkled some JavaScript
on top of that. Then front end frameworks came along with a new, totally new paradigm
where your server returns, JSON. And then the HTML is built on the client side in
JavaScript taken to the extreme, you get single page applications. And so it seemed
that the world was destined to keep moving in this direction. Do use front end
frameworks to generate HTML after all isn't the user experience of a single page app
with its lack of page reloads and instant updates, better bots, a movement was
growing a movement in the other direction. One, that's all about generating HTML on
the server. Hey buddy,

This exploded in 2020 when DHH and company greater of Ruby on rails and base camp
builds an email app entirely with server side generated HTML. They claimed that they
could get a single page application experience by building a traditional web app.
They call the idea Hotwire Symfony UX is built on top of this concept. Okay. So what
does this mean for us? Having a beautiful user interface needs two things. First, we
need a way to write professional JavaScript, not 1000 line long jQuery files, and we
need that JavaScript to work. Even if we load some HTML via Ajax, that's a classic
problem with how we have historically done JavaScript. This is solved with stimulus,
a tiny JavaScript library and the topic of this tutorial. The second part of a
beautiful user interface is being able to avoid full page refreshes. That's handled
by turbo. The topic of the next tutorial Put together stimulus in turbo, allow you to
write clean JavaScript that always works and to make every page load and form submit
load via Ajax. So basically these single page application experience without the
hassle of building a single page application.

And if you're wondering this does not kill front end frameworks like react or view.
In fact later in the tutorial, we'll render a view component from inside of stimulus.
And we'll talk about when and why you might do that. So let's get started. This stuff
is super fun. So I recommend coding along with me, download the course code from this
page and unzip it after you do, you'll find a start directory with the same code that
you see here. Check out this a wonderful read me for a great reading experience and
the instructions on how to get your project set up.

This is a tutorial about JavaScript, but we're using a fully featured Symfony app
with a database. The last step in the remeet will be to find a terminal, move into
the project and start up the flux capacitor by running Symfony serve dash D don't
worry. I programmed the flux capacitor to not let us go back to 2020. This will start
a local web server in the background to see the site we can run Symfony open local,
or if you're less lazy, go to [inaudible] zero.zero.one, colon 8,000 and say hello to
MVP office supplies, our office furniture startup for other startups that embrace the
minimum viable product mentality. All of our products are guaranteed to be barely
viable at best. This is the same project we built in our view tutorial. If you want
to compare the two right now, this site is styled, but it has absolutely zero
JavaScript. It's a completely traditional app with full page reloads and even adding
things to the cart like this has done just via a traditional form. Even the CSS file
itself is just a static file that lives in public styles. So it's not being processed
in any way. So next let's install Webpack Encore and use it to get a basic JavaScript
and CSS set up that will form the baseline for using stimulus.

Okay.

