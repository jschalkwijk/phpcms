<tr><td class="td-title"><p><?= $single->title; ?></p></td>
	<td class="td-author"><p><?= $single->users_username; ?></p></td>
	<td class="td-category"><p><?= $single->categories_title; ?></p></td>
	<td class="td-category">
		<p>
	<?php
		if(is_callable([$single,"tags"])) {
			foreach ($single->tags() as $tag){
				echo " | " . $tag->title;
			}
		}
	?>
		<p>
	</td>
	<td class="td-date"><p><?= $single->date; ?></p></td>
	<?php
	if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
		<td class="td-btn"><a href="<?= $single->table.'/edit/' .$single->get_id(); ?>"><img class="glyph-small link-btn" alt="edit-item" src="<?= IMG.'edit.png';?>"/></a></td>
		<?php if ($single->approved == 0 ) { ?>
			<td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="<?= IMG.'hide.png'?>"/></td>
		<?php }	else if ($single->approved == 1 ) { ?>
			<td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="<?= IMG.'show.png'?>"/></td>
		<?php } ?>
		<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>
	<?php } ?>
</tr>