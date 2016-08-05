<?php
class Meta_MetaTag {
	public static function create_meta($dbt,$page_title){
		$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die('Error connecting to server');
		if($dbt === 'posts'){
			$page_title = mysqli_real_escape_string($dbc,trim($_GET['title']));
			$id = mysqli_real_escape_string($dbc,trim((int)$_GET['post_id']));
			$query = "SELECT title,description FROM ".$dbt." WHERE posts_id = $id";
		} else if($dbt === 'categories') {
			$page_title = mysqli_real_escape_string($dbc,trim($_GET['category']));
			$category = mysqli_real_escape_string($dbc,trim($_GET['category']));
			$query = "SELECT title,description FROM ".$dbt." WHERE title = '$category'";
		} else if($dbt === 'pages') {
			//create new {title} replace func to create the page name.
			$query = "SELECT title,description FROM ".$dbt." WHERE title = '$page_title'";
		}
		$data = mysqli_query($dbc,$query);
		$row = mysqli_fetch_array($data);
		echo '<meta name="title" content="'.$row['title'].'">';
		echo '<meta name="description" content="'.$row['description'].'">';
		echo '<title>'.$page_title.'</title>';
	}
} 
?>
