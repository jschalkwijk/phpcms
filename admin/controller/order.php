<?php

class Order extends Controller{
    private $customer;
    private $customerSession;
    private $customerDetails;

    public function __construct(){

        $this->cart = new Support_SessionStorage('cart');
        $this->basket = new Basket_Basket($this->cart);
// Session test
        $this->customerSession = new Support_SessionStorage('customer');
        $this->customerDetails = new Order_Details($this->customerSession);
//        check if the session is set correctly.
        print_r($this->customerDetails->get());
// end session test

        // imemediatly run the basket->all() method so that every page from this controller has
        // instant access to this basket class.
        // if not instantiated here,you have to call the basket all method in the methods of this cntroller to get access, otherwise
        // the basket class is not.
        $this->basket->all();
    }

    public function index($params = null){

    $this->customer = new Customer_Customer(null, null, null, null, null, null, null);

        $this->view(
            "Cart",
            ["order.php"],
            $params,
            [
                'customer' => $this->customer,
                'js' => [JS."checkAll.js"]
            ]
        );
    }

//    public function details($params = null){
//        $this->customer = new Customer_Customer($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['address2'], $_POST['city'], $_POST['postal_code']);
//        $add = $this->customer->add();
//
//        $this->customerDetails->add($this->customer);
//
//
//        $total = $this->basket->subTotal();
////            $order = new Order_Order($add['customer_id'],$total);
////            $addOrder = $order->add();
////            $addOrderProducts = $order->addProducts($this->basket->all());
//
//        $this->view(
//            "Cart",
//            ["details.php"],
//            $params,
//            [
//                'customer' => $this->customer,
//                'messages' => [$add['messages'],$addOrder['messages']],
//                'js' => [JS."checkAll.js"]
//            ]
//        );
//
//
//    }

    public function details($params = null){
        $this->customer = new Customer_Customer($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['address1'],$_POST['address2'],$_POST['city'],$_POST['postal_code']);
        $add = $this->customer->add();

        if(isset($add['customer_id'])) {
            $total = $this->basket->subTotal();
            $order = new Order_Order($add['customer_id'],$total);
            $addOrder = $order->add();
            $addOrderProducts = $order->addProducts($this->basket->all());

            $this->view(
                "Order details",
                ["details.php"],
                $params,
                [
                    'customer' => $this->customer,
                    'messages' => [$add['messages'],$addOrder['messages']],
                    'js' => [JS."checkAll.js"]
                ]
            );
        }

    }

    public function payment($params = null){
        $this->customer = new Customer_Customer($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['address1'],$_POST['address2'],$_POST['city'],$_POST['postal_code']);
//        $add = $this->customer->add();

        if(isset($add['customer_id'])) {
            $total = $this->basket->subTotal();
            $order = new Order_Order($add['customer_id'],$total);
            $addOrder = $order->add();
            $addOrderProducts = $order->addProducts($this->basket->all());

            $this->view(
                "Cart",
                ["payment.php"],
                $params,
                [
                    'customer' => $this->customer,
                    'messages' => [$add['messages'],$addOrder['messages']],
                    'js' => [JS."checkAll.js"]
                ]
            );
        }
    }
}

?>