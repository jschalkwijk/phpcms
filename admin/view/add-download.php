<div class="container">
	<a href="<?= ADMIN."downloads/deleted-downloads"; ?>"><button>Deleted Downloads</button></a>
</div>
<?php
	if(isset($_POST['submit_file'])){
		$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('Error connecting to server');
		$errors = []; 
		$title = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['title'])));
		$category = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['category'])));
		$desc = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['description'])));
		$content = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['content'])));
		$down_url = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['down_url'])));
		$author = $_SESSION['username'];
		//$demo_url = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['demo_url'])));
		//$file_url = mysqli_real_escape_string($dbc,trim(htmlentities($_POST['file_url'])));
		(empty($title)) ? $errors[] = 'You forgot the title.' : '';
		(empty($category)) ? $errors[] = 'You forgot the category.' : '';
		(empty($content)) ? $errors[] = 'You forgot the content.' : '';
		(empty($down_url)) ? $errors[] = 'You forgot the download URL.' : '';
		if (!empty($title) && !empty($category) && !empty($content) && !empty($down_url)) {
			//get_files_func();
			$file_destination = '../downloads/';
			$thumb_destination = '../downloads/thumbs/';
			$create_sub_folder = false;
			//processUpload($file_destination,$thumb_destination,$create_sub_folder);
			$sql_data = [
				'title' => $title,
				'description' => $desc,
				'content' => $content,
				'author' => $author,
				'category' =>$category,
				'down_url' =>$down_url,
				'approved' => 0
			];
			$fields_query = implode(",", array_keys($sql_data));
			$values_query = "'".implode("','",$sql_data)."'";
			$query = "INSERT INTO downloads($fields_query,date) VALUES($values_query,NOW())";
			mysqli_query($dbc,$query) or die('Error connecting to database');
			echo 'Your post has been added';
		} else {
			$errors[] = 'New download post failed to upload';
		}
	}
?>

<div class="container large">
	<?php if(!empty($errors)){
			echo implode('<br />',$errors);
		}
	?>
	<form class="large" enctype="multipart/form-data" method="post" action="<?= $_SERVER['PHP_SELF']?>">
	<input type="text" name="title" placeholder="title"/><br />
	<input type="text" name="category" placeholder="category"/><br />
	<input type="text" name="description" placeholder="description"/><br />
	<input type="text" name="content" placeholder="content"/><br />
	<!--
	<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
	<input type="file" name="files[]" multiple/><br />
	<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
	<input type="checkbox" name="public" value="public"/>
	<label for='public'>Public</label>
	<input type="checkbox" name="secure" value="secure"/>
	<label for='secure'>Secure</label>
	-->
	<input type="text" name="demo_url" placeholder="demo_url"/><br />
	<input type="text" name="down_url" placeholder="file_url"/><br />
	<button type="submit" name="submit_file">Add Download</button>
	</form>
</div>