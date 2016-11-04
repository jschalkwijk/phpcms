<?php
namespace CMS\Models\File;

use CMS\Models\DBC\DBC;
use CMS\Models\Actions\FileActions;

class File{
	private $id = 0;
	protected $name;
	protected $type;
	protected $thumb_name;
	protected $album_id;
	private $date;
	private $secured;
	private $path;
	private $thumb_path;

	/* File object, the variable names speak for themselves */

	public function __construct($name,$type,$name,$thumb_name,$album_id,$date = null,$secured,$path,$thumb_path) {
		$this->name = $name;
		$this->type = $type;
		$this->name = $name;
		$this->thumb_name = $thumb_name;
		$this->album_id = $album_id;
		$this->date = $date;
		$this->secured = $secured;
		$this->path = $path;
		$this->thumb_path = $thumb_path;
	}

	/* All the Getters set to get the variables needed outside this object*/
	public function getName(){
		return $this->name;
	}
	public function getType(){
		return $this->type;
	}
	public function getFileName(){
		return $this->name;
	}
	public function getAlbumID(){
		return $this->album_id;
	}
	public function getDate(){
		return $this->date;
	}
	public function getSecured(){
		return $this->secured;
	}
	public function getPath(){
		return $this->path;
	}
	public function getThumbPath(){
		return $this->thumb_path;
	}
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;
	}

	/* To get all the files in an album,
	 * give the album_id and specify if you want to see the trashed or not trashed files.
	 * For every file in the DB there will be a file object created and added to the files array.
	 * Because we need the file ID from the DB we have to set the object ID var by ourselves with the setter.
	 * The array with all the file objects is returned so we can do stuff with it.
	*/
	public static function fetchFilesByAlbum($album_id,$trashed) {

		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->prepare("SELECT * FROM files WHERE album_id = ?");
		if($query) {
			$query->bind_param("i", $album_id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
		} else {
			$db->sqlERROR();
		}
		$files= array();
		while($row = $data->fetch_array()){
			$file = new File(
				$row['name'],
				$row['type'],
				$row['name'],
				$row['thumb_name'],
				$row['album_id'],
				$row['date'],
				$row['secured'],
				$row['path'],
				$row['thumb_path']
			);
			$file->setID($row['file_id']);
			// adds every object to the files array. We can access each object and its methods separately.
			$files[] = $file;
		}
		$dbc->close();
		return $files;
	}
	
	public static function fetchFilesBySearch($searchTermBits){
		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->query("SELECT * FROM files WHERE ".implode(' AND ', $searchTermBits));
		if(!$query) { $db->sqlERROR(); };
		$files= array();
		while($row = $query->fetch_array()){
			$file = new File(
				$row['name'],
				$row['type'],
				$row['name'],
				$row['thumb_name'],
				$row['album_id'],
				$row['date'],
				$row['secured'],
				$row['path'],
				$row['thumb_path']
			);
			$file->setID($row['file_id']);
			// adds every object to the files array. We can acces each object and its methods seperatly.
			$files[] = $file;
		}
		$dbc->close();
		return $files;
	}
	
	//get the trait file for the user actions.
	use FileActions;
}

?>