# Dataset

Coming soon...

To make our color selector actually work. When we click a color, we need to change
the select element in the real select box. That way, when we submit it, we'll submit
the correct color. I'm going to inspect the element around here and look at the
select. Okay. Each options value is the ID of that color in the database. So somehow
when we click these color squares, we need to know the ID of the color that we're
selecting so that we can select that option in the select. Fortunately, there's a
native way to add extra information to Dom elements, data attributes over the
template and the button let's add a new data. Dash color dash ID = curly curly color,
that ID. This is the first time we've used a data attribute that has nothing to do
with stimulus. We're just inventing this data attribute for our own purposes.

And the only rule of the data elements is that they must, of course start with data
dash and then the rest of the string needs to be lowercase. And you usually see
dashes between words like colored dash ID and a controller. There's a standard way to
read this stuff at the bottom of SLIFE color let's console, that log event, that
current target to get our button. And then that data set that color ID notice that
the color dash ID from the controller from the HTML becomes color ID inside this data
set property. This is once again, not a stimulus thing. This is just how data
attributes work. All right, let's try it.

Now. I'll go to console. When I click. Yes, we are getting the IDs logged. The next
step is to find the select element and whenever we need to find something, we need a
target over that controller. Add a second target to our controller called how about
select then in the HTML, we need to add that target. The only tricky thing in this
case is that the form widget is actually what's rendering the select element. This is
a Symfony thing. So what we need to do is add a custom attribute to that. So we're
going to add second argument to form widget. And here we can put ACTR set to another
object and inside data dash color data, dash color dash square dash target set to
select.

Then back over in our controller, assuming I've set this all up correctly, we should
now be able to reference this via this.select target. And then we can set its value.
It's very simple by saying that value = and then we'll say event dot current target
dataset dot color ID. All right, let's try that out and refresh. That is awesome. As
we go, as we click the colors, we can see the select element changing. That's really
fun. And so we are done. This is now going to work. The last step is we can now hide
the select element. Let's do that in a controller. So I'm going to add a connect
method.

Instead of here, we can find our target, this.select part, class list dot add. And
I'll say D dash none. I'm using, I'm using bootstrap. So that will add a display,
none to it. And now, Oh, that is lovely. Let's try it. Let's add a red couch to a
cart. I hit it. It looks like it worked. I can go shopping cart and yes, it did
select the right color and check out our stimulus controller. It's about 15 lines of
code that's because all of our markup is in twig. So the only thing that needs to go
into JavaScript is the little bit of behavior that we're adding next. I want to allow
the user to click. If we go back, I want to be allowed to use her to click again, to
unselect a card color, not too hard, but to make it even easier. Let's take advantage
of the fact that we can store state on our controller.

