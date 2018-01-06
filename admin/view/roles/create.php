<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <?php
                foreach($data['errors'] as $error){ ?>
                    <div class="alert alert-warning"><?= $error?></div>
                <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class=""></div>
        <form action="<?= ADMIN."roles/create";?>" method="post">
            <input type="text" name="name" placeholder="Role Name"><br />
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
                            foreach($data['permissions'] as $permission){ ?>
                                    <td> <lable><?= $permission->name?></lable> </td>
                                    <td>
                                        <input type="checkbox" value="<?= $permission->permission_id ?>" name="checkbox[]">
                                    </td>
                                    <?php if ($i % 3 == 0) echo "</tr><tr>"; $i++;?>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
