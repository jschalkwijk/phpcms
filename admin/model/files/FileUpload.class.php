<?php

class files_FileUpload{
	private $dbc;
	private $file_dest;
	private $thumb_dest;
	private $path;
	private $empty_path;
	private $params;
	private $img_path;
	private $thumb_path;
	private $fid;

	public function __construct($file_dest,$thumb_dest,$params,$empty_path = false){
		$this->dbc = new DBC;
		$this->params = $params;
		// nieuwe toevoeging empty_path is nodig in het geval wij een bestand willen uploaden buiten
		// de hoofd album/files structuur, zoals bijvoorbeeld in het geval van een product of gebruikers afbeelding.
		// het pad, $this->path wordt namelijk gecreeerd vanuit de parameters van de URL, waarin bij de hoofdsctructuur, het album_id wordt gebruikt.
		//Ex. http://craft:8888/admin/file/albums/31/Hankie
		// Deze klopt als je in files zit of in een album, maar deze URL klopt NIET! als je in een product of user etc zit,
		// Ex. http://craft:8888/admin/products/edit-product/3/Hankie
		// het ID is dan namelijk het ID van het zichtbare product of gebruiker etc, waardoor het een verkeerde folder selecteerd.
		// Als we een empty_path flag toevoegen kunnen we controlleren of we een pad moeten creeeren op basis van het huidige zichtbare album,
		// of een leeg pad moeten hebben wanneer je een bestand toevoegd vanuit een product/user weergave.
		$this->empty_path = $empty_path;
/*
		if(isset($params[0]) && isset($params[1])){
			$album_id = $this->params[0];
			$album_name = $this->params[1];
			// check if the destination folders exist, otherwhise create them.
			$query = "SELECT path FROM albums where album_id = $album_id AND name = '$album_name'";
			$data = mysqli_query($this->dbc->connect(),$query) or die('Error selecting path from albums');
			$row = mysqli_fetch_assoc($data);
			echo $row['path'];
		}
*/
		
//		if(!file_exists($file_dest.$params[1])){ mkdir($file_dest.$params[1]); }
//		if(!file_exists($thumb_dest.$params[1])) { mkdir($thumb_dest.$params[1]); }
		
		if (!empty($_FILES['files']['name'][0])) {
			$files = $_FILES['files'];
			/* for each file name in the array we store the key of the array and the corresponding value as 
			position and name like [0] = file1.png, [1] = file2.png 
			this loop will run 5 times, if 5 files are selected, 7 times is 7 etc.
			*/
			$uploaded = array();
			$failed = array();
			$allowed = ['txt','jpg','jpeg','png','pdf','zip','mp4','mp3','doc','docx','odt','csv'];
		
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
							if(isset($_POST['public'])) {
								//if you don't want to create a new album, the album name is the selected album name
								// if no album is selected, echo error, there must be a album selected or created.

								// !!!!!!!! IMPORTANT, dit kan boven de for loop, hoeft maar een keer te worden gedaan!
								if(!empty($_POST['album_name'])){
									$album_name = mysqli_real_escape_string($this->dbc->connect(),trim($_POST['album_name']));
									if(!empty($_POST['new_album_name'])) {
										$album_name = mysqli_real_escape_string($this->dbc->connect(),trim(htmlentities($_POST['new_album_name'])));
										if(strlen($album_name) > 60){
											$errors[] = 'Album name can only be 60 characters long.';
										} else if(!empty($album_name)) {
											(empty($this->path))? $this->path = $this->create_path($album_name) : "";
											$this->create_album($album_name,$file_dest,$thumb_dest);
										}
									} else if(empty($album_name)) {
										$errors[] = 'Please select an album or create a new album.';
									} else {
										(empty($this->path))? $this->path = $this->create_path($album_name) : "";
										echo 'Line 86: this-path'.$this->path;
									}
								}
								// the path has to be created only once. If this->path is empty,
								//execute function,else it's already filled.
								
								if($this->uploadFile($file_tmp,$file_dest,$file_name,$file_ext,$file_name_new,$thumb_name,$thumb_dest,$position,$album_name,0)){
									$uploaded[] = $file_name;
									if(!$this->createThumb($file_dest,$file_name_new,$thumb_name,$thumb_dest,$album_name)){
										$failed[] = 'Thumbnails failed to be created';
										}
								} else {
									// add to failed array if it cant upload, etc.
									$failed[$position] = "{$file_name} failed to upload."; 
								}
								
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
		$this->dbc->disconnect();
	}
	
	protected function uploadFile($file_tmp,$file_dest,$file_name,$file_ext,$file_name_new,$thumb_name,$thumb_dest,$position,$album_name){
		// Ik moet naar boven werken met de id's om het nieuwe pad te creeeren,met een loop die checked of de parent_id
		// != 0,dan moet de naam van dat album in de file_dest.
		// Deze loop MOET in de create_album en create RThumb functie, die hebben dit pad ook nodig!!!
		if(!$this->empty_path) {
			$path = $this->path;
		} else {
			$path = "";
		}
		echo 'Line: 137 | path: '.$path.'<br />';
		$file_dest = $file_dest.$path.'/'.$file_name_new;
		echo 'Line 139 | file_dest: '.$file_dest.'<br>';

		$sql = "SELECT album_id FROM albums WHERE name LIKE '$album_name'";
		//$sql = "SELECT album_id FROM albums WHERE name LIKE '$album_name' AND path LIKE '$path'";
		$data = mysqli_query($this->dbc->connect(),$sql) or die('Error connecting to server');
		$row = mysqli_fetch_array($data);
		$id = $row['album_id'];
	
		$thumb_path = $thumb_dest.$path.'/'.$thumb_name;
		
		// If upload paths contains 's etc we have to remove the \ (backslash) which is created automaticly.
		// To insert the path in the Database we have to keep the \ (backslash) otherwise the query will fail.
		$path = str_replace("\\","",$file_dest);
		// because we want to maybe insert a " ' " like in Person's Contacts, we need to change the ' to '', to escape the '. otherwise it will fail.
		$file_dest = str_replace(array("\\","'"),array("","''"),$file_dest);
		$this->img_path = $path;
		$this->thumb_path = $thumb_path;
		$thumb_path = str_replace(array("\\","'"),array("","''"),$thumb_path);
		$user_id = $_SESSION['user_id'];
		echo 'Line: 157 | path: '.$path.'<br />';
		if($album_name != 'None') {	
			if(move_uploaded_file($file_tmp,$path)) {
				// add to uploade array, we dont use the new filename because thats all numbers,
				// we use the original file name, we store both the original and new file name to the DB.
				$uploaded[$position] = $file_name;
				$query = "INSERT INTO files(name,type,file_name,thumb_name,album_id,date,path,thumb_path,user_id) VALUES('$file_name','$file_ext','$file_name_new','$thumb_name','$id',NOW(),'$file_dest','$thumb_path',$user_id)";
				echo $query;
				mysqli_query($this->dbc->connect(), $query) or die("Error connecting to database Uploadfile");
				return true;
			}
		} else {
			return false;
		}
	}

	protected function createThumb($file_dest,$file_name_new,$thumb_name,$thumb_dest,$album_name){
		if(!$this->empty_path) {
			$path = str_replace("\\", "", $this->path);
		} else {
			$path = "";
		}
		$file_dest = $file_dest.$path.'/'.$file_name_new;
		//echo $file_dest;
		if(!empty($file_dest)){
			$image = $file_dest;
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
			if(imagepng($new_image, $thumb_dest.$path.'/'.$thumb_name)){
				return true;
			} else {
				return false;
			}
		}
	}

	protected function create_album($album_name,$file_dest,$thumb_dest) {
		// the album_name and dest can be the same if you create a main folder!
		// else when creating a sub folder to a main folder, the album_dest is different.
		// see create_sub_folder() for details.
		// IF A ALBUM NAME ALREADY EXISTS, DON'T CREATE THE ALBUM.
		$author = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		(isset($this->params[0])) ? $parent_id = mysqli_real_escape_string($this->dbc->connect(),trim($this->params[0])): $parent_id = 0;
		$file_dest = $file_dest.$this->path;
		$thumb_dest = $thumb_dest.$this->path;
		
		$path = $this->path;
		
		if(!file_exists($file_dest)){
			$query = "INSERT INTO albums(name,author,parent_id,path,user_id) VALUES('$album_name','$author',$parent_id,'$path',$user_id)";
			mysqli_query($this->dbc->connect(),$query) or die('Error connecting to database');
			mkdir(str_replace("\\","",$file_dest),0744);
			mkdir(str_replace("\\","",$thumb_dest),0744);
			}		
		
		mysqli_close($this->dbc->connect());
	}
	
	protected function create_path($album_name){
		if(isset($this->params[0])){
				// Ik moet naar boven werken met de id's om het nieuwe pad te creeeren,met een loop die checked of de parent_id
		// != 0,dan moet de naam van dat album in de file_dest.
		// Deze loop MOET in de create_album en create Thumb functie, die hebben dit pad ook nodig!!!
			$id = mysqli_real_escape_string($this->dbc->connect(),trim($this->params[0]));
			// if (album_id != this->params[0]) path equals file_dest inputed in the controller, or path equal file_dest+ album name..???
			$query = "SELECT name,path FROM albums WHERE album_id = $id";
			$data = mysqli_query($this->dbc->connect(),$query);
			$row = mysqli_fetch_array($data);
			echo 'Hier is het probleem!!! de namen komen niet overeen met elkaar waardoor het verkeerde pad wordt gecreeerd.';
			echo 'Waarom doe ik in de add-file form geen album_id\'s in plaats van de namen, wordt deze functie dan niet veel makkelijker of overbodig?';
			echo 'Als ik een foto upload in een bestaande folder hoef ik geen nieuw pad te creeeren maar alleen op te halen uit de DB!!';
			echo 'Line 240 | Album name: '.$album_name.'<br />';
			echo 'line 241 | row[name]: '.$row['name'].'<br />';
			if($row['path'] === $album_name){
				$path = $album_name;
			} else if(str_replace("\\","",$album_name) === $row['name']){
				echo '1'.'<br />';
				$path = $row['path'];
				// dit werkt voor folders die al bestaan: zoals products/ Hamsters/Henkie. Maar als je een nieuwe subfolder maakt dan werkt dit dus niet..
				#$path = $row['path'];
			} else {
				echo '2'.'<br />';
				$path = $row['path'].'/'.$album_name;
			}
		} else {
			echo '3'.'<br />';
			$path = $album_name;
		}
		return $path;
	}

	protected function unzip_files(){
		$zip = new ZipArchive;
		$res = $zip->open('file.zip');
		if($res){
			$zip-extractTo('location');
			$zip-close();
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
