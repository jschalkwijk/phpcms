<tr><td class="td-title"><p><?= $single->getTitle(); ?></p></td>
<td class="td-author"><p><?= $single->getAuthor(); ?></p></td>
<td class="td-category"><p><?= $single->getCategory(); ?></p></td>
<td class="td-date"><p><?= $single->getDate(); ?></p></td>
<?php 
if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
		<td class="td-btn"><a href="<?= ADMIN.$single->getDbt().'/edit-'.$single->getDbt().'/' .$single->getID().'/'.$single->getLink(); ?>"><img class="glyph-small link-btn" alt="edit-item" src="<?= IMG.'edit.png';?>"/></a></td>
	<?php if ($single->getApproved() == 0 ) { ?>
			<td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="<?= IMG.'hide.png'?>"/></td>
	<?php }	else if ($single->getApproved() == 1 ) { ?>
				<td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="<?= IMG.'show.png'?>"/></td>
	<?php } ?>
	<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->getID(); ?>"/></p></td>
<?php } ?>
</tr>