<?php
namespace CMS\Models\Files;

use CMS\Models\DBC\DBC;
use ZipArchive;

class FileUpload{
	private $dbc;
	private $file_dest;
	private $thumb_dest;
	private $path;
	private $empty_path;
	private $params;
	private $album_name;

	public function __construct($file_dest,$thumb_dest,$params,$empty_path = false){
		$db = new DBC;
		$dbc = $db->connect();

		$this->file_dest = $file_dest;
		$this->thumb_dest = $thumb_dest;
		$this->params = $params;
		// nieuwe toevoeging empty_path is nodig in het geval wij een bestand willen uploaden buiten
		// de hoofd album/files structuur, zoals bijvoorbeeld in het geval van een product of gebruikers afbeelding.
		// het pad, $this->path wordt namelijk gecreeerd vanuit de parameters van de URL, waarin bij de hoofdsctructuur, het folder_id wordt gebruikt.
		//Ex. http://craft:8888/admin/file/albums/31/Hankie
		// Deze klopt als je in files zit of in een album, maar deze URL klopt NIET! als je in een product of user etc zit,
		// Ex. http://craft:8888/admin/products/edit-product/3/Hankie
		// het ID is dan namelijk het ID van het zichtbare product of gebruiker etc, waardoor het een verkeerde folder selecteerd.
		// Als we een empty_path flag toevoegen kunnen we controlleren of we een pad moeten creeeren op basis van het huidige zichtbare album,
		// of een leeg pad moeten hebben wanneer je een bestand toevoegd vanuit een product/user weergave.
		$this->empty_path = $empty_path;

		if (!empty($_FILES['files']['name'][0])) {
			$files = $_FILES['files'];
			/* for each file name in the array we store the key of the array and the corresponding value as
			position and name like [0] = file1.png, [1] = file2.png
			this loop will run 5 times, if 5 files are selected, 7 times is 7 etc.
			*/
			$uploaded = array();
			$failed = array();
			$allowed = ['txt','jpg','jpeg','png','pdf','zip','mp4','mp3','doc','docx','odt','csv'];

			// ALBUM CREATION
			//if you don't want to create a new album, the album name is the selected album name
			// if no album is selected, echo error, there must be a album selected or created.

			// !!!!!!!! IMPORTANT, dit kan boven de for loop, hoeft maar een keer te worden gedaan!
			// Als ik nu een nieuwe folder maak dan komt de file in de hoofdolder, als ik dan de folder selecteer
			// uit menu dan komt hij wel in die folder terrecht. ligt aan if not empty folder_id

			if(isset($_POST['folder_id'])){
				$folder_id = trim((int)$_POST['folder_id']);
				echo 'folder_id : '.$folder_id;
				if(isset($_POST['new_album_name'])) {
					$new_album_name = trim(htmlentities($_POST['new_album_name']));
					if(strlen($new_album_name) > 60){
						$errors[] = 'Album name can only be 60 characters long.';
					} else {
						if(empty($this->path) && $folder_id != 0) {
							$this->path = $this->create_path($folder_id,$new_album_name);
						} else {
							$this->path = $new_album_name;
						}
						$folder_id = $this->create_album($new_album_name,$folder_id);
					}
				} else if(empty($folder_id)) {
					$errors[] = 'Please select an album or create a new album.';
				} else {
					(empty($this->path))? $this->path = $this->create_path($folder_id) : "";
					echo 'Line 71: this-path'.$this->path;
				}
			}

			echo "New album ID: ".$folder_id;
			// the path has to be created only once. If this->path is empty,
			//execute function,else it's already filled.
			// END ALBUM CREATION
			foreach($files['name'] as $position => $file_name) {
				$file_tmp = $files['tmp_name'][$position];
				$file_size = $files['size'][$position];
				$file_error = $files['error'][$position];
				// from the filename rip the extension with explode at the dot.
				$file_ext = explode('.', $file_name);
				$file_ext = strtolower(end($file_ext));
				// if the extension is in the allowed array continue else error.
				if(in_array($file_ext, $allowed)){
					// if there is no error (0 stands for no error, 1 is a error)
					if ($file_error === 0){
						if($file_size <= 43500000){
							// create unique id so there will be no overwriting files on the server
							$file_name_new = uniqid('', true) . '.'. $file_ext;
							$thumb_name = $file_name.'.thumb_'.$file_name_new;

							// move uploaded file to destination folder
							if($this->uploadFile($file_tmp,$file_name,$file_ext,$file_name_new,$thumb_name,$position,$folder_id)){
								$uploaded[] = $file_name;
								if(!$this->createThumb($file_name_new,$thumb_name,$thumb_dest)){
									$failed[] = 'Thumbnails failed to be created';
									}
							} else {
								// add to failed array if it cant upload, etc.
								$failed[$position] = "{$file_name} failed to upload.";
							}
						} else {
							$failed[$position] = "{$file_name} is too large.";
						}
					} else {
						$failed[$position] = "{$file_name} errored with code '{$file_error}'";
					}
				} else {
					$failed[$position] = "{$file_name} file extension '{$file_ext}' is not allowed";
				}
			}
		} else {
			$failed[] = "No file('s) selected";
		}
		if(!empty($uploaded)) {
			// show uploaded files from the array
			echo implode(",",$uploaded).' is added to the database.';
		}
		if(!empty($failed)) {
			echo implode(",",$failed);
		}
		if(!empty($errors)) {
			echo implode(",",$errors);
		}
		$db->close();
	}

