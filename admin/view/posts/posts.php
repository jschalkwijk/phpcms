
<div class="container">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-lg-6 col-sm-offset-3 push-lg-2">
            <div class="center"><?php
                require_once($data['view']['search']);
                ?>

                <form  method="post" action="/admin/posts/action">
                    <table class="backend-table title">
                        <tr class="meta">
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Tags</th>
                            <th>Date</th>
                            <th>Edit</th>
                            <th>View</th>
                            <th>Del</th>
                            <th>
                                <button type="button" id="check-all"><img class="glyph-small" alt="check-all-items"
                                                                          src="<?= IMG . "/check.png"; ?>"/></button>
                            </th>
                        </tr>
                        <?php
                            $posts = $data['posts'];
                            $admin = $this->user->hasRole('admin');
                            foreach ($posts as $single) {
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
