<?php
namespace CMS\Models\Actions;

use \CMS\Models\DBC\DBC;
// Usage: Import UserActions into your controller. It takes a DB table name.
// Depending on which post request is past to the controller the appropiate function will
// be called from RUDactions. this will only be removing rows. Not files on the server.
// That is handled by other functions inside the File classes.
trait UserActions {
	private $id;
	private $name;
	private $checkbox;
	
	private function UserActions($model) {
		$db = new DBC;
		$dbc = $db->connect();
		
		(isset($_POST['id'])) ? $this->id = mysqli_real_escape_string($dbc,trim((int)$_POST['id'])) : '';
		(isset($_POST['name'])) ? $this->name = mysqli_real_escape_string($dbc,trim($_POST['name'])) : '';
		(isset($_POST['checkbox'])) ? $this->checkbox = $_POST['checkbox'] : '';
		
		if (isset($_POST['remove'])) {
			$remove = RUDActions::trash($dbt,$this->id,$this->name);
		}
		if (isset($_POST['approve'])) {
			$approve = RUDActions::approve($dbt,$this->id,$this->name);
		}
		if (isset($_POST['hide'])) {	
			$hide = RUDActions::hide($dbt,$this->id,$this->name);
		}
		if (isset($_POST['restore'])) {
			$restore = RUDActions::restore($dbt,$this->id,$this->name);
		} 
		if (isset($_POST['delete'])) {
			$delete = RUDActions::delete($dbt,$this->id,$this->name);

		}
		if (isset($_POST['trash-selected'])) {
			RUDActions::trash_selected($model,$this->checkbox);
		}
		if (isset($_POST['approve-selected'])) {
			RUDActions::approve_selected($model,$this->checkbox);
		}
		if (isset($_POST['restore-selected'])) {
			RUDActions::restore_selected($model,$this->checkbox);
		}
		if (isset($_POST['hide-selected'])) {
			RUDActions::hide_selected($model,$this->checkbox);
		}
		if (isset($_POST['delete-selected'])) {
			$delete = RUDActions::delete_selected($model,$this->checkbox);
			return $delete;
		}

	}

}

?>