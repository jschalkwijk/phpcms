
<?php
if(!empty($data['customer'])) {
    $customer = $data['customer'];
}
?>
<div class="container">
    <form id="addpost-form" method="post" action="<?= ADMIN."order/payment"; ?>">
        <div class="row">
            <div class="col-lg-10 col-md-offset-2">

                    <div class="small left">
                        <h2>Personal details</h2>
                        <input type="text" id="name" name="name" placeholder="Full Name (required)" value="<?= $customer->getName(); ?>"/>
                        <input type="email" id="email" name="email" placeholder="E-Mail (required)" value="<?= $customer->getMail(); ?>"/>
                        <input type="tel" id="phone" name="phone" placeholder="Phone (required)" value="<?= $customer->getPhone(); ?>"/>
                    </div>
                    <div class="small left">
                        <h2>Shipping Address</h2>
                        <input type="text" id="address1" name="address1" placeholder="Address line 1 (required)" value="<?= $customer->getAddress1(); ?>"/>
                        <input type="text" id="address2" name="address2" placeholder="Address line 2" value="<?= $customer->getAddress2(); ?>"/>
                        <input type="text" id="city" name="city" placeholder="City (required)" value="<?= $customer->getCity(); ?>"/>
                        <input type="text" id="postal" name="postal_code" placeholder="Postal Code (required)" value="<?= $customer->getPostalCode(); ?>"/>
        <!--                <input type="text" id="country" name="country" placeholder="Country"/>-->
                    </div>
            </div>
            <div class="col-lg-10 col-md-offset-2">
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
        </div>

        <div class="row">
            <div class="col-lg-10 col-md-offset-2">
                <p class="alert alert-info">Please check if your personal details and address are correct before proceeding your order.</p>
                <input type="checkbox" id="agree" name="agree"/><label for="agree">I agree to the <a href="#">Terms and Service Agreement</a> of this shop</label>
                <input type="checkbox" id="details" name="details"/><label for="details">I have filled in the correct personal details</label>
                <button type="submit" name="submit-order">Payment method</button>
            </div>

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
                        <a href="<?= ADMIN.'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?= $product->getName(); ?></a>
                    </td>
                    <td class="td-category">
                        <span><?= $product->getQuantity(); ?></span>
                    </td>
                    <td class="td-category">
                        <span><?= $product->productTotal(); ?></span>
                    </td>
                </tr>
            <?php } ?>
            <tr><td>Total QTY </td><td><span><?= $this->basket->totalQuantity(); ?><span></td></tr>
            <tr><td>Total  € </td><td></td><td><span><?= $this->basket->subTotal(); ?><span></td></tr>
        </table>
    </div>
</div>