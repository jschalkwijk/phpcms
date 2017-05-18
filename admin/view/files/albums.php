<div class="container">
    	<div class="row">
    		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
                <h1>Hello</h1>
<!--				--><?php //require_once 'view/files/add-files.php'; ?>
    		</div>
    	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
		<?php
			if(isset($data['folder'])){
				$folder = $data['folder'];
				echo '<h1>' . $folder->name . '</h1>';
				$doc = ['txt', 'doc', 'docx', 'odt'];
				$img = ['jpg', 'jpeg', 'png'];
				$url = $_SERVER["REQUEST_URI"];
                foreach($folder->products() as $product){
                    echo "Productname".$product->name."<br>";
                }
		?>
			<form id="check-files" method="post" action="<?= $url; ?>">
				<table class="files-table">
					<thead><th></th><th>Name</th><th>Type</th><th>Size</th></thead>
					<tbody>
					<?php
						foreach($folder->files() as $single){
							require 'view/files/single-file.php';
						}
					?>
					</tbody>
				</table>
				<button type="submit" name="delete" id="delete">Delete Selected</button>
				<button type="submit" name="download_files" id="download_files">Download files</button>
			</form>
		<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
			<form id="check-folders" method="post" action="<?= ADMIN.'files' ?>">
				<button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
				<table class="files-table">
					<thead></thead><th></th><th>Name</th><th>Size(MB)</th></thead>
					<tbody>

					<?php
						foreach($data['folders'] as $folder) { ?>
							<tr class="meta">
								<td><img class="glyph-medium" src="<?= ADMIN.'images/files.png' ?>"/></td>
								<td><a href="<?= ADMIN ?>files/albums/<?= $folder->album_id.'/'.$folder->name ?>"> <?= $folder->name ?></a></td>
								<td>Size</td>
								<input type="hidden" name="album_name" value="<?= $folder->name ?>"/>
								<td><input class="checkbox" type="checkbox" name="checkbox[]" value="<?= $folder->album_id ?>"/></td>
							</tr>
					<?php } ?>
					</tbody>
				</table>
				<button type="submit" name="delete-albums" id="delete-albums">Delete Albums</button>
			</form>
		</div>
	</div>
</div>
