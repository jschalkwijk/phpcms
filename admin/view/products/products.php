<div class="container">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN."products/add-product";?>">+ Product</a>
	    <a class="link-btn" href="<?= ADMIN."products/deleted-products"; ?>">Deleted Products</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <div class="center">
                <?php
                    $products = $data['products'];
                ?>
                <form class="backend-form" method="post" action="<?= ADMIN."products"; ?>">
                    <table class="backend-table title">
                        <tr><th>Name</th><th>Category</th><th>Price</th><th>In Stock</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>" alt="check-unheck-all-items"/></button></th></tr>
                        <?php foreach($products as $product){ ?>
                            <tr>
                                <td class="td-title"><a href="<?= ADMIN.'products/info/'.$product->product_id.'/'.$product->name; ?>"><?= $product->name; ?></a></td>
                                <td class="td-author"><?= $product->category_title; ?></td>
                                <td class="td-date"><?= $product->price;?></td>
                                <td class="td-category"><?= $product->quantity; ?></td>
                                <!--<input type="hidden" name="id" value="<?php /*echo $product->getID() */?>" />-->
                                <?php
                                if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                                        <td class="td-btn"><a href="<?= ADMIN.'products/edit-product/'.$product->product_id.'/'.$product->name ?>"><img class="glyph-small link-btn" src="<?= IMG.'edit.png';?>" alt="edit-item"/></a></td>
                                    <?php if ($product->approved == 0 ) { ?>
                                            <td class="td-btn"><img class="glyph-small" src="<?= IMG.'hide.png'?>" alt="item-hidden-from-front-end-user"/><!-- </button> --></td>
                                    <?php }	else if ($product->approved == 1 ) { ?>
                                                <td class="td-btn"><img class="glyph-small" src="<?= IMG.'show.png'?>" alt="item-visible-from-front-end-user"/><!-- </button> --></td>
                                    <?php } ?>
                                    <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $product->product_id; ?>"/></p></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                    <?php
                        require('view/shared/manage-content.php');
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>


