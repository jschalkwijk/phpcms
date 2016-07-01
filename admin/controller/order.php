<?php

class Order extends Controller{
    public function index($params = null){
        $this->view(
            "Cart",
            ["order.php"],
            $params,
            [
                'js' => [JS."checkAll.js"]
            ]
        );
    }

    public function checkout($params = null){
        $customer = new Customer_Customer($_POST['name'],$_POST['mail'],$_POST['address1'],$_POST['address2'],$_POST['city'],$_POST['postal_code']);
        $add = $customer->add();

        $this->view(
            "Cart",
            ["order.php"],
            $params,
            [
                'messages' => $add['messages'],
                'js' => [JS."checkAll.js"]
            ]
        );
    }
}

?>