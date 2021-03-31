# Autocomplete Controller

Coming soon...

Now that we have a new complete controller registered, let's try to use it instead of
our custom search preview controller to power our search preview functionality, to
see how look back at the docs. Let's see, looking at this example here, we need to
add the data. Dash controller attribute around the text input and the div where the
results will go. We also need to pass a URL value to where the Ajax calls should be
made. The input needs a target called input, and the results go into a target called
results. We don't need to worry about this hidden element, this hidden input thing.
That's only if you need to save a value in a form element after the user selects an
option, which doesn't apply to us, open the template for the homepage templates,
product index, that aides to all that twig.

And let's see first changed the name of the controller from search preview to auto,
complete, and nice. We're already passing a value called URL. Next on the input. We
don't need this action anymore. The controller handles setting that up for us, but we
do need to add a target to this call to input. So the controller knows this is our
text element data dash auto complete bash target = input. Finally update the results
target to use the new controller name auto complete, and the target name is now
results with an S and done. Let's try it, move over. Find our site hit refresh type D
I and nothing happened. Well, not nothing I can see in the web debug toolbar, Whoever
had back to our spot refresh type in D I nothing happened well, not nothing. I can
see that there was an Ajax call. In fact, two Ajax calls down in the web Debo. Two of
our what's check one of these out in our network tool. Look at the preview. Whoa,
it's a little small down here, but this is the full HTML page. I make that a little
bit bigger. It's not just the results,

What happened?

Okay. A few things to notice first by complete chance, the auto clique controller
sends the contents of our input as a cue query parameter, which is exactly what we
were using. Before. You can see that in source controller, product controller dot
PHP, we use Q to get our search term, but we also look for a question Mark preview,
query parameter. To know if we should render a page partial before in search preview
controller, we actually added that in, in a JavaScript manually in JavaScript. Now we
should add it to our value in index that HTML twig back up on the URL at a second
arguments path and past preview set to one that will fix the full page problem. But
if you try it now,

Same thing in Ajax call was made, but no results and the network tab. Yeah, it is now
returning the partial, not the full page. So why don't we actually see them right
here? One other rule to this library, which I would have noticed if I had read the
documentation a bit more closely is that each result should be identified by a role =
option attribute. Okay? We don't need this data out of place value, cause that
applies only if you need the hidden input thing, but we definitely always need this
role = option thing. No problem. The template for that partial is over in templates,
product_search, preview dot HTML, that twig on the a tag, which represents a single
option I'll add role equals

Sure.

And actually down here on the no results and we need to do the same thing, role =
option. And if you look again at the documentation, if you want to make it not
something not clickable, you can add an area = true options. So that will make it
show up on the list, but it's not something I'm going to actually be able to select

Right

This time, back in our site, we don't even need to refresh the page I can type and
boom, there it is. It looks exactly like before. And as a bonus, this controller has
something that ours never did. The ability to hit up and down to go through the
results and the hitting enter selects it. Let's celebrate by deleting our old search
preview controller. Thanks for teaching us how to use stimulus. Now we will use less
custom code, but I have one last question. Could we make this auto-complete
controller load laser only earlier we made the whole chart, J S controller lazy in
controllers by JSON by sending fetch to lazy. We also made the submit confirm
controller lazy by adding this special comment on top. But what about third-party
controller? We don't register this in controllers that JSON and we can't exactly open
up this file and add a comment.

So can we make it lazy? We can though, due to some rigidness in how Webpack works,
the syntax, it's not amazing in bootstrap dot JS. We need to change the import to
pass through this special stimulus bridge, lazy controller loader. You can do that by
literally saying import auto complete from the name of that loader exclamation
points, and then the name of the module that you want to import. So this module will
now be passed through this loader, but also before the exclamation point at question
Mark lazy = true that that lazy = true basically fills in for the missing lazy
comment that in the file and tells the loader to load this controller lazy. Now,
normally this would be all you need ugly, but not that ugly, but since this stimulus
auto-complete module export a exports eight named export instead of a default export,
we needed to do a little bit more. So by name to export. I mean, the fact that we had
to say import curly, auto-complete curly, that's a named export versus being able to
just say in four out of the plate, from the name of that library, if we tried this,
it would not work. So since it exports a named export, we need to tell the stimulus
letter, the name of that export. We do that by adding one more loader option here as
a query parameter called and export equals

Auto

Complete. So literally whatever it is that we're using, Yeah, that's not the
prettiest thing right there, but if you had over and reload the page and check the
network tools, it filtered for JavaScript.

[inaudible].

If you remember now refresh and then go down to the filter for on your network tools,
filter for JS. Yes. Look at this. You can look at this name here. This one here
stimulus complete is being downloaded.

[inaudible]

This script file here today is a new controller and was only loaded after the data
dash controller = auto complete was found on the page. If we want to any other page,
you would see that that is never downloaded. This new controller allowed us to have
cut less custom code in our app and added the ability to press up and down on the
search results to choose things in the list. But we did lose one thing, the nice CSS
transitions. Can we somehow add those to this third-party controller? As long as the
controller dispatches the right events, we totally can. Let's learn how next.

