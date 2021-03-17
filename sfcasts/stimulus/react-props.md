# Pass Server Data Directly to React Props

Let's do *one* more React example. Head to the registration page. I just got word
that the marketing team wants us to render a "featured product" on the sidebar to
entice users to *definitely* want to sign up. We could build that entirely in PHP
and Twig. But let's pretend that this featured product widget will have a *bunch*
of cool, interactive functionality. So we've decided to build it in React.

## Rendering the New Component

Back in the `tutorial/` directory, which you can get by downloading the
course code from this page, you should *also* have a `FeaturedProduct.js`. Copy
that into `assets/components/`.

This React component receives a `product` prop and renders a featured product.
It's actually *so* simple that I wouldn't normally use React... but it'll work
perfectly for our example.

To render this, let's create a new stimulus controller called, how about,
`featured-product-react_controller.js`. I'll cheat and copy the contents of our
`made-with-love_controller.js`, paste here, and then change the import to
`FeaturedProduct` from `FeaturedProduct`. Render `<FeaturedProduct />` below.

Super simple. The interesting part about this React component is that it requires
a `product` prop, which is the data for whatever the featured product is. If we
wanted to, we could refactor this component to make an Ajax call to some endpoint
that returns the featured product as JSON. That's often how you do things in
React or Vue.

But... I *don't* want to do that. Everything would load faster and my life would
be simpler if we could avoid creating that endpoint and making that Ajax call.
How can we do that? By preparing the featured product data on the server and
passing it directly into our React component on page load. That's easy thanks
to the values API.

## Adding a Value for the Prop

Check it out: we know that our component requires a `product` prop, which is an
object. So in our Stimulus controller, add a `static values` set to an object
with a `product` key set to `Object`. We can pass this product value into the
component as a prop: `product={this.productValue}`.

Beautiful. Now open the template for this page, which is
`templates/registration/register.html.twig`. Above the `h1`, add a new div with
a col class... and bind the controller right here:
`{{ stimulus_controller()` }} with `featured-product-react`.

Give this a second argument so we can pass in the `product` value. Hmm, this will
be an object. For now, let's hardcode some data. We know that our featured product
object needs properties like `id` and `name`. So let's just start with those.
I'll say `id: 5` and `name` set to one of our top-selling products.

That should be enough to see if things are working. Let's go try it! In the browser,
refresh and... it is!. It looks a little broken... only because our product is
missing some fields. But this proves that the `value` *is* being passed to our React
component as a prop!

## Passing Serialized Objects to a Prop

But what we *really* want to do is pass *real* `Product` data to this prop. We
can do that by serializing a product object into JSON.

Open the controller for this page: `src/Controller/RegistrationController.php`.
At the end of the method, add a new argument so we can query for the featured
product: `ProductRepository $productRepository`.

Scroll down to where we render the template... and pass a new variable called
`featuredProduct` set to `$productRepository->findFeatured()`, which is a custom
method that I already created.

Back in the template, let's think. We somehow want to transform that
`featuredProduct` object into JSON so we can pass it to the product value. Open
up the `Product` class: `src/Entity/Product.php`. I already have Symfony's
serializer component installed in my app... and I've already added some
serialization groups to this class to control exactly *which* fields are turned
into JSON: that's this `product:read` group.

The only missing piece is that... well.. there isn't a way in Twig to *use*
Symfony's serializer to transform an object into JSON. So... I built one. You can
see it in `src/Twig/SerializerExtension.php`. This adds a filter called `serialize`,
which just calls the serializer and passes our data. Fun fact, in Symfony 5.3,
this filter will come standard with Symfony: a pull request by my friend Jesse
Rushlow has already been accepted. So, you soon won't need to build this.

Anyways, back in the template, set the `product` value to `featuredProduct`
pipe `serialize()`, the format - `json` and... then any serialization
context, which is kind of like serialization options. To serialize the fields in
the group I set up, pass `groups` set to `product:read`.

We're done! We just passed a `Product` object *directly* from our server into
a React component as a prop by converting it into JSON.

Let's... ya know... see if it *actually* works. Refresh and... there it is!
Inspect element on the sidebar. Look at that product value attribute! It's
lovely! Our Twig `serialize` filter turned the `Product` object into JSON...
then Stimulus automatically parsed that JSON back into an object. Yep, Stimulus
loves frontend frameworks. They're just another tool in your toolkit.

Next, each time we add a Stimulus controller to our app, that controller's code
is added to the JavaScript that's downloaded on every page. That makes Stimulus
*super* easy to use: add a `data-controller` to any page or any Ajax response
and it *will* work.

But how can we make sure our JavaScript isn't getting too big? Let's learn
how we can visualize the size of our JavaScript files and some amazing tricks
with laziness to make them smaller.
