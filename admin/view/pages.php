<div class="container">
	<a href="pages/add-page"><button>Create Page</button></a>
	<a href="pages/deleted-pages"><button>Deleted Pages</button></a>	
</div>
<div class="container">
	<?php

	if (!empty($data['messages'])) {
		echo '<div class="container medium">';
		echo implode(",", $data['messages']);
		echo '</div>';
	}
	?>
	<table class="backend-table title">
		<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><th><button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button></th></tr>
		<form class="backend-form" method="post" action="<?php echo $data['action']; ?>">
			<?php 		
				// array of Post objects with data from the DB
				$pages = $data['pages'];

				// this is a write function, it just writes out the data,
				// the information is supplied by the model
				$writer = content_ContentWriter::write($pages); 
				require('view/manage_content.php');
			?>
		</form>	
	</tr></table>
</div>