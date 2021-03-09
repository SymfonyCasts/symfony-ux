# Ux Chart Js

Coming soon...

We now know stimulus pretty well, which as we know is really just a nice JavaScript
library that has nothing to do with Symfony. So then what exactly is Symfony UX to
answer that head, to get out of that com class Symfony /UX at its most basic Symfony
UX is a growing list of stimulus controllers that you can install into your app to
get free functionality. One of them is a controller that integrates chart JS, which
is a completely independent, really nice JavaScript power to chart library.

Since our

Sales are really starting to take off let's install and use this to build a sales
graph on an admin page, I've already created a new page at /admin.

Okay.

No, it's not very interesting yet. And none of these links actually go anywhere. All
right, let's go and check out the docs for Symfony UX chart dot JS, scroll down.
Whoa. Stimulus in chart JS are both pure JavaScript libraries. So it's a little
interesting that the first step, I mean, installation is to run is to install a PHP
package. Why are we doing that? Let's find out copy the composer require line and
spin, or dear terminal. I committed all my changes before hitting record so that I
can see exactly what any flex recipes do when I run this paste and go

[inaudible],

You'll see an error come up from Encore. Don't worry about that yet. When this
finishes running gets status interesting. This modified composer.json and composer
dot lock, which is totally normal, the PHP package we just installed is actually a
Symfony bundle. So the flux recipe auto enabled that bundle and config bundles dot
PHP, and updated the Symfony that lock file, but here's where things get interesting.
Get really interesting and also updated our package that JSON file and some other
file called assets /controllers that JSON let's see what changed in packaged at JSON,
get diff package dot JSON, Interesting it, a new package, but instead of a version
number over here, it's pointing to a directory inside of vendor. Let's go check out
that path. It was vendor Symfony, UX chart, they resource assets and cool. It's a
JavaScript package. It has a package dot JSON like all JavaScript libraries do

And

Stimulus controller inside of the source directory. Ooh, the real file we'll actually
use is a disc /controller dot JS. It's not really important. This one's a little bit
less readable, but it works in all browsers. So what does this all mean? The Symfony
UX libraries like UX chart JS are part PHP bundle in part and JavaScript library. And
there's also some magic related to how this controller is registered in our
application. But we'll talk about that in a few minutes. Anyways, we have a new PTP
bundle in our vendor directory and a new JavaScript package registered in packaged
dot JSON in our package that JSON, which I will open here, which actually points to
that directory in that bundle back to the docs. The next step is to run yarn
installed dash dash force. Let's copy that.

Normally, if you add a package to package that JSON, you need to run yarn install to
actually install that package. And actually that did just happen to us. The flex
recipe added in a new package to our package, that JSON move over, find the terminal
with Encore in it. And let's actually say quit Encore and then run yarn install dash
dash force. This command forces yarn to re-install your dependencies for us. The
important part is that that this actually copies the new copies, this directory here
into, and she copies the new, uh, package directory from vendors, Symfony UX chart JS
into node modules. But check it out. I can now scroll up, let me close a couple of
things. Open node modules we have at Symfony. We of course have Webpack Encore in
there, but we now have a UX chart JS, which is a copy of that vendor directory.

Hmm.

Things building again. Let's restart Encore,

Yarn watch.

Okay. So how do we use the new stimulus controller that lives inside the package to
answer that let's follow the docs, scroll down. Yep. To build the JavaScript powered
chart. We're actually going to write PHP, copy the top half of this PHP code,

Then

Head over and open up our admin controller, which is source controller and the
controller dot PHP. And I will paste that code onto the top here. And let's see, we
also need this chart builder interface argument. So I'll say chart builder,
interface, chart builder. And we need a use statement for this chart class here. So
I'll delete the T re-type that hit tab. And that added the use statement up on top
for me. Wait, where did these classes come from? Remember we did just install a new
bundle and bundles typically give us new services. This chart builder interface gives
us a new service that is really good at building charts in PHP. Pass this chart
variable into the template chart set to chart.

Okay.

How do we run to the chart? Go back to the docs and scroll down one more time.

Oh,

The bundle also gave us a new twig helper. Um, okay. Let's copy that.

Yeah.

Go find the template for this page. So that's templates, admin dashboard that HTML
that twig. And let me find the age

Here. There we go

All the way at the bottom. We're just going to paste that this is the renter chart is
part of that bundle and we're passing in our chart. Variable,

I guess we're done. Hey, back to our site and refresh the admin page. Whoa,

It's a JavaScript powered graph. It already works. All we did was composer require.
We package one to run yarn install to put the new package into our node modules,
directory

[inaudible]

And three, we wrote some PHP code that is the power of Symfony UX instant access to
useful stimulus controllers, like one that renders a chart simply by installing a PHP
package. But how exactly does this all work and connect together? If you're
interested, I'll explain next. It's an exciting tale of a library called stimulus
bridge.

Okay.

