<?php use CMS\model\Content\ContentWriter; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<a href="categories/deleted-categories"><button>Deleted Categories</button></a>
			<?php if (isset($data['errors'])) { echo implode(",",$data['errors']); } ?>
			<form id="addpost-form" action="<?= ADMIN."categories";?>" method="post">
				<input type="text" name="category" placeholder="Category"><br />
				<button type="submit" name="submit">Submit</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<table class="backend-table title">
					<tr><th>Category</th><th>Author</th><th>N/A</th><th>Date</th><th>Edit</th><th>Approve</th><th>Remove</th></tr>
					<form class="backend-form" method="post" action="<?= ADMIN."categories";?>">
				<?php
						$categories = $data['categories'];
						// thisis a write function, it just writes out the data,
						// the information is supplied by the model
						ContentWriter::write($categories);
						require('view/manage_content.php');
					?>
				</form>
			</table>
		</div>
	</div>
</div>