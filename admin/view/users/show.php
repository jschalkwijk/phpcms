<?php
    $user = $data['user'];
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <table class="table">
                <tbody>
                    <tr><td><?= $user->firstName; ?></td><td><?= $user->lastName; ?></td></tr>
                    <tr><td><?= $user->mail; ?></td><td><?= $user->position; ?></td></tr>
                </tbody>
            </table>

            <table class="table">
                <thead>
                <tr>
                    <th class="text-center" colspan="6">Roles</th>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $i = 1;
                        foreach($user->roles as $role){ ?>
                            <td><a href="/admin/roles/<?= $role->role_id?>"> <?= ucfirst($role->name) ?></a> </td>
                            <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <thead>
                <tr>
                    <th class="text-center" colspan="6">Permissions</th>
                </thead>
                    <tbody>
                    <tr>
                        <?php
                        $i = 1;
                        foreach($user->permissions as $permission){ ?>
                            <td><a href="/admin/permissions/<?= $permission->permission_id?>"> <?= ucfirst($permission->name) ?></a> </td>
                            <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
