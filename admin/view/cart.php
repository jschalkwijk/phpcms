<?php

$products = $data['products'];
?>
<h1 class="container">Your future possessions</h1>
<h3 class="container"><?php echo "You have ".$this->basket->itemCount()." item(s) in your cart"; ?></h3>
<?php if($this->basket->itemCount()) { ?>
    <div class="container">
        <form class="backend-form" method="post" action="/cms/admin/products">
            <table class="backend-table title">
                <tr><th>Product</th><th>€ p/item</th><th>Quantity</th><th>€ Total</th><th><button type="button" id="check-all"><img class="glyph-small" src="/cms/admin/images/check.png" alt="check-unheck-all-items"/></button></th></tr>
                <?php foreach($products as $product){ ?>
                    <tr>
                        <td class="td-title"><a href="<?php echo 'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a></td>
                        <td class="td-author"><?php echo $product->total();?></td>
                        <td class="td-category">
                            <select>

                                <?php
                                for($i = 0; $i < $product->maxStock()+1; $i++){
                                    if($i == $product->getQuantity()){ ?>
                                        <option value="<?php echo $i; ?>" selected="selected"><?php echo $i; ?></option>
                                    <?php } else{ ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php }
                                }
                                ?>

                            </select>
                        </td>
                        <td class="td-category"><?php echo $product->productTotal(); ?></td>
                        <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $product->getID(); ?>"/></p></td>
                    </tr>
                <?php } ?>
                <tr><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td><strong>Shipping</strong></td><td></td><td></td><td></td><td></td></tr>
                <tr><td><strong>Total</strong></td><td></td><td><span><?php echo $this->basket->totalQuantity(); ?></span></td><td><strong>€ </strong><span><?php echo $this->basket->subTotal(); ?></span></td> <td></td> </tr>
            </table>
        </form>
    </div>
<?php } ?>

