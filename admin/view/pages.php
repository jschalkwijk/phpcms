<div class="container">
	<a class="link-btn" href="pages/add-page">Create Page</a>
	<a class="link-btn" href="pages/deleted-pages">Deleted Pages</a>
</div>
<div class="center">
	<?php
	if (!empty($data['messages'])) {
		echo '<div class="container medium">';
		echo implode(",", $data['messages']);
		echo '</div>';
	}
	?>
	<form class="backend-form" method="post" action="<?php echo $data['action']; ?>">
		<table class="backend-table title">
			<tbody>
			<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="/admin/images/check.png" alt="check-unchek-all-items"/></button></th></tr>
			<?php
			// array of Post objects with data from the DB
			$pages = $data['pages'];

			foreach($pages as $single){
				// write out in the content_table format.
				require('view/content_table.php');
			}
			?>
			</tbody>
		</table>
		<?php
			require('view/manage_content.php');
		?>
	</form>
</div>