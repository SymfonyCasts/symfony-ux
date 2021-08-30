# Entity Broadcast

Coming soon...

Super cool feature of the turbo mercury UX package that we installed earlier that we
have not talked about. And it's this the ability to publish a mercury update
automatically whenever a, an entity is created, updated or removed.

That is a powerful

Idea. For example, instead of publishing this update from inside of our controller,
what if we publish this update? Whenever a review is added to the system, regardless
of how or where it's added, or what if we could publish a mercury update? Whenever a
review is changed, like if we changed a review in an admin area, it automatically
updated that review for anyone that was currently reading it, that is totally
possible to activate it, go into the entity itself. So source and state reviewed at
PHP and above the class. Add at broadcast. If you're using PHP, you can also use
broadcast as an attribute next and templates /products slash_review is that H two I
Mara this is where we originally use turbo stream listened to listen to the product
reviews, uh, stream temporarily. Okay, copy that. And also listened to an extreme
call app /entity /reviews. We need the double slashes here just to avoid escaping
problems. Oh. And not reviews just okay. As soon as we add the add broadcasts above
other views.

Okay. Whenever a review review is created, change or moved in update is sent to the
app /entity /review topic on our mercury hub. And now we're listening to that topic.
All right. Let's refresh the page and check out our network tools here. Let's see.
Awesome. You can see here, here's the new stream URL. I'm going to open that in a new
tab and it's just waiting for updates. Now let's try to submit a new review sit and
oh 500 air. Let's open that up and see what happened. Ah, unable to find template
broadcast /reviewed that streamed at age Tam, all that twig. Okay. So here's the
whole flow that we've activated by adding the add broadcast annotation above the
entity. First we create change or remove, remove a review from the database. Second,
the turbo mercure library notices this in tries to render a template called review.
That stream that age to Leftwich will create this in a minute and third, whatever
this template renders is published to mercure in a specific way, let's go create that
template and the templates directory add the new new directory called broadcast. Then
inside that a new file called review that stream that Asia twig. Now these templates
always look the same. I'll pace. Any skeleton. It's pretty simple. If a review is
created, the content in the create block is sent to mercure. If a review is updated,
the update block is used. And if we do need to review the contents of the remove
block, our post to Merkur, we can see this immediately. I'll close the provider tab
and let's go refresh this page and add in your view. Let me submit nothing looks
different here yet,

But check out the tablets, listen to mercure.

Yes, there it is. Our data create was sent. Now we're dangerous. Go into our original
stream, stamp up reviews that stream that HL twig, and copy both of these streams and
paste them into our create block. Boom, that's it. We can now delete reviews that
stream, .html.twig, and instead of product controller, we don't even need to dispatch
this update at all anymore. It's going to automatically happen when we create the
review. So I'll delete that update. We can delete the mercure hub and if we want to
get a really crazy, let's see, we probably have whole bunch of extra use statements
up at the top of the page. Awesome.

And then on the school reviews that age, we don't need to listen to our original
product dash reviews topic anymore. Just need to listen to app /entities /review.
Okay. Let's try it. Go back. Refresh and Polish, a new review on it. Doesn't work.
500 air let's check out what happened here. Variable product does not exist coming
from review stream that age to my twig. Oh, okay. So apparently there is not a
product variable past to this template, which begs the question. What variables are
passed to this template? When mercury renders a template passes a few variables into
here, most importantly, a variable called entity, which in this case will be set to
the review object. We can use that to fix our template. So instead of product ID, it
will be entity that product ID could do that in both places. And then this template
here also needs a product variable. So I'll pass that in set the product set to end
to cap product and down here at review, doesn't need to be set to new review anymore.
It should be set to entity. Let's see if I got everything

I'll close the error, refresh the page.

And at our review, we're looking for is that we should have the same behavior that we
had before

Summit and awesome.

Yes, we get it. Form my things to be, uh, to reform the stream updated here. And even
though I didn't pay too close attention, the quick stats area also updated over
another tab, you can see this new stuff streaming in, which is beautiful. The big
difference is behind the scenes, which will now update no matter how a review was
created select next, let's also update every user's page. Whenever re review is
updated or removed, I'll even show you a review admin area that we have hiding on our
site, where we can see this in action by changing or deleting reviews and instantly
watching our front end pages update to match pretty sweet.
