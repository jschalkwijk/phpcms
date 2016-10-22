<?php
namespace CMS\model\Content;

class ContentWriter{
	// When the Objects are created and added to the array and is returned to the variable that holds the object
	// the writer takes that object and writes out each Post object.
	// With a foreach looping over every object we can access its methods and add the content to variables.
	// Before and after the for each we provide the opening and closing html table tags.
	// inside the foreach for every post the content_table template is used to display the rows.
	static public function write(Array $data){
		//Open the table that displays content in rows.
		foreach($data as $single){
			// for each object in the array, assign the vars so the view can handle them
			// to create a single row in the table for each object:
			$trashed = $single->getTrashed();
			$id = $single->getID();
			$title = $single->getTitle();
			$category = $single->getCategory();
			$content = $single->getContent();
			$author = $single->getAuthor();
			$date = $single->getDate();
			$approved = $single->getApproved();
			$dbt = $single->getDbt();
			require('view/content_table.php');
		}
	}
}
?>