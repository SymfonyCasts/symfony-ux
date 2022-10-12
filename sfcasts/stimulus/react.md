# React (or Vue) ❤️ Stimulus

So what about React, Vue.js and other frontend frameworks? Would you ever use those
with Stimulus? Or are they opposing technologies?

## Single Page Apps (SPAs) vs Server-Rendered HTML

In some ways, Stimulus and frontend frameworks *are* opposite, competing
technologies. React, Vue or any other framework, tell us to return JSON from our
server and build HTML 100% in JavaScript. But Stimulus tells us to build and
return *HTML* from our server... then add any extra behavior we need via JavaScript.

This *does* lead us down two different paths. Either you're going to build a pure
single page application in a frontend framework *or* you're going to build
an app that primarily returns HTML from the server.

I prefer apps that return HTML. Why? Well beyond the fact that I like PHP,
Symfony and Twig, there are two big reasons. First, we've been building so-called
"traditional" apps for *years*... and we have a *ton* of tools & best practices that
help us get our job done faster! Think of Symfony's form system, automatic CSRF
protection, tools for handling social login just to name a few.

The second reason I prefer traditional apps is that you absolutely *can* still get
the nice, single page app experience of avoiding full page refreshes. How? With
Stimulus's sister technology Turbo, which we'll talk about in the next tutorial.

So if you're motivated to build an SPA *mainly* to avoid full page refreshes,
well, that's not actually unique to single page applications.

Anyways, if you *do* choose to build an application that returns HTML and
uses Stimulus, is there any use for something like React or Vue? Absolutely! If
you need to build a particularly complex feature or widget on your site,
using React or Vue might be the best tool for the job! And when you do this,
that React or Vue component will fit *perfectly* into Stimulus.

## Activating React in Encore

So enough talk! Let's see an example by building a mini React app. To add support
for react in Encore, open `webpack.config.js`. You should have a spot in here
that says `enableReactPreset()`. Uncomment that.

[[[ code('357dab8a50') ]]]

Whenever you change `webpack.config.js`, you need to restart Encore. Head over
to your terminal, find the tab with Encore, stop it with Ctrl+C and re-run:

```terminal
yarn watch
```

Ah, excellent! It reminds us that we now need a new library. Copy that and paste:

```terminal-silent
yarn add @babel/preset-react@^7.0.0 --dev
```

That's enough to get Encore to be able to parse React files. But to actually
*write* those files, we need two more libraries:

```terminal
yarn add react react-dom --dev
```

## Adding the React Component

*Now* we're ready. Here's our mission: at the bottom of any page, we have a
little "made with love" footer. It's a simple example, but I want to rebuild this
in React and make it so that if we click the heart, it multiplies.

If you downloaded the course code from this page, you should have a `tutorial/`
directory with a `MadeWithLove.js` React component inside. Copy that. Go to
`assets/`. Let's create a new directory called `components/` and paste the new
file there.

[[[ code('4f6947480b') ]]]

This simple component renders the text, manages a `hearts` state and increases
that heart state on click.

## Adding a Stimulus Controller

So... how do we render React components if we're using Stimulus? With a custom
Stimulus controller of course!

In `assets/controllers/`, add a new file called
`made-with-love_controller.js`. Start the same as always... by cheating! Steal
the code from `counter_controller`... add a `connect()` method and `console.log()`,
this time, a heart.

[[[ code('18efee37ec') ]]]

Next open up `templates/base.html.twig` and scroll down to find the footer...
here it is. Let's replace all of this with our controller. I'll keep the footer
`<div>`... break it onto multiple lines and then add
`stimulus_controller('made-with-love')`.

[[[ code('a47d4a18c2') ]]]

Let's make sure that's connected. Oh, before I do that, I think I forgot to
restart Encore:

```terminal
yarn watch
```

Awesome. Head over, refresh the page and... perfect. There's our heart!

## Rendering a React Component in Stimulus

With this simple setup, we can now render the React component into our element.
First, we need to import a few things: we need `ReactDOM` from `react-dom`,
`react` from `react` and then our component:
`import MadeWithLove from '../components/MadeWithLove'`.

[[[ code('4fafc78145') ]]]

Down in connect, say `ReactDOM.render()`, pass this our component -
`<MadeWithLove />` - and then `this.element`.

[[[ code('29bc419de0') ]]]

That's it! Go back to the page and try it. Yes! That text is coming from our React
component! And if we click the heart, it multiplies!

So... yeah! Stimulus and frontend frameworks... are... oddly enough... kind of
BFF's ("best friends forever").

Want to see something cooler? I'll inspect element on the footer and... hit
"Edit as HTML". Right above this, I'll hack in another
`<div data-controller="made-with-love">`... oh, make sure that's `data-controller`.

When I click off to add that... boom! We get a second, independent React component!
Stimulus notices the new element and instantiates a new controller, which, of
course, renders a second React app. With this setup, no matter how or when an
element gets onto the page, Stimulus will handle booting up the react app.

But stimulus can do even more for React or Vue: it can help us pass data
directly from our server into a component as props. It can even do this for
full objects! Let's see how next.
