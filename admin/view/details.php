
<?php
if(!empty($data['customer'])) {
    $customer = $data['customer'];
}
?>
<div class="container center">
    <a class ="link-btn" href="<?php echo ADMIN."order/"?>"><span>&larr;</span> Change Details</a>
    <a class ="link-btn" href="<?php echo ADMIN."order/details"?>">Summary</a>
    <a class ="link-btn" href="<?php echo ADMIN."order/payment"?>">Payment <span>&rarr;</span></a>
</div>
<div class="container center">
    <div class="row">
        <div class="col-md-6">
            <form>
                <table>
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td><input type="radio" id="ideal" name="ideal"/></td>
                        </tr>
                        <tr>
                            <td><input type="radio" id="paypal" name="paypal"/></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" id="submit-payment" name="submit-payment">Betaal</button>
            </form>
        </div>

    </div>
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
<div class="container center">
    <div class="large">
        <table class="backend-table center">
            <tbody>
                <thead><th>Personal Details</th><th></th><th>Address</th><th></th></thead>
                <tr><td class="td-title">Full Name</td><td><?php echo $customer->getName(); ?></td><td>Address Line 1</td><td><?php echo $customer->getAddress1(); ?><td></tr>
                <tr><td>E-Mail</td><td><?php echo $customer->getMail(); ?></td><td>Address Line 2</td><td><?php echo $customer->getAddress2(); ?></td></tr>
                <tr><td>Phone</td><td><?php echo $customer->getPhone(); ?></td><td>City</td><td><?php echo $customer->getCity(); ?></td></tr>
                <tr><td></td><td></td><td>Postal</td><td><?php echo $customer->getPostalCode(); ?></td></tr>
            </tbody>
        </table>
        <div class="center clearfix">
            <?php
                if(!empty($data['messages'])){
                    foreach($data['messages'] as $messages){
                        foreach($messages as $msg){
                            if($msg == "Success"){
                                echo '<p class="alert alert-success"><span class="center">Order successfully placed</span></p>';
                            } else {
                                echo '<p class="alert alert-warning">' . $msg . '</p>';
                            }
                        }
                    }
                }
            ?>
        </div>
        <div class="center clearfix">
            <a href="<?php echo ADMIN."order/confirm"; ?>" id="payment" class="link-btn">To Payment</a>
        </div>
    </div>
</div>