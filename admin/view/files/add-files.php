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
						<?php foreach($folders as $dir){ ?>
							<option value="<?= $dir->get_id() ?>"><?= $dir->name ?></option>
						<?php } ?>
					</select><br>
					<input type="text" name="name" placeholder="New Folder" maxlength="60"/>
					<?php if ($folder != null) { ?>
					<input type="hidden" name="parent" value="<?= $folder->get_id() ?>">
					<?php } ?>
					<button type="submit" name="submit">Add File('s)</button>
				</form>
			</div>
		</div>
	</div>
</div>
