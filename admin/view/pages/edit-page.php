<?php use CMS\Models\DBC\DBC; ?>

<script type="text/javascript" src="<?= JS."tinymce/tinymce.min.js"; ?>"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
<?php 
	$dbc = new DBC;
	if (isset($params['id']) && isset($params['name'])) {
		// Grab the score data from the GET
		$id = mysqli_real_escape_string($dbc->connect(),trim((int)$params['id']));
		$title = mysqli_real_escape_string($dbc->connect(),trim($params['name']));

	  } else if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
		// Grab the score data from the POST
		$id = mysqli_real_escape_string($dbc->connect(),trim($_POST['id']));
		$title = mysqli_real_escape_string($dbc->connect(),trim($_POST['title']));
		$page_desc = mysqli_real_escape_string($dbc->connect(),trim($_POST['page_desc']));
		$content = mysqli_real_escape_string($dbc->connect(),trim($_POST['content']));
		$dbc->disconnect();
	  } else {
		echo 'There is no page selected.';
	  }

	if (isset($_POST['submit'])) {
		if ($_POST['confirm'] == 'Yes') {
		  // Edit the post data from the database
		  $query = "UPDATE pages SET title = '$title',description = '$page_desc',content = '$content' WHERE id = '$id';";
		  mysqli_query($dbc->connect(), $query);
		  $dbc->disconnect();

		  // Confirm success with the user
		  echo '<p>The page with title ' . $title . ' and content ' . $content . ' was successfully edited.';
		}
		else {
		  echo '<p class="error">The page was not edited.</p>';
		}
	  }
	if (isset($id) && isset($title)) {
		$query = "SELECT * FROM pages WHERE id = $id";
		$data = mysqli_query($dbc->connect(),$query);

		while($row = mysqli_fetch_array($data)) {
?>
		<form id="edit-form"method="post" action="<?= ADMIN."edit-pages.php"; ?>">
			<input type="hidden" name="id" value="<?= $row['id'];?>"/>
			<input type="text" name="title" value="<?= $row['title'];?>"/><br />
			<input type="text" name="page_desc" placeholder="Page Description (max 160 characters)" value="<?php if(!empty($row['description'])){ echo $row['description']; }?>"/><br />
			<textarea type="text" name="content"><?= $row['content']?></textarea><br />
			<p>Are you sure you want to edit the following page?</p>
			<input type="radio" name="confirm" value="Yes" /> Yes
			<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
			<button type="submit" name="submit">Submit</button>
		</form>
<?php 
	}// end while
	$dbc->disconnect();
}//end else/if ?>	