# Cleanup Before Snapshotting (e.g. Modals)

If we refresh open a modal, click back, then click forward again. We have a pretty
strange looking page. The modal did not completely hide itself. The problem is
basically that hiding a modal is a synchronous in bootstrap waits for the transition
to finish before finally removing all of the elements like this backdrop, but the
snapshot does not wait. It basically takes the snapshot. The moment things start to
close, which is one that backdrop is still visible, worse because the modal element
is technically removed from the page. It's transition is canceled. And so bootstrap
doesn't know its time. Isn't notified that it's ever time to do the final clean.

Yeah, the fix or refresh

Is to force both the modal and the backdrop to be synchronous, to remove the
animation that you see right now. When I click off of this, that's you not usually
something you do after a modal is created. So it's a bit ugly to get this working

I'll paste in the code.

This does the same thing as before it finds the element, the modal instance and calls
Hyde

On it,

But it also does some extra stuff. Most importantly, before it hides, we remove the
`fade` class from the modal. And we also reach into this internal ugly internal
backdrop instance, config to set it `isAnimated` to false. The results is that
bootstrap will now know that Niagara that both the modal and the backdrop should not
fade out. They should be removed instantly. The precise fix for this type of problem
will be different. Each time you run into it. And you usually need to dig around in
the third-party code a little bit to figure out the best option. Figuring this out, I
admit was tricky, but ultimately don't overthink it. Your goal is to basically clear
out elements that you don't want visible and a preview. Often you can just find the
problematic element and remove it. The good news is that this will fix the problem
for the entire site. Let's try it. I'll refresh the page.

Open the model, click back, click forward. And yes, it's gone. And if we click add
new product, the modal still works. You might notice that the backdrop is missing
this time, but that's only due to the bug in bootstrap 5.0.1 that I mentioned
earlier that will work in bootstrap 5.0.2. So we have the same problem. No, we fix
this. We do have the same problem in one other spot. Let me go back to the homepage
and let's add an item to my cart and go to the shopping cart. Now remove this cute
little dialogue is powered by a library called sweet alert. Once again, if we click
back and then forward, it pops up again, which might not be the worst thing if it
actually worked, but it doesn't because all of its event listeners were removed.
Okay. Let's try using sweet alerts, close the functionality to, to tell it to close,
to do that this time we're going to `import SwaL from 'sweetalert2'`. And then
down here, still inside of our function, we can say if `Swal.isVisible()`, so they
have a nice little function to check if sweetalerts visible, then `Swal.close()`.

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

