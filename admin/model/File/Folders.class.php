<?php

/*
 *
 * IMPORTANT
 *
 * als ik in een user of product foto's moet uploaden kan ik toch ook in de user table een
 * folder_id row aanmaken, zodat ik meteen het pad van de gelinkte folder kan fetchen.
 *
 *
 * */
class File_Folders {
	# Create a construct becaus we can then separate the html from the model.
	use File_FileActions;

	/* used by view/add-files.php to get the selected folder and optional folders to
	 * upload files to.
	 * Main folders don't have a parent folder so the parent_id = 0.
	 * If the album_id != empty we search for it's child folders and put them in the form to select.
	 * */
	public static function get_albums($album_id,$album_name) {
		$dbc = new DBC;
		($album_id != null) ? $id = mysqli_real_escape_string($dbc->connect(),trim((int)$album_id)) : $id = 0;
		$album_query = "SELECT album_id,name FROM albums WHERE parent_id = $id OR album_id = $id";
		$albums_data = mysqli_query($dbc->connect(),$album_query) or die("Error connecting to database");
		echo '<select id="albums" name="album_id">';
		if(!isset($album_name)){
			echo '<option value="0">None</option>';
		}
		while ($row = mysqli_fetch_array($albums_data)) {
			echo '<option value="' . $row['album_id'] . '">' . $row['name'] . '</option>';
		}

		// Join to select all products with matching album_id's
		//$album_query = "SELECT album_id FROM products WHERE product_id = $id ";
//		$album_query = "SELECT products.*, albums.name as name FROM products LEFT JOIN albums ON products.album_id = albums.album_id WHERE albums.album_id = $id";
//		echo $album_query;
//		$albums_data = mysqli_query($dbc->connect(),$album_query) or die("Error connecting to database");
//		echo '<select id="albums" name="album_name">';
//		if(!isset($album_name)){
//			echo '<option name="none" value="None">None</option>';
//		} else {
//			while($row = mysqli_fetch_array($albums_data)) {
//				echo '<option value="'.$row['album_id'].'">'.$row['name'].'</option>';
//			}
//		}

		echo '</select>';
		echo '<label for="select">Albums</label>';
		$dbc->disconnect();
	}

	// Deletes the albums selected. Makes use of the removeDir and removeRows Traits.
	public static function delete_album($checkbox){
		$dbc = new DBC;
		// get values from the checkboxes, these are the ID's of the Albums or subfolders.
		$multiple = implode(",",$checkbox);
		$query = "SELECT album_id,path,name FROM albums WHERE album_id IN({$multiple})";
		// It's as easy as this:
		$data = mysqli_query($dbc->connect(), $query) or die('error connecting to database');
		foreach ($data as $delete) {
			// recursive deleting function. Deletes al folders/files and subfolders/files from server.
			File_Folders::removeDir('./././files/'.$delete['path']);
			File_Folders::removeDir('./././files/'.'thumbs/'.$delete['path']);
		}
		foreach ($data as $row){
			$id = $row['album_id'];
			File_Folders::removeRows($id);
		}
		$dbc->disconnect();
	}

	/*
	 * Used in view/albums.php to display the folders inside of the parent folder that is currently
	 * viewed by the user.
	 * Selected folders created by the user itself. So NO folders will be displayed that you haven't
	 * created yourself.
	*/
	public static function show_albums($album_id){
		$dbc = new DBC;
		$user_id = $_SESSION['user_id'];
		if(!empty($album_id)){
//			$query = "SELECT album_id,name FROM albums WHERE parent_id = $album_id AND user_id = $user_id";
			$query = "SELECT album_id,name FROM albums WHERE parent_id = $album_id";
			$data = mysqli_query($dbc->connect(),$query);
			//start form
			echo '<form id="files-form" method="get" action="/admin/file">';
		} else {
			$query = "SELECT * FROM albums WHERE parent_id = 0 AND user_id = $user_id";
			$data = mysqli_query($dbc->connect(),$query);
			// start form
			echo '<form method="get" action="'.ADMIN.'"file">';

		}
		while($row = mysqli_fetch_array($data)){
			echo '<div class="media">';
			echo '<div class="meta">';
						echo '<input class="checkbox left" type="checkbox" name="checkbox[]" value="'.$row['album_id'].'"/>';
						echo '<div class="left"><a href="'.ADMIN.'file/albums/'.$row['album_id'].'/'.$row['name'].'""> '.$row['name'].'</a></div>';
			echo '</div>';
			echo '<div class="center"><img class="files" src="'.ADMIN.'images/files.png"/></div>';
			echo '<input type="hidden" name="album_name" value="'.$row['name'].'"/>';
			echo '</div>';
		}
		echo '<button type="submit" name="delete-albums" id="delete-albums">Delete Albums</button>';
		echo '</form>';
		$dbc->disconnect();
	}
	
	/*
	 *	we use this function in a slightly different way then in the file_upload class because we cant
	 *	make use of the GET params here since we are not in the main folder structure but in the product creation.
	*/
	public static function auto_create_folder($album_name,$file_dest,$thumb_dest,$main_folder,$category_name = null) {
		$dbc = new DBC;
		$author = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		
		/*
		 *	if there is no category set, we don't pass the categories id and use the default products folder.
		 *	for insurance we have to check its id and use that as the parent folder.
		 *	if it is set, it means we have created a category already and the folders parent should be the category folder.
		*/
		
		if($category_name === null){
			$query = "SELECT album_id FROM albums WHERE name = '$main_folder'";
			$data = mysqli_query($dbc->connect(), $query) or die('Error connecting to database');
			$row = mysqli_fetch_assoc($data);
			$parent_id = $row['album_id'];
		} else {
			$query = "SELECT album_id FROM albums WHERE name = '$category_name'";
			echo $query.'<br />';
			$data = mysqli_query($dbc->connect(), $query) or die('Error connecting to database');
			$row = mysqli_fetch_assoc($data);
			$parent_id = $row['album_id'];
		}

		
		$path = File_Folders::auto_create_path($album_name,$parent_id);
	
		if(!file_exists($file_dest)){
			$query = "INSERT INTO albums(name,author,parent_id,path,user_id) VALUES('$album_name','$author',$parent_id,'$path',$user_id)";
			echo 'Create album: '.$query.'<br />';
			mysqli_query($dbc->connect(),$query) or die('Error connecting to database product folder');
			mkdir($file_dest,0744);
			mkdir($thumb_dest,0744);
		}		
		
		mysqli_close($dbc->connect());
		
		$query = "SELECT album_id FROM albums WHERE name = '$album_name'";
		$data = mysqli_query($dbc->connect(), $query);
		$row = mysqli_fetch_array($data);
		$album_id = $row['album_id'];
				
		return $album_id;
	}
	
	// we use this function in a slightly different way then in the file_upload class because we cant
	// make use of the GET params here since we are not in the main folder structure but in the product/user creation.
	public static function auto_create_path($album_name,$parent_id){
		$dbc = new DBC;
		$query = "SELECT path FROM albums WHERE album_id = $parent_id";
		$data = mysqli_query($dbc->connect(),$query);
		$row = mysqli_fetch_array($data);
		if($row['path'] === $album_name){
			$path = $album_name;
		} else {
			$path = $row['path'].'/'.$album_name;
		}
		return $path;
	}
//
}
?>