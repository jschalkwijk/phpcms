<?php

class Cart extends Controller {

    protected $basket;

    public function __construct(Basket_Basket $basket = null){
        $this->basket = $basket;
    }
    public function index($params = null){
        $cart = new Support_SessionStorage('cart');

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

    public function add($params = null){
        $id = $params[0];
        $quantity = $params[1];

        // TO DO: create exists function to only check if it exists.
        $product = Products_Product::fetchSingle($id);

        if(!$product){
            header('Location: '.'/admin/cart');
        }

        $cart = new Support_SessionStorage('cart');
        $cart->set($id,['product_id' => $id,'quantity' => $quantity]);
        header('Location: '.'/admin/cart');
    }

    public function remove(){

    }

}

?>