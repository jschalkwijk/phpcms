<div class="container">
    <div class="row">

            <div class="col-sm-8 col-md-8 col-md-offset-2">
                <p class="logged">Hello <?= $_SESSION['username']; ?></p>
                <a href="<?= ADMIN."add-post";?>"><button>+ Post</button></a>
                <a href="<?= ADMIN."add-page"; ?>"><button>+ Page</button></a>
                <?php if($_SESSION['rights'] == 'Admin') { echo '<a href="'.ADMIN."add-user".'><button>+ User</button></a>'; } ?>
                <a href="#"><button>+ Products</button></a>
            </div>

    </div>

    <div class="row">
        <div class="col-md-6 col-lg-6">
            <h2>Posts</h2>
            <form class="backend-form" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
                <table class="backend-table title">
                    <tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th>Tags</th><th class="td-date">Date</th><th>Edit</th><th>View</th>
                    <th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
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
                <input type="hidden" name="dbt" value="posts"/>
            </form>
        </div>
        <div class="col-lg-6">
            <h2>Pages</h2>
            <table class="backend-table title">
                <tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th>Tags</th><th class="td-date">Date</th><th>Edit</th><th>View</th>
                    <th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
                <form class="backend-form" method="post" action="<?= ADMIN ;?>">
                    <?php
                        $pages = $data['pages'];
                        foreach($pages as $single){
                            require 'view/shared/content-table.php';
                        }
                        require($data['view']['actions']);
                    ?>
                    <input type="hidden" name="dbt" value="pages"/>
                </form>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-4">
            <h2>New Users</h2>
            <table class="backend-table title">
                <tr><th>User</th><th>Rights</th> <?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><!-- <th>Remove</th> --><?php } ?> </tr>
                <form class="backend-form" method="post" action="<?= ADMIN; ?>">
                        <?php
                            $users = $data['users'];
                            $users = $data['users'];
                            foreach ($users as $single) {
                                require 'view/users/user_table.php';
                            }
                            require('view/shared/manage-content.php');
                        ?>
                        <input type="hidden" name="dbt" value="login"/>
                    </form>
            </table>
        </div>
        <div class="col-sm-4 col-md-4">
            <h2>Add Files</h2>
            <?php require('view/files/add-files.php'); ?>
        </div>


        <div class="col-sm-4 col-md-4">
            <h2>Search Posts</h2>
            <?php require_once('view/search/search-post.php'); ?>
        </div>
    </div>
</div>
				
