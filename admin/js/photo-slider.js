/* versie 0.9.0*/
window.onload = init;
function init() {
	view.createSlider();
	controller.dashboardHandler();
}
/* Model */

var model = {
	sec: 2,
	loop: false,
	folder: 'images' +'/',
	imagePath: document.getElementsByClassName('image_link'),
	next: 'next.png',
	previous: 'previous.png',
	play: 'play.png',
	pause:'pause.png',
	download: 'download.png',
	fullScreen: 'fullscreen.png',
	num: document.getElementsByClassName('image_link').length - 1, // the -1 is needed because the array of the images starts at 0, and not 1.
};
/* View */

var view = {
	next: model.folder + model.next,			 	
	previous: model.folder + model.previous,
	play: model.folder + model.play,
	pause: model.folder  + model.pause,
	download: model.folder + model.download,
	fullScreen: model.folder + model.fullScreen,
	hideImages: function(id) { 
			id.className = "hidden";
	},
	showImages: function(id) {
		id.className = "#";
	},
	changeImage: function() {
			if (controller.i != model.num) {
				if (next.onclick || play.onclick || (event.keyCode == 39)) {
				controller.i++;
				console.log(controller.i);
				} else if (previous.onclick || (event.keyCode == 37)) {
				controller.i--;
				console.log(controller.i);
				}
				img.src = model.imagePath[controller.i];
			} else {
				controller.i = 0;
				img.src= model.imagePath[0].href;
			}
	},
	rotateImage: function() {
			interval = setInterval(view.changeImage, model.sec * 1000);
			return interval;
	},
	pauseRotate: function() {
			clearInterval(interval);
			img.src = model.imagePath[controller.i];
	},
	showDashboard: function() {
			next.setAttribute('src', view.next);
			view.showImages(next);
			previous.setAttribute('src', view.previous);
			view.showImages(previous);
			play.setAttribute('src', view.play);
			view.showImages(play);	
			pause.setAttribute('src', view.pause);
			view.showImages(pause)
			download.setAttribute('src', view.download);
			view.showImages(download);
			fullScreen.setAttribute('src', view.fullScreen);
			view.showImages(fullScreen);
	},
	hideDashboard: function(){
			view.hideImages(next);
			view.hideImages(previous);
			view.hideImages(play);
			view.hideImages(pause);
			view.hideImages(download);
			view.hideImages(fullScreen);			
	},
	createSlider: function(){
		//Creates the menu div with the image 
		var frame = document.createElement("div");
		frame.class = 'frame';
		var slider = document.createElement("div");
		frame.class = 'slider';
		var dashboard = document.createElement("div");
		frame.class = 'dashboard';
		var controls = document.createElement("div");
		frame.class = 'controls';
		var img = document.createElement("img");
		img.class= "img"; 	
		frame.appendChild(slider,dashboard);
		slider.appendChild(img);
		dashboard.appendChild(controls);
		}
};
/* Controller  */

var controller = {
	i: 0,
	dashboardHandler: function() { 
		/* Next Onclick */									
		next.onclick = view.changeImage;		
		/* Previous Onclick */	
		previous.onclick = view.changeImage;		
		/* Play Onclick */	
		play.addEventListener('click', function(event) {
			if (model.loop == false) {
				view.rotateImage();
				model.loop = true;
				} else {
					view.pauseRotate();
					model.loop = false;
				}	
			});
		
		/* Pause Onclick */	
		pause.onclick = view.pauseRotate;
		/* Download Onclick */	
		download.onclick = function() {
			downloadLink.href = model.imagePath[controller.i].href;
		}
		/* Dashboard Onmouseover */	
		dashboard.onmouseover = view.showDashboard;		
		/* Dashboard Onmouseout */			
		dashboard.onmouseout = view.hideDashboard;
		// Event listener for the full-screen button
		fullScreen.onclick = function() {
			var img = document.getElementById("img");
			if (img.requestFullscreen) {
				img.requestFullscreen();
			} else if (img.mozRequestFullScreen) {
				img.mozRequestFullScreen(); // Firefox
			} else if (img.webkitRequestFullscreen) {
				img.webkitRequestFullscreen(); // Chrome and Safari
			}
		}
		// Event listener for the prev/next keypresses
		document.addEventListener('keydown', function(event) {
		 if (event.keyCode == 37) {
			 view.changeImage();
			 } else if (event.keyCode == 39) {
				 view.changeImage();
			} else if (event.keyCode == 32) {
				if (model.loop == false) {
					view.rotateImage();
					model.loop = true;
				} else {
					view.pauseRotate();
					model.loop = false;
				}
			}	
			});
	},
};

function toggleOverlay(){
	var overlay = document.getElementById('overlay');
	var specialBox = document.getElementById('specialBox');
	overlay.style.opacity = .8;
	if(overlay.style.display == "block"){
		overlay.style.display = "none";
		specialBox.style.display = "none";
	} else {
		overlay.style.display = "block";
		specialBox.style.display = "block";
	}
}
