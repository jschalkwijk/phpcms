<?php

class Cart extends Controller {

    public function index($params = null){
        $cart = new Support_SessionStorage('cart');
        $cart->set(3,['product_id' => 3,'quantity' => 9]);
        $cart->set(4,['product_id' => 4,'quantity' => 5]);

        $basket= new Basket_Basket($cart);

        //$basket->unsetProduct(10);
       // $products = $basket->all();
        $products = $basket->all();
        $count = $basket->itemCount();
        $this->view(
            "Cart",
            ["cart.php"],
            $params,
            [
                'products' => $products,
                'count' => $count,
                'js' => ['/admin/js/checkAll.js']
            ]);
    }

    public function add(){

    }

    public function remove(){

    }

}

?>