# Turbo: Drive, Frames & Streams!

Hey friends! Welcome back for part two of our Symfony UX series. The whole point of
this series is to take a traditional web app - so an app with Twig templates that
return HTML - and learn to do two things with it.

First, how to write truly professional JavaScript that... always works... even if
some HTML is loaded via Ajax. We covered this in the first tutorial about Stimulus.

The second goal of this series is all about how we can make our app feel like
a single page application. What I mean is: how we can make our site *lightning*
fast by *never* having *any* full page refreshes. *That* is what Turbo gives us.

## The 3 Parts of Turbo

To be more precise, Turbo is actually *three* different parts.

The first is "Turbo Drive". It's what turns clicks and form submits into Ajax
calls. This is what gives you that single page app experience.

The second part is "Turbo Frames", which allows you to separate your page into small
sections that can load and navigate independently.

And the third part is "Turbo Streams", which allows you to update any HTML element
that's currently showing on the page... from inside your Symfony app. Crazy, right?
When you use Turbo Streams along with Mercure, this can even give you the ability
to make a real time chat app...  while writing *zero* JavaScript.

And you're free to use all three parts... or just one or two: they operate
independently.

## Is Turbo New?

Now Turbo itself is... sort of brand new. If you check out its GitHub page, it's
version 7.0.0-beta.5 at the time of recording. So... why is it version 7 if it's
so new? Because *one* part of turbo - Turbo Drive, the part that turns link clicks
and form submits into Ajax calls - has been around for *years*. It was previously
called "Turbolinks" and you can still find helpful blog posts and StackOverflow
answers if you search using that term.

But the other two parts - Turbo Frames and Turbo Streams *are* brand new. These are
mostly already very good, but we *will* see a few rough edges and missing features
along the way. But we won't let that stop us: Turbo's event system will give us
the power to do almost anything.

Before we dive in, I also need to mention that Stimulus And Turbo were both affected
by a serious situation at the company Basecamp. This has left both libraries without
their lead developers. Am I worried? It's not ideal... but I'm not too worried. The
community is large and some big companies use this technology. And at the very least,
I'm confident that Turbo - or something very similar to Turbo - will be around for
a long time to come. You can't stop a great idea. We're actively integrating
Turbo into SymfonyCasts right now.

## Project Setup

So let's do this! To "turbocharge" your learning experience you should code along
with me! Hey - the puns probably won't get any better, so, settle in. Download the
course code from this page. When you unzip it, you'll have a `start/` directory with
the same code that you see here. Check out the, `README.md` file for all the setup
details.

I'll go through just the last few steps. Open a terminal and move into the project.
I'll use the Symfony binary to start a local web server with:

```terminal
symfony serve -d
```

Before we go check that out, let's also make sure to run Webpack. Install the Node
dependencies with:

```terminal
yarn install
```

And... when that finishes, run Webpack with:

```terminal
yarn watch
```

As soon as this builds... perfect - spin over to your browser and head to
https://127.0.0.1:8000 to see... MVP Office Supplies! Our store for selling minimally
viable office products to trendy startups. This is the same project as the first
tutorial though, I did make some changes, like adding a review system below each
product... and upgrading some libraries.

Next, now that we have this running, let's install Turbo and activate Turbo Drive
to instantly eliminate full page refreshes. Woh.
