
<?php
if(!empty($data['customer'])) {
    $customer = $data['customer'];
}
?>
<div class="center">
    <div class="large">
        <form id="addpost-form" method="post" action="<?php echo ADMIN."order/details"; ?>">
            <div class="small left">
                <h2 class="container">Personal details</h2>
                <input type="text" id="name" name="name" placeholder="Full Name (required)" value="<?php echo $customer->getName(); ?>"/>
                <input type="email" id="email" name="email" placeholder="E-Mail (required)" value="<?php echo $customer->getMail(); ?>"/>
                <input type="tel" id="phone" name="phone" placeholder="Phone (required)" value="<?php echo $customer->getPhone(); ?>"/>
            </div>
            <div class="small right">
                <h2 class="container">Shipping Address</h2>
                <input type="text" id="address1" name="address1" placeholder="Address line 1 (required)" value="<?php echo $customer->getAddress1(); ?>"/>
                <input type="text" id="address2" name="address2" placeholder="Address line 2" value="<?php echo $customer->getAddress2(); ?>"/>
                <input type="text" id="city" name="city" placeholder="City (required)" value="<?php echo $customer->getCity(); ?>"/>
                <input type="text" id="postal" name="postal_code" placeholder="Postal Code (required)" value="<?php echo $customer->getPostalCode(); ?>"/>
<!--                <input type="text" id="country" name="country" placeholder="Country"/>-->
            </div>
            <div class="center clearfix">
                <?php
                if(!empty($data['messages'])){
                    foreach($data['messages'] as $messages){
                        foreach($messages as $msg){
                            echo "<p>".$msg."<br /></p>";
                        }
                    }
                }
                ?>
            </div>
            <div class="center clearfix">
                <button type="submit" name="submit-order">Place Order</button>
            </div>
        </form>
    </div>

    <div class="medium">
        <h2 class="center">Summary</h2>
        <table class="backend-table title center">
            <tr><th>Product</th><th>Quantity</th><th>€ Total</th></tr>
            <?php foreach($this->basket->all() as $product){ ?>
                <tr>
                    <td class="td-title">
                        <a href="<?php echo ADMIN.'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a>
                    </td>
                    <td class="td-category">
                        <span><?php echo $product->getQuantity(); ?></span>
                    </td>
                    <td class="td-category">
                        <span><?php echo $product->productTotal(); ?></span>
                    </td>
                </tr>
            <?php } ?>
            <tr><td>Total QTY </td><td><span><?php echo $this->basket->totalQuantity(); ?><span></td></tr>
            <tr><td>Total  € </td><td></td><td><span><?php echo $this->basket->subTotal(); ?><span></td></tr>
        </table>
    </div>
</div>