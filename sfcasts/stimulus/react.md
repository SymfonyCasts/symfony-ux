# React

Coming soon...

So, what about react Vue JS and other front end frameworks? Would you ever use those
as stimulus or are they opposing technologies in some ways stimulus and traditional
front end frameworks are opposite competing technologies, react or Vue. Tell us to
return JSON from our server and build HTML 100% in JavaScript while stimulus tells us
to build HTML on the server, like in our normal templates and just add some behavior
in JavaScript. This does lead us down two different paths either. You're going to
build a pure single page application in a front end framework, or you're going to
build a more traditional app that returns HTML.

I prefer apps that return HTML why well beyond the fact that I like PHP, Symfony and
twig. There are two big reasons. First single page apps are still pretty complex, but
we've been building traditional apps for decades and have a lot of tools and best
practices just think about authentication in a traditional app. It's easy. It's a log
and form that uses session cookies. And if you want to submit that via Ajax instead,
great. Do it with a single page app. There's a lot to think about with potentially,
uh, OAuth access tokens and other things. The second reason I prefer traditional apps
is that you absolutely can still get the nice single page app experience avoiding
full page refreshes.

How

With stimulus is sister technology turbo, which we'll talk about in the next
tutorial. So if you're motivated to build a single page app to avoid full pier page
refreshes, well, that's not unique. It's not actually unique to single page
applications. Anyways, if you do choose to build an application that returns HTML and
use a stimulus, is there any use for something like react or Vue? Absolutely. If you
need to build a particularly complex feature on your site, like some widget somewhere
using react or Vue might be the best tool for the job. And when you do this, that
react or vue app will fit perfectly into stimulus. So enough talk, let's see an
example by building a mini react app to add support for react in Encore, open the
`webpack.config.js` file. And you should have a spot in here that says
`enableReactPreset()`.

You can uncomment that whenever you change `webpack.config.js`. You need
to restart Encore. So, so I'll head over to my terminal, find a tab with Encore, stop
and rerun 

```terminal
yarn watch
```

When we do that, ah, it reminds us, we now need a new library.
I'll copy that yarn, add line and paste that

```terminal-silent
yarn add @babel/preset-react@^7.0.0 --dev
```

that's enough to get Encore, to be able
to parse, uh, react files. Of course actually use react to we're going to need to
install react. So let's say `yarn add react` and then also `react-dom` to be
able to render it.

```terminal-silent
yarn add react react-dom --dev
```

Now we're ready. Here's the goal at the bottom of any page, we have a little made
with love filter footer. It's a pretty simple example, but I want to rebuild this in
react and make it so that if we click it, the hearts multiply. If you downloaded the
course code, you should have eight `tutorial/` directory where they `MadeWithLove.js`
react component inside. Okay, copy that. Go to `assets/`. Let's create a new
directory called how about `components/`? And let's paste that in there. This simple
component renders the text, manages a hearts state and increases that heart state on
quick. Awesome. So how do we render react components? If we're using stimulus with a
custom stimulus controller, of course, and `assets/controllers/`, let's create a new
file called `made-with-love_controller.js`. We'll start the same way we
always do. I'll cheat and copy code from `counter_controller`. Okay, great. A `connect()`
method in `console.log()` this time. Nice heart.

Okay.

Then next open up `templates/base.html.twig` and scroll down to find the footer.
Here it is. Let's replace all this in here with a nice called the stimulus
controller. So I'll keep this footer `<div>` break into multiple lines and we will add
curly, curly `stimulus_controller()` and pass this `made-with-love`. All right.
Let's make sure that's connected. Oh, before I do that, I need to make sure that I
restart Encore with a 

```terminal
yarn watch
```

Awesome. Head over,

Refresh our page and perfect. There's our heart. Wow.

We're good with this simple setup. Okay.

We can now render the react component into, into our element in connect first. I'll
import a couple of things on need. I know I'll need `ReactDOM` from `react-dom`. Um, I'll
need `react` from `react` and then we'll need our actual components. So 
`import MadeWithLove from '../components/MadeWithLove'`. Then down here, we can
leverage, react on `ReactDOM.render()` and then we'll use some JSX here. So we
use `<MadeWithLove />`. We won't pass that any props, and then we're going to actually
want to render this into our elements. So it's `this.element`.

How'd I say it

Let's try. It had ever refreshed. And yes, there is our text coming from the react
element. And if we click this heart, it multiplies you tuffle. So yeah, stimulus in
front of the frameworks they play together. You roughly want to see something even
cohort. I'm going to inspect element on this footer and let's actually hit edit as
HTML. And right, maybe above this, I'm going to create another 
`<div data-controller="made-with-love">`. And I make sure I have `data-controller` in that data
 and I'll close that. What are these going to happen when I do this? When I click
off boom, we get a second react component, independent react component,

Stimulus

Notices the new element, instantiates, a new controller, which of course renders a
second react application with this setup. No matter how or when an element gets onto
the page, stimulus will handle booting up the new react app, but stimulus can do even
more for react, like allowing us to pass data directly from our server into the react
component as prompts. That's next.

