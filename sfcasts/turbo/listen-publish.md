# Listening & Publishing

The purpose of Mercure is to have a hub where we can subscribe - or *listen* - to
messages and also *publish* messages.

Here's our high-level goal, it's three steps. First, set up some JavaScript that
listens to a "topic" in Mercure - a topic is like a message key or category.
Second, in PHP, publish a message *to* that topic containing Turbo Stream HTML.
And finally, when our JavaScript receives a message, make it pass the Turbo
Stream HTML to the stream-processing system. The result? The power to update *any*
part of *anyone's* page *whenever* we want to right from PHP. If this doesn't make
sense yet, don't worry: we're going to put this into action right now.

But before we jump in, open `index.php` and remove the dump... so that our site
is no longer dead. Excellent.

## Listening in JavaScript via the Stimulus Controller

Ok, step 1: open `templates/product/reviews.html.twig`, which is the template that
holds the entire reviews turbo frame. At the top, or really anywhere, add a `div`.
Where its attributes live, render a new Twig function from the UX library we
installed a few minutes ago - `turbo_stream_listen()` - and pass this the name of a
"topic"... which could be anything. How about `product-reviews`. Then, close the
`div`.

I know, that looks kind of weird. To see what it does, go refresh a product page...
and inspect the reviews area to find this `div`. Here it is.

Ok: this `div` is a *dummy* element. What I mean is: it won't *ever* contain
content *or* be visible to the user in any way. Its *real* job is to activate a
Stimulus controller that listens for messages in the `product-reviews` topic.
You can see the `data-controller` attribute pointing to the controller we installed
earlier as well as an attribute for the `product-reviews` topic and the public
URL to our Mercure hub.

## Viewing a Mercure Topic in your Browser

Go to your network tools and make sure you're viewing fetch or XHR requests.
Scroll up. Woh! There was a request to our Mercure hub *with* `?topic=product-reviews`.
The Stimulus controller did this.

But the *really* interesting thing about this request is the "type": it's not
fetch or XHR, it's `eventsource`. Right Click and open this URL in a new tab.
Yup, it just spins forever. But not because it's broken: this is working perfectly.
Our browser is *waiting* for messages to be published to this topic.

## Publishing Messages via curl

We are now listening to the `product-reviews` topic both in this browser tab
and, apparently, from some JavaScript on this page thanks to the Stimulus controller
we just activated. So... how can we publish messages *to* that topic?

Basically... by sending a POST request to our Mercure hub. Over in its documentation,
go to the "Get Started" page and scroll down a bit down. Here we go: publishing.
This shows an example of how you can publish a basic message to Mercure. Copy
the `curl` command version. Then, over my editor, I'll go to File -> "New Scratch
File" to create a plaintext scratch file. I'm doing this so we have a
convenient spot to play with this long command.

In fact, it's so long that I'll add a few `\` so that I can organize it onto
multiple lines. This makes it a *bit* easier to read... but I know, it's
still pretty ugly.

Before we try this, change the topic: the example is a URL, but a topic can be
any string. Use `product-reviews`. And at the end, update the URL that we're
POSTing to so that it matches our server: 127.0.0.1:8000.

We'll talk about the other parts of this request in minute. For now, copy this,
find your terminal, paste and... hit enter! Okay: we got a response... some
`uuid` thing. Did that work?

Spin back over to your browser tab. Holy cats, Batman! It showed up! Our message
contained this JSON data... which *also* appears in our tab.

## The Parts of a Publish Request

Even if you're not super comfortable using `curl` at the command line - honestly,
I do this *pretty* rarely - most of what's happening is pretty simple. First:
we're sending a `topic` POST parameter set to `product-reviews` *and* a `data`
POST parameter set to... well, whatever we want! For the moment, we're sending
some JSON data, which is passed to anyone listening to this topic.

At the end of the command, we're making this a POST request to our Mercure Hub URL.
But what about this `Authorization: Bearer` part... with this super long key?
What's that? It's a JSON web token. Let's learn more about what it is, how it
works and where it came from next. It's the *key* to convincing the Mercure
Hub that we're allowed to *publish* messages to this topic.
