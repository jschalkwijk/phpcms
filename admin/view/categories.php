<div class="container">
	<table class="backend-table title">
			<tr><th>Category</th><th>Author</th><th>N/A</th><th>Date</th><th>Edit</th><th>Approve</th><th>Remove</th></tr>
			<form class="backend-form" method="post" action="/admin/categories">
		<?php 
				$categories = $data['categories'];
				// thisis a write function, it just writes out the data,
				// the information is supplied by the model
				$writer = content_ContentWriter::write($categories);
				require('view/manage_content.php'); 
			?>
		</form>	
	</table>
</div>