<tr><td class="td-title"><p><?php echo $single->getTitle(); ?></p></td>
<td class="td-author"><p><?php echo $single->getAuthor(); ?></p></td>
<td class="td-category"><p><?php echo $single->getCategory(); ?></p></td>
<td class="td-date"><p><?php echo $single->getDate(); ?></p></td> 
<input type="hidden" name="id" value="<?php echo $single->getID() ?>" />
<?php 
if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
		<td class="td-btn"><a href="<?php echo $single->getDbt().'/edit-'.$single->getDbt().'/' .$single->getID().'/'.$single->getLink(); ?>"><img class="glyph-small link-btn" src="<?php echo IMG_UPLOADPATH.'edit.png';?>"/></a></td>
	<?php if ($single->getApproved() == 0 ) { ?>
			<td class="td-btn"><!-- <button id="btn-hide" type="submit" name="approve"> --><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'hide.png'?>"/><!-- </button> --></td>
	<?php }	else if ($single->getApproved() == 1 ) { ?>
				<td class="td-btn"><!-- <button id="btn-approve" type="submit" name="hide" alt="approve"> --><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'show.png'?>"/><!-- </button> --></td>
	<?php } ?>
	<td class="td-btn"><input type="checkbox" name="checkbox[]" value="<?php echo $single->getID(); ?>"/></td>
<?php } ?>
</tr>