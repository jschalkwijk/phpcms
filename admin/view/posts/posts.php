<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <div class="center"><?php
                require_once($data['view']['search']);
                ($data['trashed'] === 1) ? $action = ADMIN.'posts/deleted' : $action = ADMIN.'posts' ;
                ?>

                <form class="backend-form" method="post" action="<?= $action; ?>">
                    <table class="backend-table title">
                        <tr><th>Title</th><th>Author</th><th>Category</th><th>Tags</th><th>Date</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
                        <?php
                            $posts = $data['posts'];
                            foreach($posts as $single){
                                require 'view/shared/content-table.php';
                            }
                        ?>
                    </table>
                    <?php
                        require($data['view']['actions']);
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
