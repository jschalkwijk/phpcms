<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <form class="backend-form" method="post" action="<?= ADMIN."tags";?>">
                <table class="backend-table title">
                    <tr><th>Tag</th><th>Author</th><th>N/A</th><th>Date</th><th>Edit</th><th>Approve</th><th>Remove</th></tr>
                    <?php
                        $tags = $data['tags'];
                        foreach($tags as $single){ ?>
                        <tr><td class="td-title"><p><?= $single->title; ?></p></td>
                        <td class="td-author"><p><?= $single->users_username; ?></p></td>
                        <td class="td-date"><p><?= $single->created_at; ?></p></td>
                    <?php
                        if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                            <td class="td-btn"><a href="<?= $single->table.'/edit-'.$single->table.'/' .$single->id().'/'.$single->getLink(); ?>"><img class="glyph-small link-btn" alt="edit-item" src="<?= IMG.'edit.png';?>"/></a></td>
                            <?php if ($single->approved == 0 ) { ?>
                                <td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="<?= IMG.'hide.png'?>"/></td>
                            <?php }	else if ($single->approved == 1 ) { ?>
                                <td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="<?= IMG.'show.png'?>"/></td>
                            <?php } ?>
                            <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->id(); ?>"/></p></td>
                        <?php } ?>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
                <table>
                    <tr><th>Delete</th><th>Show</th><th>Hide</th></tr>
                    <tr>
                        <td><p><button type="submit" name="delete-selected" id="delete-selected"><img class="glyph-small" alt="delete-selected-from-trash" src="<?= IMG.'delete-post.png'?>"/></button></p></td>
                        <td><p><button type="submit" name="approve-selected" id="approve-selected"><img class="glyph-small" alt="approve-selected-for-front-end-view" src="<?= IMG.'show.png'?>"/></button></p></td>
                        <td><p><button type="submit" name="hide-selected" id="hide-selected"><img class="glyph-small" alt="hide-selected-from-front-end-view" src="<?= IMG.'hide.png'?>"/></button></p></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>