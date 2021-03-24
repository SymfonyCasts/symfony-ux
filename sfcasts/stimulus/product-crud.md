# Product CRUD

I've mentioned a few times that Stimulus has a sister technology called Turbo...
both of which live under this brand called "Hotwire", which, as we've learned,
is *all* about returning HTML from your server.

## Turbo: You'll Write Less Custom JavaScript

We're going to discuss Turbo in the next tutorial in this series. But in a
nutshell, Turbo allows you to turn all of the clicks *and* form submits on your
site into Ajax calls instantly & automatically. And it has several other neat
super powers.

Right now, on the cart page, when we remove an item, we did some extra work to submit
the delete form via Ajax and reload the cart area *also* via Ajax. Once you start
using Turbo, you absolutely *can* still do stuff like this... but you'll find
that it's less necessary. In this case, if Turbo were active, after confirming
in the modal, we *could* just let the form submit normally... which would
automatically happen via Ajax. My point is: Turbo will allow us to have a slick
user interface... while writing *less* custom JavaScript. But if you *ever* want
to do something extra, you are *completely* free to write custom JavaScript.

## Let's Build an AJAX Form Modal!

In that spirit, after talking with a few of you wonderful people, I thought it might
be good to show how we could submit a full form via Ajax, including handling
validation errors and reloading part of the page after that Ajax call finishes.

So here's the plan: we're going to generate a new product admin section. Then, on
the list page. we'll add a "new product" button that, on click opens a modal with
a form inside. We'll submit that form via Ajax in the modal, show validation errors
in the modal and, finally, on success, reload the product list on the page via
another Ajax call. It's going to be an *epic* example of Stimulus. We're also going
to do *part* of this using jQuery... just in case you prefer using it over vanilla
JavaScript.

## Upgrading to Bootstrap 5

So let's get going! To start, find your terminal.

The project is currently using Bootstrap version 4. I'm going to upgrade to
Bootstrap 5 because I like it's JavaScript components better. Run:

```terminal
yarn add bootstrap@5 --dev
```

At this exact moment, Bootstrap 5 is only in beta. So I'll select that version.

## Peer Dependency Warnings?

See this "peer dependency" warning? If you ever see these errors - like up here -
and they mention Webpack or Babel... it's probably fine. This happens because Encore
handles *so* much stuff for us. These libraries that it's complaining about
*are* installed... but they're installed by Encore directly.

But in this case, this popper thing *is* going to be a problem... but we can wait
to see what error it causes. Bootstrap 5 *does* change some class names versus
Bootstrap 4... but it's minor enough that I'm going to ignore it. If you look at
our site, the page still looks just fine. You *will* notice a few things that
don't look right on our form... but we won't worry about fixing those.

Oh, and by the way, if you haven't already after the last chapter, make sure you
stop the webpack-bundle-analyzer and restart Encore with:

```terminal
yarn watch
```

## make:crud

*Anyways*, now we need to generate our product admin section. Back at your terminal,
run

```terminal
php bin/console make:crud
```

We want to generate a CRUD for our `Product` entity. MakerBundle 1.30 now asks
you the name of your controller class. We already have a class called
`ProductController`, so let's call this new one `ProductAdminController`.

And... done! This created the new `ProductAdminController`, a form class, and a
bunch of templates. Go check out the controller:
`src/Controller/ProductAdminController.php`. Oh, let's change the URL to
`/admin/product`... that's probably a better URL.

Let's go see what it looks like! Head over to `/admin/product`.

And... okay! Good start: this has everything we need... though, it doesn't really
fit into our design super well. Let's improve that a *tiny* bit. Open up this
page's template, which is `templates/product_admin/index.html.twig`. On top, add
a `<div>` class `container-fluid` and `mt-4` for some margin. All the way at the
bottom, add the ending `div`.

Copy this... because we need it in all of our templates. Open edit, do
that same thing... add the closing div... `new.html.twig`... and finally
`show.html.twig`.

When you're done, refresh the list page again. Okay: it's *slightly* better. It
could still use some margin over here, but it's good enough for now.

## Adding __toString to Make the Select Field Work

Click to edit a product. Ah!

> Error: class `Category` could not be converted to string.

Rude! This is because the form is trying to make a category select drop down...
and it needs to know what text to use for each Category option. Go into
`src/Entity/Category.php` and, anywhere in here - I'll put it at the bottom - add
a `public function __toString()` method that returns `$this->name`. I'm going to
cast that to a string... just in case the name is `null`.

Oh, and while we're thinking about the form, I want to make it a bit smaller. In
`src/Form/ProductType.php`, the form contains *every* field. To make life simpler,
remove `brand`, `weight`, `stockQuantity`, `imageFilename`, and also `colors`.

Very nice.

Refresh the edit page now and... it works! If we change something and hit
update - yes our buttons *do* need some styling - that works too.

Oh, but there's one more change I want to make: this will help our example.
Back over in a `ProductAdminController::index()`, change the query to sort
the *newest* on top. Do that by changing this `findAll()` to `findBy()`, pass it
an empty criteria - so it still returns everything - and then sort by `id` `DESC`.
You could also use a `createdAt` column if you want.

Head over and refresh now. Perfect: the highest ids are on top.

Ok: our setup is complete! Next: let's create an "add" button right here on the list
page that, on click, opens a Bootstrap modal with our form inside. We'll
accomplish that with a Stimulus controller and an Ajax call.
