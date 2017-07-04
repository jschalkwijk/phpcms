<?php
namespace CMS\Models\Actions;

use \CMS\Models\DBC\DBC;
use CMS\Models\Files\Folder;

// Usage: Import UserActions into your controller. It takes a DB table name.
// Depending on which post request is past to the controller the appropiate function will
// be called from RUDactions. this will only be removing rows. Not files on the server.
// That is handled by other functions inside the File classes.
trait UserActions {
	private $id;
	private $name;
	private $checkbox;

	public function UserActions($model) {
		(isset($_POST['checkbox'])) ? $this->checkbox = $_POST['checkbox'] : '';

		if (isset($_POST['trash-selected'])) {
			Actions::trash_selected($model,$this->checkbox);
		}
		if (isset($_POST['approve-selected'])) {
			Actions::approve_selected($model,$this->checkbox);
		}
		if (isset($_POST['restore-selected'])) {
			Actions::restore_selected($model,$this->checkbox);
		}
		if (isset($_POST['hide-selected'])) {
			Actions::hide_selected($model,$this->checkbox);
		}
		if (isset($_POST['delete-selected'])) {
			$delete = Actions::delete_selected($model,$this->checkbox);
			return $delete;
		}
//
//		$db = new DBC;
//		$dbc = $db->connect();
//
//		(isset($_POST['id'])) ? $this->id = mysqli_real_escape_string($dbc,trim((int)$_POST['id'])) : '';
//		(isset($_POST['name'])) ? $this->name = mysqli_real_escape_string($dbc,trim($_POST['name'])) : '';

//
//		if (isset($_POST['remove'])) {
//			$remove = Actions::trash($dbt,$this->id,$this->name);
//		}
//		if (isset($_POST['approve'])) {
//			$approve = Actions::approve($dbt,$this->id,$this->name);
//		}
//		if (isset($_POST['hide'])) {
//			$hide = Actions::hide($dbt,$this->id,$this->name);
//		}
//		if (isset($_POST['restore'])) {
//			$restore = Actions::restore($dbt,$this->id,$this->name);
//		}
//		if (isset($_POST['delete'])) {
//			$delete = Actions::delete($dbt,$this->id,$this->name);
//		}

	}

}

?>