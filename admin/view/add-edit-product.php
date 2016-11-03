<?php use CMS\model\Content\Categories; ?>
<script type="text/javascript" src="<?= ADMIN."/vendor/tinymce/tinymce/tinymce.min.js"; ?>"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>

<?php

	$product = $data['product'];

	(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'products/edit-product/'.$product->getID().'/'.$product->getName() : $action = ADMIN.'products/add-product';
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6">
		<?php
			if (isset($_POST['submit_product'])) {
				echo '<div class="container medium">';
					echo implode(",",$data['errors']);
					echo implode(",",$data['messages']);
				echo '</div>';
			}
		?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6">
			<?php
				if ($output_form){ ?>
				<form id="addpost-form" action="<?= $action; ?>" method="post">
					<input type="hidden" name="id" value="<?= $product->getID();?>"/>
					<input type="text" name="name" placeholder="Name" value="<?= $product->getName(); ?>" required="required" title="He daar, ik mis nog wat informatie!."/><br />
					<input type="number" name="price" placeholder="Price" pattern="(^\d+(\.|\,)\d{2}$)" min="0" value="<?= $product->getPrice(); ?>">
					<input type="number" name="quantity" placeholder="Quantity between 0 and 1000" min="0" max="1000" value="<?= $product->getQuantity(); ?>" required="required" title="He daar, ik mis nog wat informatie!."/>

					<select id="categories" name="cat_name">
						<option name="none" value="None">None</option>'
						<?php $category = Categories::getSelected($product->getCategory(),'product'); ?>
					</select>

					<input type="text" name="category" placeholder="Category"/><br />
					<input type="hidden" name="cat_type" value="product"/><br />
					<textarea type="text" name="description" placeholder="Description" value="<?php  echo $product->getDescription();?>"><?= $product->getDescription(); ?></textarea><br />

					<?php if (isset($params[0]) && isset($params[1])) { ?>
						<p>Are you sure you want to edit the following product?</p>
						<input type="radio" name="confirm" value="Yes" /> Yes
						<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
					<?php } ?>

					<button type="submit" name="submit_product">Submit</button>
				</form>
			</div>
		<div class="col-sm-6 col-md-6">
			<?php }
				require_once('include-files-tinymce.php');
			?>
		</div>
	</div>
</div>
