# Lazy Frame Full Page

Coming soon...

I want to show one more lazy frame example, but before we do, I'm going to find my
terminal. And yes, once again, run 

```terminal
yarn upgrade @hotwired/turbo
```

This time I get a beta version 8, which is actually the release I was waiting for. This changes,
how JavaScript is handled inside frames, which will be important with what we're
about to, but to start completely forget about frames. And let's pretend that we want
to add a weather page to our site. Sure. We have this weather footer on the bottom of
every page, but we also want people to be able to go to `/weather` and see the
weather report front and center. So over in `src/Controller`, let's create a new
piece B class called `WeatherController`, make it extend `AbstractController` mental,
create a public function `weather()` and above this, I'll give it route `@Route('/weather')`  and a
`name="app_weather"` inside. We'll just simply return `$this->render('weather/index.html.twig')`
Cool.

Let's go make that template down to `templates/`, create a new directory called the
`weather/`, and then inside there, a new file called `index.html.twig` and will
give us some structure `{% extends 'base.html.twig' %}`, and then our `{% block body %}`
`{% endblock %}` an `<h1>`, the weather and for the body, let's go into `base.html.twig`
twig, go down to the bottom and we're just going to completely steal all of the, the
anchor tag and the script for the weather widget back `index.html.twig` we'll
paste that finally back in `base.html.twig` let's find the cart link.

There it is. And on a copy of the cart link, what a new link to our weather page
change the path to `app_weather`. Okay. And then for the texts itself, I'll
just use a font. Awesome icon, `fas fa-sun`. Hopefully your weather is sunny.
All right, cool. Let's head over. Refresh the page. And there is our sunshine. When
we quick, we have a weather page. Amazing though, having two other widgets on the
page does look weird. So let's remove the one in the footer for just this page and
`base.html.twig`, scroll back down to that area. Let's surround this in a
new `{% block %}`, okay. `whether_widget` and then the other side of it `{% endblock %}` a competent
name of that block. And back-end `index.html.twig` just anywhere, how good at the
bottom, we will reinvent that block, but make it empty.

All right. Refresh again. And cool. Got it. Now, obviously we have some code
duplication between `index.html.twig`, and `base.html.twig`, we could
easily fix that by isolating all of this into its own template. And then using the
twig `{{ include() }}` function in both of these templates to include that. But like we did
with the featured product sidebar, I want you to pretend that it takes a lot of work
to generate this HTML here. Maybe we make database calls, API and API calls to
generate it. And so if we could convert the weather widget, that's on the footer of
every page into a lazy turbo frame. Well, that would make every page load faster.
When we created a lazy turbo frame for the featured product sidebar, I started by
making a route and a controller that rendered just that part of the page, just the
featured product. But this time I'm not going to do that.

Why? Why not? Because we already have a page that contains the HTML that we need the
weather page. Sure. It contains a lot of extra stuff that we don't want, like the
HTML layout, uh, and this `<h1>` tag, but the turbo frame system can ignore all that.
In other words, we can jump straight to adding the turbo frame with zero extra work.
So in base studies and I'll tweak remove all of the duplicated code and instead say,
`<turbo-frame id="">`nd we'll give him some ID to identify this. So we'll hop
out `weather_widget`. And then, because this is a lazy frame, we'll say `src=""` equals.
And we will paint pointed at the full page that we want to target, which is the
weather page.

If we want to try this, now, let me go to the homepage. It's not going to work. And
you forget the console. We know why we saw this error earlier. Spawns has no matching
`<turbo-frame id="weather_widget">` element. And basically we need to tell the turbo
frame system, which part of the weather page to use for this frame over in index,
that age timeouts way for our weather page, wrap the entire weather section with a
`<turbo-frame>` that has `id="whether_widget"`. So I moved the closing frame down there and
then I'll invent everything.

All right, Bri fresh again, and works. That's amazing. We're now able to reuse just
parts of existing pages simply by wrapping pieces inside a `<turbo-frame>`. If you go
into network tools and find the AGS call for the weather page, there's no magic line
here. The AGS call for that, uh, frame did return to full HTML. And this is really
how frames are meant to be used. You have an existing page like the other page, and
then you're able to reuse parts of that page inside a frame instead of needing to
build an extra end point that only returns the part you want ready to be more
amazing. Yes, the homepage. This is in long page. So don't you think it's kind of a
wasteful to load the weather widget in the footer. Even if the user never scrolls
down that far, it is wasteful and we can fix that and `base.html.twig` on the
terminal frame, add a new attribute `loading="lazy"`, all right.

Scroll to the top of the homepage refresh and make sure you're looking at the Ajax
calls network. Notice. There is no Ajax request yet for the weather page, but watch
this area. If we scroll down. Yeah, there it is. Yup. With `loading="lazy"`, the
request isn't made until the frame becomes visible. I love that, but there is a
lingering bug in our code. It's more about the JavaScript for the weather widget.
Then about the turbo frames we've created. Let's find out what the bug is next and
create a stimulus controller to make the weather JavaScript finally, fully
functional, no matter how we load it.

