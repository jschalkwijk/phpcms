<?php

class Order extends Controller{
    public function index($params = null){
        $this->view(
            "Cart",
            ["order.php"],
            $params,
            [
                'js' => ["/admin/js/checkAll.js"]
            ]);
    }

    public function checkout(){
        $customer = new Customer_Customer($_POST['name'],$_POST['mail'],$_POST['address1'],$_POST['address2'],$_POST['city'],$_POST['postal_code']);

    }
}

?>