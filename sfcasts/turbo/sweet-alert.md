# Fixing the Sweetalert Modal

So we've crushed the Bootstrap modal problem! But we do have the same issue
in one other spot. Go back to the homepage, add an item to your cart and go to the
shopping cart. Try to remove the item. This cute little dialog is powered by a library
called Sweetalert. Once again, if we click back and then forward, it pops up again,
which *might* not ok... if it actually worked. But... it doesn't because all of its
event listeners were removed.

Okay: let's try using Sweetalert's close functionality to tell it to close before
the page is snapshotted. To do that, `import Swal from 'sweetalert2'`.

Then, down inside of the function, if `Swal.isVisible()` - they have a nice
function to check if Sweetalert is visible - then `Swal.close()`.

It's that simple! Or at least... it *might* be. Let's go try this. Refresh this
cart page, hit remove, go back, go forward and... it worked! Wait, I can't scroll...
and nothing is clickable. Inspect element anywhere. Uh oh: a Sweetalert backdrop
element is still there! It's invisible, but it's blocking the page!

This is the exact same problem we just saw with Bootstrap's modal: the close
animation never finishes, so cleanup never happens. The solution is to, once again,
tell Sweetalert to close but *without* an animation.

This is easier than Bootstrap... but it still tool some digging to figure out how
to disable the animation. In this case, right before we close, we can say
`Swal.getPopup()` - which gives you the HTML `Element` associated with the dialog -
`.style.animationDuration = 0ms`.

How could I possibly know this would do the trick? If you look internally at
Sweetalert, you'll notice that it looks at its popup element and checks to see
*if* the popup element has an `animationDuration` declared on its style. If it does,
then it waits for the animation to finish before cleaning up. By changing the
`animationDuration` to zero, Sweetalert will *now* see that it does *not* need to
wait and it can clean up everything immediately.

Let's try it! Refresh, click remove, click back and click forward. Everything looks
fine! When I hover over the checkout button, it is *not* being blocked by a backdrop
*and* I can click "remove" again. All is good!

## Lazily Importing Sweetalert

So one tiny problem with this approach is that both Bootstrap's modal and
the `sweetalert2` library will now be downloaded on every page since we're importing
them from our main `app.js` file.

You might not care... and you probably shouldn't care... at least not until you
investigate optimizing your CSS and JS file sizes later.

But, this *is* interesting. Sweetalet is only used on this *one* page. So, it's
kind of wasteful to force the user to download it on initial page load... even
though they'll rarely need it.

Open `assets/controllers/submit-confirm_controller.js`. This is the controller
that handles the Sweetalert confirmation on this page. Notice that it has
`stimulusFetch: lazy`above this.

This is something that we added in our Stimulus tutorial. Thanks to this, *before*
we started adding all of this new code in `app.js` - so pretend this isn't there -
the `sweetalert2` JavaScript was *not* downloaded on every page. It was only
downloaded when an element that uses this controller first appeared on the page...
which is pretty cool! Any code used by this controller literally *waits* until
its needed and then downloads itself.

But now that we're importing `sweetalert2` directly in `app.js`, it *is* being
downloaded on every page. If you care enough about this, you can fix it using a
very specific Webpack trick. It's a little nuts actually. I'll paste in the
first half of the code, indent, then close things.

Let's walk through this. The `__webpack_modules__` thing is an internal way - along
with `require.resolveWeak` - to check to see if `sweetalert2` has *already* been
imported and is available. But it does this *without* causing it to become packaged
with `app.js`. If it *has* already been downloaded, *then* we can use this import
to grab it. Because we know it's already available, this execute instantly. Then,
we can run our normal code down here. The only thing we need to change - due
to the way that the `import()` function works - is that every `Swal` needs a
`.default` to access that module's `default` export.

If this isn't making much sense to you, don't worry. This is a complex performance
optimization - I thought I'd mention it for the performance and Webpack geeks
out there.

Oh, and before we try this, scroll up and remove the now-unused import.

To see the result of this, go back to the homepage and then do a full page refresh.
Over on the network tools, view the JS tab. It's not super obvious yet, but if you
look closely at the names here... you won't see any that mention sweetalert2.
It has not been downloaded yet.

Let me clear this and let's watch what happens when we click to the cart page.
Yes! Check it out. One of the files that was downloaded - this one - has
`sweetalert2` in the middle of its name! That contains Sweetalert and proves
that it wasn't downloaded until it was actually needed... even though we have some
code in our `app.js` that takes advantage of it.

So now that we've tackled some of the most annoying problems with Turbo, which is
cleaning up the preview, let's organize all of the new event code to make room for
more turbo event listeners later. That will put us in a great position to discuss
the last tricky thing with Turbo drive: handling third-party hosted JavaScript like
JavaScript widgets and analytics code.
