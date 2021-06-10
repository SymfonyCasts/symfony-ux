# Fixing the Sweetalert Modal

Coming Soon...

So we've crushed the Bootstrap modal problem! But we do have the same problem
in one other spot. Go back to the homepage, add an item to your cart and go to the
shopping cart. Try to remove the item. This cute little dialog is powered by a library
called Sweetalert. Once again, if we click back and then forward, it pops up again,
which *might* not ok... if it actually worked. But... it doesn't because all of its
event listeners were removed.

Okay: let's try using Sweetalert's close functionality to tell it to close before
the page is snapshotted. To do that, `import Swal from 'sweetalert2'`.

Then, down inside of the function, if `Swal.isVisible()` - they have a nice
little function to check if sweetalerts visible, then `Swal.close()`.

It's that simple. All right, let's try this. Refresh this car, page it remove, go
back, go forward. And it figure worked. Wait, I can't scroll. And nothing is
clickable. You inspect element anywhere. You'll find that the sweetalert container
it's backdrop is still there. It's invisible, but it's blocking the page. This is the
exact same problem we just saw with bootstraps modal. The close animation never
finishes. So before cleanup never happens. So we can once again, tell sweetalert to
not use animations. This is easier than a bootstrap, but it still takes some digging
to figure out how to disable animations. So in this case, right before we close, we
can say `Swal.getPopup()` which is this kind of internal popup object `.style`, or
actually it's an element `.animationDuration = `0ms`. Yeah. So if you
look internally inside sweet alert, you'll notice that it looks at its pop-up element
and checks to see if the pop-up element has an animation, uh, declared on its style.

If it does, then it does the whole waiting for the animation to finish. So by
changing the animation duration to zero milliseconds internally, sweet alert. We'll
now see that it does not need to wait and it can clean up everything immediately. All
right, let's try this refresh, click remove, quit back. Quick forward. Everything
looks fine. And as you can see, if I have our own checkout button, I'm not being
blocked by big a backdrop of a hair remove again, everything still works. So one tiny
problem with this approach is that both bootstraps, modal and sweet alert will now be
downloaded on every page. Since we're importing them from our main `app.js` file, you
might not care and probably you shouldn't care, But for us, squealer is only used on
this one page. So it's kind of wasteful to force the user to download it on initial
page load, even though they'll rarely need it. And if you open
`assets/controllers/submit-confirm_controller.js` open this file. This is the controller that
handles this sweetalert confirmation opening up.

Yeah.

Notice that we have it `stimulusFetch` lazy above this. This is something that we
built in our stimulus tutorial, and we learned about this really cool feature. Thanks
to this. Before we started adding all of this code in `app.js`. So pretend this isn't
there. It meant that sweet alert was not downloaded on every page. It was only
downloaded when an element that uses this controller first appears on the page, But
now that we're importing sweet alert directly in `app.js`, it is being downloaded on
initial page load. If, if you care enough about this, you can fix it using a very
specific Webpack trick. it's a little nuts, actually. I'm a copy then.

So I'm a copy of the first half of some statements here in dentist inside,

And then close some things up. So let's walk through this here. So this
`__webpack_modules__`, things have really, really internal way along with this
`require.resolveWeek` to check to see if  `sweetalert2`, has already been
imported and is available, but it does it without causing it to become packaged with
`app.js`, if it has already been downloaded, then we can use this import to grab
it. Since it's already downloaded that this will just run, this will run instantly,
and then we can run our code down here. The only thing we need to change is due to
the way that the import syntax works. We need to add `Swal.default` on
to all of these. If this is not making sense to you, this is very, very complex and
it's a performance optimization. So just don't worry about it. But to see the result
of this, let's go back to the homepage of our site and do a full page refresh over on
the network tools, go over to the JavaScript. It's not super obvious yet, but if you
look closely at the names here, you won't see that a sweet alert has been downloaded
yet, but check out what happens when we click to the cart page. Actually let me clear
this. Let's click the cart page and yes, there it is. You can see downloaded two
files. It's not very obvious by their name.

[inaudible]

All right, before I try this, though, don't free to go up here and move this import
right there

To see the reason

To see the result of this. Let's go back to the homepage, do a full page, refresh and
go over to your network tools and click on J S. Now it's not too obvious in here. If
you look closely, I don't see anything that looks like it has the name of Sweedler in
it. So if we've done our job correctly, it's it has not been downloaded yet. Let me
clear this and let's watch what happens when we click to the cart page. Yes. Check
this out. One of the files that was downloaded right there, you can see in the middle
of the name, it contains sweet alert. So this proves that Sweedler was not downloaded
until it was actually needed. Even though we have some code in our app.JS file, that
takes advantage of sweet alert. So now that we've tackled some of the most annoying
problems with turbo, which is cleaning up the preview, let's organize all of this new
code to make room for more turbo event listeners later, that'll put us in a great
position to discuss the last tricky thing with turbo drive handling, third party
hosted JavaScript like JavaScript, widgets, or an analytics code.
