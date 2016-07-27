<?php

class File_File{
	private $id = 0;
	protected $name;
	protected $type;
	protected $file_name;
	protected $thumb_name;
	protected $album_id;
	private $date;
	private $secured;
	private $path;
	private $thumb_path;

	/* File object, the variable names speak for themselves */

	public function __construct($name,$type,$file_name,$thumb_name,$album_id,$date = null,$secured,$path,$thumb_path) {
		$this->name = $name;
		$this->type = $type;
		$this->file_name = $file_name;
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
		return $this->file_name;
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

		$dbc = new DBC;
		$query = "SELECT * FROM files WHERE album_id = $album_id";
		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$files= array();
		while($row = mysqli_fetch_array($data)){
			$file = new File_File(
				$row['name'],
				$row['type'],
				$row['file_name'],
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
		return $files;
		$dbc->disconnect();
	}
	
	public static function fetchFilesBySearch($searchTermBits){
		$dbc = new DBC;
		$query = "SELECT * FROM files WHERE ".implode(' AND ', $searchTermBits);
		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$files= array();
		while($row = mysqli_fetch_array($data)){
			$file = new File_File(
				$row['name'],
				$row['type'],
				$row['file_name'],
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
		return $files;
		$dbc->disconnect();
	}
	
	//get the trait file for the user actions.
	use File_FileActions;
}

?>