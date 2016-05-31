<?php

class Cart extends Controller {

    public function __construct(){
        $this->cart = new Support_SessionStorage('cart');
        $this->basket = new Basket_Basket($this->cart);
    }
    public function index($params = null){

        //$basket->unsetProduct(10);
        // $products = $basket->all();
        $products = $this->basket->all();
        $this->view(
            "Cart",
            ["cart.php"],
            $params,
            [
                'products' => $products['items'],
                'sumTotal' => $products['sumTotal'],
                'count' => $products['count'],
                'countAll' => $products['countAll'],
                'js' => ["/admin/js/checkAll.js"]
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
        $this->basket->add($product,$quantity);
        header('Location: '.'/admin/cart');
    }

    public function remove(){

    }
}

?>