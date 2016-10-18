<?php
namespace Jorn\admin\model\Users;

class UserWriter{
	// When the Objects are created and added to the array and is returned to the cariable that holds the object
	// the writer takes that object and writes out each Post object.
	// With a foreach looping over every object we can access its methods and add the content to variables.
	// Before and after the for each we provide the opening and closing html table tags.
	// inside the foreach for every post the content_table template is used to display the rows.
	static public function write(Array $users){
		foreach($users as $single){
			// for each object in the array, assign the vars so the view can handle them
			// to create a single row in the table for each object:
			$trashed = $single->getTrashed();
			$id = $single->getID();
			$username = $single->getUserName();
			$rights = $single->getRights();
			$approved = $single->getApproved();
			$dbt = $single->getDbt();
			require('view/user_table.php');
		}
	}
}
?>