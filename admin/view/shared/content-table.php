<tr>
    <td class="td-title"><p><?= $single->title; ?></p></td>
	<td class="td-author"><p><?= $single->users_username; ?></p></td>
	<td class="td-category"><p><?php
            if(is_callable([$single,"category"])) {
                foreach ($single->category() as $cat){
                    echo $cat->title;
                }
            }
//            echo $single->categories_title;
            ?></p></td>
	<td class="td-category">
		<p>
	<?php
		if(is_callable([$single,"tags"])) {
			foreach ($single->tags() as $tag){
				echo " | " . $tag->title;
			}
		}
	?>
		</p>
	</td>
	<td class="td-date"><p><?= $single->date; ?></p></td>
    <?php
        if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
            <td class="td-btn">
                <a href="<?= $single->table . '/edit/' . $single->get_id(); ?>"><img class="glyph-small link-btn"
                                                                                     alt="edit-item"
                                                                                     src="<?= IMG . 'edit.png'; ?>"/></a>
            </td>
            <?php if ($single->approved == 0) { ?>
                <td><a class="btn btn-sm btn-info" href="/admin/posts/approve/<?= $single->get_id() ?>"><img
                                class="glyph-small" alt="item-hidden-from-front-end-user"
                                src="<?= IMG . 'hide.png' ?>"/></a></td>
            <?php } else if ($single->approved == 1) { ?>
                <td><a class="btn btn-sm btn-success" href="/admin/posts/hide/<?= $single->get_id() ?>"><img
                                class="glyph-small" alt="item-visible-for-front-end-user"
                                src="<?= IMG . 'show.png' ?>"/></a></td>
            <?php }
            if ($single->trashed == 0) { ?>
                <td><a href="/admin/posts/trash/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                class="glyph-small" alt="trash-item" src="<?= IMG . 'trash-post.png' ?>"/></a></td>
                <?php
            } else if ($single->trashed == 1) { ?>
                <td><a href="/admin/posts/destroy/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
            <?php } ?>
            <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>

        <?php } ?>
</tr>