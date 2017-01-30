<div class="container">
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php require_once 'view/categories/create'; ?>
        </div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <form class="backend-form" method="post" action="<?= ADMIN."categories";?>">
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