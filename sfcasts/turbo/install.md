# Install

Coming soon...

Wouldn't it be cool. If when we click on a link or even submit a form yep. Instead of
that, making a full page, refresh it made an Ajax call, then updated the page with
the new HTML. Well, that's exactly what turbo drive does. And it's a huge step
towards making our app feel like a single page app or an spa turbo drive is turbo is
just a JavaScript library. It has nothing to do with Symfony, but Symfony does have a
package that makes it easier to play with. So let's go get that package, install,
head back to your terminal, open a new tab and run composer require Symfony /UX
turbo. Yeah. After this finishes, let's run. Get status to see what change, what
changes it's recipe made. Let's see this installed a new bundle called turbo bundle.
It also changed our package that JSON file

I'll show you that it had a

Two new packages including turbo itself. Then also updated our controllers that JSON
file, which we learned about in the first, this tutorial, this adds a new stimulus
controller to our application, more on what that controller does later, but now you
probably saw it. We have an error from yarn the file, add Symfony UX turbo package
that JSON could not be found. Try running yarn installed dash dash force.

That makes sense. As we learned about in the first tutorial, we need to re-install a
yarn

So it can copy our new package into the node modules, directory. Okay. Control CN,
yarn run, yarn installed dash dash force, and one that finishes yarn watch again and
all is happy. So what do we do now to activate turbo drive

Nothing. It already works back to the browser. What's a refresh the full page and
start clicking around. Whoa. It already does feel a lot faster.

All right, click it and go to inspect and then open up my network tools and watch the
XHR. That's the Ajax requests. Every single click is an Ajax request. There is no
full page reloading happening.

We now have dare I say a single page app tutorial finished by. Okay. Of course we
can't finish yet. This feels like magic and that's never a great feeling. So next
let's find out how

Turbo drive works behind the scenes.

We'll also see how turbo was

Magically activated simply by installing it.

And I'll introduce you to a few, uh, subtle features of turbo drive beyond what you
can see already.

