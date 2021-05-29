# The "defer" Attribute & Conditionally Activating Turbo

Inspect element and go check out the `head` tag. Notice that all of our `script`
elements live up here in the `head` with a `defer` attribute. That's on purpose.
And this `defer` attribute comes from our configuration:
`config/packages/webpack_encore.yaml`: `script_attributes`, `defer`

## The defer Attribute

The reason we placed our `script` tags up in the `head` element is, well, we
learned why in the last chapter. By adding them here, they won't be
re-executed on every Turbo visit

But normally, adding `script` tags to the `head` is bad for performance. When your
browser sees a `script` tag, it freezes the page rendering while it downloads
the file and execute it. But by adding `defer`, the file is downloaded in the
background and the page continues loading *without* waiting. Once the page
*finishes* loading, *then* the JavaScript is executed. If you want to learn more
about the `defer` attribute, we have a blog post about it on Symfony.com:
https://symfony.com/blog/moving-script-inside-head-and-the-defer-attribute

Anyways, here's the big takeaway about using Turbo Drive and JavaScript: to get it
to work reliably, you're going to need all of your JavaScript to be written in
Stimulus. But that doesn't mean that you need to completely rewrite it. If you
have a big block of JavaScript that works on an element, you can copy that
code into the `connect()` method of a Stimulus controller, which is called each
time a matching `data-controller` element is found. Often, the only change you
need to make is to remove any `document.ready()` code, and tweak your JavaScript
to target `this.element`.

And... if you can't or don't want to use Stimulus, you can also tweak your code so
that it's executed on each page load, like by wrapping that code in a Turbo event,
that's fired on each visit instead of using jQuery's `document.ready()` method.
We'll talk about turbo events later.

## Completely Disabling Turbo

By the way, if you *did* need to disable Turbo for a specific link... or even for
an entire section of the page, you can do that with a special `data-turbo`
attribute. For example, to *completely* disabled Turbo drive on your entire site,
head over to `base.html.twig`. Find the `body` tag and add `data-turbo="false"`.

Now, any link clicks or form submits *inside* of this element - which is
everything - will *not* use Turbo drive. Check it out: refresh the page and click
around. We are back to boring full page reloads. Boo.

To reenable Turbo Drive on a link or section, you can set the same attribute to true.
For example, let's activate Drive for *just* the links up in the `navbar`. Find
that element... it's this `ul`, and add `data-turbo="true"`

Refresh again. When we click a category, it still triggers a full page reload. But
if we click to go to the cart... that loaded with Drive! You can use this strategy
to activate Turbo Drive on only some parts of your site that are ready.

Let's remove both of these out to *fully* get Turbo Drive.

Next: we've activated Turbo Drive and gotten the no-page-reload goodness with zero
changes to our Symfony code! That's amazing! But there is *one* tiny change that
we *will* need to make to any pages that have a *form*.
