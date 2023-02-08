# Mercure: Pushing Stream Updates Async

Turbo streams would be *much* more interesting if we could *subscribe* to
something that could send us stream updates in real time.

## The Use-Case: Pushing Streams Directly to Users

Like, imagine we're viewing a page... and generally minding our own business. At
the *same* moment, someone else - on the other side of the world - adds a new review
to this same product. What if that review instantly popped onto *our* page *and*
the quick stats updated? That would be... incredible!

Or imagine if, in `ProductController`, inside of the reviews action, after a
successful form submit, we could *still* return a redirect like we were doing
before... but we could *also* push a stream to the user that updates some *other*
parts of the page, like the quick stats area. I said earlier that returning a
redirect *and* a stream isn't possible. But... that's not entirely true.

The truthiest truth is that both of these scenarios are *totally* possible. How?
Turbo Streams comes with built-in support to listen to a web socket the returns Turbo
Stream HTML. It also supports doing that same thing with server-sent events, which
are kind of a modern web socket: it's a way for a web server to *push* information
to a browser without *us* needing to make an Ajax call to ask for it.

## Hello Mercure!

And fortunately, in the Symfony world, we have *great* support for a technology that
enables server-sent events: Mercure. Mercure could... probably be its own tutorial,
so we'll just cover the basics.

Mercure is a "service" that you run, kind of like your database service,
Elasticsearch or Redis. It allows, in JavaScript for example, to *subscribe* to
messages. Then, in PHP, we can *publish* messages to Mercure. Anything that has
*subscribed* will *instantly* receive those messages and can do something with
them. If you're familiar with WebSockets, it has a similar feel.

## Installing the Mercure Libraries

We're going to get Mercure rocking... and it's going to *really* make things fun.
To start, install a package that makes it easy to work with Mercure and Turbo.
At the command line, run:

```terminal
composer require "symfony/ux-turbo-mercure:^1.3"
```

***TIP
The `symfony/ux-turbo-mercure` is deprecated in favor of `symfony/ux-turbo` which already
contains the cool Mercure stuff. Just install `symfony/mercure-bundle` to get it working:

```terminal
composer require symfony/mercure-bundle
```

Or to get the version used in the tutorial, continue with:

```
composer require "symfony/ux-turbo-mercure:^1.3"
```
***

This installs several things. First, a PHP library called mercure that helps
*talk* to the Mercure service in PHP. Second, a MercureBundle that makes that
*even* easier in Symfony. And third, a `symfony/ux-turbo-mercure` library that
gives us a special Stimulus controller that helps Mercure and Turbo Streams
work together. Go team!

This executed a recipe... so run `git status` to see what it did.

```terminal-silent
git status
```

Ok cool. Let's look at `.env` first. At the bottom, we have three new environment
variables that will help us talk to Mercure. More about these in a few minutes. The
recipe also modified `controllers.json`. Remember: this means that a new Stimulus
controller is now available that lives inside this bundle. We'll use that 2 chapters
from now.

***TIP
Instead of a new section in this file, find the existing ``@symfony/ux-turbo`` section.
It will have a key called `mercure-turbo-stream`. Change its `enabled` key to `true`
to activate the Stimulus controller we'll be using.
***

This also enabled a bundle... and added a new library to our `package.json` file.
We've seen this several times before with UX packages: this adds a new package
to our project... but instead of downloading the code, it already lives in the
`vendor/` directory.

To get that part properly set up, near the bottom of the terminal output, it tells
us to stop Encore and run `yarn install --force`.

In the other tab, hit Ctrl+C to stop Encore and run:

```terminal
yarn install --force
```

When that finishes, restart Encore:

```terminal
yarn watch
```

Ok, we just installed some PHP and JavaScript code that's going to help us
communicate with Mercure. But... we don't actually have a Mercure service running
yet! That's like installing Doctrine... but without MySQL or Postgresql running!

So next, let's get the Mercure service running. There are a bunch of ways to do
this. But if you're using the Symfony binary web server like we are... then...
it's already done!
