<?php

class Cart extends Controller {

    public function __construct(){
        $this->cart = new Support_SessionStorage('cart');
        $this->basket = new Basket_Basket($this->cart);
        // imemediatly run the basket->all() method so that every page from this controller has
        // instant access to this basket class.
        // if not instantiated here,you have to call the basket all method in the methods of this cntroller to get access, otherwise
        // the basket class is not.
        $this->basket->all();
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
                'products' => $products,
                'js' => ["/admin/js/checkAll.js"]
            ]);
    }

    public function add($params = null){
        $dbc = new DBC;
        $id = $params[0];
        $quantity = mysqli_real_escape_string($dbc->connect(),trim((int)$_POST['quantity']));

        // TO DO: create exists function to only check if it exists.
        $product = Products_Product::fetchSingle($id);

        if(!$product){
            header('Location: '.'/admin/cart');
        }

        try {
            $this->basket->add($product,$quantity);
            header('Location: '.'/admin/cart');
        } catch (Basket_QuantityExc $e){
            echo "Sorry, we don't have more in stock!";
        }

    }

    public function update($params = null){
        $dbc = new DBC;
        $id = $params[0];
        $quantity = mysqli_real_escape_string($dbc->connect(),trim((int)$_POST['quantity']));
        $product = Products_Product::fetchSingle($id);

        $this->basket->update($product,$quantity);
        header('Location: '.'/admin/cart');
    }
}

?>