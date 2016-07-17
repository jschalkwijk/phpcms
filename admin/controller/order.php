<?php

class Order extends Controller{
    private $customer;
    private $customerSession;
    private $customerDetails;
    private $order;
    private $orderSession;
    private $orderDetails;

    public function __construct(){

        $this->cart = new Support_SessionStorage('cart');
        $this->basket = new Basket_Basket($this->cart);
// Session test
        // Customer Session
        $this->customerSession = new Support_SessionStorage('customer');
        $this->customerDetails = new Order_CustomerDetails($this->customerSession);
        //Order Session
        $this->orderSession = new Support_SessionStorage('order');
        $this->orderDetails = new Order_OrderDetails($this->orderSession);
//        check if the session is set correctly.
        echo '<br>';
        print_r($this->customerDetails->single());
// end session test

        // imemediatly run the basket->all() method so that every page from this controller has
        // instant access to this basket class.
        // if not instantiated here,you have to call the basket all method in the methods of this cntroller to get access, otherwise
        // the basket class is not.
        $this->basket->all();
    }

    public function index($params = null){
        // !!!!!!!!! Bij het aanpassen van een email, wordt een nieuwe klant aangemaakt en dus toegveoegd aan de sessie, dit moet een update zijn van de sessie,
        // dus de email aanpassen geen nieuwe customer aanmaken!.
        if(!empty($this->customerDetails->single())) {
            $this->customer = $this->customerDetails->single();
        } else {
            $this->customer = new Customer_Customer(null,null,null,null,null,null,null);
        }

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

    public function details($params = null){
        /* When a new customer is created and inserted to the DB , the $customer var doesn't hold the new ID yet.
         This is set after the customer is added, so we need to fetch it again with the customers->fetshSingle method in order
         to gain the new ID. Then we can pass it to the session and get the correct data.
        */

        $this->customer = new Customer_Customer($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['address1'],$_POST['address2'],$_POST['city'],$_POST['postal_code']);
        $add = $this->customer->add();
        $this->customer = $this->customer->fetchSingle($add['customer_id']);
        $this->customerDetails->add($this->customer);

        if(isset($add['customer_id'])) {

            $total = $this->basket->subTotal();
            if($this->orderDetails->single() == null){
                $this->order = new Order_Order($add['customer_id'], $total);
                $addOrder = $this->order->add();
                $this->order = $this->order->fetchSingle($addOrder['order_id']);
                $this->orderDetails->add($this->order);
                $this->order->addProducts($this->basket->all());
            } else {
                $this->order = $this->orderDetails->single();
                $this->order->setTotal($this->basket->subTotal());
                $addOrder = $this->order->add();
                $this->order = $this->order->fetchSingle($addOrder['order_id']);
                $this->orderDetails->add($this->order);
                $this->order->updateProducts($this->basket->all());
            }

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
        /* When a new customer is created and inserted to the DB , the $customer var doesn't hold the new ID yet.
          This is set after the customer is added, so we need to fetch it again with the customers->fetshSingle method in order
          to gain the new ID. Then we can pass it to the session and get the correct data.
         */
        $add = $this->customer->add();
        $this->customer = $this->customer->fetchSingle($add['customer_id']);

        $this->customerDetails->add($this->customer);

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
}
?>