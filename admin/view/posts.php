<div class="container">
	<?php
	require_once($data['view']['search']);
	($data['trashed'] === 1) ? $action = ADMIN.'posts/deleted-posts' : $action = ADMIN.'posts' ;
	?>

	<form class="backend-form" method="post" action="<?php echo $action; ?>">
		<table class="backend-table title">
			<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?php echo IMG."/check.png"; ?>"/></button></th></tr>
			<?php
			// array of Post objects with data from the DB
			$posts = $data['posts'];
			// thisis a write function, it just writes out the data,
			// the information is supplied by the model
			foreach($posts as $single){
				// write out in the content_table format.
				require('view/content_table.php');
			}
			?>
		</table>
		<?php
			require($data['view']['actions']);
		?>
	</form>
</div>
