<script type="text/javascript" src="/admin/js/tinymce/tinymce.min.js"></script>
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

	(isset($params[0]) && isset($params[1])) ? $action = '/admin/products/edit-product/'.$product->getID().'/'.$product->getName() : $action = '/admin/products/add-product';
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
	<div class="container small">
		<form class="small" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
		<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
		<input type="file" name="files[]" multiple/><br />
		<input type="checkbox" name="public" value="public"/>
		<label for='public'>Public</label>
		<input type="checkbox" name="secure" value="secure"/>
		<label for='secure'>Secure</label>
		<input type="hidden" name="album_name" value=""/>
		<input type="hidden" name="category_name" value="<?php echo $product->getCategory(); ?>"/>
		<input type="hidden" name="album_id" value="<?php echo $product->getAlbumID(); ?>"/>
		<?php (isset($params)) ? files_Folders::get_albums($product->getAlbumID(),$params[1]) : files_Folders::get_albums(null,null) ;?>
	<?php 	if(!isset($_GET['album'])){ ?>
				<input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
	<?php 	} else { ?>
				<input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
	<?php 	} ?>
		<button type="submit" name="submit_file">Add File('s)</button>
		</form>
	</div>

<?php
	if (isset($_POST['submit_product'])) {
		echo '<div class="container medium">';
			echo implode(",",$data['errors']);			
			echo implode(",",$data['messages']);		
		echo '</div>';
	}
	if ($output_form){ ?>
		<form id="addpost-form" action="<?php echo $action; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $product->getID();?>"/>
			<input type="text" name="name" placeholder="Name" value="<?php echo $product->getName(); ?>" required="required" title="He daar, ik mis nog wat informatie!."/><br />
			<input type="number" name="price" placeholder="Price" pattern="(^\d+(\.|\,)\d{2}$)" min="0" value="<?php echo $product->getPrice(); ?>">
			<input type="number" name="quantity" placeholder="Quantity between 0 and 1000" min="0" max="1000" value="<?php echo $product->getQuantity(); ?>" required="required" title="He daar, ik mis nog wat informatie!."/>
			
			<select id="categories" name="cat_name">
				<option name="none" value="None">None</option>'
				<?php $category = content_Categories::getSelected($product->getCategory(),'product'); ?>
			</select>
			
			<input type="text" name="category" placeholder="Category"/><br />
			<input type="hidden" name="cat_type" value="product"/><br />
			<textarea type="text" name="description" placeholder="Description" value="<?php  echo $product->getDescription();?>"><?php echo $product->getDescription(); ?></textarea><br />
			
			<?php if (isset($params[0]) && isset($params[1])) { ?>
				<p>Are you sure you want to edit the following product?</p>
				<input type="radio" name="confirm" value="Yes" /> Yes
				<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
			<?php } ?>
			
			<button type="submit" name="submit_product">Submit</button>
		</form>

<?php }
require_once('blocks/include-files-tinymce.php'); 
?>