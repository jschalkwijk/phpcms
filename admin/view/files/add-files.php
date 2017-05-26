<?php
use CMS\Models\Files\FileUpload;
use CMS\Models\Files\Folders;

	$file_dest = 'uploads/';
	$thumb_dest= 'uploads/thumbs/';
	//print_r($params);
	if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
		$upload = new FileUpload($file_dest,$thumb_dest,$params);
	}

?>
<!--<div class="container">-->
<!--	<div class="row">-->
<!--		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">-->
			<form class="small" enctype="multipart/form-data" method="post" action="<?php (!isset($params['id'])) ? ADMIN."files" : ADMIN."files/albums/".$params['id'].'/'.$params['name'];?>">
				<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
				<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
				<input type="file" name="files[]" multiple/><br />
				<input type="checkbox" name="public" value="public"/>
				<label for='public'>Public</label>
				<input type="checkbox" name="secure" value="secure"/>
				<label for='secure'>Secure</label>
				<?php (isset($params)) ? Folders::get_albums($params['id'],$params['name']) : Folders::get_albums(null,null) ;?>
				<?php 	if(!isset($params['id'])){ ?>
							<input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
				<?php 	} else { ?>
							<input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
				<?php 	} ?>
				<button type="submit" name="submit_file">Add File('s)</button>
			</form>
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
