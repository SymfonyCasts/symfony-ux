# Frames, Redirects & Form "action" Attributes

Coming soon...

But something isn't right. Let's change the title and
submit the form. Well, that looked like a full page refresh. Let's try that again.
Watch the console closely down here. It also didn't save update. Whoa, you see that
there was a failed post request. So for some reason, the post request failed and then
the whole page reloaded is going on here. So let's start by getting more information
about what that fit, what happened on that field post request. So I'm going to open
the profile for this page in a new window. [inaudible]

[inaudible]

And then go and hit last 10. All right. Perfect. Here. You can see what a 4 0 5 air.
Interesting. So let's look at the profile for that page. Hmm. Okay. So it says no
route Von for post method, not allowed. Allow get, wait, look at the you URL out.
That is not the right you were out. The form is, should be submitting to the product
admin area, which if you look at that the product admin area, when you edit a
product, look at the URL, it should be as many, do something like this /admin
/product /12 /edit. Instead, this was submitting to the public product show page. Why
close this tab and then hit edit again. Let me get this out of the way here, refresh
there to ever go refresh to get that web diva, two of our, out of the way now inspect
element on the form.

Let's see. Ah, the form element does not have an action attribute. Normally this is
fine. If you go to the product admin page and click to edit a product in this case,
that form also doesn't have an action attribute. That's fine because when a form
doesn't have an action attribute, it tells your browser to submit to these same URL
that it's currently on. So for this page, that's perfect. But when we're on the
public product show page, and we load the same form now having that empty action is
not okay. Our browser incorrectly thinks it should submit to /product /one. So here's
kind of the takeaway. If you're planning to load a form into a turbo frame, that form
does need an action attribute. We can't be lazy. Like we normally are. You can set
the action ads to be in a few places, but I like to do it in the controller where we
create the form.

So open the controller for the product admin area, which is src/Controller, product
admin controller. Yeah, right now we're only dealing with the edit page, but I'm
going to set the F the action on both the new and the edits actions. So the way we
can do that is by adding a third argument to create form and passing an option called
action. So say this->generate URL, we'll generate right back to this route. So
product admin new now scroll down to the one that we really care about, which is the
edits and same thing here. Won't pass a third argument action set to this-> generate
URL to product admin, edit this time, matching this one. And of course, this will
need to also have an ID set to product-> get ID

[inaudible]

Okay, Tim, let's give that a try, refresh the page, click at it, change the title and
let's submit the form. Got it. Okay. Very nice. Mostly, if you look down here, there
we go. It did update the title [inaudible]

But you can see it redirected as a back to the product list page, not the product
show page what's really going on here is that when we click this edit button, that
does load the form into this frame, but then because our frame has target = top on
it. Yeah. When we submit this form, this submits to the whole page and navigates the
whole page, that's why hitting save here, redirects us to a totally different page.
And that's probably okay. This is already a better experience than when we started,
but we could make it a bit more awesome by having it redirect back to the product
show page. So let's do that this time. I'll do it just in the edit action. So let's
see here after success right now, we're listening, redirecting back to the index
page. So let's change this to app_product.

That's the, to our show page. And this has an ID wild card. So little by little,
we're just making this experience better. So now I can open up my floppy disk public
show page, hit, edits, change the title and enter. And it redirects me right back
here, gorgeous. But if we want to week and enhance a little bit further, edit the
product again and empty the title so that we fail form validation. When we submit
this, navigate us away from that page and put us in the admin section, which makes
complete sense. Since we know this form is still submitting to the full page, not to
the frame. And so this is probably okay, but we could make the form also, but could
we also make the form submit in the frame? Totally. And we have two ways to do this
first over and showed at HTML on twig.

We added the target = top to our turbo frame. So one way that we can make the form,
the edit form submit into this frame as by removing target = top so that everything
is out of that frame navigates inside that frame. But if we did that, we would need
to make sure that any other links or forms that are inside of here that should target
the main page, have data turbo frame = top attribute. So the other option is that we
can leave the target = top on here and then on just the product form. So just this
form here we add data turbo frame = product info. Okay. So for me, this is still kind
of a confusing, unclear spot with turbo drive in general, the idea of, do you add the
data turbo frame equals, do you add target = top on the frame and then on individual
forms and links inside of here, you target the frame or do you leave target = top off
of the frame and then only add target = top to the individual links inform submits
inside of it.

There are just two different ways to go. And haven't found one that is definitely
better than the other. So in this case, I'm going to keep things safer by keeping
this target = top up here in just changing the target of the form to target the
frame. So if you remember this form, uh, the edit page is edit the HTML twig and the
form itself lives in_form at that age two month twigs. So let's open up that
template. So we want to make this form tag target. And the frame this form started
here is actually responsible rendering the opening form tag. So we basically want to
do is add a data turbo frame attribute right here. So it's a little bit ugly, but we
can do that by passing a second argument to form start passing that in ATT are
variable. And inside of that, a data turbo frame attribute set to product dash info.

