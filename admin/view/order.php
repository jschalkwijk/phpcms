
<div class="container xlarge">
    <form>
        <div class="container medium left">

        </div>
        <div class="container medium left">
            <h2 class="container">Summary</h2>
            <table class="backend-table title">
                <?php foreach($this->basket->all() as $product){ ?>
                    <tr><th>Product</th><th>Quantity</th><th>€ Total</th></tr>

                    <tr>
                        <td class="td-title">
                            <a href="<?php echo '/cms/admin/products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a>
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
    </form>
</div>