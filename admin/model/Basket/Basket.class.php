<?php

class Basket_Basket
{
    protected $storage;
    protected $product;
    protected $subTotal;
    protected $totalQuantity;

    public function __construct(Support_StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function add(Products_Product $product, $quantity)
    {
        $this->product = $product;

        if($this->has($product)){
            // set quantity to current quantity + new quantity
            $quantity = $this->get($product)['quantity'] + $quantity;
            echo $quantity;
        }

        $this->update($product,$quantity);
        // update session with product
    }

    public function update(Products_Product $product, $quantity)
    {

        if(!$product->fetchSingle($product->getID())->hasStock($quantity)) {

            throw new Basket_QuantityExc;
        }

        if ($quantity == 0) {
            $this->remove($product);

            return;
        }

        $this->storage->set($product->getID(),[
            'product_id' => (int) $product->getID(),
            'quantity' => (int) $quantity,
        ]);
    }

    public function remove(Products_Product $product)
    {
        $this->storage->unsetProduct($product->getID());
    }

    public function has(Products_Product $product)
    {
        return $this->storage->exists($product->getID());
    }

    public function get(Products_Product $product)
    {
        return $this->storage->get($product->getID());
    }

    public function clear() {
        $this->storage->clear();
    }

    public function all(){

        $ids = [];
        $items = [];
        $subTotal = 0;
        $countAll = 0;
        if(!empty($this->storage->all())) {
            foreach ($this->storage->all() as $product) {
                $ids[] = $product['product_id'];
            }

            $products = Products_Product::fetchAllByID($ids);

            foreach ($products as $product) {
                // The current stock is the max stock, this is not the quantity that a user wants to order perse,
                // Will be used inside cart view for selecting only the maximum items;
                $product->setMaxStock($product->getQuantity());
                // if the product still has enough stock we set the quantity to order
                // to the desired amount. If a other user checked out when you are shopping
                // the stock changes. if the product stock is lower then the desired amount added
                // earlier the quantity changed to the maximum available current stock.
                // because we don't change the original qty of the fetched product it will be displayed
                // with the current stock on the cart page view.
                // ex. I added a product with a quantity of 5. when I return to the cart, a other user
                // has checked out 2. Then the basket will display 3, because that is max amount available.
                if($product->hasStock($this->get($product)['quantity'])){
                    // is we have enough stock, we change the product objects qty to the desired qty.
                    $product->setQuantity($this->get($product)['quantity']);
                } else {
                    $this->update($product,$product->getQuantity());
                }

                $items[] = $product;
                $subTotal = $subTotal + ($product->total() * $product->getQuantity());
                $countAll = $countAll + $product->getQuantity();
            }
            $this->subTotal = $subTotal;
            $this->totalQuantity = $countAll;

        }

        return $items;

    }

    public function itemCount(){
        return $this->storage->count();
    }

    public function subTotal(){

        return $this->subTotal;
    }

    public function totalQuantity(){
        return $this->totalQuantity;
    }

}

?>