# Installing Turbo

Wouldn't it be cool if when we click on a link or even submit a form, instead of
that trigger a full page reload, it made an Ajax call... then updated the page with
the new HTML? Well, that's *exactly* what Turbo Drive does. And it's a *huge* step
towards making our app feel like a single page application - or an SPA.

Turbo is... just a JavaScript library: it has nothing to do with Symfony. But Symfony
*does* have a package that makes it easier to use. So get that package installed.

Head back to your terminal, open a new tab and run:

```terminal
composer require symfony/ux-turbo
```

After this finishes... let's run: `git status` to see what its recipe did:

```terminal-silent
git status
```

Let's see: it looks like this installed a new bundle... called `TurboBundle`. It
also changed our `package.json` file - let's go find that. It added two new packages
including turbo itself. The recipe *also* updated our `controllers.json` file, which
we learned about in the Stimulus tutorial. This adds a new Stimulus controller
to our application. More on what that controller *does* a bit later.

But... you probably noticed that we have an error from yarn:

> the file `@symfony/ux-turbo/package.json` could not be found. Try running
> `yarn install --force`.

That makes sense. As we learned about in the first tutorial, we need to re-install
our yarn dependencies so it can copy the new `@symfony/ux-turbo` from our vendor
directory into `node_modules/`. Let's do it:

```terminal
yarn install --force
```

When that finishes... run `yarn watch` again and... all is happy.

```terminal-silent
yarn watch
```

## Hello Turbo Drive

Cool! So the `@hotwired/turbo` JavaScript package is now installed. So... what
do we need to do to *activate* Turbo Drive?

The answer is... nothing! It's already working.

Head back to your browser and refresh the page. Now, start clicking around. Woh!
It's alive! And it *feels* fast!

Open up your browser tools... and then go to network tools and watch for XHR
requests - or Ajax request. Yep! Every single click is now an Ajax request. There
are *zero* full page reloads!

We now have... dare I say... a single page app! Tutorial finished! Good luck!

Okay, okay... of *course* the tutorial isn't finished yet. Turbo Drive feels like
black magic... and that's never a great feeling. So next, let's discover how
Turbo Drive *works* behind the scenes. We'll also see how Turbo was magically
activated simply by installing it and I'll introduce you to a few subtle features
of Turbo Drive that are already making the experience feel extra quick.
