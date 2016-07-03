<?php

class Order_Order{
    private $id;
    private $hash;
    private $total;
    private $paid;
    private $customer_id;

    public function __construct($customer_id,$total)
    {
        $this->total = $total;
        $this->paid = false;
        $this->customer_id = $customer_id;
    }

    public function add(){
        $dbc = new DBC;
        $newHash = $this->newHash();
        $hash = $newHash;
        $total = $this->getTotal();
        $paid = $this->getPaid();
        $customer_id = $this->getCustomerId();
        $query = "INSERT INTO orders(hash,total,paid,customer_id) VALUES('$hash',$total,'$paid',$customer_id)";
        mysqli_query($dbc->connect(),$query) or die("Error inserting new order!");
        $dbc->disconnect();
        echo $query;
        $query = "SELECT order_id FROM orders WHERE hash = '$hash'";
        echo $query;
        $data = mysqli_query($dbc->connect(),$query) or die("Error fetching order_id");
        $row = mysqli_fetch_array($data);
        $this->setID($row['order_id']);
        echo $this->getID();
        $dbc->disconnect();

        return ['messages' => ["Order successful"]];

    }

    public function addProducts($products){;
        $dbc = new DBC;
        $order_id = $this->getID();
        $queryRows = [];
        //Create rows for multiple insert query
        foreach($products as $product) {
            $product_id = $product->getID();
            $quantity = $product->getQuantity();
            $queryRows[] = "(".$order_id.","."$product_id".",".$quantity.")";
        }
        $query = "INSERT INTO orders_products(order_id,product_id,quantity) VALUES".implode(',', $queryRows);
        echo $query;
        mysqli_query($dbc->connect(),$query);
        $dbc->disconnect();

    }
    public function getCustomerId()
    {
        return $this->customer_id;
    }
    public function getHash()
    {
        return $this->hash;
    }

    public function getTotal()
    {
        return $this->total;
    }
    public function setPaid($paid)
    {
        $this->paid = $paid;
    }
    public function getPaid()
    {
        return $this->paid;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    private function newHash(){
        return md5(uniqid(rand(), true));
    }

}

?>