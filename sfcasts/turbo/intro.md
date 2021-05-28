# Intro

Coming soon...

Hey friends. Welcome back for part two of our Symfony UX series. The whole point of
this series is to take a traditional web app. So web app with twig templates that
returns HTML and learn to do two things with it. First, how to write truly
professional JavaScript that always works. Even if some HTML is loaded via Ajax. The
first part of the tutorial about stimulus covered that the second thing is the topic
of this tutorial, how we can make our app feel like a single page application. What I
mean is how we can make our site super fast and responsive by never having any full
page refreshes. That is what turbo gives us. Turbo is actually three parts. First
turbo drive is what turns clicks and form submits into Ajax calls. That's what gives
you that single page app experience. Second turbo frames allows you to separate your
page into small sections that can load and navigate independently. And third turbo
streams allows you to update any HTML element on the page from inside of your Symfony
app. When you use it along with Merck here, this can even give you the ability to
make a real time chat app without writing any JavaScript.

And you can use all of these pieces or just one or two, they operate independently.
Now turbo itself is sort of brand new. Okay? If you check out the get hub page, it's
currently version 7.0 beta five at the time of recording. So why is it version seven
if it's so new, because one part of turbo turbo drive the part that turns link clicks
and form submits into Ajax calls. That's been around for years. It was previously
called turbo links, and you can still find helpful blog posts in stack overflow
answers. If you search using that term, the other two parts, turbo frames and turbo
streams are brand new. These are mostly already very good, but we will see a few
rough edges along the way, and we'll learn how to work around them.

Before we dive in, I also need to mention that stimulus interval were both affected
by a problem at the company base camp. This has left both libraries for the, without
their lead developers. Am I worried? It's not ideal, but I'm not too worried. The
community is large. And some big companies use this technology at the very least. I'm
confident that turbo or something very similar to turbo will be around for a long
time to come. You can't stop a great ideas. So let's do this to turbocharge your
learning. Yes, I reserve the right to make a terrible pun. At least once at the start
of each tutorial, you should definitely code along with me. Download the course code
from this page. When you unzip it, you'll have a start [inaudible]. You don't have a
start directory that holds the same code that you see here. Check out the, read me
that MD file for all the setup details. I'll go through just the last steps. Couple
of steps, open a terminal and move into the project. I'll use the Symfony binary to
start a local web server with Symfony serve a dash deep.

Before we go check that out. [inaudible]

Let's also make sure to run back install the no dependencies with yarn install

[inaudible].

Then when that finishes, one went back with yarn watch.

As soon as this builds, [inaudible]

Perfect. Spin over to your browser and had a one to seven to zero to zero, that one
colon 8,000

To C [inaudible]. Okay. [inaudible]

MVP office supplies, our store for selling minimally viable products, office products
to trendy startups. This is the same project as the first tutorial though, I did make
some changes like adding a review system below each product and upgrading some
libraries. So next, now that we have this let's install, turbo and activate turbo
drive to instantly eliminate full page refreshes.

