<?php
$dbc = mysqli_connect('localhost','root','','userdatabase');

$per_page = 6;
$query = "SELECT * FROM pagination";
$data= mysqli_query($dbc,$query);
$pages= ceil(mysqli_num_rows($data) / $per_page);

$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$query2 = "SELECT * FROM pagination LIMIT '$start','$per_page'";
$show_pages = mysqli_query($dbc,$query2);

while($row = mysqli_fetch_assoc($show_pages)) {
	echo '<p>',$row['name'],'</p>';
}

if ($pages >= 1 && $page <= $pages) {
	for($i = 1; $i <=$pages;$i++) {
		echo ($i == $page) ? '<strong><a href="?page='.$i.'">'.$i.'</a></strong> ' : '<a href="?page='.$i.'">'.$i.'</a> ';
	}
} else {
	echo 'Page not found';
}	
?>