	protected function uploadFile($file_tmp,$file_name,$file_ext,$file_name_new,$thumb_name,$position,$folder_id){
		// Ik moet naar boven werken met de id's om het nieuwe pad te creeeren,met een loop die checked of de parent_id
		// != 0,dan moet de naam van dat album in de file_dest.
		// Deze loop MOET in de create_album en create RThumb functie, die hebben dit pad ook nodig!!!
		$db = new DBC;
		$dbc = $db->connect();

		$id = $folder_id;
		$destination = $this->path.'/'.$file_name_new;
		$thumb_path = $this->path.'/thumbs/'.$thumb_name;

		// If upload paths contains 's etc we have to remove the \ (backslash) which is created automaticly.
		// To insert the path in the Database we have to keep the \ (backslash) otherwise the query will fail.
		$path = str_replace("\\","",$destination);
		// because we want to maybe insert a " ' " like in Person's Contacts, we need to change the ' to '', to escape the '. otherwise it will fail.
		$destination = str_replace(array("\\","'"),array("","''"),$destination);
		$thumb_path = str_replace(array("\\","'"),array("","''"),$thumb_path);
		$user_id = $_SESSION['user_id'];
		echo 'Line: 168 | path: '.$path.'<br />';
		if(!empty($folder_id)) {
			if(move_uploaded_file($file_tmp,$path)) {
				// add to uploade array, we dont use the new filename because thats all numbers,
				// we use the original file name, we store both the original and new file name to the DB.
				$uploaded[$position] = $file_name;
				try {
                    $sql = "INSERT INTO files(name,type,file_name,thumb_name,folder_id,date,path,thumb_path,user_id) VALUES(?,?,?,?,?,NOW(),?,?,?)";
                    echo $sql;
                    $query = $dbc->prepare($sql);
					$query->execute([$file_name, $file_ext, $file_name_new, $thumb_name, $id, $destination, $thumb_path, $user_id]);;
					return true;
                } catch (\PDOException $e){
                        echo $e->getMessage();
                }
			} else {
                return false;
            }
		} else {
			return false;
		}
	}

	protected function createThumb($file_name_new,$thumb_name){
		$path = str_replace("\\", "", $this->path);
		$destination = $path.'/'.$file_name_new;

		if(!empty($destination)){
			$image = $destination;
			// creates array with [0]width [1] height from the file
			$image_size = getimagesize($image);
			// get current size
			$original_width = $image_size[0];
			$original_height = $image_size[1];
			// scale down to size to keep the aspect ratio
			$new_size = ($original_width+$original_height)/($original_width*($original_height/45));
			$new_width = $original_width * $new_size;
			$new_height = $original_height * $new_size;

			$new_image = imagecreatetruecolor($new_width,$new_height);
			// check image type and set the right thumb creation. 1 is g.gif, 2 is .jpeg, 3 is png.
			if ($image_size[2] == 1) { $old_image = imagecreatefromgif($image); }
			if ($image_size[2] == 2) { $old_image = imagecreatefromjpeg($image); }
			if ($image_size[2] == 3) { $old_image = imagecreatefrompng($image); }
			imagecopyresized($new_image, $old_image, 0,0,0,0,$new_width,$new_height, $original_width,$original_height);
			//echo $path.'<br />';
			if(imagepng($new_image, $path.'/thumbs/'.$thumb_name)){
				return true;
			} else {
				return false;
			}
		}
	}

	protected function create_album($album_name,$folder_id) {
		$db = new DBC;
		$dbc = $db->connect();
		// the album_name and dest can be the same if you create a main folder!
		// else when creating a sub folder to a main folder, the album_dest is different.
		// see create_sub_folder() for details.
		// IF A ALBUM NAME ALREADY EXISTS, DON'T CREATE THE ALBUM.
		$author = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		if(!empty($folder_id)) {
			$parent_id = trim((int)$folder_id);
            $destination = $this->path;
		} else {
			$parent_id = 0;
            $destination = $this->file_dest.$this->path;
			$this->path = $destination;
		}

		$thumb_dest = $destination."/thumbs";

//		$path = $this->path;

		if(!file_exists($destination)){
			try {
                $query = $dbc->prepare("INSERT INTO albums(name,author,parent_id,path,user_id) VALUES(?,?,?,?,?)");
				$query->execute([$album_name, $author, $parent_id, $destination, $user_id]);

				mkdir(str_replace("\\", "", $destination), 0744);
				mkdir(str_replace("\\", "", $thumb_dest), 0744);

				// get the new created id of the folder.
				$parent_id = $dbc->lastInsertId();
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
        }

		$db->close();

		return $parent_id;
	}
	
	protected function create_path($folder_id,$new_album_name = null){
		$db = new DBC;
		$dbc = $db->connect();

		$id = trim((int)$folder_id);
		try {
            $query = $dbc->prepare("SELECT name,path FROM albums WHERE folder_id = ?");

			$query->execute([$id]);
			$row = $query->fetch();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

//		if(mysqli_num_rows($data) == 0){
		if($query->rowCount() == 0){
			$path = $new_album_name;
		} else {
			if($new_album_name == null){
				$this->album_name = $row['name'];
				$path = $row['path'];
			} else {
				$this->album_name = $new_album_name;
				$path = $row['path'].'/'.$new_album_name;
			}
		}
		$db->close();
		return $path;
	}

	protected function unzip_files(){
		$zip = new ZipArchive;
		$res = $zip->open('file.zip');
		if($res){
			$zip->extractTo('location');
			$zip->close();
			echo 'files extracted';
		} else {
			echo 'file failed to extract';
		}
		
	}

	public function setPath($path){
		$this->path = $path;
	}
	
	public function getImgPath(){
		return $this->img_path;
	}
	public function getThumbPath(){
		return $this->thumb_path;
	}
}
?>
