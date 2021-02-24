# Search Preview

Coming soon...

One thing we haven't done yet in stimulus is handle Ajax. Let's do that by enhancing
the search on this page, the search here does work. It's a completely normal form
that submits via a `GET` request that we then read in the controller 
`src/Controller/ProductController` to filter the product list. Now our UX team wants to make it
fancier as the user types. We will show the user a quick results drop down below the
search box. Kind of like an auto complete let's get to work, start by creating the
stimulus controller. So an `assets/controllers/` let's create a new file called how about
`search-preview_controller.js`. Okay. It almost starts in a normal way. 
`import { Controller } from 'stimulus'` And `export default class extends Controller`.

Yeah.

And as you know, I like to have a connect method. I just started the `connect()` method
where I just `console.log()`. Something like connected. So I can know that this is
all working the template for the homepage lives in `templates/product/index.html.twig`
And if you scroll down a little bit perfect, here is our
search form. So let's think we could add the `data-controller` attribute directly
to the `<input>`. After all we need to risk. We need to do something when that input
changes, but we're also going to need a place to put, to put the new quick results
HTML. So let's add a controller on the `<div>` around the input. So I'll break this onto
multiple lines and then add `{{ stimulus_controller() }}`, the name, and then the
name of our controller `search-preview`. All right, let's check it, move over,
refresh and check the console connected for this feature. Each time the user types,
something in the, into the box, we need to make an Ajax request

To get

That returns, the matching products. That means we need to add a action to the input
so that stimulus calls a method on a controller. Each time the box changes

Do that down here with our actions and tax

`data-action=""` the name of our controller `search-preview#`. And
then the method to call I'll say `onSearchInput`, I'm calling it on search input
because the default action name for an, for an input element is actually called
input. And it will be called every time. There's a change to the text box. I'll copy
them at the name, then head in the controller and we'll hijack the connect method and
call it `onSearchInput()` with an `event` argument, Hey, let's `console.log()`, that
event to make sure that everything is working. Try that out refresh. And when I type
perfect.

Yeah,

Let's look back at the controller for this `src/Controller/ProductController.php`
the `index()` method.

This

Is the action that's responsible for rendering the homepage that we're currently
looking at. And it also has the logic in it to filter the products and based on a
`q` query parameter that's because if you look on the template, our form actually
submits right back to this URL. It doesn't have an `action=""`, which means it will
submit back to the current URL. You can see that over here, it's submitting back to
the homepage, just with the question `q=` part.

So here's the plan. We're

Going to send the Ajax request from our stimulus controller to the homepage route and
controller, because it already has all of the search logic we need, but we're also
going to add a preview query parameter. We'll use that in here in a minute to either
render the full HTML page like normal or the HTML for just a little search preview
area

Right now. Okay.

The point is that we're going to make the Ajax request to the homepage you are out.
So in our stimulus controller, should we just hard-code that?

We're all here we could, but it's okay.

So super easy to pass that you were out from Symfony into stimulus with a value,
check it out. Let's add a `static values = {}` an object, and let's create one called the
`url`, which is going to be a `string`. And then down here, instead of constantly logging
the event, let's `console.log(this.url)` valid

Over in the,

Over in our template to pass this in, we need to add another attribute to our main
controller element. And we can do that via the second argument to `stimulus_controller`
I'll pass a `url`. That's the name of the value. And then we can use the
normal `path()` function and use the name of our route, which is you check it out
`app_homepage`. So I'll paste that. All right, let's try it. When we go over and
refresh the first thing you can see if we inspect element is that we have the new
`data-search-preview-url-value="/"` . And as I type now beautiful, it is correctly
rendering that we've cracked. They have access to that in our controller Next let's,
let's make the Ajax call and watch the Ajax end point return. JSON, no way HTML, but
not a full page of HTML.

