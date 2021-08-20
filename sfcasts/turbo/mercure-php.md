# Publishing Mercure Updates in PHP

We *now* know that we can *easily* subscribe to a Mercure topic in JavaScript.
*And*, if we publish a message *to* that topic with `<turbo-stream>` HTML in it,
our JavaScript will instantly notice & process it. Sweet!

So far, we've published messages to Mercure via curl at the command line... but
that was just to get a feel for how it works. In reality, we're going to publish
message from PHP... which is a heck of a lot simpler anyways.

Copy the `<turbo-stream>`... then go find `ProductController`... and the
reviews action.

## Publishing a Message from PHP

To publish updates to a Mercure Hub, we need to autowire a new service. Use
`HubInterface` and I'll call it `$mercureHub`.

Down below, to start, let's publish an update when we submit the form... but not
necessarily when it's successful. I'm lazy: this will let us test without
filling out the form successfully. Add a variable - `$update` - set to `new Update()` -
a handy class for creating messages. We need to pass this two arguments. The first
is the topic or topics that we want to publish to. Use `product-reviews`. The second
is the *data* that we want to send. Paste in the `<turbo-stream>` string.

Below, to *publish* this, all we need is `$mercureHub->publish($update)`.

Kind of... beautiful, isn't it?

Let's try this! Find your browser and refresh so the quick stats area is restored.
Scroll down and submit the form empty. Uh... 500 error? Open the profiler for that
request. Hmm:

> Failed to send an update

## Setting verify_peer to False in dev for Macs

Not... very explanatory. But notice that there were *four* exceptions. When this
happens, it's often one of the *other* exceptions that has more details. Ah:

> SSL peer certificate or SSH remote key was not okay

This... is a problem specific to the Symfony binary web server, https and... Macs.
You can learn more about it on this issue for the Symfony CLI. If you're not
using a Mac, good for you! That hopefully just worked.

If you *are*, the easiest way to fix this is to disable "peer verification" in the
`dev` environment.

To do this, open `config/packages/framework.yaml`. At the bottom, use `when@dev`
to set config specific to the `dev` environment - that's a feature that's new
to Symfony 5.3. Under this, set `framework`, `http_client`, `default_options` then
`verify_peer: false`.

That's *not* something you want to set in production... and it's a bummer we need
to set it in the `dev` environment. But it *should* fix our issue.

Close this... then refresh the page again. Scroll down... and submit the review
form. Ok! We get the normal validation error - that's expected. But scroll up.
Yes! We just updated the page with our stream through Mercure! That's awesome!

So next: let's use this new superpower to simplify our reviews action. We can now
redirect on success like we originally were... *and* publish a stream to update
the quick stats area.
