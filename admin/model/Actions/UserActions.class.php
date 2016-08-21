<?php

// Usage: Import UserActions into your controller. It takes a DB table name.
// Depending on which post request is past to the controller the appropiate function will
// be called from RUDactions. this will only be removing rows. Not files on the server.
// That is handled by other functions inside the File classes.
trait Actions_UserActions {
	
	private $id;
	private $name;
	private $checkbox;
	
	private function UserActions($dbt) {
		$db = new DBC;
		$dbc = $db->connect();
		
		(isset($_POST['id'])) ? $this->id = mysqli_real_escape_string($dbc,trim((int)$_POST['id'])) : '';
		(isset($_POST['name'])) ? $this->name = mysqli_real_escape_string($dbc,trim($_POST['name'])) : '';
		(isset($_POST['checkbox'])) ? $this->checkbox = $_POST['checkbox'] : '';
		
		if (isset($_POST['remove'])) {
			$remove = actions_RUDActions::trash($dbt,$this->id,$this->name);
		}
		if (isset($_POST['approve'])) {
			$approve = actions_RUDActions::approve($dbt,$this->id,$this->name);
		}
		if (isset($_POST['hide'])) {	
			$hide = actions_RUDActions::hide($dbt,$this->id,$this->name);	
		}
		if (isset($_POST['restore'])) {
			$restore = actions_RUDActions::restore($dbt,$this->id,$this->name);
		} 
		if (isset($_POST['delete'])) {
			$delete = actions_RUDActions::delete($dbt,$this->id,$this->name);

		}
		if (isset($_POST['trash-selected'])) {
			$delete = actions_RUDActions::trash_selected($dbt,$this->checkbox);
		}
		if (isset($_POST['approve-selected'])) {
			$delete = actions_RUDActions::approve_selected($dbt,$this->checkbox);
		}
		if (isset($_POST['restore-selected'])) {
			$delete = actions_RUDActions::restore_selected($dbt,$this->checkbox);
		}
		if (isset($_POST['hide-selected'])) {
			$delete = actions_RUDActions::hide_selected($dbt,$this->checkbox);
		}
		if (isset($_POST['delete-selected'])) {
			$delete = actions_RUDActions::delete_selected($dbt,$this->checkbox);
			return $delete;
		}

		
	}

}

?>