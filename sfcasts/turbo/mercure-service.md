# Running the Mercure Service in the symfony Binary

Mercure itself is a "service" or "server" - kind of like MySQL or Elasticsearch.
The Mercure server is called the "hub"... and there are several good ways to get
it running. First, they have a managed version where they handle it all for you.
This is great for production: it keeps things simple *and* you can help support
the project.

Or, you can download Mercure and set it up locally. *Or* you can set up Mercure
with Docker - that's totally supported. Or the *final* or is... *if* you're using
the Symfony binary as your local web server then... well... it's already running!

## The Embedded Mercure Hub

Head to your open terminal tab, clear the screen and run:

```terminal
symfony server:status
```

As a reminder, *way* back at the start of this tutorial, we used the Symfony
binary to run a local web server for us. Back at the browser, open a new tab
and go to https://127.0.0.1:8000 - the URL to our site - then `/.well-known/mercure`.

If everything is working... yes! You should see this error:

> Missing "topic" parameter.

This *is* a Mercure hub. Yup, the Symfony binary comes with Mercure *already*
running at this URL. We get that for free.

## The Environment Variables

To communicate with this, head back over to your editor and open the `.env` file.
These three environment variables define values that are used in a new
config file: `config/packages/mercure.yaml`. `MERCURE_PUBLIC_URL` is the
*public* URL to the Mercure hub that our JavaScript will use to *subscribe* to
messages and `MERCURE_URL` is the URL that our PHP code will use to *publish*
messages. These are usually the same. `MERCURE_SECRET` is basically a password
that will allow us to publish: more on that later.

In our case, both URL variables *already*, by chance, point to the correct URL!
Yay! But actually, if you're using the latest version of the Symfony binary... we
don't even *need* these variables in this file! Why? Well, in addition to setting
up Mercure for us, the Symfony binary *also* sets these environment variables
automatically to their correct values.

Check it out. Back over in our editor, open `public/index.php`. Let me close a
few things... then open it. Cool. Right after the runtime load, I'll paste in
some code.

This looks fancy, but I'm basically dumping the `$_SERVER` variable... except
only the keys that contain `MERCURE`. The `$_SERVER` variable - among other things -
will contain all environment variables. I'm filtering for `MERCURE` basically...
because I don't want to accidentally publish any secret keys from my computer
to the internet... as fun as that would be.

*Anyways*, this will run *before* the `.env` file is loaded, so it will only
print *real* environment variables. Back over on our site, refresh!

Yay! We see 4 environment variables including 2 we need! The first one is just
a flag that tells us that the Symfony binary is running Mercure... and that last
one is there for legacy reasons: we don't need it.

This means that our app is already configured and *ready* to talk to our Mercure
Hub! In production, you'll need to run a *real* Mercure Hub and set these environment
variables manually, however you do that in your hosting environment.

So... we have a Mercure hub running! What does that... mean? Well, it's a central
place where some things can *listen* for messages and other things can *publish*
messages. Next, let's do both of these things: listen to a Mercure "topic" in
JavaScript and publish messages to it, both from the command line - just to see
how it works - and from PHP, which is our *real* goal.
