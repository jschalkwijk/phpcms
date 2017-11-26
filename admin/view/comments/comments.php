<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 offset-xs-3 offset-sm-3 offset-lg-3">
            <div class="center button-block">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 offset-xs-3 offset-sm-3 offset-md-2 offset-lg-3">
            <div class="center">
                <form method="post" action="/admin/comments/action">
                    <table class="table table-sm table-striped">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th class="hidden-xs-down">Author</th>
                            <th class="hidden-xs-down">Post</th>
                            <th class="hidden-xs-down">Replies</th>
                            <th class="hidden-md-down">Date/Time</th>
                            <th>Edit</th>
                            <th>Status</th>
                            <th>Del</th>
                            <th>
                                <button type="button" id="check-all"><img class="glyph-small" alt="check-all-items"
                                                                          src="check.png"/></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data['comments'] as $key => $c) { ?>
                            <tr>
                                <td><?= $key++ ?></td>
                                <td><?= $c->title ?></td>
                                <td class="td-category"><?= $c->user->username?></td>
                                <td class="td-category"><?= $c->post->title ?></td>
                                <td> <?php
                                        echo count($c->replies);
                                    ?>
                                </td>
                                <td class="td-date"><p><?= $c->date; ?></p></td>
                                <?php
                                    if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                                        <td class="td-btn">
                                            <a class="btn btn-sm edit-link" href="<?= $c->table . '/edit/' . $c->get_id(); ?>"><img class="glyph-small"
                                                                                                                                              alt="edit-item"
                                                                                                                                              src="<?= IMG . 'edit.png'; ?>"/></a>
                                        </td>
                                        <?php if ($c->approved == 0) { ?>
                                            <td><a class="btn btn-sm btn-info" href="/admin/comments/approve/<?= $c->get_id() ?>"><img
                                                            class="glyph-small" alt="show-item"
                                                            src="<?= IMG . 'hide.png' ?>"/></a></td>
                                        <?php } else if ($c->approved == 1) { ?>
                                            <td><a class="btn btn-sm btn-success" href="/admin/comments/hide/<?= $c->get_id() ?>"><img
                                                            class="glyph-small" alt="hide-item"
                                                            src="<?= IMG . 'show.png' ?>"/></a></td>
                                        <?php }
                                        if ($c->trashed == 0) { ?>
                                            <td><a href="/admin/comments/trash/<?= $c->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="trash-item" src="<?= IMG . 'trash-post.png' ?>"/></a></td>
                                            <?php
                                        } else if ($c->trashed == 1) { ?>
                                            <td><a href="/admin/comments/destroy/<?= $c->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
                                        <?php } ?>
                                        <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $c->get_id(); ?>"/></p></td>

                                    <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php
                        require('view/shared/manage-content.php');
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
