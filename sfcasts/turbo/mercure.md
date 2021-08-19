# Mercure

Coming soon...

[inaudible]

Turbo stream has become much more interesting. If we can subscribe to a sync streams,
like imagine we're viewing a page and minding our own business at the same moment,
someone else on the other side of the world adds a new review to this product. What
if that review instantly popped onto our page and the quick stats updated? That would
be awesome. Or imagine if in product controller, inside of our reviews action, after
a successful form submit, we could still return a redirect like we were doing before.
Okay. But in addition to that, we could also push a stream to the user so that we can
update other parts of the page. Outside of the frame, both of these are totally
possible. Turbo streams have built in support to listen to a web socket, the returns,
terrible stream HTML. It also supports doing the same thing with server scent events,
which are kind of a modern web socket. It's a way for a server to push information to
a browser without us needing to make an Ajax call, to ask for it. And fortunately, in
the Symfony world, we have a great support for technology that enables server send
events. Merkur okay.

Talking about murder could probably be its own tutorial. So we'll see, but we'll
start with the basics. Basically. Mercury is a service like my SQL, like your
database elastic search or redness. It allows you to, for example, subscribe to in
JavaScripts, subscribed to messages from it. Then in PHP, we can send messages, we
can publish messages to it. Anyone subscribed, instantly received those messages and
can do something. And if you're familiar with WebSockets, it's got a similar field.
Anyways, we're going to get mercury rocking and it's going to really make things fun.
To start install a package that makes it easy to work with Merck air and turbo. So
air command line, we're going to propose the replier Symfony /UX dash

Turbo dash here.

All right. This installs a few things. First it installs a PHP library called a
murkier. No was talk to the mercury service. Second install, the mercury bundle that
makes that even easier and Symfony and the third and in solves the Symfony UX turbo
bundle, which is a tiny bundle that helps us communicate, uh, connect turbo streams
with murkier. So we can listen for turbo streams from Jarvis. Sure is also executed a
recipe. And which did a couple of things. You can see it if we run gets deaths. So in
Diane, let's check that file out. We now have the bottom three new environment
variables to help us talk to murkier. I'll talk more about those in a minute. There's
also modified controllers that JSON, which I had a new stimulus controller that will
allow us to listen to mercury streams. We'll talk about, we'll use that in the next
chapter. And of course like other, uh, UX packages and also add in a bundle and like
all the, you have packages. It added a new library to our pack is that JSON file,
which points add some JavaScript that's inside of that UX turbo murkier package. So
like usual. And we can see at the bottom, we need to stop yarn and run yarn installed
dash dash force. So it can synchronize that new pack to that. JSON change, once
that's done let's rerun, yarn, watch start Encore.

We just installed some PHP code and some JavaScript. That's going to help us
communicate with Merkur, but we don't actually have a murkier service running yet.
Mercury services called the mercury hub. So we have a few options to get this
running. We can use their hosted or managed hub, which is a great option, especially
for production. We can also download mercury directly them and set it up locally. We
can also set it up with Docker, which is something else that's supportive, or we can
just use the Symfony binary. The thing that we're already using to run our local web
server. Yep. That has mercury built into it.

Check us out. And our open tab, I'll clear the screen. If you're on Symfony server
status, it will tell you that it is running. Now, if you had back over to your
browser and open a new tab and go to our website. So one to seven, zero to a hundred
at 1,800 /dot well dash and /murkier. If everything is working, you should see an
error. This says missing topic parameter. This is actually the mark, your hub. The
moment that we installed the murkier bundle the Symfony web server that we're using.
Notice that it started up merger, the murkier hub for us. If this didn't work, try
stopping and restarting your, uh, server. So yeah, with no work locally, we get a
free embedded Merkur server mercury hub that we can communicate to. Now, in order to
communicate to this, if you head back over and go to the debt and file, remember we
have three environment variables is here.

These all connect with a new config file that lives in config packages, murkier dot
yet basically the public URL is the URL that you're going to use to listen to events.
The URL, this URL is the URL you can use to publish events. And the secret is
basically the password that's going to allow you to publish in dot end. You can see
that both mercury and mercury public URL are pointing at our murkier servers already
have the correct URL, which is perfect. But actually if you're using the latest
version of the Symfony binary, these environment variables aren't even needed. Why?
Well, think about it. These Symfony binary sets up the merger hub for us. And since
it did that, it also, it knows the URL. It also exposes the environment variables
medically

[inaudible] okay.

Check it out, go back over to your editor and open a public /index dot PHP. Let me
close a couple of things. There we go. Public /index that BHB and right after the
load runtime, I'm going to paste in some code here. This looks a little bit fancy,
but basically what I'm doing is dumping the dollar sign,_server variable, but I'm
only dumping keys that start with mercury, which I'm mostly doing to. So I don't
expose a bunch of secret keys here on the video. Otherwise I would just dump
everything among other things. The server variable will hold any environment
variables that exist this line. We'll also run it before the dot end file is loaded.
So it's only going to show us real environment variables. So we've got to run back
back over now and refresh our site and see one called Symfony mercury in

[inaudible]. Okay. [inaudible] okay.

We see four, including mercury public, you were out and murder. You were out smart
guard covenants. You were out as legacy things. We don't need to worry about that.
One is these are already being set for you in production. You know, the point, these
environment variables to you to real mercury your point, these two, your real mercury
you, where else either by setting real environment variables or by adding them to
that and that local anyways, we now have a murkier Hobbit running. What does that
mean? Well, it's kind of a central place where some people can listen for messages in
other people can publish them. So next let's do both of these things, listen to a
murkier topic in JavaScript and then publish a messages, both from the command line,
just to see how it works and from PHP, which is our real goal.

