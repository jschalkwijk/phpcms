<?php
    $products = $data['products'];
?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 push-lg-3">
            <h1>Your future possessions</h1>
            <h3><?= "You have ".$this->basket->itemCount()." item(s) in your cart"; ?></h3>
            <?php if($this->basket->itemCount()) { ?>
                <div class="container">

                    <table class="backend-table title">
                        <tr>
                            <th>Product</th>
                            <th>€ p/item</th>
                            <th>Quantity</th>
                            <th>€ Total</th>
                            <th>
                                <button type="button" id="check-all">
                                    <img class="glyph-small" src="<?= IMG . "check.png"; ?>" alt="check-unheck-all-items"/>
                                </button>
                            </th>
                        </tr>
                        <?php foreach($products as $product){ ?>
                            <tr>
                                <td class="td-title"><a href="<?= ADMIN.'products/info/'.$product->product_id.'/'.$product->name; ?>"><?= $product->name; ?></a></td>
                                <td class="td-author"><?= $product->total();?></td>
                                <td class="td-category">
                                    <form class="backend-form" method="post" action="<?= ADMIN."cart/update/".$product->product_id;?>">
                                        <select name="quantity">
                                            <?php
                                            for($i = 0; $i < $product->maxStock()+1; $i++){
                                                if($i == $product->getQuantity()){ ?>
                                                    <option value="<?= $i; ?>" selected="selected"><?= $i; ?></option>
                                                <?php } else{ ?>
                                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                                <?php }
                                            }
                                            ?>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td class="td-category"><?= $product->productTotal(); ?></td>
                                <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $product->product_id; ?>"/></p></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td><strong>Shipping</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td></td>
                            <td><span><?= $this->basket->totalQuantity(); ?></span></td>
                            <td><strong>€ </strong><span><?= $this->basket->subTotal(); ?></span></td>
                            <td></td>
                        </tr>
                    </table>
                    <div class="container"><a href="<?= ADMIN."order";?>" class="link-btn">Checkout</a></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
