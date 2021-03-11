# React Props

Coming soon...

Let's do one more react example, head to the registration page. Let's pretend that
the marketing team wants us to render a featured product on the sidebar to entice
users do definitely want to sign up. We could do that entirely in PHP and twig. Well,
let's pretend for some reason that this feature product widget will have a bunch of
cool functionality. So we've decided to build it in react back in the tutorial
directory, which you should have. If you downloaded the course code, you should also
have a featured product dot J S file. Top of that into assets components. This react
component receives a product prop and renders a featured product. It's actually so
simple that I wouldn't normally use react, but it'll work perfectly for our example,
to render this, let's create a new stimulus controller called how about Featured
product react on our score controller dot JS I'll cheat and copy the contents of our
made with love controller and here, and then we'll just change the important to
feature product from featured product and then render feature product below. Super
simple. The interesting part about this react component is that it requires a product
prop, which is the data for whatever the featured product is. If we wanted to, we
could refactor this component to make an Ajax call to some end point on our app that
returns the featured product as JSON that's often how you do things in reactor view

[inaudible],

But I don't want to do that. Everything would load faster. If we get avoid the Ajax
call by preparing the data, the feature product data on the server in passing it
directly into our react component on page load. And we can totally do that. How with
the handy values API, check it out. We know that our component requires a product
prop, which is an object. So in our controller, let's add a static values = with a
product value set to an object. Then pass this value in as the product prop, you do
that with the product = this.product value.

Beautiful.

Next open the template for this page, which is templates, registration registered at
H twig and above the H rule on let's add a new dev here. How about with classical's
call

Sam dash three,

Then we'll put the stimulus controller right here. So curly, curly stimulus
controller, it's called featured product react. And then we'll pass that value as the
second arguments. So product we'll set this to an object. And for now I'm just going
to start with some coded data. So we know that our feature product object needs like
an ID and a name key. So let's just start with those. I'll say ID five and Nate set
the name to a wonderful product.

That should be enough to see if things are working. Let's go try it on the browser,
refresh and boom. There it is. It's a little broken because our product is still
missing some fields, but that value is being passed to react as a prop. But what we
really want to do is pass real product data to this prop. And we can do that by
serializing, a product object. And JSON first opened the controller for this page
source controller registration controller, and at the end of the method, add a new
argument so that we can query for the feature product, product repository, product
repository. Next scroll down to where we run to the template and let's pass in a new
variable here called featured

Product

Set to product depository arrow, find a feature which is a custom method that I
already created now in the template. We somehow want to transform that featured
product object into JSON so we can pass it to this product value, open up the product
class source entity product at PHP. I already have Symfonys serializer installed in
my app, and I've already added some serialization groups to this class to control
exactly which fields are turned into JSON using this product colon read group. The
only missing piece is that there isn't a way in twig to use Symfony serializer to
transform an object to JSON. So I built one. You can see it in source twig, see,
realize extension dot PHP. This adds a filter called serialize, which just calls the
serializer and passes our data. In fun fact, in Symfony 5.3, this filter will come
standard. A pull request that added it has already been accepted. This means back and
the template. We can say the product, colon feature, product pipe serialize,

And then the first argument to the filter is the format. So we'll use JSON. And the
second argument is any serious action context we need to pass. So for us, and we need
to pass a group's key set to product colon read. This is a little specific to the
serializer on passing this product, colon read here, which corresponds with the
groups I'm using inside of my entity class, which will tell it which fields to turn
into JSON. That's it. We just passed a product object directly from our server into a
react, into react as a prop. I converting it into JSON. Let's see if it works.
However, where you refresh the page and it does inspect element on this

[inaudible]

And look at that product value attribute.

Lovely.

Our product was turned into JSON, then stimulus automatically parse that JSON, back
into an object. Yep. Stimulus loves front end frameworks. They're just another tool
in your toolkit. Next, each time we asked him a stimulus controller to our app, that
controllers code is added to the JavaScript that's downloaded on every page that
makes stimulus super easy to use at a data dash controller to any page or any Ajax
response. And it will work, but how can we make sure our JavaScript isn't getting too
big next, let's learn our way. Let's learn how to visualize the size of our
JavaScript files and some amazing tricks with laziness to make them smaller.

