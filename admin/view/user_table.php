<tr>
<td class="td-title"><p><?php echo '<a href="/admin/users/profile/'.$single->getID().'/'.$single->getUsername().'">'.$single->getUsername().'</a>'; ?></p></td>
<td class="td-title"><p><?php echo $single->getRights(); ?></p></td>
	<input type="hidden" name="id" value="<?php echo $single->getID(); ?>"/>
	<input type="hidden" name="username" value="<?php echo $single->getUsername(); ?>" />
<?php 
if ($_SESSION['rights'] == 'Admin') { ?>
		<td class="td-btn"><a href="<?php echo 'users'.'/edit-'.$single->getDbt().'/' .$single->getID().'/'.$single->getUsername();?>"><img class="glyph-small link-btn" src="<?php echo IMG_UPLOADPATH.'edit.png';?>"/></a></td>
	<?php if ($approved == 0 ) { ?>
				<td class="td-btn"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'hide.png'?>"/></td>
	<?php } else if ($approved == 1 ) { ?>
				<td class="td-btn"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'show.png'?>"/></td>
	<?php } // remove to trash button ?>
		<td class="td-btn"><input type="checkbox" name="checkbox[]" value="<?php echo $id; ?>"/></td>
<?php } ?>
</tr>
