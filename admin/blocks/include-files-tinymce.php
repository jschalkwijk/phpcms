<div class="xsmall">
	<div class="container search">
		<input type="text" id="search" name="search" placeholder="Search files"/>
		<button type="submit" id="search-file" name="search-file">Search</button>
	</div><br />
	<?php
	$dbc = mysqli_connect('localhost','root','root','nerdcms_db') or die('Error connecting to server');
	$output = '';
	if(isset($_POST['search-file'])) {
		$searchq = mysqli_real_escape_string($dbc, trim($_POST['search']));
		// replaces everything that is not a number or letter with nothing
		$searchq = preg_replace("#[^0-9a-z]#i", " ", $searchq);
		$searchTerms = explode(' ', $searchq);
		$searchTermBits = array();
		foreach ($searchTerms as $term) {
			$term = trim($term);
			if (!empty($term)) {
				$searchTermBits[] = "name LIKE '%$term%'";
			}
		}
		$query = "SELECT * FROM files WHERE " . implode(' AND ', $searchTermBits);
		$result = mysqli_query($dbc, $query) or die('Error connecting to database');
	?>
		<div id="add-image" class="container medium">
			<button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button>
	<?php
		while ($row = mysqli_fetch_assoc($result)) {
	?>
			<div class="media">
				<div class="meta">
					<input class="checkbox left" type="checkbox" name="checkbox[]" value="<?php echo "/admin/".$row['thumb_path']."#"."/admin/".$row['path'];?>"/>
					<div class="left center"><?php echo $row['name'];?></div>
				</div>
				<div class="center">
					<a class="image_link" href="#">
						<img class="files" src="<?php echo "/admin/".$row['thumb_path'];?>" name="<?php echo "/admin/".$row['path'];?>"/>
					</a>
				</div>

				<!--			// the value of the radio button corresponds to the actual filename stored in the-->
				<!--			// DB, we can get this value with JS and then add the image with the correct src.-->
				<span><strong><?php echo $row['name']; ?></strong></span><br />
			</div>
		<?php } ?>
			<button id="add-multiple" type="button" name="add-multiple">Add Selection</button>
			</div>
	<?php
		mysqli_close($dbc);
	}
	?>
</div>