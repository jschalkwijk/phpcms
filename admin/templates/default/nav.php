<nav>
	<ul>
		<li><a href="<?php echo HOST; ?>">Home</a></li>
		<li><a href="<?php echo ADMIN; ?>">Dashboard</a></li>
		<li><a href="<?php echo ADMIN."posts"; ?>">Posts</a></li>
		<li><a href="<?php echo ADMIN."pages"; ?>">Pages</a></li>
		<li><a href="<?php echo ADMIN."files"; ?>">Files</a></li>
		<li><a href="<?php echo ADMIN."contacts"; ?>">Contacts</a></li>
		<?php if($_SESSION['rights'] == 'Admin') { ?> <li><a href="<?php echo ADMIN."users"; ?>">Users</a></li> <?php } ?>
		<!--
<li><a href="/admin/downloads">Downloads</a></li>
		<li><a href="/admin/projects">Projects</a></li>
-->
		<li><a href="<?php echo ADMIN."products"; ?>">Products</a></li>
		<li><a href="<?php echo ADMIN."cart"; ?>"><?php echo "Cart(".$this->basket->itemCount().")"; ?> </a></li>
		<?php
		$dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die ('Error connecting to server');
		$query = "SELECT title,path,approved FROM pages";
		$links = mysqli_query($dbc,$query);
		while($row = mysqli_fetch_array($links)) {
			// get url and place it in the menu.
			// add column with a Num so we can change the position in the menu, eg, first, second, last.
			if($row['approved'] == 1) {
				echo '<li><a href="'.ADMIN.$row['path'].'">'.$row['title'].'</a></li>';
			}
		}
		?>
		<li><a href="<?php echo ADMIN."search"; ?>"><img class="glyph-medium" alt="search" src="<?php echo IMG."search-1.png"; ?>"/></a></li>
		<li><a href="<?php echo HOME.'/login.php?id='.$_SESSION['user_id'].'&amp;username='.$_SESSION['username'];?>"><img class="glyph-medium" alt="logout" src="<?php echo IMG."logout-1.png"; ?>"/></a></li>
	</ul>
</nav>
<div id="main">