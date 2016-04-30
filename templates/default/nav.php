<nav>
	<ul>
		<li><a href="<?php echo HOST.'/home' ;?>">Home</a></li>
		<li><a href="<?php echo HOST.'/about' ;?>">About Me</a></li>
		<li><a href="<?php echo HOST.'/skills' ;?>">Skills</a></li>
		<li><a href="<?php echo HOST.'/portfolio' ;?>">Portfolio</a></li>
		<li><a href="<?php echo HOST.'/blog' ;?>">Blog</a></li>
		<li><a href="<?php echo HOST.'/contact' ;?>">Contact</a></li>
		<?php
		$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die ('Error connecting to server');
		$query = "SELECT * FROM pages";
		$links = mysqli_query($dbc,$query);
		while($row = mysqli_fetch_array($links)) {
			// get url and place it in the menu.
			// add column with a Num so we can change the position in the menu, eg, first, second, last.
			if($row['approved'] == 1) {
				echo '<li><a href="'.$row['path'].'">'.$row['title'].'</a></li>';
			}
		}
		?>
	</ul>
</nav>