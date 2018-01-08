<?php $role = $data['role']; ?>
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
            <?php (isset($params['id'])) ? $action = ADMIN . 'roles/update/' . $role->role_id : $action = ADMIN . 'roles/create'; ?>
            <form action="<?= $action;?>" method="post">
                <input type="text" name="name" placeholder="Role Name" value="<?= $role->name ?>"><br />
                <button type="submit" name="submit">Submit</button>
                <table class="table">
                    <thead>
                            <tr>
                            <th class="text-center" colspan="6">Permissions</th>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                                $i = 1;
                                foreach($data['permissions'] as $permission){
                                    echo "<td>";
                                    if(in_array($permission->permission_id,$data['currentPermissions'])) {
                                        echo "<input type='checkbox' value='$permission->permission_id' name='checkbox[]' checked/>";
                                    } else {
                                        echo "<input type='checkbox' value='$permission->permission_id' name='checkbox[]'/>";
                                    }
                                    echo "</td>";
                                    ?>
                                    <td><lable><?= $permission->name?></lable> </td>
                                    <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
