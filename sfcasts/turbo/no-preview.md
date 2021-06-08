# Form Submits & The Preview Feature

One of the cooler features of Turbo Drive is its snapshot feature, which we've talked
about several times. When we visit a page that we've already been to, like Office
Supplies or Furniture, it instantly shows the snapshot while it waits for the new
Ajax call to finish in the background. And when we hit back, it instantly shows the
snapshot... and that's it: it makes *no* Ajax calls in this situation.

This feature, which is *great* for making your site snappy - is, I'll admit, one
of the most *problematic* when it comes to perfecting your site with Turbo Drive.

## Snapshots and Form Submits

Let's see an example. Head over to the registration page and fill out the form
incorrectly: I'll use a bogus email address and hit enter. Cool. The forms submitted
via Ajax and we see the errors.

Now, click back to the home page. I'm going to click to revisit the registration
page. But watch closely when I do. Woh! For just a moment, we saw the form *with*
the values filled in and the validation errors!

Here's what happened. When we were originally on the registration page with the
validation errors showing, we clicked to leave the page to go to the homepage.
At *that* moment - *just* before we're navigated away, Turbo saved the snapshot
for the registration page. That means the snapshot was for a page that had
filled-in form fields and validation errors.

Then, when we clicked back to the registration page, that snapshot was restored.
A moment later, when the AJAX call finished, the fresh content - with an empty form -
replaced the snapshot.

This is a known issue with submitted forms. And... well... maybe it's not really an
issue. It's... tricky. And,ma maybe you don't really care that this shows up
for a moment before it clears. In that case, just ignore it and move on with your
life! Go grab a croissant.

## How to Handle Problematic Snapshots

But let's say that we *do* want to avoid this. One option is that we could disable
the snapshot from being taken on this page *completely*. But when I fill out the
form... and get the errors... and go to the homepage... and then hit the "back"
button in my browser, it *is* nice that, thanks to the snapshot, we see the form
with the values still filled-in. So, you kind of want the snapshot cache to be used
when hitting the back button... but not for the preview.

There are two main ways that we can fix this. The first involves *preparing* a
page before its snapshot is taken. We could clear the form errors and empty the
fields so that's the snapshot is clean. The code to do this would work for *any*
form on your site... so it would kind of take care of everything all at once.
The only downside is that clicking the back button would show an empty form. We're
not going to use this solution in *this* case, but we will go into *how* to do this
in depth soon for another situation.

## Disabling the Preview on a Page

A second solution is to simply disable the preview feature for *this* page. And,
that's one of the nice things about Turbo. Don't like something? Just disable it.

How? By adding a special `meta` tag to the `head` element. Head over to the code and
open up `templates/base.html.twig`? We don't want to remove the preview functionality
for *every* page. So instead of adding the `meta` tag right here, add a block so that
a *child* template can add new meta elements: `{% block metas %}` `{% endblock %}`.

Now open up `templates/registration/register.html.twig` and override that block:
`{% block metas %}`, `{% endblock %}` and inside add `<meta>`
`name="turbo-cache-control"` with `content="no-preview"`.

The `no-preview` means: don't show a preview for this page. The other possible value
is `no-cache`, which tells Turbo to not do *any* snapshotting: not even for the back
button.

Let's see how this feels! Refresh the registration page, fill out the form with
errors and click away from this page. Now, click back to it. Beautiful! Instead of
instantly showing the preview, it stayed on the previous page until the new Ajax
call finished loading, just like a *normal* navigation. You can repeat this for
any pages that have a public-facing form where you care enough to avoid this problem.

## Dimming the Opacity of a Preview

Speaking of the preview feature, you can also change what a preview page *looks*
like, in case you want to make it more obvious that a preview is being shown or
give it a "loading" feel. How? Open your Elements inspector. It's quick, but
watch this `html` element. Whenever you navigate and a preview is rendered, it
will add a `data-turbo-preview` attribute to this `html` element.

Boom! It's there for just a moment, but I saw it! We can use that to, for example,
lower the *opacity* on a preview.

Head over to `assets/styles/app.css`. Target that attribute using the lesser-known
attribute syntax: `[data-turbo-preview]` then `body` to apply some body styling.
Inside, set opacity to .2 so it's really obvious.

Let's go check it! Refresh. As we click to new pages, we don't see anything. But
if we click to a page we've been to... yes! The whole page was nearly invisible
while the preview was being shown. This is also kind of a fun way while you're
developing to get a feel for when the preview is shown.

But... since that looks a bit extreme, let's go back to `app.css` and comment that
out.

Next: in addition to the form situation we just saw, there's one other common
time when the preview feature will do something that... we don't want. Let's talk
about what happens when something like a modal is open at the moment a snapshot is
taken.
