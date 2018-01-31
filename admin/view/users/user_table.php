<tr>
<td class="td-title"><p><?= '<a href="'.ADMIN.'users/'.$single->user_id.'">'.$single->username.'</a>'; ?></p></td>
<td class="td-title"><p><?php foreach ($single->roles() as $role){ echo $role->name.' | '; }; ?></p></td>
	<!--<p><input type="hidden" name="id" value="<?php /*echo $single->getID(); */?>"/></p>
	<p><input type="hidden" name="username" value="<?php /*echo $single->getUsername(); */?>" /></p>-->
    <?php
        if ($this->user->hasRole('admin')) { ?>
            <td class="td-btn">
                <a class="btn btn-sm edit-link" href="<?= $single->table . '/edit/' . $single->get_id(); ?>"><img class="glyph-small"
                                                                                                                  alt="edit-item"
                                                                                                                  src="<?= IMG . 'edit.png'; ?>"/></a>
            </td>
            <?php if ($single->approved == 0) { ?>
                <td><a class="btn btn-sm btn-info" href="/admin/contacts/approve/<?= $single->get_id() ?>"><img
                                class="glyph-small" alt="show-item"
                                src="<?= IMG . 'hide.png' ?>"/></a></td>
            <?php } else if ($single->approved == 1) { ?>
                <td><a class="btn btn-sm btn-success" href="/admin/contacts/hide/<?= $single->get_id() ?>"><img
                                class="glyph-small" alt="hide-item"
                                src="<?= IMG . 'show.png' ?>"/></a></td>
            <?php }
            if ($single->trashed == 0) { ?>
                <td><a href="/admin/contacts/trash/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                class="glyph-small" alt="trash-item" src="<?= IMG . 'trash-post.png' ?>"/></a></td>
                <?php
            } else if ($single->trashed == 1) { ?>
                <td><a href="/admin/contacts/destroy/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
            <?php } ?>
            <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>

        <?php } ?>
</tr>
