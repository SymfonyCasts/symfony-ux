# Listen Publish

Coming soon...

Okay. The purpose of mercury is to have a hub where we can subscribe or listen to
messages. And also publish messages are going to high level is three steps. We are
going to set up some JavaScript that listens to a topic in murkier. It's kind of like
a key or a category for messages. Then in PHP, we're going to publish some turbo
stream HTML to that topic. And finally, we're going to make that JavaScript, that
turbo stream HTML and pass it to the stream processing system, the results, the power
to update any part of anyone's page. Whenever we want to fright from PHP, if this
doesn't make sense yet, don't worry. We're going to see it in action. First over
index at VHB. Well that's removed the DVD so that our page is no longer a debt.

Perfect. Now go into templates, product_reviews that age two much twig, which is the
template that holds the entire review's turbo frame at the top, or really anywhere
and a div and with curly curly and use a new function from the bundle we installed in
the last chapter turbo stream, listen and pass this, the topic ordering listened to
which I'm just going to make up product reviews and then close the diff it looks kind
of weird, right? To see what this does. Go refresh a product page and let's inspect
the reviews area so we can find this diff perfect. Here it is right there. Okay.

This dip is a dummy element. What I mean is it won't never contain content. It will
always be empty. Its real job is to activate a stimulus controller, uh, listens for
events in the product reviews topic. You can see the data controller pointing to that
controller. We installed a controller that we installed in the last video and then
the product reviews topic and then the URL to our murkier hub. Now check this out, go
to your network tools, make sure you're on fetch /XHR and scroll up. See this. We
have a request in the background to our merch, your hub with question mark topic =
product reviews. That JavaScript is doing that. But the really interesting thing is
the type it's not fetch or XHR it's event source, right? Click and open this in a new
tab.

Yep. It just spins forever. Why? Because it's waiting for messages to be published to
this topic. Okay. So we are now listening to the product reviews topic of both in
this browser tab and apparently from some JavaScript on this page, thanks to the
stimulus controller we just activated. So how can we publish messages to with the
product reviews topic basically by sending a post request to murkier over in its
documentation, go to the get started page and scroll a bit down. Here we go.
Publishing. Here's an example of how you can publish a basic message to a mercury.
I'm going to copy this curl command here and then over my editor, I'm going to go to
file new scratch file and then type to create a new plain text scratch file. I'm
doing this just so we have an easier time of updating, uh, this value.

In fact, I'm going to get a little fancy here by adding us back/that will allow me to
break this onto multiple lines. Just to make it a little bit easier to read. I know
it's still kind of ugly, but uh, it's a little bit better. I'll have one more right
there. Perfect. All right. The parts that we need to change are the topic part here.
So our topic is going to be not this URL. Um, there's nothing special about you using
a URL. You can use any string for your topic. So we're going to use product reviews
and then down here for the URL, we of course are going to be sending this 2 1 2 7 0
zero.one, colon 8,000.

Then we'll explain the rest of this in a second. So I'm going to copy this, go find
my terminal paste and hit enter. Okay. So we've got a little response here. The Yardi
spin back over to your browser tab. Holy cats, Batman. It showed up. We published
this data to this topic and it instantly showed up in this browser tab. Even if
you're not super comfortable using curl at the command line, honestly, I don't do it
that often. Most of what's happening here is pretty obvious. First we're sending a
topic post France that do product reviews and a data post parameter set to well,
whatever we want for the moment we're sending JSON to this event, which ultimately
shows up over here

[inaudible]

And then down here, we're sending a post request to our merch, your hub, but where
does this authorization bear and then super long string come from. It turns out this
is a JSON web token competence, entire long string here. And one let's head over to
JWT dot I O nicely utility site for working with JSON. I love tokens. If you scroll
down a little bit here, they have an editor we can paste in the encoded token. So
this weird string here is actually can actually be decoded to this JSON right here.

[inaudible]

