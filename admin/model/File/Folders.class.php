<?php
namespace Jorn\admin\model\File;

use Jorn\admin\model\DBC\DBC;
use Jorn\admin\model\Actions\FileActions;
/*
 *
 * IMPORTANT
 *
 * als ik in een user of product foto's moet uploaden kan ik toch ook in de user table een
 * folder_id row aanmaken, zodat ik meteen het pad van de gelinkte folder kan fetchen.
 *
 *
 * */
class Folders {
	# Create a construct becaus we can then separate the html from the model.
	use FileActions;

	/* used by view/add-files.php to get the selected folder and optional folders to
	 * upload files to.
	 * Main folders don't have a parent folder so the parent_id = 0.
	 * If the album_id != empty we search for it's child folders and put them in the form to select.
	 * */
	public static function get_albums($album_id,$album_name) {
		$db = new DBC;
		$dbc = $db->connect();

		($album_id != null) ? $id = mysqli_real_escape_string($dbc,trim((int)$album_id)) : $id = 0;
		$album_query = $dbc->prepare("SELECT album_id,name FROM albums WHERE parent_id = ? OR album_id = ?");
		if($album_query) {
			$album_query->bind_param("ii", $id, $id);
			$album_query->execute();
			$albums_data = $album_query->get_result();
			$album_query->close();
		} else {
			$db->sqlERROR();
		}
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
		$dbc->close();
	}

	// Deletes the albums selected. Makes use of the removeDir and removeRows Traits.
	public static function delete_album($checkbox){
		$db = new DBC;
		$dbc = $db->connect();

		// get values from the checkboxes, these are the ID's of the Albums or subfolders.
		$multiple = implode(",",$checkbox);
		$query = $dbc->query("SELECT album_id,path,name FROM albums WHERE album_id IN({$multiple})");
		if(!$query){ $db->sqlERROR(); };
		// It's as easy as this:

		while ($row = $query->fetch_array()) {
			// recursive deleting function. Deletes al folders/files and subfolders/files from server.
			Folders::removeDir('./././files/'.$row['path']);
			Folders::removeDir('./././files/'.'thumbs/'.$row['path']);
			Folders::removeRows($row['album_id']);
		}
		$query->close();
		$dbc->close();
	}

	/*
	 * Used in view/albums.php to display the folders inside of the parent folder that is currently
	 * viewed by the user.
	 * Selected folders created by the user itself. So NO folders will be displayed that you haven't
	 * created yourself.
	*/
	public static function show_albums($album_id){
		$db = new DBC;
		$dbc = $db->connect();
// Uncomment the below comments and comment the current if statement to only show folders the currentuser created. Update this
// so the user can specify if the folder is personal or had to be used system wide for every user.
		$user_id = $_SESSION['user_id'];
//		if(!empty($album_id)){
//			$query = "SELECT album_id,name FROM albums WHERE parent_id = $album_id AND user_id = $user_id";
		if (empty($album_id)) { $album_id = 0; };
			$query = $dbc->prepare("SELECT album_id,name FROM albums WHERE parent_id = ?");
			if($query) {
				$query->bind_param("i", $album_id);
				$query->execute();
				$data = $query->get_result();
				$query->close();
			} else {
				$db->sqlERROR();
			}
			//start form
			echo '<form id="files-form" method="get" action="/admin/file">';
//		} else {
//			$query = $dbc->prepare("SELECT * FROM albums WHERE parent_id = 0 AND user_id = ?");
//			$query->bind_param("i",$user_id);
//			$query->execute();
//			$data = $query->get_result();
//			$query->close();
//			// start form
//			echo '<form method="get" action="'.$_SERVER["REQUEST_URI"].'" file">';
//		}
		while($row = $data->fetch_array()){
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
		$dbc->close();
	}
	
	/*
	 *	we use this function in a slightly different way then in the file_upload class because we cant
	 *	make use of the GET params here since we are not in the main folder structure but in the product creation.
	*/
	public static function auto_create_folder($album_name,$file_dest,$thumb_dest,$main_folder,$category_name = null) {
		$db = new DBC;
		$dbc = $db->connect();

		$author = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		
		/*
		 *	if there is no category set, we don't pass the categories id and use the default products folder.
		 *	for insurance we have to check its id and use that as the parent folder.
		 *	if it is set, it means we have created a category already and the folders parent should be the category folder.
		*/
		
		if($category_name === null){
			$query = $dbc->prepare("SELECT album_id FROM albums WHERE name = ?");
			if($query) {
				$query->bind_param("s", $main_folder);
				$query->execute();
				$data = $query->get_result();
				$query->close();
				$row = $data->fetch_array();
				$parent_id = $row['album_id'];
			} else {
				$db->sqlERROR();
			}
		} else {
			$sql = "SELECT album_id FROM albums WHERE name = ?";
			echo $sql.'<br />';
			$query = $dbc->prepare($sql);
			if($query) {
				$query->bind_param("s", $category_name);
				$query->execute();
				$data = $query->get_result();
				$query->close();
				$row = $data->fetch_array();
				$parent_id = $row['album_id'];
			} else {
				$db->sqlERROR();
			}
		}

		$path = Folders::auto_create_path($album_name,$parent_id);
	
		if(!file_exists($file_dest)){
			$sql = "INSERT INTO albums(name,author,parent_id,path,user_id) VALUES(?,?,?,?,?)";
			echo 'Create album: '.$sql.'<br />';
			$query = $dbc->prepare($sql);
			if($query){
				$query->bind_param("ssisi",$album_name,$author,$parent_id,$path,$user_id);
				$query->execute();
				$query->close();
				mkdir($file_dest,0744);
				mkdir($thumb_dest,0744);
			} else {
				$db->sqlERROR();
			}
		}
		
		$query = $dbc->prepare("SELECT album_id FROM albums WHERE name = ?");
		if($query) {
			$query->bind_param("s", $album_name);
			$query->execute();
			$data = $query->get_result();
			$query->close();
			$row = $data->fetch_array();
			$album_id = $row['album_id'];
		} else {
			$db->sqlERROR();
		}

		$dbc->close();
		return $album_id;
	}
	
	// we use this function in a slightly different way then in the file_upload class because we cant
	// make use of the GET params here since we are not in the main folder structure but in the product/user creation.
	public static function auto_create_path($album_name,$parent_id){
		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->prepare("SELECT path FROM albums WHERE album_id = ?");
		if($query){
			$query->bind_param("i",$parent_id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
			$row = $data->fetch_array();
			if($row['path'] === $album_name){
				$path = $album_name;
			} else {
				$path = $row['path'].'/'.$album_name;
			}
			return $path;
		} else {
			$db->sqlERROR();
			return false;
		}
	}
//
}
?>