<?php include_once "../../config.php"; ?>
<div class="xsmall">
	<div class="container search">
		<input type="text" id="search" name="search" placeholder="Search files"/>
		<button type="submit" id="search-file" name="search-file">Search</button>
	</div><br />
	<?php
	$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('Error connecting to server');
	$output = '';
	if(isset($_POST['search-file'])) {
		$table = 'files';
		$searchq = mysqli_real_escape_string($dbc, trim($_POST['search']));
		// replaces everything that is not a number or letter with nothing
		$searchq = preg_replace("#[^0-9a-z]#i", " ", $searchq);
		$searchTerms = explode(' ', $searchq);
		$searchTermBits = array();
		foreach ($searchTerms as $term) {
			$term = trim($term);
			if (!empty($term)) {
				$searchTermBits[] = "{$table}.name LIKE '%$term%'";
			}
		}
//		$query = "SELECT * FROM files WHERE " . implode(' AND ', $searchTermBits);
		$query = "SELECT files.*, albums.path AS albums_path FROM files JOIN albums ON files.album_id = albums.album_id WHERE " . implode(' AND ', $searchTermBits);
		$result = mysqli_query($dbc, $query) or die('Error connecting to database');
	?>
		<div id="add-image" class="container medium">
			<button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button>
	<?php
		while ($row = mysqli_fetch_assoc($result)) {
	?>
			<div class="media">
				<input class="checkbox left" type="checkbox" name="checkbox[]" value="<?php echo ADMIN.$row['albums_path']."/thumbs/".$row['thumb_name']."#".ADMIN.$row['albums_path']."/".$row['name'];?>"/>
<!--					<div class="left center">--><?php //echo $row['name'];?><!--</div>-->
				<a class="image_link" href="#">
					<img class="files" src="<?php echo ADMIN.$row['albums_path']."/thumbs/".$row['thumb_name'];?>" name="<?php echo ADMIN.$row['path'];?>"/>
				</a>


				<!--			// the value of the radio button corresponds to the actual filename stored in the-->
				<!--			// DB, we can get this value with JS and then add the image with the correct src.-->
<!--				<span><strong>--><?php //echo $row['name']; ?><!--</strong></span><br />-->
			</div>
			</br>
		<?php } ?>
			<button id="add-multiple" type="button" name="add-multiple">Add Selection</button>
			</div>
	<?php
		mysqli_close($dbc);
	}
	?>
</div>