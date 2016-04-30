<?php	require_once('wysiwyg/editor.php');
require_once('blocks/include-files.php'); ?>
					<form id="addpost-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<input type="text" name="title" placeholder="Title" value="<?php if(!empty($title)) { echo $title; } ?>"><br />
						<input type="text" name="category" placeholder="Category" value="<?php if(!empty($category)) { echo $category; } ?>"><br />
						<textarea id="text" type="text" name="content" placeholder="Content" value="<?php if (!empty($content)) { echo $content; } ?>"></textarea><br />
						<iframe id="target" srcdoc="<?php if (!empty($content)) { echo $content; } ?>"></iframe><br />
						<button type="submit" id="submit_post">Submit</button>
					</form>
					<script src="wysiwyg/wysiwyg2.js"></script>
					<script type="text/javascript" src="wysiwyg/colorPick/jscolor.js"></script>