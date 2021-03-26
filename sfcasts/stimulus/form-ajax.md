# Form Ajax

Coming soon...

When we click this save button, we want to submit the form via Ajax. If you look at
the structure of this modal, let's take a look. The save button is actually outside
the form. The form is down here inside of modal body. This means that we can add an
action, a stimulus action submit action to the form because clicking save doesn't
actually submit the form. It does nothing instead over an underscore modal that age,
Timo twig, we're going to add a new action to the button itself. So I'll add data.
Dash action equals we'll use the default action for a button, which is click use our
controller name, modal dash form pound sign, and then a new method name. How about
submit form.

Copy that method name and go add it inside of our stimulus controller submit form.
All right, let's see. What we need to do is find the form element so we can read its
fields so we can read its field data. Normally, when we need to find something, we
add a target, should we do the same thing here we could, but in a perfect world, I'd
like to make it as easy as possible to reuse our modal form controller for other
forms. If we can avoid needing to add extra attributes to our form element, which is
rented by this a form start function, that will make that much easier. So instead in
our controller, let's start with, by getting w let's use it, this dot model body
target, which is going to be this element right here and look inside of it for a form
element with jQuery. We can do that with constant dollar sign form equals Dyson, open
parentheses, passing our elements, this dot modal body target, then dot find form.

So notice with jQuery, you're always going to use dollars and open parentheses, and
then some element. If we wanted to look for something inside of our entire controller
element, we'd use this dot element inside of here that keeps all of our jQuery.
Selectors is scoped to our controller. I also as convention prefix my variable names
with dollar sign, when they are J objects, there's nothing special about that. If you
wanted to do this without jQuery, it would be really similar. You'd use this stop
model, that modal body target that get elements by tag name, pass it form, and then
use these zero index off of that. Anyways, to make the AGS call, we're going to need
the data from all of the fields in the form without jQuery. If you look at our submit
confirm controller and scroll down to submit form, we learned that you can do that
with a combination of URL, search prams and form data passing it, the form element,
which in this case was this dot element. So we could do that again here, but since
we're using jQuery, there's a shortcut council that log dollar sign form got
serialized. All right, let's try that. Move over, refresh the page,

Open the model

And fill in at least one of the fields And then go down here and hit safe. Okay.
Nothing visually happened. We've looked at the log. We got it. It gives us this long
string, which is in the format that we can use to submit, uh, to the controller. And
if you see here, you can see product name, shoelaces. That's the part that I typed
in. Okay. So to make the HS call, we three things and we already have the first one.
We need the form field data. We have that with form that serialize and also the URL
to submit to and the method to use. We can get those last two directly from the form
element.

So let's say dollar sign dot Ajax, and then I'm going to pass it. The, um, the
options format where I pass even the URL as the option is I here, I'll say you were
out too. And we can say form dot and you might expect me to use attribute here,
instead of we're gonna use it form doc prop action. That's slightly different. That
will actually even work. Even if there isn't an no action attribute, it will figure
out what to do. If you look back at submit form controller. Um, last time we used
this, that element, which is the form element dot actions are actually reading this
action property. That's just a built-in property that works in all form elements that
gives you the action by using the dot prop method in jQuery, we're doing the same
thing. So I can do the same thing for method methods, set to form dot prop method.

And then finally, for the data, can we say data set to form dot serialize? So there
you have it in a jQuery version of how you can submit a form via Ajax. Now, of
course, this will submit the form, but we want you to submit the form and then do
something with the response. So automatically inside of our product admin controller
at the form is submitted to this URL and is invalid. It's automatically going to
rerender our underscore form that HTML twig, but this time it's going to have a form
that has errors on it. So what we want to do with this HTML is actually from the AGS
call is actually put it back onto this dot modal body target that encourage team out.
So I'm going to copy that and we'll say this dot molded by the target that enriched
team out equals await dollar sign that Ajax, and then we'll make async submit form
async okay. Moment of truth. Spin over, refresh the page, click the button, but leave
the form blank. This time hit, save, and

Ooh,

Check this out. You can actually see it down here. Post four Oh five and there's a
four Oh five air that was unexpected. I'm gonna open that link up in my profiler and
you'd see no route found for post slash admin slash product method, not allowed,
allowed get. So you can see that this is not the URL that we expected, And this is a
little gotcha. In this case,

In my code, I was trying to be really responsible by reading the action and the
method directly from the form. The problem is if you inspect the form on this, you
can see that my form actually doesn't have an action attribute. That's normally fine.
What that means is it's going to submit right back to the same URL that it's
currently on. Now, when we render this on the product, on the actual product, new
page, like slash admin slash products slash new, that's fine because it will submit
right back to this URL. But over here on the index page, when we see that empty, that
missing action attribute, it makes it look like it's a should submit right back to
this URL, which is not actually what we want. So to fix this, instead of form that
prop, that action, what we're going to use here is actually the, our form, our form
URL value. So I'll say this dot form you are L value. So what I'm assuming here is
that the same URL that you can use to fetch the form is also the URL that you can use
to submit the form. All right, let's try that again. Refresh the page and add, save.
And yes. Look at that. We already get some validation errors.

If I had saved again on that field, it submits again and they'd go away,

But there is

One tiny problem with this. If I put my, if I focus on an element and hit enter, Oh,
actually let me put a price here. Right? I'll put a price down here and enter now

The forms

Admitted, but not via Ajax. No problem. In addition to running our submit form
method, when we click the save button, let's also execute that method. One, the form
element is submitted. We'll wait, does that mean we need to add a stimulus data dash
action attribute to this form element. Like we need to do it inside of our underscore
form that HTML, that twig template. I thought we were trying to avoid doing that.
Fortunately, we do not need to do that in underscore modal that age, a twig Break,
the modal body onto multiple lines for readability,

And then add data. Dash dash action equals the name of the event, submit arrow the
name of our controller, mobile dash form pound sign. And then that same submit form
method. Remember events bubble up the submit event is first dispatched on this form
element, but then it bubbles up to the modal body diff that means that this element
also receives a submit, a submit event is dispatched on it. So it's that easy to
prevent the form from submitting so that we can make the AGS call on the submit form,
uh, method, add the event argument and say event dot prevent the fault.

When we hit enter on the form, that will prevent the submit. When we click the save
button, this actually has no effect because clicking a button outside of form doesn't
natively, do anything. Okay. Try it out. Okay. Reload. Get the modal up. Focus. A
feel can enter. Uh, of course HTML five validation is getting me. So I actually type
in the two required fields here. I'll hit enter. Actually, I'm gonna put a negative
number, so that fails validation and perfect, gorgeous. Next. This is working great
when there is a validation error, but when the submit, but when the submit is
successful, we need to do something different. We need to close the modal. We'll
create. We're going to create a systematic way in our controller to communicate
whether or not a form submit was successful back to our JavaScript as a bonus, we're
going to talk about how we could listen to the native bootstrap modal events from
inside stimulus. This will give us the power to, for example, run custom code when
the modal was closed. Like maybe when we click cancel, or maybe when we hit the X
button up here.

