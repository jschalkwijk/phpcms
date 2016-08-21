<?php
// This are the  update,delete and approve functions which regards post,pages, users and products.
// these will only remove rows, not files etc.
// Import the UserActions trait inside the controller and enter the database name insid ethe function
// to alter the DB rows.  UserActions handles the Post requests and calls these functions
class Actions_RUDActions{

	public static function trash_selected($dbt,$checkbox){
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$multiple = implode(",",$checkbox);
		$query = "UPDATE ".$dbt." SET trashed = 1,approved = 0 WHERE ".$id_row." IN({$multiple})";
		mysqli_query ($dbc->connect(),$query) or die('error connecting because of the id name, for contacts it\'s contact_id, fox it!');
		$dbc->disconnect();
		header('Location: '.ADMIN.$dbt);
	}
	public static function delete_selected($dbt,$checkbox){
		$db = new DBC;
		$dbc = $db->connect();

		$id_row = substr($dbt, 0, -1).'_id';
		$multiple = implode(",",$checkbox);
		$flag = false;
		$messages = [];

		if($dbt === "pages"){
			$deleted = content_Pages_Page::deletePage($multiple);
			$flag = $deleted['flag'];
			if(isset($deleted['message']) || $deleted['error']) { $messages[] = $deleted['message']; }
		} else {
			$flag = true;
		}

		if($flag) {
			$query = $dbc->query("DELETE FROM " . $dbt . " WHERE " . $id_row . " IN({$multiple})");
			$dbc->close();

		}
		// ALLEEN ALS ER ERRORS ZIJN MOET IK DIE RETURNEN. ANDERS MOET IK DE HEADER LOCATION DOEN,
		// ANDERS WORDT DE PAGINA NIET VERVERST :-)
		if(!empty($messages)) {
			return ['messages' => $messages];
		} else {
			header('Location: ' . ADMIN . $dbt . '/deleted-' . $dbt);
		}
	}
	public static function approve_selected($dbt,$checkbox){
		$db = new DBC;
		$dbc = $db->connect();
		$id_row = substr($dbt, 0, -1).'_id';
		$multiple = implode(",",$checkbox);
		$query = $dbc->query("UPDATE ".$dbt." SET approved = 1 WHERE ".$id_row." IN({$multiple})");
		$dbc->close();
		header('Location: '.ADMIN.$dbt);
	}
	public static function restore_selected($dbt,$checkbox){
		$db = new DBC;
		$dbc = $db->connect();

		$id_row = substr($dbt, 0, -1).'_id';
		$multiple = implode(",",$checkbox);
		$query = $dbc->query("UPDATE ".$dbt." SET trashed = 0 WHERE ".$id_row." IN({$multiple})");
		$dbc->close();

		header('Location: '.ADMIN.$dbt.'/deleted-'.$dbt);
	}
	public static function hide_selected($dbt,$checkbox){
		// Approve the score by setting the approved column in the database
		$db = new DBC;
		$dbc = $db->connect();

		$id_row = substr($dbt, 0, -1).'_id';
		$multiple = implode(",",$checkbox);
		$query = $dbc->query("UPDATE ".$dbt."  SET approved = 0 WHERE ".$id_row." IN({$multiple})");
		$dbc->close();
		header('Location: '.ADMIN.$dbt);
	}

	/*// used by Main:Content, Sub(Post,Page),Main: User.
	public static function trash($dbt,$id,$name){
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "UPDATE ".$dbt." SET trashed = 1,approved = 0 WHERE ".$id_row." = $id";
		mysqli_query($dbc->connect(),$query) or die('error connecting');
		$dbc->disconnect();
		}
		//header('Location: '.ADMIN.$dbt.'/info/'.$id.'/'.$name);

	public static function restore($dbt,$id,$name){
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "UPDATE ".$dbt." SET trashed = 0 WHERE ".$id_row." = $id";
		mysqli_query($dbc->connect(),$query) or die('error connecting');
		$dbc->disconnect();
		header('Location: '.ADMIN.$dbt.'/info/'.$id.'/'.$name);
	}

	public static function approve($dbt,$id,$name){

		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "UPDATE ".$dbt." SET approved = 1 WHERE ".$id_row." = $id";
		mysqli_query($dbc->connect(),$query) or die('error connecting');
		$dbc->disconnect();
		header('Location: '.ADMIN.$dbt.'/info/'.$id.'/'.$name);
	}
	public static function hide($dbt,$id,$name){

		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "UPDATE ".$dbt."  SET approved = 0 WHERE ".$id_row." = $id";
		mysqli_query($dbc,$query) or die('error connecting');
		$dbc->disconnect();
		header('Location: '.ADMIN.$dbt.'/info/'.$id.'/'.$name);
	}
	public static function delete($dbt,$id,$name){
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query1 = "SELECT url FROM pages WHERE ".$id_row." = $id LIMIT 1";
		$data = mysqli_query($dbc->connect(),$query1) or die('error connecting');
		$row = mysqli_fetch_array($data);
		if(unlink('../'.$row['path'])){
			echo 'Page deleted from server.';
		} else {
			echo 'Oops, something went wrong, the page is not deleted from the server.';
		}
		// Delete the data record permanently!
		$query2 = "DELETE FROM ".$dbt." WHERE ".$id_row." = $id LIMIT 1";
		mysqli_query($dbc->connect(),$query2);
		$dbc->disconnect();
		header('Location: '.ADMIN.$dbt.'/info/'.$id.'/'.$name);
	}*/
}
?>