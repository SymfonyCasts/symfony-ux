# Product Crud

Coming soon...

I've mentioned a few times that stimulus is stimulus has a sister technology called
turbo. They're both kind of under this brand called Hotwire.

Okay.

And we're going to discuss to turbo in the next tutorial in the series, in a
nutshell, turbo allows you to instantly turn all of the clicks on your website, into
Ajax. Call clicks on your website and form submits into Ajax calls as well as some
other really neat super powers right now on the cart page. If we remove an item, we
put in some extra, more work so that when we confirm it submits the form via Ajax and
then reloads the car area also via Ajax. Once you start using turbo, you can still
totally do stuff like this, but you'll find that it's less necessary in this case. If
turbo were active after confirming the removal, we could just let the form submit
normally, which would automatically happen via Ajax. So my point is turbo will allow
you to have a slick user interface while writing less custom JavaScript.

But if you want to do something custom, you are totally free to do that in that
spirit. After talking with a few of you, I thought it might be good to show how we
could submit a full form via Ajax handle validation, errors, and reload part of the
page after that Ajax call finishes. So here's the plan where you're going to generate
a new product admin section on the list page. We'll add a new product button that on
quick, opens a muddle with a form inside. We'll submit that form B we'll submit that
form via Ajax in the model And reload the list after the Ajax is successful. Sound
good? Let's go to start find your terminal. Okay.

Right.

The project is currently using bootstrap version four. I'm going to upgrade to
bootstrap five because I like it's JavaScript integration, better run

```terminal
yarn add bootstrap@5 --dev
```

at this exact moment. Bootstrap five is only in beta. So I'll
select that version and it'll work perfectly By the way. See this peer dependency
thing down here.

If you ever see these errors like up here that are related to a Webpack or even some
Babel preset stuff, it's probably fine. It's because Encore handles so much stuff for
us. But in this case, this popper thing is going to be a problem, but we can wait to
see what air it causes later. Now, bootstrap five does change some styling versus
bootstrap four, but it's minor enough that I'm going to ignore it. And if you look,
our page mostly works, our page still looks just fine. You will notice a couple of
things that don't look quite right on our form later. Well, I'm not going to worry
about that right now. Now we need to generate our product admin section back at your
terminal, run

```terminal
php bin/console make:crud
```

Let's say we want to generate a CRUD for
our `Product` entity and make a bundle. One that 30 now asks you the name of your
controller class. We already have a class called product controller. So let's call
our new one product admin controller. Awesome. This created a new product admin
controller, a form class, and a bunch of templates. Let's go check out the controller
source controller, product admin controller, that PHP, ah, let's actually change
this. You were out before. Do you have anything to `/admin/product`? Cool. Let's go
see what it looks like. Head over. Let's go to `/admin/product`

And

Okay. That's a good start. It's got everything we need. No, it doesn't really fit
into our design yet. Let's change that. Open up the pages template, which is that
`templates/product_admin/index.html.twig` now, right on top, I'm going to add a
`<div>` class `container-fluid` and `mt-4` for some margin. And then I'm
going to put the D any dev all the way at the end. I'm actually going to copy that
because we'll need this on all of our templates. So I'll go to edit, do that same
thing and put the closing div

New and finally the show template. Awesome.

Let's try the list page again now. Okay. A little bit better. You could still use
some margin over here, but I'm not going to worry about it, but good enough for us.
All right. Let's try that. Click to edit. One of these error class category could not
be converted to string. This is because the form is trying to make a category drop
down and it needs to know how to render each category in that dropdown. So we need to
do is go into the `src/Entity/Category.php` and anywhere in here, I'll put it
at the bottom. Let's make a `public function __toString()` method that returns
`$this->name`. And actually I'm going to type hint that to a string just in case the
name is no, that will return an empty string. And while we're talking about the form,
let's make it a bit smaller in source form product type, it renders with all the
fields by default to make our life a little simpler. Let's remove `brand` `weight`,
`stockQuantity`, `imageFilename`, and also `colors`. A lot of nice small focused form.
Okay. Refresh the edit page now and got it. And if we change something and hit
update, yes, our buttons do need some styling. It works.

Oh, there's one more change I want to make. That'll make our whole example a lot
easier to see back over in a `ProductAdminController::index()`, change the query to sort
of, to the two sorts, the newest on top. We can do that by changing this at `findAll()`
to `findBy()` pass it. No an empty criteria. So it still returns everything. And then ID
the sending, or it could use a creative that column if you want. All right now,
refresh and perfect. I can see the highest IDs are on top. Okay. Our setup is ready
next. Let's create an add button right here on the page that on click opens a
bootstrap modal with our form inside. We'll accomplish that with a stimulus
controller and an Ajax call. Okay.

