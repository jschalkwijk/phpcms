<div class="container large">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN . "contacts/create"; ?>">Add Contact</a>
        <a class="link-btn" href="<?= ADMIN . "contacts/deleted"; ?>">Deleted Contacts</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <div class="center">
                <form method="post" action="/admin/contacts/action">
                    <table class="backend-table title">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Phone</th>
                            <th>E-mail</th>
                            <th>Edit</th>
                            <th>
                                <button id="check-all"><img class="glyph-small" src="<?= IMG . "check.png"; ?>"
                                                            alt="check-unchek-all-items"/></button>
                            </th>
                        </tr>
                        <?php foreach ($data['contacts'] as $contact) { ?>
                            <tr>
                                <td><?= '<a href="' . ADMIN . 'contacts/info/' . $contact->contact_id. '/' . $contact->firstName() . '">' . $contact->firstName() . '</a>' ?></td>
                                <td><?= $contact->lastName(); ?></td>
                                <td><?= $contact->phone1(); ?></td>
                                <td><?= $contact->mail1(); ?></td>
                                <?php
                                    if ($this->user->hasRole('admin')) { ?>
                                        <td class="td-btn">
                                            <a class="btn btn-sm edit-link" href="<?= $contact->table . '/edit/' . $contact->get_id(); ?>"><img class="glyph-small"
                                                                                                                                              alt="edit-item"
                                                                                                                                              src="<?= IMG . 'edit.png'; ?>"/></a>
                                        </td>
                                        <?php if ($contact->approved == 0) { ?>
                                            <td><a class="btn btn-sm btn-info" href="/admin/contacts/approve/<?= $contact->get_id() ?>"><img
                                                            class="glyph-small" alt="show-item"
                                                            src="<?= IMG . 'hide.png' ?>"/></a></td>
                                        <?php } else if ($contact->approved == 1) { ?>
                                            <td><a class="btn btn-sm btn-success" href="/admin/contacts/hide/<?= $contact->get_id() ?>"><img
                                                            class="glyph-small" alt="hide-item"
                                                            src="<?= IMG . 'show.png' ?>"/></a></td>
                                        <?php }
                                        if ($contact->trashed == 0) { ?>
                                            <td><a href="/admin/contacts/trash/<?= $contact->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="trash-item" src="<?= IMG . 'trash-post.png' ?>"/></a></td>
                                            <?php
                                        } else if ($contact->trashed == 1) { ?>
                                            <td><a href="/admin/contacts/destroy/<?= $contact->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
                                        <?php } ?>
                                        <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $contact->get_id(); ?>"/></p></td>

                                    <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php
                    require_once('view/shared/manage-content.php');
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
