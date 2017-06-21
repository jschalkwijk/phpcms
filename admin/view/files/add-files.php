<?php
use CMS\Models\Files\Folder;
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 offset-md-3 col-lg-6 offset-lg-3">
			<div class="center">
				<form class="small" enctype="multipart/form-data" method="post" action="<?= ADMIN."files/create";?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
					<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
					<input type="file" name="files[]" multiple/><br />
					<label for="destination">Select Destination</label>
					<select name="destination">
						<option value="0" selected>None</option>
						<?php foreach($data['folders'] as $folder){ ?>
							<option value="<?= $folder->get_id() ?>"><?= $folder->name ?></option>
						<?php } ?>
					</select><br>
					<input type="text" name="name" placeholder="New Folder" maxlength="60"/>
					<?php if ($data['folder'] != null) { ?>
					<input type="hidden" name="parent" value="<?= $data['folder']->get_id() ?>">
					<?php } ?>
					<button type="submit" name="submit">Add File('s)</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!--<div class="container">-->
<!--	<div class="row">-->
<!--		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">-->
			<form class="small" enctype="multipart/form-data" method="post" action="<?= ADMIN."files/create";?>">
				<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
				<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
				<input type="file" name="files[]" multiple/><br />
				<?php (isset($params)) ? Folder::get_albums($params['id'],$params['name']) : Folder::get_albums(null,null) ;?>
				<select id="albums" name="album_id">
					<option value="0" selected>None</option>
					<?php foreach($data['folders'] as $folder){ ?>
					<option value="<?= $folder->get_id() ?>"><?= $folder->name ?></option>
					<?php } ?>
				</select>
					<label for="select">Albums</label>
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
