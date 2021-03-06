<?php use CMS\Models\Categories\Categories; ?>

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

<script type="text/javascript" src="<?= "/admin/view/posts/edit-lock/edit-lock.js";?>"></script>

<?php
    foreach ($data['js'] as $script){
        echo '<script type="text/javascript" src="'.$script.'"></script>';
    }
	$post = $data['post'];
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
<div id="main" class="container">
	<div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <?php
            foreach($data['errors'] as $error){ ?>
                <div class="alert alert-warning"><?= $error ?></div>
            <?php } ?>
        </div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-6">
		<?php
			(isset($params['id'])) ? $action = ADMIN . 'posts/update/' . $post->post_id : $action = ADMIN . 'posts/create';
			?>
			<form id="addpost-form" class="large" action="<?= $action; ?>" method="post">
				<input type="text" name="title" placeholder="Title"
					   value="<?= $post->title; ?>"><br/>
				<input type="text" name="description" placeholder="Post Description (max 160 characters)"
					   value="<?= $post->description ?>"/><br/>
				<label for="select">Category</label>
				<select id="categories" name="category_id">
					<option name="none" value="None">None</option>
					<?php $category = Categories::getSelected($post->category_id, 'post'); ?>
				</select>
				<select id="tags" name="tag_id" multiple>
					<option name="none" value="None">None</option>
					<?php
						foreach ($data['tags'] as $tag){
							if(!in_array($tag->tag_id,$data['selectedTag'])) {
								echo "<option name='$tag->title' value='$tag->tag_id'>$tag->title</option>";
							} else {
								echo "<option name='$tag->title' value='$tag->tag_id' selected>$tag->title</option>";
							}
						}
					?>
				</select>
				<input type="text" name="category" placeholder="Category"
					   value=""/><br/>
				<input type="hidden" name="cat_type" value="post"/><br/>
				<textarea type="text" name="content"
						  placeholder="Content"><?= $post->keep($post->content); ?></textarea><br/>

				<?php if (isset($params['id'])) { ?>
					<p>Are you sure you want to edit the following product?</p>
					<input type="radio" name="confirm" value="Yes"/> Yes
					<input type="radio" name="confirm" value="No" checked="checked"/> No <br/>
				<?php } ?>

				<button type="submit" name="submit">Submit</button>
			</form>
        </div>
		<div id="return" class="col-sm-6 col-lg-6">
			<?php
				require_once('./view/shared/include-files-tinymce.php');
			?>
		</div>

	</div>
</div>



