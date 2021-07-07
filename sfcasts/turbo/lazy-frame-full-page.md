# Using a Full HTML Page to Populate a Frame

I want to show one more lazy frame example. But before we do, I'm going to find my
terminal and, yes, once again, run:

```terminal
yarn upgrade @hotwired/turbo
```

This time I get beta version 8, which is actually the release I was waiting for.
This changes how JavaScript is handled inside frames, which will be important with
what we're about to do.

But for a minute, I want you to completely forget about frames. Let's pretend
that we, being the nerds that we are, want to add a weather page to our site. Sure,
we have this weather footer on the bottom of every page, but we *also* want people
to be able to go to `/weather` and see the weather report front and center.

## Creating a Normal Weather Page

Over in `src/Controller/`, let's create a new piece class called `WeatherController`.
Make it extend `AbstractController` and add a public function `weather()` with a
route above it: `@Route('/weather')` and `name="app_weather"`. Inside, return
`$this->render('weather/index.html.twig')`.

Cool! Let's go make that template! Down in `templates/`, create a new directory called
`weather/`, and, inside, a new file called `index.html.twig`. Give this the basic
structure `{% extends 'base.html.twig' %}`, `{% block body %}`, `{% endblock %}` and
an `<h1>`.

*Now* go into `base.html.twig` and... at the bottom, steal all of the weather stuff:
the anchor tag and the script element. In `index.html.twig`, paste.

Done! Oh, but in `base.html.twig`, let's add a link to this... find the cart link -
there it is - copy it, paste, change the route to `app_weather` and.. for the text,
I'll use a FontAwesome icon: `fas fa-sun`.

Let's go check it out! Move over, refresh and... there's our sunshine! When we click
the icon, we have a weather page. Amazing!

Though... having *two* weather widgets on the page *does* look weird. Let's remove
the one in the footer for *just* this page. In `base.html.twig`, scroll back down to
that area. Surround this in a new `{% block weather_widget %}` and, on the other
side, `{% endblock %}`.

Back in `index.html.twig`, anywhere, override that block but make it empty.

Ok, refresh again and... cool!

At this point, we *do* have some code duplication between `index.html.twig`, and
`base.html.twig`. We could easily fix that by isolating the weather widget code
into its own template and then using the Twig `{{ include() }}` function in both
templates to bring that in.

## Creating the Lazy Turbo Frame

But like we did with the featured product sidebar, I want you to pretend that it
takes a lot of work to generate this HTML... maybe we make some database calls or
API calls to generate it. And so, if we could convert the weather widget that's
on the footer of every page into a lazy turbo frame, well, that would make *every*
page load faster.

When we created a lazy turbo frame for the featured product sidebar, we started by
making a route and a controller that rendered just that *part* of the page, just
the featured product itself, but with not layout. But this time, we're *not* going
to do that.

Why not? Because we already have a page that contains the HTML that we need: the
weather page. Sure, it contains a lot of *extra* stuff that we *don't* want in
the footer, like the HTML layout and this `<h1>` tag, but the turbo-frame system
can ignore all that. Yup, we can jump *straight* to adding the turbo frame with zero
extra work.

In `base.html.twig`, remove all the duplicated code and instead say,
`<turbo-frame id="">`, how about, `weather_widget`. Then, because we want this to
be a lazy frame, add `src=""` point this at the *full* HTML page that we want
to target: the weather page.

If we try this...  I'll go to the homepage, it's not going to work. In the console,
we see a familiar error!

> Response has no matching `<turbo-frame id="weather_widget">` element.

Of course! We need to tell the Turbo frame system, *which* part of the weather
page to use for this frame. Over in `index.html.twig` - the template for the full
weather page - wrap the entire weather section in a `<turbo-frame>` that has
`id="weather_widget"`. I'll put the closing tag down here... and indent everything.

Testing time! Refresh again and... it works! That's amazing! We're now able to reuse
just *parts* of existing pages simply by wrapping those parts inside a `<turbo-frame>`.
If you look aat the network tools... and find the Ajax call for the weather page,
there's no magic here: the ajax call for the frame *did* return the full HTML.

And this is really how frames are meant to be used. You have an existing page like
the weather page, and then you're able to reuse parts of that page inside a frame
instead of needing to build an extra endpoint that only returns the *part* you want.

## Truly Lazy Frames: Load only when Visible

Ok, ready to be *more* amazed? Check out the homepage: this is a *long* page. Don't
you think it's kind of a wasteful to load the weather widget in the footer... even
if the user never scrolls down that far? It is wasteful! And we can fix that!

In `base.html.twig`, on the `turbo-frame`, add a new attribute: `loading="lazy"`,

Let's see what that did. Scroll to the top of the homepage, refresh and make sure
you're looking at the Ajax calls in the network tools. Notice that Turbo has *not*,
yet, made an Ajax request for the weather page. But keep an eye on this. If we scroll
down... there it is! Yup, when you add `loading="lazy"`, the request isn't made until
the frame becomes *visible*. That's *super* cool.

But... there's a lingering bug in our code. It's more about the *JavaScript* for
the weather widget thaan about the turbo-frame we created. Let's find out what
the bug is next and create a Stimulus controller that will make the weather JavaScript
finally, fully functional, no matter how we load it.
