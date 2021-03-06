<div class="container">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN."users/create"; ?>">+ User</a>
        <a class="link-btn" href="<?= ADMIN."users/deleted";?>">Deleted Users</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <div class="center">

                <form method="post" action="/admin/users/action">
                    <table class="backend-table title">
                        <tr><th>User</th><th>Rights</th><?php if ($this->user->hasRole('admin')) { ?> <th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png";?>" alt="check-uncheck-all-items"/></button></th></tr> <?php } ?>
                        <?php
                        $users = $data['users'];
                        foreach ($users as $single) {
                            require 'view/users/user_table.php';
                        }
                        ?>
                    </table>
                    <?php
                        require('view/shared/manage-content.php');
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
