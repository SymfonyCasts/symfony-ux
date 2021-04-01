# 3rd Party Controllers

Coming soon...

This `search_preview` controller has been an awesome example for us to learn how to
make great looking functional things in stimulus. But I have a confession to make. We
didn't really need to do all this work. Why? Because somebody's already made an open
source autocomplete stimulus controller, head to https://yarnpkg.com and search for
stimulus. You'll actually find a lot of stimulus tools here that you can look
through. What I'm looking for is called a stimulus auto complete head to get a page
for its documentation. Ooh, a GIF that looks like exactly what we want, by the way,
before we implement this, this is only one of many pre-made stimulus controllers that
exists out there.

Stimulus components has a bunch of them, including demos for each of them like this
light box controller. If you use tailwind CSS, check out the tail and CSS stimulus
components. It also has a demo with a bunch of cool stuff on here. Like a slide over
modals and have functionality down here. Just to name a couple of things. There's
also a stimulus controller for any gating, with the hotkeys library. This allows you
to add a bindings via hockey's a controller for email, for, uh, integrating with flat
picker, which if you don't know as a really nice date picker library, just to name a
few of these, Oh, there's also betterstimulus.com, which holds a bunch of really
interesting patterns and best practices around stimulus. Anyways, let's get back to
integrating this autocomplete controller first. Let's get installed. I'll copy the
yarn. Add command, find a terminal, run 

```terminal
yarn add stimulus-autocomlete --dev
```

And then wait for this to finish. But now how do we use this new controller?
When we put files into our `assets/controllers/` directory stimulus automatically
registers those as controllers. And when we install a Symfony UX package, the
`controllers.json` does the same thing for those controllers. But what about the
controller that we just installed? How do we register that with stimulus back at
Zach's under usage, you can see that they call something called 
`application.register()` to do it

To register this one controller. But where does this go in our code? The answer is
`assets/bootstrap.js` you notice this looks pretty similar though. Not exactly
the same as the `bootstrap.js`. They show over here, they are doing the same thing.
This `application` variable here over on our side is known as app Is application
variable here. It's the same as this `app` variable that we have in our file. All we
need to do is import the library. Uh, copy that from the documentation and pop it on
top.

And then register the controller down here `app.register('autocomplete')`. This could
be anything we want, but complete makes sense. And then pass at that `Autocomplete`
that we just passed it. Congratulations. You now have a new controller called
`autocomplete`. Next let's use this controller instead of our search preview
controller. It's going to be well refreshingly, easy to drop in. And could we make
this third-party controller lazy if we want to Totally.

