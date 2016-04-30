<?php
	$id = 22;
	$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('Error connecting to server');
	$query = "SELECT * FROM pages where page_id = $id";
	$page = mysqli_query($dbc,$query);
	while ($row = mysqli_fetch_array($page)) {
		echo '<h1 class="article-title">'.$row['title'].'</h1>';
		echo '<div class="article-content">'.'<p>'.$row['content'].'</p>';
	}
?>
