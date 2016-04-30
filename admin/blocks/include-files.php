<?php
	$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('Error connecting to server');
	$output = '';
	if(isset($_POST['search-file'])){
		$searchq = mysqli_real_escape_string($dbc,trim($_POST['search']));
		// replaces everything that is not a number or letter with nothing
		$searchq = preg_replace("#[^0-9a-z]#i"," ",$searchq);
		$searchTerms = explode(' ', $searchq);
		$searchTermBits = array();
		foreach ($searchTerms as $term) {
			$term = trim($term);
			if (!empty($term)) {
				$searchTermBits[] = "name LIKE '%$term%'";
			}
		}
		$query = "SELECT * FROM files WHERE ".implode(' AND ', $searchTermBits);
		$result = mysqli_query($dbc,$query) or die('Error connecting to database');
		echo '<div class="container large">';
		echo '<form>';
		while($row = mysqli_fetch_assoc($result)) {	
			echo '<div class="media">';
			echo '<a href="files/'.$row['file_name'].'">'.'<img class="files xxsmall" src="'.'files/thumbs/'.$row['thumb_name'].'"/>'.'</a>';
			echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">';
			// the value of the radio button corresponds to the actual filename stored in the
			// DB, we can get this value with JS and then add the image with the correct src.
			echo '<input type="radio" name="radio[]" value="'.$row['file_name'].'"/>';
			echo '<span> '.$row['name'].'</span>';
			echo '</div>';
		}
		echo '</form>';
		echo '</div>';
	}
	mysqli_close($dbc);
?>
<form class="container search" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<input type="text" name="search" placeholder="Search files"/>
	<button type="submit" name="search-file">Search</button>
</form><br />
<?php 
/*
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to database');
	$query1 = "SELECT * FROM files";
	$data = mysqli_query($dbc,$query1);
	echo '<form>';
	while($row = mysqli_fetch_assoc($data)) {
		echo '<div class="media">';
		echo '<a href="files/'.$row['file_name'].'">'.'<img class="files" src="'.'files/thumbs/'.$row['thumb_name'].'"/>'.'</a>';
		echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">';
		// the value of the radio button corresponds to the actual filename stored in the
		// DB, we can get this value with JS and then add the image with the correct src.
		echo '<input type="radio" name="radio[]" value="'.$row['file_name'].'"/>';
		echo '<span> '.$row['name'].'</span>';
		echo '</div>';
	}
	echo '</form>';
	*/
?>