<?php

$products = $data['products'];
$count = $data['count'];
$total = $data['sumTotal'];
$countAll = $data['countAll'];
?>
<h1 class="container">Your future possessions</h1>
<h3 class="container"><?php echo "You have ".$count." item(s) in your cart"; ?></h3>
<div class="container">
    <form class="backend-form" method="post" action="/admin/products">
        <table class="backend-table title">
            <tr><th>Product</th><th>€ p/item</th><th>Amount</th><th>€ Total</th><th><button type="button" id="check-all"><img class="glyph-small" src="/admin/images/check.png" alt="check-unheck-all-items"/></button></th></tr>
            <?php foreach($products as $product){ ?>
                <tr>
                    <td class="td-title"><a href="<?php echo 'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a></td>
                    <td class="td-author"><?php echo $product->total();?></td>
                    <td class="td-category"><?php echo $product->getQuantity(); ?></td>
                    <td class="td-category"><?php echo $product->getQuantity() * $product->Total(); ?></td>
                    <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $product->getID(); ?>"/></p></td>
                </tr>
            <?php } ?>
            <tr><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td><strong>Total</strong></td><td></td><td><span><?php echo $countAll; ?></span></td><td><strong>€ </strong><span><?php echo $total; ?></span></td> <td></td> </tr>

        </table>
    </form>
</div>
