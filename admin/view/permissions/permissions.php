<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 col-sm-offset-3 push-lg-2">
            <div class="center">
                <form  method="post" action="/admin/permissions/action">
                    <table class="backend-table title">
                        <tr class="meta">
                            <th>Name</th>
                            <th>Date</th>
                            <th>Edit</th>
                            <th>Del</th>
                            <th>
                                <button type="button" id="check-all"><img class="glyph-small" alt="check-all-items"
                                                                          src="<?= IMG . "/check.png"; ?>"/></button>
                            </th>
                        </tr>
                        <?php
                            $permissions = $data['permissions'];
                            foreach ($permissions as $single) { ?>
                            <tr>
                                <td class="td-title"><a href="/admin/<?= $single->table.'/'.$single->get_id()?>"><?= $single->name; ?></a></td>
                                <td class="td-date"><p><?= $single->date; ?></p></td>
                                <!-- Single actions per item -->
                                <?php
                                    if ($this->user->hasRole('admin')) { ?>
                                        <td class="td-btn">
                                            <a class="btn btn-sm edit-link" href="<?= $single->table . '/edit/' . $single->get_id(); ?>"><img class="glyph-small"
                                                                                                                                              alt="edit-item"
                                                                                                                                              src="<?= IMG . 'edit.png'; ?>"/></a>
                                        <td><a href="/admin/<?= $single->table ?>/destroy/<?= $single->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                        class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
                                        <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>

                                    <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
            </div>
        </div>
    </div>
</div>
