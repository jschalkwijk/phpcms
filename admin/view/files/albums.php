<?php
	use CMS\Models\File\File;
	use CMS\Models\File\FileWriter;
	use CMS\Models\File\Folders;
	use CMS\Models\DBC\DBC;
?>
<div class="container">
    	<div class="row">
    		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
                <?php require_once 'view/files/add-files.php'; ?>
    		</div>
    	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
			<?php
				$dbc = new DBC;
				$album_id = null;
				if(isset($params[1])) {
					$album_id = trim((int)$params[0]);
					$album_name = trim($params[1]);

					echo '<h1>' . $album_name . '</h1>';
					$doc = ['txt', 'doc', 'docx', 'odt'];
					$img = ['jpg', 'jpeg', 'png'];
					$url = $_SERVER["REQUEST_URI"];
					echo '<form method="get" action="' . $url . '">';
			?>
			<form id="check-files" method="post" action="<?= $url; ?>">
				<table class="files-table">
					<thead><th></th><th>Name</th><th>Type</th><th>Size</th></thead>
					<tbody>
						<?php
						$files = File::fetchFilesByAlbum($album_id,0);
						FileWriter::write($files,'view/files/single-file.php',$doc,$img);
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
			<form id="check-folders" method="post" action="<?= ADMIN.'file' ?>">
				<button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
				<table class="files-table">
					<thead></thead><th></th><th>Name</th><th>Size(MB)</th></thead>
					<tbody>
					<?php Folders::show_albums($album_id); ?>
					</tbody>
				</table>
				<button type="submit" name="delete-albums" id="delete-albums">Delete Albums</button>
			</form>
		</div>
	</div>
</div>
