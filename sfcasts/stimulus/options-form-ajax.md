# Options Form Ajax

Coming soon...

We can reuse this new controller on any form where we want the user to confirm before
submitting. That's awesome. But to truly unlock its potential, we need to make it
configurable like this title, text icon, and yeah. Hi, I haven't been it's over by
like the middle schools. I was going to go pick up our ages, but like I would have
had to cross math to continue at dependency, go there and there was a card with it,
like put open and left. Fortunately, the values API makes this easy at the top of our
controller, add a `static values = {}` and let's make a few things customizable. I use the
same keys is down here. So we'll say `title: String`, `text: String`, `icon: String`, and
`confirmButtonText: String`. We could configure more, but that's enough for me
below. We can use these, for example, for a `title`, we can say `this.titleValue` or `null`,
there's no way to give a value a default. So it's common to use the or syntax like
this. This means you is `titleValue`. If it's set and a truthy, [inaudible] not, but
you can use whatever logic you want to set this stuff. Let's do some more of these,
`this.textValue` or `null` `this.iconValue` or `null`. And then down here, 
`this.confirmButtonTextValue`.

And for this one, I'm actually going to say, or, or yes, if you have a confirm
button, it looks a little silly with no text. All right, that looks good. If you move
over refresh and try it out, it looks a little bit silly. Let's pass them values in
VR template. So over in `cart.html.twig`, I'll find my `stimulus_controller` and we
can pass those values via the second argument here. So let's see, let's pass `title`
that too. How about remove this item? `icon` set two warning. There are about six built
in icon types that you can use a sweet alert and then `confirmButtonText` said to
yes. Remove it.

All right. Let's try that out. Now. Remove ad. That looks awesome. While we're here.
I want to add a one more option to our controller. The ability to submit the form
after confirmation via Ajax instead of a real forum summit, because here's the goal.
After confirming, we will submit the form via Ajax, then remove the row from the cart
table without any full page refreshes side note. Our next tutorial in this series,
which will be about stimulus is sister technology. Turbo gives you an even easier way
to submit forms via Ajax, but this will, but doing this with stimulus would be a good
exercise and give us more control and flexibility first to support submitting via
Ajax. We need to change around our sweet alert, config config a little bit,

Basically add a `showLoaderOnConfirm` key set to true. And then we're basically
going to add another `preConfirm` option set to an arrow function. And this is going to take
play the, uh, take the plate. This is going to take place. This is going to replace
the `.then()`. And actually what let's do here is I'll do you in a little more factory
and let's make a method down here called `submitForm()` for now. I'll just say, `console.log()`,
thatsubmitting form, and then up here and `preConfirm`, let's call `this.submitForm()`
Basically when you use `preConfirm`, this callback will be executed after
the user confirms the big difference between this and what we had before is that this
allows us to do something asynchronous, like an Ajax call and sweetalert will stay
open and show a loading icon until that Ajax call finishes. Let's make sure you've got
this hooked up and,

And

Yes, so you can see submitting form. Now let's submit via Ajax it's down here or
plays the `console.log()` with `return fetch()`. And then for the URL, our element is a form
element. So it's actually nice. We can just say `this.element.action`. And
then for the second argument, there's two things we're going to need. We need the
method. So say methods set, do `this.element.method`, and then the body, like
what we actually need to submit to the form. And we can use a really cool trickier.
We can say `new URLSearchParameter()`, that's that object we used earlier, and then
`new FormData()`. That's another core JavaScript objects and pass this, `this.element`,
which is our form.

So that's just a really cool way to be able to submit a form via Ajax. Notice the
return here, we're returning the promise from fetch so that we can return that same
at promise from `preConfirm` things to this. Instead of closing the modal immediately,
sweet alert will rate. We'll wait for this promise, which is, or to finish, which is
our Ajax call. So let's go try it, refresh and click remove, watch the confirm
button. After I click it, it'll turn into a loading icon while the AGS call finishes
and go. Awesome. Yeah, I think that worked, it didn't remove the row from the page.
We still need to work on that, but if we refresh it's gone, but I don't want this
Ajax summit to happen on all the forms where I use this controller, because it
requires some extra work to sort of reset the page after the Ajax finishes. So let's
make this configurable over and a controller up and values add one more called the
`submitAsync` set, which will be a `Boolean`.

And then down in summit forum, we're going to see use that. So if not `this.submitAsyncValue`
then `this.element.submit()`, and that will dismiss normally and then do a
return statement. All right, nice. Let's try this for, I do. I'm here. Actually. Let
me add a couple more items to my cart. Cause it's getting kind of empty. I'll add the
sofa and all three colors. Go back to the cart and let's remove this one and
beautiful. You can see it's back to the full page refresh. Let's now reactivate the
Ajax by passing in the `submitAsync` value via the template. So down here, we'll say
`submitAsync` set to `true`. At this point, we have a clean reusable submit confirm
controller that could be used on any form as a bonus. You can even submit the form
via Ajax, but when we do that, we need to somehow remove the row that was just
deleted to do that. We're going to create a second controller around the entire car
area and have the two controllers communicate to each other. Teamwork. That's next?

Okay.

