# Publishing Mercure Updates in PHP

Coming soon...

want to publish via PHP and that's actually much easier than using curl. So let's
publish this same at message that we have right here. I'll actually copy it.

But from inside of our controller, so over in product controller, inside of reviews,
action, the thing that we need to publish messages from PHP is a new argument called
a hub interface. So I'll say how I'm interface, I'll call this merch, your hub, and
then down here. Okay. Just something that's really easy right after we, uh, post, but
even before we're successful, let's create a new update. So what it is is you're
creating update variable, say update = new updates to use this nice little update
class. Instead of here, this takes two arguments. The first one is the topic or
topics that we want to publish to. So I'll save product reviews. And the second thing
is the data that we want to send. So I'm going to put a string and I'll paste in that
turbo stream that we just had. Okay.

Then below this to publish this, we all say mark, your hub-> publish update.
That's it. All right. So head back over, I'm going to refresh my page so we can get
our original, quick stats back then. All we need to do down here is just submit the
form. We don't even need to fill it out successfully. That should submit it. And oh,
500 air. Let me open up that page in my profiler. And I get failed to send an update
and it's not really clear what the problem with the issue is, but you can see it says
exceptions for sometimes there are multiple exceptions on the page and if you hide
one, ah, here we go. SSL peer certificate or SSH remote key was not okay for our URL.
This is just a Mac thing with the Symfony binary. Okay.

You can learn more about it on this issue for the Symfony C L I. So this is only
happening because we're using the Symfony binary and we're on a Mac. So to work
around it, I'm actually just going to disable, uh, in dev environment, only a SSL
check. So the way to do this is to go into config packages, framework dynamical, and
we're going to configure the Symfony HTTP client to ignore a SSL areas in dev only.
And we can actually do this by using this cool when app dev syntax, that's new and
Peachtree and Symfony 5.3, then framework HDV client default options, verified peer
false ID here. All right. So let me close this over here. Refresh the page again, see
our number review sections up there and reviewed out here and okay. We've got the
validation air here. That's expected, but scroll up. Yes. We updated the page with
our stream. That is awesome. So next let's use this new power to simplify our reviews
action. We can now really direct on success. Like we were originally were and publish
a stream to update the quick stats area through mercure. Okay.
