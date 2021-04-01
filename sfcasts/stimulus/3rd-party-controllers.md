# Free 3rd Party Controllers!

This `search_preview` controller has been an awesome example for us to learn how to
make great-looking functional things in Stimulus. But I have a confession to make:
we didn't *really* need to do all this work. Why? Because somebody already made
an open source autocomplete Stimulus controller!

## Hello stimulus-autocomplete!

Head to https://yarnpkg.com and search for "stimulus". You'll actually find a lot
of Stimulus tools here that you can look through. What we're looking for is called
us [stimulus-autocomplete](https://github.com/afcapel/stimulus-autocomplete). Here
it is. Head to GitHub for find its documentation. Ooh! A GIF! Or.. GIF! Um, this
looks like *exactly* what we want!

## Hello stimulus-components!

Before we implement this, this is just one of *many* pre-made Stimulus controllers
that exists out there. Let's take a quick tour-de-tools!

First up is
[Stimulus Components](https://stimulus-components.netlify.app/docs/components/index/):
a collection of a *bunch* of controllers... each with a fancy demo! Let's pick a
random one - how about this Lightbox controller - and click into its demo. Yup!
If you need to display photos in a Lightbox, there's a pre-made controller for
that!

## Hello TailwindCSS Stimulus Components!

If you use Tailwind CSS, check out the
[TailwindCSS Stimulus Components](https://github.com/excid3/tailwindcss-stimulus-components)
It *also* has a demo with a bunch of cool stuff on here, like a slide over, modals
tab functionality down here and more.

## Hello stimulus-hotkeys!

There's also a [stimulus-hotkeys](https://github.com/leastbad/stimulus-hotkeys)
if you want to be able to easily add keyboard shortcuts to a Stimulus controller.

## Hello stimulus-flatpickr

Need a date picker library? Try the
[stimulus-flatpickr](https://github.com/adrienpoly/stimulus-flatpickr) controller,
which integrates with [flatpickr](https://flatpickr.js.org/) JavaScript library.
And... that's not even everything! You can check out the
[Awesome Stimulus](https://github.com/skatkov/awesome-stimulusjs) GitHub resource
for more libraries, packages, podcasts, etc about Stimulus.

## Hello BetterStimulus.com

One last interesting resource is [betterstimulus.com](https://www.betterstimulus.com/),
which holds a bunch of interesting patterns and best practices around Stimulus.

## Installing stimulus-autocomplete

*Anyways*, let's get back to integrating this autocomplete controller. First, we
need to install it! Copy the "yarn add" command, find your terminal and run:

```terminal
yarn add stimulus-autocomlete --dev
```

## Registering 3rd Party Controllers

So... once this finishes... how are we going to actually *use* this new controller?
When we add a file into our `assets/controllers/` directory, Stimulus automatically
registers that as a controller, which means we can add a `data-controller` attribute
to the page and it'll work. When we install a Symfony UX package, the
`controllers.json` does the same thing for *those* controllers.

But what about the controller that we just installed? How do we register that with
our Stimulus app? Back at the docs, under "Usage", you can see that they import
the `Autocomplete` package... then all some `application.register()` thing
to register this *one* controller.

But... which file should we add this code to? The answer is `assets/bootstrap.js`.
Notice that this looks *pretty* similar though to the code example, though not
exactly the same. This `application` variable in the docs is the same as the `app`
variable that we have in our file.

On the docs, copy the `import` line and... pop that onto the top of our file. Then,
to register the controller, down here say: `app.register('autocomplete')` - that
name could be *anything*... and will determine the `data-controller` that will
be used to connect to this controller - then  `Autocomplete`.

Congratulations! With 1 command and 2 lines of code, we now have a new controller
called `autocomplete` in our app.

Next: let's use this controller instead of our `search-preview` controller. It's
going to be, well, refreshingly easy to drop in. Also: can we make this third-party
controller load lazily like we've with some other controllers? Totally!
