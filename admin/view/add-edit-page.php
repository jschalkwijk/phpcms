<script type="text/javascript" src="/admin/js/tinymce/tinymce.min.js"></script>
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
<script type="text/javascript" src="/admin/js/preview.js"></script>
<?php
$page = $data['page'];

(isset($params[0]) && isset($params[1])) ? $action = '/admin/pages/edit-page/'.$page->getID().'/'.$page->getTitle() : $action = '/admin/pages/add-page';
(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;

if (isset($_POST['submit'])) {
	echo '<div class="container medium">';
	echo implode(",",$data['messages']);
	echo '</div>';
}

if($output_form) { ?>
	<iframe id="target">hello</iframe>
	<form id="create-form" action="<?php echo $action; ?>" method="post">
		<input type="checkbox" name="front-end"/>
		<label for="front-end">Front-End Page</label>
		<input type="checkbox" name="back-end"/>
		<label for="back-end">Back-End Page</label>
		<select id="pages" name="sub_page">
			<option name="none" value="None">None</option>
			<?php $options = content_pages_Page::getSelection('none'); ?>
		</select>
		<input type="hidden" name="id" value="<?php echo $page->getID();?>"/>
		<input type="text" name="page_title" placeholder="Page Title" value="<?php echo $page->getTitle(); ?>"/><br />
		<input type="text" name="page_desc" placeholder="Page Description (max 160 characters)" value="<?php echo $page->getDescription(); ?>"/><br />
		<textarea type="text" name="page_content" placeholder="Content"><?php echo $page->getContent(); ?></textarea><br />
		<input type="text" name="page_url" placeholder="URL" value=""/><br />
		<input type="text" name="template" placeholder="Template URL" value=""/><br />
		<button type="submit" name="submit">Create Page</button>
	</form>
<?php } ?>