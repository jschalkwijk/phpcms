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
        if (isset($_POST['move-selected']) && $_POST['destination'] != 0) {
		    $to = Folder::one($_POST['destination']);
            Actions::move_selected($model,$this->checkbox,$to->path);
        }
	}

}

?>