<script type="text/javascript" src="/admin/js/tinymce/tinymce.min.js"></script>
<?php

	$post = $data['post'];

	(isset($params[0]) && isset($params[1])) ? $action = '/admin/posts/edit-posts/'.$post->getID().'/'.$post->getTitle() : $action = '/admin/posts/add-post';
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
	
	if (isset($_POST['submit'])) {
		echo '<div class="container medium">';
			echo implode(",",$data['errors']);			
			echo implode(",",$data['messages']);
		echo '</div>';
	}
	if ($output_form){ ?>
		<form id="addpost-form" action="<?php echo $action; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $post->getID();?>"/>
			<input type="text" name="title" placeholder="Title" value="<?php echo $post->getTitle(); ?>"><br />
			<input type="text" name="post_desc" placeholder="Post Description (max 160 characters)" value="<?php  echo $post->getDescription();?>"/><br />
			<label for="select">Category</label>
			<select id="categories" name="cat_name">
				<option name="none" value="None">None</option>
				<?php $category = content_Categories::getSelected($post->getCategory(),'post'); ?>
			</select>
			
			<input type="text" name="category" placeholder="Category"/><br />
			<input type="hidden" name="cat_type" value="post"/><br />
			<textarea type="text" name="content" placeholder="Content"><?php echo $post->getContent(); ?></textarea><br />
			
			<?php if (isset($params[0]) && isset($params[1])) { ?>
				<p>Are you sure you want to edit the following product?</p>
				<input type="radio" name="confirm" value="Yes" /> Yes
				<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
			<?php } ?>
			
			<button type="submit" name="submit">Submit</button>
		</form>
<div id="return">
	<?php }
	require_once('blocks/include-files-tinymce.php');
	?>
</div>
<div class="container"><button id="ajax" name="ajax" value="Jorn">Ajax</button></div>



