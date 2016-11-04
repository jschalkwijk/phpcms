<?php
	use CMS\model\File\File;
	use CMS\model\File\FileWriter;
	use CMS\model\File\Folders;
	use CMS\model\DBC\DBC;
?>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
			<?php
				$dbc = new DBC;
				$album_id = null;
				if(isset($params[1])) {
					$album_id = mysqli_real_escape_string($dbc->connect(), trim((int)$params[0]));
					$album_name = mysqli_real_escape_string($dbc->connect(), trim($params[1]));

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
		<div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
			<form id="check-folders" method="post" action="/admin/file">
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
