<?php
namespace CMS\Models\Actions;

use CMS\Core\FileSystem\FileSystem;
use CMS\Core\Model\Model;
use CMS\Models\Files\File;
use CMS\Models\Files\Folder;
use CMS\Models\Pages\Page;

// This are the  update,delete and approve functions which regards post,pages, users and products.
// these will only remove rows, not files etc.
// Import the UserActions trait inside the controller and enter the database name insid ethe function
// to alter the DB rows.  UserActions handles the Post requests and calls these functions

class Actions{

	public static function trash_selected($model,$checkbox){
		Actions::update($model,$checkbox, ["trashed" => 1]);
	}
	public static function approve_selected($model,$checkbox){
		Actions::update($model,$checkbox, ["approved" => 1]);
	}
	public static function restore_selected($model,$checkbox){
		Actions::update($model,$checkbox, ["trashed" => 0]);
	}
	public static function hide_selected($model,$checkbox){
		Actions::update($model,$checkbox, ["approved" => 0]);
	}

	protected static function update(Model $model,$checkbox,$columns){
        $checkbox = (!is_array($checkbox)) ? [(int)$checkbox] : $checkbox;

      	$model->update($columns)->whereIN([$model->primaryKey => $checkbox])->grab();

//		header('Location: '.ADMIN.$model->table);
	}
	public static function delete_selected(Model $model,$checkbox){

		$multiple = implode(",",$checkbox);
		$messages = [];
		
		if($model->table == 'folders'){
			$parents = $checkbox;
			$folders = Folder::allWhere(['folder_id'=> $checkbox]);
			foreach ($folders as $folder) {
				$paths[] = $folder->path;
			};
			(new FileSystem())->removeDirectory($paths);

			Folder::deleteRecursive($parents);
		}

		if($model->table == 'files'){
			$files = File::allWhere(['file_id'=> $checkbox]);
			foreach ($files as $file) {
				$paths[] = $file->path;
				$paths[] = $file->thumb_path;
			};
			(new FileSystem())->delete($paths);


		}
		if($model->table === "pages"){
			$deleted = Page::deletePage($multiple);
			$flag = $deleted['flag'];
			if(isset($deleted['message']) || $deleted['error']) { $messages[] = $deleted['message']; }
		}
		$model->delete($multiple);

		// ALLEEN ALS ER ERRORS ZIJN MOET IK DIE RETURNEN. ANDERS MOET IK DE HEADER LOCATION DOEN,
		// ANDERS WORDT DE PAGINA NIET VERVERST :-)
		if(!empty($messages)) {
			return ['messages' => $messages];
		} else {
			return header('Location: ' . ADMIN . $model->table . '/deleted');
		}
	}

    public static function move_selected(Model $model,$checkbox,$to)
    {
        if($model->table == 'files'){
            $files = File::allWhere(['file_id'=> $checkbox]);
            $fileSys = new FileSystem();
            foreach ($files as $file) {
                $path = $_SERVER['DOCUMENT_ROOT'].'/admin/'.$file->path;
                $thumb = $_SERVER['DOCUMENT_ROOT'].'/admin/'.$file->thumb_path;
                $fileSys->move($path,$to.'/'.$file->file_name);
                $fileSys->move($thumb,$to.'/thumbs/'.$file->thumb_name);
            };
        }
	}
}
?>