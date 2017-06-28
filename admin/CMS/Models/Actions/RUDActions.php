<?php
namespace CMS\Models\Actions;

use CMS\Core\FileSystem\FileSystem;
use CMS\Core\Model\Model;
use CMS\Models\DBC\DBC;
use CMS\Models\Files\File;
use CMS\Models\Files\Folder;
use CMS\Models\Pages\Page;
use CMS\Core\Pluralize\Inflect;
// This are the  update,delete and approve functions which regards post,pages, users and products.
// these will only remove rows, not files etc.
// Import the UserActions trait inside the controller and enter the database name insid ethe function
// to alter the DB rows.  UserActions handles the Post requests and calls these functions
class RUDActions{

	public static function trash_selected($model,$checkbox){
		RUDActions::update($model,$checkbox, ["trashed" => 1]);
	}
	public static function approve_selected($model,$checkbox){
		RUDActions::update($model,$checkbox, ["approved" => 1]);
	}
	public static function restore_selected($model,$checkbox){
		RUDActions::update($model,$checkbox, ["trashed" => 0]);
	}
	public static function hide_selected($model,$checkbox){
		RUDActions::update($model,$checkbox, ["approved" => 0]);
	}

	protected static function update(Model $model,$checkbox,$columns){

      	$model->update($columns)->whereIN([$model->primaryKey => $checkbox])->grab();

		header('Location: '.ADMIN.$model->table);
	}
	public static function delete_selected(Model $model,$checkbox){
		$db = new DBC;
		$dbc = $db->connect();


		$primaryKey = Inflect::singularize($model->table).'_id';;

		$multiple = implode(",",$checkbox);
		$flag = false;
		$messages = [];
		
		if($model->table == 'albums'){
			$parents = $checkbox;
			// Delete all folders/files and subdirectories
			foreach($parents as $parent){
				$folder = Folder::one($parent);
				(new FileSystem())->removeDirectory($folder->path);
			}
			Folder::removeRows($parents);
		}

		if($model->table == 'files'){
			$files = File::allWhere(['file_id'=> $checkbox]);
			foreach ($files as $file) {
				$paths[] = $file->path;
				$paths[] = $file->thumb_path;
			};
			(new FileSystem())->delete($paths);

//			$files->delete();
		}
		if($model->table === "pages"){
			$deleted = Page::deletePage($multiple);
			$flag = $deleted['flag'];
			if(isset($deleted['message']) || $deleted['error']) { $messages[] = $deleted['message']; }
		} else {
			$flag = true;
		}

		if($flag) {
            try{
			    $dbc->query("DELETE FROM " . $model->table . " WHERE " . $primaryKey . " IN({$multiple})");
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
            $db->close();
		}
		// ALLEEN ALS ER ERRORS ZIJN MOET IK DIE RETURNEN. ANDERS MOET IK DE HEADER LOCATION DOEN,
		// ANDERS WORDT DE PAGINA NIET VERVERST :-)
		if(!empty($messages)) {
			return ['messages' => $messages];
		} else {
			header('Location: ' . ADMIN . $model->table . '/deleted');
		}
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