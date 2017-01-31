<div class="container">
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php require_once 'view/categories/add-category.php'; ?>
        </div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php
                ($data['trashed'] === 1) ? $action = ADMIN.'categories/deleted' : $action = ADMIN.'categories' ;
            ?>
            <form class="backend-form" method="post" action="<?= $action;?>">
                <table class="backend-table title">
                        <tr><th>Category</th><th>Author</th><th>N/A</th><th>Date</th><th>Edit</th><th>Approve</th><th>Remove</th></tr>
                    <?php
                    $categories = $data['categories'];
                    foreach($categories as $single){
                        require 'view/shared/content-table.php';
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