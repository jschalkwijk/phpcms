<div class="container">
	<table>
		<?php if($data['trashed'] === 0){ ?>
			<tr><th>Trash</th><th>Show</th><th>Hide</th></tr>
			<tr><button type="submit" name="trash-selected" id="trash-selected"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'trash-post.png'?>"/></button></tr>
			<tr><button type="submit" name="approve-selected" id="approve-selected"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'show.png'?>"/></button></tr>
			<tr><button type="submit" name="hide-selected" id="hide-selected"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'hide.png'?>"/></button></tr>
		<?php } else if($data['trashed'] === 1){ ?>
			<tr><th>Restore</th><th>Remove</th></tr>
			<tr><button type="submit" name="restore-selected" id="restore-selected"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'add-post.png'?>"/></button></tr>
			<tr><button type="submit" name="delete-selected" id="delete-selected"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'delete-post.png'?>"/></button></tr>
		<?php } ?>
	</table>
</div>