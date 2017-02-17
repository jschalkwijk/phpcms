<?php
namespace CMS\Models\Files;

// Used by view/albums.php
class FileWriter{
	// When the Objects are created and added to the array and is returned to the cariable that holds the object
	// the writer takes that object and writes out each Post object.
	// With a foreach looping over every object we can access its methods and add the content to variables.
	// inside the foreach for every post the content_table template is used to display the rows.
	static public function write(Array $files,$layout,$doc,$img){
		//Open the table that displays content in rows.
		foreach($files as $single){
			// for each object in the array, assign the vars so the view can handle them
			// to create a single row in the table for each object:
			$id = $single->getID();
			$name = $single->getName();
			$type = $single->getType();
			$file_name = $single->getFileName();
			$album_id = $single->getAlbumID();
			$date = $single->getDate();
			$secured = $single->getSecured();
			$path = $single->getPath();
			$thumb_path = $single->getThumbPath();
			require($layout);
		}
		//controller file: handles approve/remove/edit/delete actions.
	}
}
?>