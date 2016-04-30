<?php require_once('globals.php');?>
<?php
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to database');
if(!empty($_FILES['files']['name'][0])) {
	$files = $_FILES['files'];
	$uploaded = array();
	$failed = array();
	$allowed = ['txt','jpg','jpeg','png','pdf','mp4','mp3','word'];
	/* for each file name in the array we store the key of the array and the corresponding value as 
	position and name like [0] = file1.png, [1] = file2.png 
	this loop will run 5 times, if 5 files are selected, 7 times is 7 etc.
	*/
	foreach($_FILES['files']['name'] as $position => $file_name) {
		$file_tmp = $files['tmp_name'][$position];
		$file_size = $files['size'][$position];
		$file_error = $files['error'][$position];
		// from the filename rip the extension with explode at the dot.
		$file_ext = explode('.', $file_name);
		$file_ext = strtolower(end($file_ext));
		// if the extension is in the allowed array continue else error.
// create unique id so there will be no overwriting files
		$file_name_new = uniqid('', true) . '.'. $file_ext;
		$file_destination = 'files/'.$file_name_new;
		$thumb_name = 'thumb_'.$file_name_new;
		$thumb_location = 'files/thumbs/';
		// move uploade file to destination folder
		if(in_array($file_ext, $allowed)){
			// if there is no error (0 stands for no error, 1 is a error) 
			if ($file_error === 0){
				if($file_size <= 3500000){
					if(move_uploaded_file($file_tmp,$file_destination)) {
							$uploaded[$position] = $file_name; 
							$query = "INSERT INTO files(name,type,file_name,thumb_name,date) VALUES('$file_name','$file_ext','$file_name_new','$thumb_name',NOW())";
							mysqli_query($dbc, $query) or die("Error connecting to database");
							
							// create thumbnails, must check for deleting them!
							if (!empty($file_destination)) {
								$image = $file_destination;
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
								// check image type and set the right thumb creation.
								if ($image_size[2] == 1) { $old_image = imagecreatefromgif($image); }
								if ($image_size[2] == 2) { $old_image = imagecreatefromjpeg($image); }
								if ($image_size[2] == 3) { $old_image = imagecreatefrompng($image); }
								imagecopyresized($new_image, $old_image, 0,0,0,0,$new_width,$new_height, $original_width,$original_height);
								imagepng($new_image, $thumb_location.$thumb_name);
							}
							} else {
						// add to failed array
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
	if(!empty($uploaded)) {
		// show uploaded files from the array, we send this with Json to the JS file for displaying uploade files.
		header('Content-Type: application/json');
		echo json_encode(implode(',',$uploaded).' uploaded to the server');
	}
	if(!empty($failed)) {
		echo json_encode(implode(',',$failed));
	}
}