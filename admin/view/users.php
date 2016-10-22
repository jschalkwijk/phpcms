<?php use CMS\model\Users\UserWriter; ?>
<div class="container">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN."users/add-user"; ?>">+ User</a>
        <a class="link-btn" href="<?= ADMIN."users/deleted-users";?>">Deleted Users</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="center">
                <form class="backend-form" method="post" action="<?= ADMIN."users"; ?>">
                    <table class="backend-table title">
                        <tr><th>User</th><th>Rights</th><?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png";?>" alt="check-uncheck-all-items"/></button></th></tr> <?php } ?>
                        <?php
                        $users = $data['users'];
                        UserWriter::write($users);
                        ?>
                    </table>
                    <?php
                        require('view/manage_content.php');
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
