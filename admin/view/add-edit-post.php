<?php
	use CMS\model\Content\Categories;
?>

<script type="text/javascript" src="<?= ADMIN."/vendor/tinymce/tinymce/tinymce.min.js"; ?>"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste",

		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		paste_data_images: true,
		relative_urls :false,
		convert_urls: true
	});
</script>
<script type="text/javascript" src="<?= JS."preview.js";?>"></script>

<?php

	$post = $data['post'];

	(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'posts/edit-posts/'.$post->getID().'/'.$post->getTitle() : $action = ADMIN.'posts/add-post';
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6">
			<?php
			if (isset($_POST['submit'])) {
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
			<?php if ($output_form){ ?>
				<form id="addpost-form" class="large" action="<?= $action; ?>" method="post">
					<input type="hidden" name="id" value="<?= $post->getID();?>"/>
					<input type="text" name="title" placeholder="Title" value="<?= $post->getTitle(); ?>"><br />
					<input type="text" name="post_desc" placeholder="Post Description (max 160 characters)" value="<?php  echo $post->getDescription();?>"/><br />
					<label for="select">Category</label>
					<select id="categories" name="cat_name">
						<option name="none" value="None">None</option>
						<?php $category = Categories::getSelected($post->getCategory(),'post'); ?>
					</select>

					<input type="text" name="category" placeholder="Category"/><br />
					<input type="hidden" name="cat_type" value="post"/><br />
					<textarea type="text" name="content" placeholder="Content"><?= $post->getContent(); ?></textarea><br />

					<?php if (isset($params[0]) && isset($params[1])) { ?>
						<p>Are you sure you want to edit the following product?</p>
						<input type="radio" name="confirm" value="Yes" /> Yes
						<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
					<?php } ?>

					<button type="submit" name="submit">Submit</button>
				</form>
		</div>
		<div class="col-sm-6 col-md-6">
			<?php }
				require_once('include-files-tinymce.php');
			?>
		</div>

	</div>
</div>



