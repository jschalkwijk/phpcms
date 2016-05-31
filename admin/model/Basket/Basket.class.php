<?php

class Basket_Basket
{
    protected $storage;
    protected $product;

    public function __construct(Support_StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function add(Products_Product $product, $quantity)
    {
        if($this->has($product)){
            // set quantity to current quantity + new quantity
            $quantity = $this->get($product)['quantity'] + $quantity;
        }

        $this->update($product,$quantity);
        // update session with product
    }

    public function update(Products_Product $product, $quantity)
    {
        $this->product = $product;

        if(!$this->product->fetchSingle($product->getID())->hasStock($quantity)) {

            throw new Basket_QuantityExc;
        }

        if ($quantity === 0) {
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
        $sumTotal = 0;
        $countAll = 0;
        $itemCount = 0;
        if(!empty($this->storage->all())) {
            foreach ($this->storage->all() as $product) {
                $ids[] = $product['product_id'];
            }

            $products = Products_Product::fetchAllByID($ids);

            foreach ($products as $product) {
                $product->setQuantity($this->get($product)['quantity']);
                $items[] = $product;
                $sumTotal = $sumTotal + ($product->total() * $product->getQuantity());
                $countAll = $countAll + $product->getQuantity();
            }
            $itemCount = $this->itemCount();
        }

        return ['items' => $items, 'sumTotal' => $sumTotal, 'count' => $itemCount,'countAll' => $countAll];

    }

    public function itemCount(){
        return $this->storage->count();
    }

}

?>