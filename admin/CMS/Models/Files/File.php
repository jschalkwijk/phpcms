<?php
namespace CMS\Models\Files;

use CMS\Core\Model\Model;
use CMS\Models\DBC\DBC;
use CMS\Models\Actions\FileActions;

class File extends Model{
	public $primaryKey = 'file_id';

	public $table = 'files';

	protected $relations = [
		'folders' => 'folder_id',
		'users' => 'user_id'
	];

	// either use the joins var to join in related tables or use the relationsship functions.
	// I would use join if you just need a few row items. Only use the relation functions if you
	// really need all of it. To just display a category name for example just join that value.
	protected $joins = [
		'albums' => ['name','path'],
		'users' => ['username']
	];

	protected $allowed = [
		'name',
		'type',
		'file_name',
		'thumb_name',
		'folder_id',
		'path',
		'thumb_path',
		'user_id',
		'secured',
	];

	public function setID($id){
		$this->id = $id;
	}
	public function get_id(){
		return $this->file_id;
	}
	
	public static function fetchFilesBySearch($searchTermBits){
		$db = new DBC;
		$dbc = $db->connect();
        try {
		    $query = $dbc->query("SELECT * FROM files WHERE ".implode(' AND ', $searchTermBits));
            $data = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
		$files= array();
        foreach($data as $row){
			$file = new File(
				$row['name'],
				$row['type'],
				$row['name'],
				$row['thumb_name'],
				$row['folder_id'],
				$row['date'],
				$row['secured'],
				$row['path'],
				$row['thumb_path']
			);
			$file->setID($row['file_id']);
			// adds every object to the files array. We can acces each object and its methods seperatly.
			$files[] = $file;
		}
		$db->close();
		return $files;
	}
	
	//get the trait file for the user actions.
	use FileActions;
}

?>