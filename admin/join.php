<?php
$dbc = mysqli_connect('localhost','root','root','nerdcms_db');

//Left Join
$query = "SELECT people.*, countries.name as country FROM people LEFT JOIN countries ON people.country_id = countries.country_id";
$data = mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($data)){
	echo $row['first_name'].'('.$row['country'].')'.'<br />';
}	
	
?>