So when we send this to the server, you can basically think that what we're sending
the server is really this adjacent data right here. The subscribed part down here is
an important, the payload is not important, but the publish is important. This
basically says, hi, merch, your hub. I have permission to publish to any topic. Why
does the mercury server trust trust this? Because this message here though, it's
readable. It can be decoded by anyone it's signed with our mercury hubs secret key,
the mercury hub star. When you first install it or run it with the Symfony. Binary
starts with a secret, changed me. This is actually the secret that you'll see in the
dot and file.

So this is actually the secret key of our mercury hub. If you go back over to J D
Gio, if you look down here in the bottom, it says invalid signature, because it's
basically doing is it's trying to use this your 2 56 bit secret as the CA as the
secret key right now. So it's basically saying, given this JSON web token and this
secret, this is an invalid JSON web token. If we paste in our real instead signature
and verify. And if we try to mess around with any of this stuff inside of our heat,
uh, message here, you now see that once I

[inaudible],

These are trying to mess with anything on here. Um,

Then the, uh,

The signature would no longer match

[inaudible]

And you can actually regenerate a new JV at Debbie to using the same payload and the
wrong secret. Take us out if we do this, this has actually been updating our section
new JWT right here. So this is a JWT that's going to send to our server with the
same. Um, so send the same data door server, but with the wrong secrets, this is be
an example of somebody trying to communicate to them, or if your hub that doesn't
have permission to create these messages. If we go over here right now to our scratch
pad and it change that JWT token, copy this, and then rerun it in our command line.
You can see on authorized, we have created a message, but the message which can be
read by our murkier hub, but the signature on it is failing. So it knows it's being
tampered with. So I went back over here, I'm going to undo that in my scratch pad so
I can get back to my original payload.

[inaudible]

All right. So back over here, let's go back to our original secret. There we go.
Change me and now let's change the payload to just the part we need. Um, so again,
the subscribed payload stuff here is not something we need in our case. So what I
missed the saying is here is I want to create this payload. I do. I want to use this
secret key. And it generates the JWT that represents a signed version of that JSON
payload. So I can go over here and use that much shorter key on my current request.
So I'll copy that, go around with command line and perfect. You see, it did publish
that.

I have to go back over to our tab asset updated with a second event over here. So
this is a little feel for kind of how the authentication and signing of these tokens
happens. Most of the time. You're not really going to need to worry about it. Okay.
So enough about that stuff. Now we can really send anything that we want as the data
on the event. This example sends JSON. And in theory, we could write some JavaScript
that listens to this topic and does something with that. JSON re, but remember that
turbo stream listen, function that we called earlier, that activity that is stimulus
controller that is already listening to this topic. Wow. It's listening and waiting
for a turbo street. So yeah,

[inaudible], [inaudible]

Over in our scratch pad. Let's choose the data here. Instead of being JSON, I'm going
to paste in a turbo stream. So it's a little ugly it's cause it's all on one line
here, which you can see. I have turbo stream action goes update on targeted product,
quick stats. And I'm just putting quick stats inside of there.

[inaudible]

So first I'm going to watch and see if this comes up inside of my browser tab, that's
listening for messages.

[inaudible]

I'll copy this for my scratch pad. Go over paste on my terminal. That looks good. And
[inaudible]

[inaudible] yeah. [inaudible]

And actually it looks like my tab may have stopped listening that maybe just be a
browser and have timing out. So I'll refresh that I'll spin back over, copy, curl
command. I'm a terminal paste and head back over to that tab. There it is. You can
see and listen to the triple stream HTML, but the real cool thing is back in our main
browser tab. If we scroll up, yes, it updated the quick stats, the JavaScript
listening. This is that topic saw that I got back a turbo stream and processed the
turbo stream. Of course we aren't going to normally publish via the command line. We
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

Then below this to publish this, we all say mark, your hub arrow, publish update.
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
a stream to update the quick stats area through murkier. Okay.

