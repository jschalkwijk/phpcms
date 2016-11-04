<?php

use CMS\model\Controller\Controller;
use CMS\model\Support\SessionStorage;
use CMS\model\Basket\Basket;
use CMS\model\Customer\Customer;
use CMS\model\Order\CustomerDetails;
use CMS\model\Order\OrderDetails;
use CMS\model\Order\Order as Ordr;

class Order extends Controller
{
    private $customer;
    private $customerSession;
    private $customerDetails;
    private $order;
    private $orderSession;
    private $orderDetails;

    public function __construct()
    {
        // Start all the needed sessions.
        // For different uses we set updifferent session arrays with specific names.
        // Then we can access all the session information per topic

        // Cart Session
        $this->cart = new SessionStorage('cart');
        $this->basket = new Basket($this->cart);
        // Customer Session
        $this->customerSession = new SessionStorage('customer');
        $this->customerDetails = new CustomerDetails($this->customerSession);
        //Order Session
        $this->orderSession = new SessionStorage('order');
        $this->orderDetails = new OrderDetails($this->orderSession);

        // immediately run the basket->all() method so that every page from this controller has
        // instant access to this basket class.
        // if not instantiated here,you have to call the basket all method in the methods of this cntroller to get access, otherwise
        // the basket class is not.
        $this->basket->all();
    }

    public function index($params = null)
    {

        if (!empty($this->customerDetails->single())) {
            $this->customer = $this->customerDetails->single();
        } else {
            $this->customer = new Customer(null, null, null, null, null, null, null);
        }

        $this->view(
            "Cart",
            ["order/order.php"],
            $params,
            [
                'customer' => $this->customer,
                'js' => [JS . "checkAll.js"]
            ]
        );
    }

    public function payment($params = null)
    {
        /* When a new customer is created and inserted to the DB , the $customer var doesn't hold the new ID yet.
         This is set after the customer is added, so we need to fetch it again with the customers->fetshSingle method in order
         to gain the new ID. Then we can pass it to the session and get the correct data.
        */
        // If post is not set, the user probably refreshed the page or used rthe back button,
        // we can then just use the stored customer details from the session. if post is set,
        // the details could have been changed so we should update them.
        if (!$_POST) {
            $this->customer = $this->customerDetails->single();
        } else {
            $this->customer = new Customer($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['address2'], $_POST['city'], $_POST['postal_code']);
        }
        $add = $this->customer->add();
        $this->customer = $this->customer->fetchSingle($add['customer_id']);
        $this->customerDetails->add($this->customer);

        if (isset($add['customer_id'])) {
            // Twijfel nog of ik de order in de database moet zetten bij deze stap of de volgende stap?
//            $total = $this->basket->subTotal();
//            if($this->orderDetails->single() == null){
//                $this->order = new Order_Order($add['customer_id'], $total);
//                $addOrder = $this->order->add();
//                $this->order = $this->order->fetchSingle($addOrder['order_id']);
//                $this->orderDetails->add($this->order);
//                $this->order->addProducts($this->basket->all());
//            } else {
//                $this->order = $this->orderDetails->single();
//                $this->order->setTotal($this->basket->subTotal());
//                $addOrder = $this->order->add();
//                $this->order = $this->order->fetchSingle($addOrder['order_id']);
//                $this->orderDetails->add($this->order);
//                $this->order->updateProducts($this->basket->all());
//            }

            $this->view(
                "Order details",
                ["order/details.php"],
                $params,
                [
                    'customer' => $this->customer,
                    'messages' => [$add['messages']],
                    'js' => [JS . "checkAll.js"]
                ]
            );
        }

    }

    public function confirm($params = null)
    {
//        /* When a new customer is created and inserted to the DB , the $customer var doesn't hold the new ID yet.
//          This is set after the customer is added, so we need to fetch it again with the customers->fetshSingle method in order
//          to gain the new ID. Then we can pass it to the session and get the correct data.
//         */
//        $add = $this->customer->add();
//        $this->customer = $this->customer->fetchSingle($add['customer_id']);
//        $this->customerDetails->add($this->customer);
        $this->customer = $this->customerDetails->single();
        if ($this->customer->getID() != null || $this->customer->getID() != 0) {

            $total = $this->basket->subTotal();
            if ($this->orderDetails->single() == null || $this->orderDetails->single()->getCustomerId() != $this->customer->getID()) {
                $this->order = new Ordr($this->customer->getID(), $total);
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
                ["order/details.php"],
                $params,
                [
                    'customer' => $this->customer,
                    'messages' => [$addOrder['messages']],
                    'js' => [JS . "checkAll.js"]
                ]
            );
        }
    }
}

?>