<?php

    namespace Controller;
use CMS\Models\Controller\Controller;
use CMS\Models\DBC\DBC;
use CMS\Models\Products\Product;
use CMS\Models\Basket\Basket;
use CMS\Models\Basket\QuantityExc;
use CMS\Models\Support\SessionStorage;

class CartController extends Controller
{

    public function __construct()
    {
        $this->cart = new SessionStorage('cart');
        $this->basket = new Basket($this->cart);
        // immediatly run the basket->all() method so that every page from this controller has
        // instant access to this basket class.
        // if not instantiated here,you have to call the basket all method in the methods of this cntroller to get access, otherwise
        // the basket class is not.
        $this->basket->all();
    }

    public function index($response,$params = null)
    {

        //$basket->unsetProduct(10);
        // $products = $basket->all();
        $products = $this->basket->all();
        $this->view(
            "Cart",
            ["cart/cart.php"],
            $params,
            [
                'products' => $products,
                'js' => [JS . "checkAll.js"]
            ]);
    }

    public function add($response,$params = null)
    {
        $dbc = new DBC;
        $id = $params['id'];
        $quantity = trim((int)$_POST['quantity']);

        // TO DO: create exists function to only check if it exists.
        $product = Product::one($id);

        if (!$product) {
            header('Location: ' . ADMIN . 'cart');
        }

        try {
            $this->basket->add($product[0], $quantity);
            header('Location: ' . ADMIN . 'cart');
        } catch (QuantityExc $e) {
            echo "Sorry, we don't have more in stock!";
        }

    }

    public function update($response,$params = null)
    {
        $id = $params['id'];
        $quantity = trim((int)$_POST['quantity']);
        echo $quantity;
        $product = Product::one($id);

        $this->basket->update($product[0], $quantity);
        header('Location: ' . ADMIN . 'cart');
    }
}

?>