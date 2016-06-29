<table>
	<?php if($data['trashed'] === 0){ ?>
		<tr><th>Trash</th><th>Show</th><th>Hide</th></tr>
		<tr>
			<td><p><button type="submit" name="trash-selected" id="trash-selected"><img class="glyph-small" alt="trash-selected" src="<?php echo IMG.'trash-post.png'?>"/></button></p></td>
			<td><p><button type="submit" name="approve-selected" id="approve-selected"><img class="glyph-small" alt="approve-selected-for-front-end-view" src="<?php echo IMG.'show.png'?>"/></button></p></td>
			<td><p><button type="submit" name="hide-selected" id="hide-selected"><img class="glyph-small" alt="hide-selected-from-front-end-view" src="<?php echo IMG.'hide.png'?>"/></button></p></td>
		</tr>
	<?php } else if($data['trashed'] === 1){ ?>
		<th>Restore</th><th>Remove</th></tr>
		<tr>
			<td><p><button type="submit" name="restore-selected" id="restore-selected"><img class="glyph-small" alt="restore-selected-from-trash" src="<?php echo IMG.'add-post.png'?>"/></button></p></td>
			<td><p><button type="submit" name="delete-selected" id="delete-selected"><img class="glyph-small" alt="delete-selected-from-trash" src="<?php echo IMG.'delete-post.png'?>"/></button></p></td>
		</tr>
	<?php } ?>
</table>
