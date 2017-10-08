<div class="container">
    <div class="center">
        <a class="link-btn" href="<?= ADMIN."products/create";?>">+ Product</a>
	    <a class="link-btn" href="<?= ADMIN."products/deleted"; ?>">Deleted Products</a>
    </div>
</div>s
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <div class="center">
                <?php
                    $products = $data['products'];
                    ($data['trashed'] === 1) ? $action = ADMIN.'products/deleted' : $action = ADMIN.'products' ;
                ?>
                <form method="post" action="<?= $action; ?>">
                    <table class="backend-table title">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>In Stock</th>
                            <th>Edit</th>
                            <th>View</th>
                            <th>Del</th>
                            <th>
                                <button type="button" id="check-all"><img class="glyph-small"
                                                                          src="<?= IMG . "check.png"; ?>"
                                                                          alt="check-unheck-all-items"/></button>
                            </th>
                        </tr>
                        <?php foreach($products as $product){ ?>
                            <tr>
                                <td class="td-title"><a href="<?= ADMIN.'products/info/'.$product->product_id.'/'.$product->name; ?>"><?= $product->name; ?></a></td>
                                <td class="td-category"><p><?php
                                            if(is_callable([$product,"category"])) {
                                                echo $product->category()->title;

                                            }
                                        ?></p></td>
                                <td class="td-date"><?= $product->price;?></td>
                                <td class="td-category"><?= $product->quantity; ?></td>
                                <?php
                                if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                                    <?php
                                    if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                                        <td class="td-btn">
                                            <a class="btn btn-sm edit-link" href="<?= $product->table . '/edit/' . $product->get_id(); ?>"><img class="glyph-small"
                                                                                                                   alt="edit-item"
                                                                                                                   src="<?= IMG . 'edit.png'; ?>"/></a>
                                        </td>
                                        <?php if ($product->approved == 0) { ?>
                                            <td><a class="btn btn-sm btn-info" href="/admin/products/approve/<?= $product->get_id() ?>"><img
                                                            class="glyph-small" alt="show-item"
                                                            src="<?= IMG . 'hide.png' ?>"/></a></td>
                                        <?php } else if ($product->approved == 1) { ?>
                                            <td><a class="btn btn-sm btn-success" href="/admin/products/hide/<?= $product->get_id() ?>"><img
                                                            class="glyph-small" alt="hide-item"
                                                            src="<?= IMG . 'show.png' ?>"/></a></td>
                                        <?php }
                                        if ($product->trashed == 0) { ?>
                                            <td><a href="/admin/products/trash/<?= $product->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="trash-item" src="<?= IMG . 'trash-post.png' ?>"/></a></td>
                                            <?php
                                        } else if ($product->trashed == 1) { ?>
                                            <td><a href="/admin/products/destroy/<?= $product->get_id() ?>" class="btn btn-sm btn-danger"><img
                                                            class="glyph-small" alt="destroy-item" src="<?= IMG . 'delete-post.png' ?>"/></a></td>
                                        <?php } ?>
                                        <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $product->get_id(); ?>"/></p></td>
                                <?php } }?>
                            </tr>
                        <?php } ?>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