Okay, let's try the flow. So when I refresh right now, I have a turbo frame that has
target = top target. He goes top, but then inside of here, we have a edit link that
targets the frame specifically. So when I click this, add a button that opens inside
the frame, now that just loaded a form, we still have turbo frame = target top, but
now we have a frame that is also targeting product info when it's submitted. So
thanks to this. If we empty this title and then submit, yes, that keeps us right on
the page and submit inside of the frame. That is lovely.

If we put the title back and change it and submit beautiful. So it was subtle, but we
actually just saw one important behavior of terrible frames. When we submitted that
form successfully, it's submitted to the edit action inside of product admin
controller. So this handled the form, submit on success. This redirects to the pro
public products show page. It turns out if you submit a form in a frame and it
redirects turbo does not follow the redirect and navigate the entire page. Well, well
actually the AJAX called does follow the redirect. Let me show you in the network
tools here, you can see this edit here was for our unsuccessful forms event or for 20
to this second post to the edit page here was our successful forms, Mitt it
redirected. And so turtle made a second Ajax call to the public product page. This is
exactly how turbo works in general, but instead of actually changing the URL to this
page, because we're in a turbo frame, it read the HTML of this redirected page, found
the product info frame and loaded that right here. It's kind of hard to see because
this just redirected back to the same. You were all that we have here, but it's not
actually redirecting the full page to this URL. It's just using its content.

So another way to think about this is that if you submit a form inside of a frame and
that page redirects, the frame system will grab the Tribble frame from that
redirected page and put it in the frame, but it's not going to navigate the entire
page, navigate what the navigation stays inside the frame, an easier place to see
this is actually the product admin area, where this is actually causing a problem. So
if I go to the product admin area right now and edit a product, we now know that this
frame is target. Ms. Form is targeting the frame. So even though this is instead of a
terrible frame of targeting, we'll stop. The form is now targeting the product info
frame. So watch what happens when we change this title and hit enter, actually. So if
we clear out the title and it enter the frame, it's it actually just submitted right
into this frame.

If we put back the title, change it and submit successfully. Watch what happens here.
Ah, Frankenstein page weird half of the public product page just exploded onto this
admin page. So unfortunately, turbo frames, the turbo frame is doing exactly what
we're asking it to do. If you look at the network tools here, [inaudible] and scroll
up a bit, you can see, we submitted successfully to the edit page that redirected to
the public show page. Then the, because we're submitting in a turbo frame, turbo
frame found the product info frame here, which has all of this kind of info up here,
grabbed it and popped it onto this page.

It did not. For example, redirect the entire page, just last products. Last 12, we
stay on the page and only the contents of the frame change. That's definitely not
what we want. And look at this point, things are getting kind of complicated. So
let's think when we load the form from the product show page, by hitting edit, we do
want this form to submit into this frame. But when we load that same frame on the,
from the product, have an area, we kind of just want this to behave like normal by
submitting to the entire page. Could we do that? Totally had to product admin
controller, edit action. Whenever turbo is navigating inside a frame, it sends an
extra called turbo frame with the name of the frame. So when we click the edit link
from a product show page, that Ajax request yeah, adds a turbo frame header. You can
see it all the way down here under headers. We want request headers. Let's see here,
there it is turbo frame at product dash info, but why don't we just navigate directly
to the product admin area and look at that AJAXrequest. But down here, there is no
turbo frame. So we can actually detect whether we're being loaded inside of a turbo
frame or not. From inside of Symfony.

We can use that inside of our controller, down the template. I'm going to pass a new
variable here, call it form target, set to request-> headers,-> get turbo frame. And
then I can add a second argument here. If there is no header, let's have the default
value be_top. Okay. Now in_form by age two months wig, instead of having this target
product info, in all cases, we can use that new form target variable. Now because
this pen temple parcels also included from the new page and we're not passing this
in. I'm going to add a little default here and defaulted to_top as well.

Now I think we're good. All right, let's try it. Refresh the product admin page and
hit save beautiful that submit it to the whole page and redirected the entire page to
after submit now, click edit, empty the title and enter. And yes, this is still
navigating just right inside the frame. If you inspect element on that form, you can
see it does have the extra data turbo frame attribute set to product info. Okay, we
are done. I included this example both because it's really cool to have this inline
edits, but also it shows up situations where turbo frames can get a little bit
complex. It's always up to you to balance the complexity with the user experience
that you want next. What am I using turbo frames inside of a modal after aisle? You
often want navigation like links and form submits inside of a modal to stay inside of
modal, which is what turbo frames are really good at. What's transformed this model
into a turbo frame power model next.
