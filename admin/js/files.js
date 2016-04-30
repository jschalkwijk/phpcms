var model = {
	getChecked : function(){
		//var checked = document.querySelector('.checkbox:checked').value;
		var getMedia = document.getElementsByClassName('media');
		var downloadLink = document.getElementsByClassName('downloadLink');
		var getChecked = document.getElementsByClassName('checkbox');
		var links = [];
		var checked = [];
		for(var i = 0; i < getMedia.length; i++){
			if(getChecked[i].checked){
				// for test logging
				checked.push(getChecked[i].value);
				links.push(downloadLink[i].href);
				// Just opens the files in a new tab.
				//window.location = downloadLink[i];
				// window.open(downloadLink[i], '_blank');
				// Gives Error: Resource interpreted as Document but transferred with MIME type image/png:
				//window.open(downloadLink[i],'_self');
				
				// creates a mouseclick event but opens only the first of multiple selected files.
				var link = downloadLink[i];
				if(document.createEvent){
					var e = document.createEvent('MouseEvents');
					e.initEvent('click',true,true);
					link.dispatchEvent(e);
					return true;
				}
			}
		}
		console.log(links);
		console.log(checked);
		//view.download(links);
		
	}
}
var view = {
	test : function() {
		console.log('hello');
	},
	download : function(path){
		for(var i = 0; i < path.length; i++){
			file = path[i];
			// These just open the file in a new tab.
			//window.location = file;
			//window.open(path[i], '_blank');
		}
	}
}

var controller = {
	actions : function(){
		var downloadSel= document.getElementById('downloadSel');
		downloadSel.onclick = model.getChecked;
	},
}

window.onload = init;

function init(){
	controller.actions();
}


