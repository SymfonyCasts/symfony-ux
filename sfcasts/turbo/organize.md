# Organizing our Turbo Events Code

Did you turbo drive to work super nicely. We're going to need to hook into a few turbo events like turbo before cash. Before we're done, we're going to hook into even more events to help us properly leverage JavaScript, widgets, add transitions, and do more craziness. When we talk about turbo frames. So instead of putting all that logic right here in app dot JS, let's organize a bit. There's no right or wrong way to do this, but let's create a class that holds all of this special turbo logic in the assets directory create a sub-directory called turbo inside a new file called turbo helper dot JS. Inside here we say constant terminal helper equals a class. I won't give that class a constructor. And the idea is that we can go back over here into app digests, copy all of this code

[inaudible] and paste

And knows when we did that, that important. It'd be modal up here for me. Now at the bottom of this, we'll actually export is export default new turbo helper. So this will instantiate a new instance of her object and export it. It won't really matter for us, but each time we import this module, we'll get the same one instance of this object back and add that JS. I'm going to delete all of this code and then we'll import dot slash turbo. Oops, flash turbo helper. I don't need to set that to any variable, just importing it is going to cause that object to be instantiated. And when that object is instantiated, it will register the event listener. So this should be enough to get it to work. Let's try it a refresh

Quick, remove on an item, go back forward and yep.

Everything still works. Now that we have a class, we can organize this a bit better.

[inaudible]

Copy the modal code here. Remove that, create a new method down here called close modal and paste. Then up here inside of our terminal before cash call back, we'll say this dock close modal. We'll do the same thing for squealer. Copy all of that sweet alert code,

But on here, create a new method called close sweet alert paste in call it up here. Okay.

After that, that does that pose. Well, this dot closed Sweden, that looks better. And if we just go over here real quick, make sure that I didn't mess anything up. I click remove good back forward and yes, it still works. So next let's learn what types of things can go wrong when including a third party, JavaScript, widgets like an analytics with like analytics, JavaScript, or a cute little weather widget.
