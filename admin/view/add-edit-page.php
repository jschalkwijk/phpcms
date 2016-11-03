<?php use CMS\model\Content\Pages\Page; ?>

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
<script type="text/javascript" src="<?= JS."preview.js"; ?>"></script>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php
			$page = $data['page'];

			(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'pages/edit-page/'.$page->getID().'/'.$page->getTitle() : $action = ADMIN.'pages/add-page';
			(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;

			if (isset($_POST['submit'])) {
				echo '<div class="medium">';
				echo implode(",",$data['messages']);
				echo '</div>';
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php if($output_form) { ?>
				<iframe id="target">hello</iframe>
				<form id="create-form" action="<?= $action; ?>" method="post">
					<input type="checkbox" name="front-end"/>
					<label for="front-end">Front-End Page</label>
					<input type="checkbox" name="back-end"/>
					<label for="back-end">Back-End Page</label>
					<select id="pages" name="sub_page">
						<option name="none" value="None">None</option>
						<?php $options = Page::getSelection('none'); ?>
					</select>
					<input type="hidden" name="id" value="<?= $page->getID();?>"/>
					<input type="text" name="page_title" placeholder="Page Title" value="<?= $page->getTitle(); ?>"/><br />
					<input type="text" name="page_desc" placeholder="Page Description (max 160 characters)" value="<?= $page->getDescription(); ?>"/><br />
					<textarea type="text" name="page_content" placeholder="Content"><?= $page->getContent(); ?></textarea><br />
					<input type="text" name="page_url" placeholder="URL" value=""/><br />
					<input type="text" name="template" placeholder="Template URL" value=""/><br />
					<button type="submit" name="submit">Create Page</button>
				</form>
			<?php } ?>
		</div>
	</div>
</div>
