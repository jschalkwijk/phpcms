<tr>
<td class="td-title"><p><?php echo '<a href="'.ADMIN.'users/profile/'.$single->getID().'/'.$single->getUsername().'">'.$single->getUsername().'</a>'; ?></p></td>
<td class="td-title"><p><?php echo $single->getRights(); ?></p></td>
	<!--<p><input type="hidden" name="id" value="<?php /*echo $single->getID(); */?>"/></p>
	<p><input type="hidden" name="username" value="<?php /*echo $single->getUsername(); */?>" /></p>-->
<?php 
if ($_SESSION['rights'] == 'Admin') { ?>
		<td class="td-btn"><a href="<?php echo ADMIN.'users/edit-'.$single->getDbt().'/' .$single->getID().'/'.$single->getUsername();?>"><img class="glyph-small link-btn" src="<?php echo IMG.'edit.png';?>" alt="edit-item"/></a></td>
	<?php if ($approved == 0 ) { ?>
				<td class="td-btn"><img class="glyph-small" src="<?php echo IMG.'hide.png'?>" alt="item-hidden-from-front-end-user"/></td>
	<?php } else if ($approved == 1 ) { ?>
				<td class="td-btn"><img class="glyph-small" src="<?php echo IMG.'show.png'?>" alt="item-visible-from-front-end-user"/></td>
	<?php } // remove to trash button ?>
		<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $id; ?>"/></p></td>
<?php } ?>
</tr>
