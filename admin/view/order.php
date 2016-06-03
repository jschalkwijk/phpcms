<div class="container xlarge">
    <form>
        <div class="container medium left">
            <?php echo "You have ".$this->basket->itemCount()." item(s) in your cart"; ?>

        </div>
        <div class="container medium left">
            <h3>Summary</h3>
            <table>
                <?php foreach($this->basket->all() as $product){ ?>
                    <tr>
                        <td class="td-title">
                            <a href="<?php echo 'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a>
                        </td>
                        <td>
                            <span><?php echo $product->getQuantity(); ?></span>
                        </td>
                    </tr>
                <?php } ?>
                <tr><td>Total QTY </td><td><span><?php echo $this->basket->totalQuantity(); ?><span></td></tr>
                <tr><td>Total  € </td><td><span><?php echo $this->basket->subTotal(); ?><span></td></tr>
            </table>
        </div>
    </form>
</div>