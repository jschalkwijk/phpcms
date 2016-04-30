// create closure, do research on how this works
(function(){
	var dropzone = document.getElementById('dropzone');
	
	var displayUploads = function(data) {
		var uploads = document.getElementById('uploads'),
		anchor,
		x;
		
		anchor = document.createElement('a');
		anchor.innerText = data;
		uploads.appendChild(anchor);	
	}
	
	var upload = function(files) {
		//form data object, to append to a list of files
		var formData = new FormData(),
		// create request, info via xhtml request
			xhr = new XMLHttpRequest(),
			x;
			
			for(x = 0; x< files.length; x++) {
				formData.append('files[]', files[x]);
				console.log(formData);
			}
			// if request is loaded display uploaded files
			xhr.onload = function() {
				var data = this.responseText;
				displayUploads(data);
			}
			// open php file to handle uploaded files
			xhr.open('post', 'drop-files.php');
			// send array with file names etc.
			xhr.send(formData); 
	}
	
	dropzone.ondrop = function(e) {
		// this will prevent the browser from loading the image on page
		e.preventDefault();
		this.className = 'dropzone';
		// files list info: console.log(e.dataTransfer.files);
		upload(e.dataTransfer.files);
	};
	
	dropzone.ondragover = function() {
		this.className = 'dropzone dragover';
		return false;
	};
	
	dropzone.ondragleave = function() {
		this.className = 'dropzone';
		return false;
	};
}())