<?php $permission = $data['permission']; ?>
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 push-sm-3 push-md-3">
            <?php
                foreach($data['errors'] as $error){ ?>
                    <div class="alert alert-warning"><?= $error?></div>
                <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 push-sm-3 push-md-3">
            <?php (isset($params['id'])) ? $action = ADMIN . 'permissions/update/' . $permission->permission_id : $action = ADMIN . 'permissions/create'; ?>
            <form action="<?= $action;?>" method="post">
                <input type="text" name="name" placeholder="Permission Name" value="<?= $permission->name ?>"><br />
                <button type="submit" name="submit">Submit</button>
                <table class="table">
                    <thead>
                            <tr>
                            <th class="text-center" colspan="6">Select Role (Optional)</th>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                                $i = 1;
                                foreach($data['roles'] as $role){
                                    echo "<td>";
                                    if (isset($data['currentRoles']) && in_array($role->role_id,$data['currentRoles'])) {
                                        echo "<input type='checkbox' value='$role->role_id' name='checkbox[]' checked/>";
                                    } else {
                                        echo "<input type='checkbox' value='$role->role_id' name='checkbox[]'/>";
                                    }

                                    echo "</td>";
                                    ?>
                                    <td><label><?= ucfirst($role->name) ?></label> </td>
                                    <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
