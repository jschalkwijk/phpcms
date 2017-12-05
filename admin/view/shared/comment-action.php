<table>
    <tr>
        <?php
            if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                <td class="td-btn">
                    <a class="btn btn-sm edit-link" href="<?= $single->table . '/edit/' . $single->get_id(); ?>"><img class="glyph-small"
                                                                                                                      alt="edit-item"
                                                                                                                      src="<?= IMG . 'edit.png'; ?>"/></a>
                </td>
                <?php if ($single->approved == 0) { ?>
                    <td><a class="btn btn-sm btn-info" href="/admin/<?= $single->table ?>/approve/<?= $single->get_id() ?>"><img
                                    class="glyph-small" alt="show-item"
                                    src="<?= IMG . 'hide.png' ?>"/></a></td>
                <?php } else if ($single->approved == 1) { ?>
                    <td><a class="btn btn-sm btn-success" href="/admin/<?= $single->table ?>/hide/<?= $single->get_id() ?>"><img
                                    class="glyph-small" alt="hide-item"
                                    src="<?= IMG . 'show.png' ?>"/></a></td>
                <?php } ?>
                    <td><a href="/admin/<?= $single->table ?>/destroy/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                    class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
                <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>

            <?php } ?>
    </tr>
</table>
