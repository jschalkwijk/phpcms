
<div class="container large">
    <div class="container large left">
        <form id="addpost-form" method="post" action="#">
            <div class="container small left">
                <h2 class="container">Personal details</h2>
                <input type="text" id="full-name" name="full-name" placeholder="Full Name"/>
                <input type="email" id="email" name="email" placeholder="E-Mail"/>
                <input type="tel" id="phone" name="phone" placeholder="Phone"/>
            </div>
            <div class="container small left">
                <h2 class="container">Shipping Address</h2>
                <input type="text" id="address1" name="address1" placeholder="Address line 1"/>
                <input type="text" id="address2" name="address2" placeholder="Address line 2"/>
                <input type="text" id="city" name="city" placeholder="City"/>
                <input type="text" id="postal" name="postal" placeholder="Postal Code"/>
<!--                <input type="text" id="country" name="country" placeholder="Country"/>-->
            </div>
            <div class="container clearfix">
                <button type="submit" name="submit-order">Place Order</button>
            </div>
        </form>
    </div>
    <div class="container medium">
        <h2 class="container">Summary</h2>
        <table class="backend-table title">
            <tr><th>Product</th><th>Quantity</th><th>€ Total</th></tr>
            <?php foreach($this->basket->all() as $product){ ?>
                <tr>
                    <td class="td-title">
                        <a href="<?php echo '/admin/products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a>
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