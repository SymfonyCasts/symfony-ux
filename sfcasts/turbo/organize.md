# Organizing our Turbo Events Code

To get Turbo Drive to work *super* nicely, we're going to need to hook into a few
turbo events, like `turbo:before-cache`. Before we're done, we'll listen into
even *more* events to help us properly load JavaScript widgets, add transitions,
and do more craziness when we talk about Turbo Frames.

## Isolating the Turbo Logic

So instead of putting all that logic right here in `app.js`, let's organize a bit.
There's no right or wrong way to do this, but let's create a class that holds all
of this special Turbo logic. In the `assets/` directory add a sub-directory called
`turbo/` and, inside a new file called `turbo-helper.js`. Start with `const
TurboHelper = class {}` with `constructor() {}` method inside.

Head back to `app.js`, copy all of this code, and paste! When we did that, PhpStorm
added the `import { Modal }` automatically. At the bottom of this file, export
`export default new TurboHelper()`.

This instantiates a new instance of our object and exports it. It won't really matter
for us... but thanks to this, each time we import this module, we'll get the same
*one* instance of this object back.

In `app.js`, delete all the original code and then `import './turbo/turbo-helper'`.
We don't need to set that to a variable... and *just* by importing it, the object
will be instantiated and the listeners will be registered. So this should be enough
to get it to work.

Let's try! Refresh, click to remove an item, go back and go forward. Yep! All good.

## Organizing the Class

Now that we have a class, we can organize a bit more. Copy the modal code here, remove
it, create a new method below called `closeModal()` and paste. Then, back up
inside the `turbo:before0cache` callback, say `this.closeModal()`.

Repeat this for Sweetalert: copy all of the Sweetalert code, create a new method
called `closeSweetalert()`, paste... and... then back in the callback, call it:
`this.closeSweetalert()`.

That looks better! Let's... make sure we didn't mess anything up. Do the same
dance as before: refresh, click remove, go back and go forward. All good!

Next: let's learn what types of things can go wrong when including third-party hosted
JavaScript, like a JavaScript widgets or analytics code. This type of JavaScript
is often supposed to be included in the *body* of the page... and often is expecting
full page refreshes.
