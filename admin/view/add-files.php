<div class="container small">
	<?php
		$file_dest = 'files/';
		$thumb_dest= 'files/thumbs/';
		//print_r($params);
		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			$upload = new files_FileUpload($file_dest,$thumb_dest,$params);
		}
		
	?>
	<form class="small" enctype="multipart/form-data" method="post" action="<?php (!isset($params[0])) ? "/admin/file" : "/admin/file/albums/".$params[0].'/'.$params[1];?>">
	<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
	<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
	<input type="file" name="files[]" multiple/><br />
	<input type="checkbox" name="public" value="public"/>
	<label for='public'>Public</label>
	<input type="checkbox" name="secure" value="secure"/>
	<label for='secure'>Secure</label>
	<?php (isset($params)) ? files_Folders::get_albums($params[0],$params[1]) : files_Folders::get_albums(null,null) ;?>
	<?php 	if(!isset($params[0])){ ?>
				<input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
	<?php 	} else { ?>
				<input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
	<?php 	} ?>
	<button type="submit" name="submit_file">Add File('s)</button>
	</form>
</div>