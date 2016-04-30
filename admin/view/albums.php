<div class="container large">
	<?php
		$dbc = new DBC;
		$album_id = null;
		if(isset($params[1])) {
			$album_id = mysqli_real_escape_string($dbc->connect(),trim((int)$params[0]));
			$album_name = mysqli_real_escape_string($dbc->connect(),trim($params[1]));

			echo '<h1>'.$album_name.'</h1>';
			$doc = ['txt','doc','docx','odt'];
			$img = ['jpg','jpeg','png'];
			$url = $_SERVER["REQUEST_URI"];
			echo '<form method="get" action="'.$url.'">';	
					$files = files_File::fetchFilesByAlbum($album_id,0);
					$writer = files_FileWriter::write($files,'view/singleFile.php',$doc,$img);
				echo '<div class="left">';
					echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
					echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';	
				echo '</div>';
			echo '</form>';
		}
	?>
</div><br/>
	<div class="container large">
		<button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button>
		<?php files_Folders::show_albums($album_id); ?>
	</div>
