# Reloading When JS/CSS Changes

Coming soon...

How does turbo handle when a JavaScript or CSS file that's downloaded onto our page changes.

When we navigate it's

Smart enough to merge any new CSS or jazz into our head element, but not duplicate something that's already there.

But what about

CSS or JavaScript file was just updated because we deployed, this is really a problem specific to production because locally, if we change the CSS or JS file over and our editor, we just come back and trigger a full page reload. But how was this handled on production? Well, if you do nothing, it's pretty simple. Your users will continue to surf around with the old CSS and Java script, which is not something we want, especially since they will be getting the newest HTML from our site, which may only work with the newest CSS and JavaScript, but a slightly different thing happens if we enable versioning on our assets, had I read your editor and open up a web pack that can figure that JS what halfway down this file you'll find here it is enable versioning.

This tells Encore that if we are in a production build, then each file name should contain a hash that's unique to its contents. It's a great strategy to make sure that when you deploy updates, each file gets a new file name, which forces users in non turbo land to download the latest version, to see what happens with turbo. Let's activate this for dev builds also by just removing this argument, let's make this take effect, find your terminal, go over, okay. Find your terminal, go over to your tab with, hit control C and then rerun. Yarn watch wants that finishes move over refresh, and you can kind of navigate around important thing. If you check out the head tag is that app that now app, that [inaudible] blah, blah, blah, that CSS and app that a different hash dot JS. So let's go modify the app dot JS file that's over at assets slash app dot JS, and we will just console dot log new code now without refreshing the page navigate to a new page. And if you look at the console, Hmm, interesting. I didn't see any log there. And we have two app digests on the page. That is probably not what we want. First of all, it wasn't executed at twice because civilly, because Webpack was smart enough to realize that the app script was already loaded. In other words, our new JavaScript doesn't load at all.

And even if it did load, it would probably mean that we would have things like event listeners registered twice on the page, which is also not what we want. Yeah. Well, what we're seeing in the head tag does make sense based on what we know about turbo, because the app dot JS has a new file name. It looks like a new script file. And so turbo added it to the head. So how do we fix this situation? Well, let's think one of the huge benefits of turbo is that your JavaScript and CSS are downloaded and executed just once on initial page load and then a reused for every navigation after. It's a big reason why turbo is so fast, but if one of these files changes, we sort of do need to hit the reset button. In other words, this is the one rare case when the page should refresh, so it can download everything new.

Fortunately, there's an easy way to do this by adding a special data dash turbo dash track attribute to every CSS and JS tech. First, let's add that everywhere. And this can be done really easily inside of config packages, Webpack Encore, dot Yammel, or as a script attribute attributes thought, which lets us add an attribute to every script tag that Encore outputs. So I'm going to say data dash turbo dash track and set this to reload. We'll talk about what this does in the second. Also uncommon out link attributes and we'll want the same thing there. Yeah. Now with that simple change, every script and link tag that Encore outputs is going to have that data turbo track equals reload on it. So here's how this works. It's pretty simple. When we navigate to a new page, turbo finds all of the elements with data turbo track and compares their file names

To the data

Turbo track elements on the new page. If the total collection of file names on the old page and the new page with this element are not identical. Don't match. Turbo will trigger a full page. Reload watch. If we click around now, we just see a lot of boring turbo powered, uh, uh, nah, navigations visits, but now let's go back into our assets app digests and remove that console dot log behind the scenes. A new app dot JS file with a new file name was just output. So you can see it here. It was this before, and it's this now. So if we go over and visit a new page, watch carefully.

Yeah. See it.

Full page reload turbo saw that the new pages that tracked file names did not match the old pages tracked file names and it reloaded did a full page. Reload problem solved. Next. Sometimes you may want to change the page via to navigate to another page, to be a Java script. Like after an Ajax call, sometimes you need to redirect to another URL could be used turbo to do, to visit instead of doing a full page reload. Absolutely. Right.

