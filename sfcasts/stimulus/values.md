# Values

Coming soon...

What if we want to automatically select a color on page load, like maybe there is a
most popular color. The best approach would probably be to pre select an option in
the select element. Then we could read that from within our stimulus controller,
probably in connect and pre select that color square, but for the purposes of
learning, I want to do something else because this is a really common problem. The
problem of passing options and server server-side information into your stimulus
controller, like in this case, which options should be preselected. And we already
know one approach, data attributes. We invented a data attribute arm button to add
more information to it. Hmm. Why not add a data data attribute to our top level
controller element? Let's do it. I'll break this onto multiple lines. And then we'll
add data dash color dash ID about data dash dash at eight equals. And then here, I'm
actually just going to do a, we're just going to select the, whatever the second
color is to kind of fake this. So what I'm going to do is copy this whole add to
cart, that bars thing down here, they're paced up there. And then at the very end,
I'll do Los Rebecca at one to get the second element ID.

So the second colors should be selected over inside of our controller inside connect.
Let's see if we can log this console.log( and we'll get our top level element via
this.element and the way you use dataset and then caller ID. All right, let's try it
when we refresh. Okay. First of all, you can see our data dash color ID = two on the
elements and over my log, it is logging too awesome. But this is such a common thing
to do. Passing options and information from your server into your controller. That
stimulus gives us a special way to handle these with a few nice advantages. It's
called the values API. Here's how it works. Step one at a static values, set to an
object, whatever values we want. So we want a color ID value, and you'll actually set
this to the type. So this going to be a number.

As soon as we define this, we can magically access a color ID, a this.a color ID
value property. We can log it down here. So console.log(, this.color ID value.
Finally, to pass, to pass this value, we will use a data attribute, but it has a
special syntax. So it's going to be data dash the name of our controller. So color
dash square dash, the name of our value color dash ID. It's actually color ID inside
of here, but we're always going to use color dash ID inside of our data attributes.
Then dash value = and then the actual value itself. Now I know that is a long ugly
syntax. Don't worry. I'm going to show you an awesome shortcut in a minute, but let's
try it. When we move over and refresh it works, it logs are two and it's subtle. But
this two down here is actually a number a moment ago before we were using the values
API. That two was a string. And that makes sense. Technically in HTML, all data
attributes are strings, but the values API allows us to set the type of each value.
It then takes care of converting. Whatever is set onside of the data attribute into
that type. So we can use things like object, array, bullion, or any other JavaScript
type there. But yikes is, I love this values API, and, but this syntax is huge.

And so we've made it easier. And Symfony so far, we've created, always graded our,
our, uh, data controller elements by hand, because it's been simple enough, but
Webpack Encore bundle, it gives us a shortcut method. So check this out. I'm going to
run it inside of our element, where we want the data dash controller to be. We're
going to say curly, curly stimulus,_control. And as up here, past the name of the
controller color square. And then for the second argument, second optional argument,
we can pass an array of variables. So the variable we actually want here is color
dash ID. That's going to be set to, and I'll go copy our long add cart, form virus
thing, and paste. Then delete the data dash controller and the value below that will
give you the exact same result as before you can see it. If we inspect the element.
So first I'm actually going to go and, uh, find my data dash controller. Now I'll
refresh Refresh, and it looks exactly the same and we still get the log. I love this
feature Because the Valley's API is incredibly powerful in this takes all the pain
out of it to make it even more awesome. This will automatically escape the values for
each we'll automatically escape all of the values. So that, for example, if this
contained a double quote, it wouldn't break the attribute and then break the entire
page.

Okay?

Oh, and the function automatically normalizes the value names for you. So if you
want, you can use color ID here and we don't even need the quotes anymore to exactly
match the name of the value inside of your stimulus controller. When this renders all
refresh and go back to elements, it still renders the exact same way and it all still
works. Next. Let's use this new color ID value property to pre-select. These, the
color thinks that the organization of our controller, that's going to be really easy
and things to a feature of the values API that we have not seen yet. We're going to
end up with even less code than when we started.

