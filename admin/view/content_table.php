<tr><td class="td-title"><p><?php echo $single->getTitle(); ?></p></td>
<td class="td-author"><p><?php echo $single->getAuthor(); ?></p></td>
<td class="td-category"><p><?php echo $single->getCategory(); ?></p></td>
<td class="td-date"><p><?php echo $single->getDate(); ?></p></td>
<?php 
if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
		<td class="td-btn"><a href="<?php echo $single->getDbt().'/edit-'.$single->getDbt().'/' .$single->getID().'/'.$single->getLink(); ?>"><img class="glyph-small link-btn" alt="edit-item" src="<?php echo IMG.'edit.png';?>"/></a></td>
	<?php if ($single->getApproved() == 0 ) { ?>
			<td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="<?php echo IMG.'hide.png'?>"/></td>
	<?php }	else if ($single->getApproved() == 1 ) { ?>
				<td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="<?php echo IMG.'show.png'?>"/></td>
	<?php } ?>
	<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $single->getID(); ?>"/></p></td>
<?php } ?>
</tr>