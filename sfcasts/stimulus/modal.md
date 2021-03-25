# Modal

Coming soon...

Our current goal is to be able to add a new product completely without ever leaving
or reloading this page. Do that. We'll need a button open the template for this page
templates, product admin index, that HTML twig and wrap the age one here in a div
class equals D flex flex dash row. And also give the age one, a little M E dash three
class that a little, add a little margin between it and the new button. We'll say
button.

Okay.

I made the text ad and then give this class equals BTN BTN primary BTN S M Cool,
because clicking the button will open a modal via JavaScript. Let's immediately
attach a stimulus data dash controller attribute, but instead of me adding it to the
button directly wrap this button in a div

And add a controller there, curly curly it stimulus controller. Let's call it. How
about modal dash perform? Because we're going to try to make a reusable stimulus
controller that can be used to open any form on the site via a model. Why are we
adding this div instead of attaching the controller directly to the button? Well, we
do not need it right now, but it's going to come in handy in a few minutes. You'll
see. Let's go create the new controller and assets controllers. Create a new file
called modal dash form underscore controller dot J S I'll go steal the starting code
from another controller paste and do our usual connect method with a console that log

Coffee,

Refresh the page to make sure everything is connected. And it is there is our tiny
coffee. Okay. Step two. On quick, we want to open a modal. This means we need to aid
add an action to the button.

Okay.

Over in our template, let's add data. Dash data dash action equals the name of our
controller model form pound sign, and let's call it a method open modal, copy that
head into the controller, renamed connected to open model and the event argument in
case we need it in left console. That log events. If we refresh now and quick, we are
on a roll. So how do we open the modal? One of the nice things about bootstrap is
that it has standalone at JavaScript utilities, including one to help open a model in
bootstrap five. We can import those by saying import that by saying import Modal from
bootstrapper.

But Oh,

As soon as we do that, we have a failing build. That's head over to our terminal and
go to the tab. That's opening. That's running Encore. Bootstrap contains the
reference to the file at popper slash core. This file can not be found.

Ah,

We learned about this earlier with the peer dependencies thing we saw when we
installed bootstrap, many of bootstraps, JavaScript tools depend on another library
called popper for good but technical reasons instead of bootstrap listing popper JS
as its own dependency. So that it's automatically downloaded is it's listed as a peer
dependency, which means it's our responsibility to install directly. No problem. Let
me copy that app popper JS core part, head to our other terminal. And we will are on
yarn, add app hopper JS slash core dash

Death. Now,

When this finishes beautiful, our build is instantly happy. Okay, we've imported this
modal, okay.

Object. Now what the modal system,

It works like this. We create a bunch of HTML that represents our modal, put it on
the page, but hide it by default. When we want to open that modal, we point
bootstraps modal object at that element and say, show modal.

This

Means that we need to get some modal HTML onto our page somewhere to do that and to
hopefully make this HTML reusable in the templates, correct directory, create a new
file called underscore modal that HTML that twig

Aside I'll paste a basic modal structure. There's no magic here. You can find and
copy a bunch of different modal examples from the bootstrap docs. This has a modal
header, a modal body, which is basically empty and a modal footer with some buttons.
Now go back to index that HTML twig, right after the include, the modal include
underscore modal that HTML, twig, why are we including it right there? You'll see why
in a minute, but first go refresh the page. If you inspect element on our button, the
Moodle HTML is on the page, but as you can see, it's hidden. This element here is
basically a template for what we want our modal to look like, to get the modal, to
open head back to our controller and remove the console that lock now say constant
modal equals new modal.

Okay.

The past year is the Dom element that holds the modal temple. In other words, this
element right here, how would we find that element from inside of our controller by
using a target of course, back in underscore modal that HTML twig all the way up on
the top level element, let's add a target data dash the name of our controller modal
dash form dash target equals. And let's call this new target. How about modal? This
does make this template a bit specific to this stimulus controller, but I'm okay with
that. If we needed to make this same element of target for a different controller
later, we can totally do that. You can add as many target attributes as you need.
I'll copy that Ord modal head back to our controller. So we can declare that, do that
with static targets equals and array with modal inside. Thanks to this. Now we can
say new model, this dot modal target that creates a new modal object, but doesn't
actually open it yet to do that. Say modal that show.

All right, let's try it head over refresh quick. And Ooh, we got an air that time
cannot read property class list of undefined. You can see it's coming from bootstrap,
so it's not entirely clear what's happening here, but the undefined is very telling
and it makes me wonder if my target is not being seen correctly. And you probably saw
my mistake. Static targets. There's no air directly from stimulus. If you make a typo
on that, it is valid to make a property called target. But of course the target
doesn't work, which meant this was undefined on, move on out and refresh again. Try
it this time. We've got it. And the close and X buttons already work, but there's no
form inside here yet. So next let's make an Ajax call to load the new product form
right into the modal. But what do we do that we're going to be careful to make sure
that our entire modal system could be re-used for any form on our site, not just this
one.

