<div class="container large">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN . "contacts/add-contact"; ?>">Add Contact</a>
        <a class="link-btn" href="<?= ADMIN . "contacts/deleted-contacts"; ?>">Deleted Contacts</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="center">
                <?php ($data['trashed'] === 1) ? $action = ADMIN . 'contacts/deleted-contacts' : $action = 'contacts'; ?>
                <form class="backend-form" method="post" action="<?= $action; ?>">
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
                                <td><?= '<a href="' . ADMIN . 'contacts/edit-contact/' . $contact->contact_id . '/' . $contact->firstName() . '">Edit</a>' ?></td>
                                <td class="td-btn"><p><input type="checkbox" name="checkbox[]"
                                                             value="<?= $contact->contact_id; ?>"/></p></td>
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
