<?php
	$category = $data['category'];
	if (!isset($params['id'])) {
		echo 'There is no category selected.';
	}
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
			<?php
			if (isset($_POST['submit'])) {
				echo '<div class="container medium">';
					echo implode(",",$data['messages']);
				echo '</div>';
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
			<form id="edit-form" method="post" action="<?= ADMIN."categories/edit/".$category->get_id(); ?>">
				<input type="text" name="title" placeholder="Category" value="<?= $category->title;?>"/><br />
				<!-- When page first loads, the hidden field will containe the set title, if the user edits the title. we can change the corresponding post categories. 			 -->
				<input type="text" name="description" placeholder="Category Description (max 160 characters)" value="<?= $category->description; ?>"/><br />
				<p>Are you sure you want to edit the following category?</p>
				<input type="radio" name="confirm" value="Yes" /> Yes
				<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
				<button type="submit" name="submit">Submit</button>
			</form>
		</div>
	</div>
    <h3>Tree</h3><br>
    <?php
        echo $data['tree'];
    ?>
	<h3>Related Posts</h3><br>
	<?php
		foreach ($category->posts() as $post){
			echo $post->title."<br>";
		};
	?>
    <h3>Related Products</h3><br>
    <?php
        foreach ($category->products() as $product){
            echo $product->name."<br>";
        };
    ?>
</div>