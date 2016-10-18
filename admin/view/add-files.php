<?php
use CMS\model\File\FileUpload;
use CMS\model\File\Folders;

	$file_dest = 'files/';
	$thumb_dest= 'files/thumbs/';
	//print_r($params);
	if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
		$upload = new FileUpload($file_dest,$thumb_dest,$params);
	}

?>
<form class="small" enctype="multipart/form-data" method="post" action="<?php (!isset($params[0])) ? ADMIN."file" : ADMIN."file/albums/".$params[0].'/'.$params[1];?>">
<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
<input type="file" name="files[]" multiple/><br />
<input type="checkbox" name="public" value="public"/>
<label for='public'>Public</label>
<input type="checkbox" name="secure" value="secure"/>
<label for='secure'>Secure</label>
<?php (isset($params)) ? Folders::get_albums($params[0],$params[1]) : Folders::get_albums(null,null) ;?>
<?php 	if(!isset($params[0])){ ?>
			<input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
<?php 	} else { ?>
			<input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
<?php 	} ?>
<button type="submit" name="submit_file">Add File('s)</button>
</form>
