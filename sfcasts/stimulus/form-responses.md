# Form Responses

Coming soon...

No

Modal and fill out the

Name

And price fields. The only two required fields. I want to see what happens if we
submit this form successfully. And Whoa, that was weird on success. Our controller
currently redirects them back to Slack. The pro uh, currently redirects, when an Ajax
call from jQuery hits a redirect, it follows that redirect. There's the first
redirect. And there's the second, since that redirect is for the list page, it
grabbed that HTML, that full HTML for that page and jammed it into the modal. So, um,
okay. That's not what we want instead of on success. I w I want to detect that the
form,

I want to say,

What was the model to do that? We're going to need to be able to detect whether or
not a form submit was successful or had a validation error. And the very least, if we
refresh the page, we do see the new product in the list. So, yay. We know that's
working back over in the controller. If the form is being submitted via Ajax, instead
of a redirect, we can return an empty, successful response. We can do that with if
request arrow is XML HTTP request, Or you could be looking for a query parameter. If
you did it that way, then return new response. The one from HTTP foundation, no, and
two Oh four,

Two Oh four is a special status code. That means this was successful, but I have no
information I need to send back. So it's an empty response, but there's still one
problem. When the form hasn't validation errors, we're currently returning it to 100
status code. And that's what this error render does by the fault. In other words,
whether the form submit is successful or not successful, both situations return a
successful response that doesn't make our life very easy in JavaScript to figure out
which B which situation we're in. What can we do return an error response when the
form fails validation, this will not only make our life easier in JavaScript. It's
more correct. And it'll still work fine if you're submitting a form normally without
JavaScript, check it out. The third argument to render

Is a response object that the HTML from the template should be put into when this is
not passed. The response object with a 200 status code is automatically created. So
now let's pass our own response, new response, the same one from HTTP foundation.
They don't pass this Knoll for the first argument for the content, uh, because that's
going to be replaced by the HTML from the template anyways. And the really important
thing for us is the status code. Now this render method is called both when the form
is originally loaded and when it's submitted, uh, with invalid data. So we need to
figure out whether w which situation we're in. So I'll use the ternary syntax here to
say, form arrow is, is submitted.

Yeah,

Four 22 L's 200. Again, if the form was submitted and successful, we would already be
inside this loop here, and we'd never get down there. So if the form is submitted, we
definitely know it's an invalid submit. So we can use this four 22 status code for 22
means unprocessed unprocessable entity. And it's a standard status code for
validation errors. As a bonus, doing this on your forms will play super nicely with
stimulus's sister technology turbo. Oh, and by the way, in symphony 5.3, there is a
new render form shortcut in your controller, which will automatically set the four 22
status code for you just like this. That'll make this much cleaner.

Okay.

Back in our stimulus controller. Now we have the info we need. When the SIM form
submit fails validation, the await dollar sign dot Ajax will now throw in exception.
Thanks to that four 22 status code. So let's wrap this in a try catch block.

I'll say, try catch.

And what we want to do here is actually Take out the Vista modal body.target. I enter
HTML because we only want to do that on air. So down in the air will say this, that
mold about by target to enter HTML and to get the response tax, you can actually get
it off of the air. Object is E dot response text. If you look at the documentation in
the successful situation, let's just say, console that log success

For now. All right, let's try this

Refresh open the modal and let's first submit this empty. Beautiful. We have the
heirs now fill in a name and price

And submit, okay. Nothing happened. Okay.

But if we check the console, yes, we do see the log. All we need to do now is close
the modal on success.

Okay.

We do that by calling hide on this modal object. The only problem is that we don't
have access to that modal object from down here in our submit form method. That's
okay. Let's send it on a pro as a property

Up here,

Uh, up at the top of the class. I don't have to do this, but I'm going to say modal
equals no to initialize that property. Then down here, we'll say this stop modal
equals new modal And this dot modal that show down then replaced concert log of
success with this dot modal

Doc hide. Let's try it again.

Refresh. You can see my awesome zip drive. Is there,

Fill out the fields and submit, Oh God,

We have a fully functioning Ajax modal system that is reusable. The only imperfect
thing is that we don't see the new item on the page, unless we refresh let's handle
that in the next chapter.

But before we do

Search for bootstrap five modal click into its docs, and then go down to the events
section at the bottom, Total opens and closes the modal itself, dispatches some
events. What if we needed to listen to those? Like, what if we need to run some
custom code? Whenever a modal is closed, how can we do that? This is the beauty of
stimulus. We already know how, if something is dispatching an event, all we need to
do is add in action for that event. Check it out over in index that HTML that twig
I'll break This div. The steam has controller and a multiple lines, and then add a
data dash action here. What we're going to do is we're going to listen to this
hidden, that BS dot mole event, which happens after the modal is finished being
hidden.

Okay.

So I'll say that. And then we'll say arrow, the name of our controller modal dash
form pound sign, and then let's call the new method modal hidden.

Okay.

Now the hidden BS modal event, won't be dispatch dispatched directly on this dip. It
will be dispatched on the modal element, but we already know that's okay. The event
will bubble up to this diff competent model, hidden name, go into our stimulus
controller at the bottom. Add that method and let's just console dot log. It was
hidden.

Try it out, go back to our site, refresh over there,

Model and hit, cancel. There's the log opening again, hit X there's the another log.
I love that next to make this a truly smooth user experience after a successful form
summit, what we should really do is make an Ajax call to reload the product list so
that the user can see the new product. Let's do that by once again, making a re
usable stimulus controller, this controller will be capable. We'll be able to reload
the HTML for any element.

