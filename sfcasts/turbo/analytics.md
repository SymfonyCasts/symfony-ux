# Fixing External JS + Analytics Code

Coming soon...

Head back to the turbo docs specifically to reference and then events. We saw this list of events earlier. Now we're going to hook into a new event, turbo colon before render. Here it is. Fire is before rendering the page. This event triggers before turbo renders a page, but not counting V initial page refresh. In other words, it renders one turbo is specifically responsible for rendering a page. This is useful because we can, yeah. Is it to help our third party weather widget get working right before the page renders head over to assets, turbo, turbo helper, and up here in the construct method. Good. Let's say document that add event listener to listen to our turbo before render, pass us an error function. And let's just that log before renders. So we can see exactly when this in does and doesn't execute.

Cool. Let's try it

At over. I'll refresh. Go console. Okay. So nothing on initial page load, but then as we click to another page, there it is click to another page. There's a second one. Click to the homepage. There's a third one. Awesome. Clear out the console. And let's go back to a page. We went to a second ago. It logs twice. This is an important detail about this event. It fired twice because first the preview was rendered and then the final page was rendered. Just keep that fact in mind. So here's the plan right before the page is rendered. So inside of our new listener here, we're going to find and remove this weather widget IO, J S script tech. Then with any luck when the new page is rendered, the JavaScript from our base template, we'll execute and it will re add that script tag and everything will work.

Let's try it. Revise that counts that log with document dot query selector. And we are going to look for pound sign weather dash weather widget, dash IO, dash J S. And then we'll say remove. And if you want, so you can code defensively in case that element isn't there. That's up to you. All right. Let's refresh. And it works navigate to a different page. And yes, it still works. If you look in the head element here, there's only one of those script tags works without duplicating the script act. So this is cool, but if you want to do some digging, there is an alternate solution. I'm going to copy this URL to widget I'm in digest and open it in my browser. I'm going to copy the source code here, close this, and let's go over to our project and just create a file anywhere like pizza dot JS. We're not gonna actually gonna use this. I'm going to paste that there, then actually select it again and go to code reformat code.

So we can at least kind of read it. It's still not super clear, but well, let's see, ah, there's a function called weather widget, a knit, and it looks like maybe that is the key to re initializing the weather widget. In other words, instead of removing and re adding the script tag on each render, we might be able to just call this function. First changed the event from a turbo before render to turbo render. That is one other event here, and we need to use that because in order for this weather widget and knit function to work, the anchor tag and that's brought in when the new page load needs to be on the page.

The turbo before render is triggered before the new body is on the page. And turbo render is called after it's on the page. In other words, inside of this, we know that the new body is going to be on the page so we can call it that weather widget and knit function, which let me steal that from the other file. And there we go. Let's try it over. Okay. Refresh the first page loads. That's no surprise. That's just the normal functionality. Now, when we go to a second page, as it still works, no matter how many pages do we go to, it keeps working. I like this solution better though. I also realize that we're sort of using an internal function here, which, and it's possible that they might change the name of his function in

The future. Anyways,

Let's refactor this, uh, logic into a method for clarity. So very soon, I'm going to copy this weather widget align here, go to the bottom of my class and let's create a method. Call initialize why the widget I'll paste. Call that from up here in our listener Vista initialize weather widget.

Cool,

By the way, there is a third way to solve this problem. And we'll talk about it later. It's needed. If it's appropriate, if you need to load an external widget like our weather widget, but that widget might be loaded onto the page. At any time, even via custom, a custom Ajax call non turbo Ajax call it basically involves running the same code that we have here, but leveraging a

Stimulus controller

Before we move on, we do need to talk about one more type of external JavaScript analytics code. As an example, here's what Google analytics code looks like. Here's what you're supposed to paste into the head tag of your page. It turns out the key thing that actually triggers the visit visit is this last G tag config line here. If we paste it all of this onto our site, guess what would happen? It would register the first visit. Then this code would never execute again, no matter how many pages the user visited, that's not great. Fortunately, single page applications like those written and view or react have the same problem. And often you can find docs that talk about how to integrate with those. In this case, the solution would be to paste all this code, except for the G-Tech config line into your head, like, like normal for this last line.

This we want to execute this on initial page load. And then every visit afterwards, I'll pull up a, you know, a blink that talks about this with a really nice solution. As you can see here, Henrik is using a turbo colon load event, which is yet another event that we haven't talked about yet. Turbo load is nice because it's executed on the initial page load. And one time on every visit, it avoids the double dispatch that happens with turbo render. When you visit a page that shows a preview. In other words, it triggers exactly when you would want, uh, analytics code to trigger a visit. And so inside of it, it calls G-Tech and

Fig this

Google analytics ID for script is actually his way of just basically, uh, putting in the, whatever your custom, uh, Google, uh, measurement ID is. And then the one special thing is that you do need to pass a little bit of extra data to help Google analytics know what the actual URL is that it should use.

What do you pop this in? It will work

Next. We already know that with turbo drive, we download the CSS and JavaScript under a page. Just one time. Then as we navigate around, if turbo sees a file in the new head tag that already exists in the existing ad tag, it ignores it. But what happens if we deploy a new version of our sites and the contents of these files changes, how will we force the user to download the newest version of our assets? That's an important question. And one where the answer is so refreshingly simple. Okay.

