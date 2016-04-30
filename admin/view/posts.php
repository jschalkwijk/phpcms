<div class="container">
	<?php 
		require_once($data['view']['search']);
		($data['trashed'] === 1) ? $action = '/admin/posts/deleted-posts' : $action = '/admin/posts' ;
	?>
	<table class="backend-table title">
		<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><th><button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button></th></tr>
		<form class="backend-form" method="post" action="<?php echo $action; ?>">
			<?php	
				// array of Post objects with data from the DB
				$posts = $data['posts'];
				// thisis a write function, it just writes out the data,
				// the information is supplied by the model
				$writer = content_ContentWriter::write($posts); 
				require($data['view']['actions']);
			?>
		</form>	
	</tr></table>
</div>
