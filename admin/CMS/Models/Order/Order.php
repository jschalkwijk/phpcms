<?php

namespace CMS\Models\Order;

use CMS\Models\DBC\DBC;

class Order{
    private $id;
    private $hash;
    private $total;
    private $paid;
    private $customer_id;

    public function __construct( $customer_id, $total, $paid = null, $hash = null)
    {
        $this->total = $total;
        $this->paid = false;
        $this->customer_id = $customer_id;
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
    public function setTotal($total)
    {
        $this->total = $total;
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

    public function add(){
        $db = new DBC;
        $dbc = $db->connect();

        $newHash = $this->newHash();
        $hash = $newHash;
        $total = $this->getTotal();
        $paid = $this->getPaid();
        $customer_id = $this->getCustomerId();

        if(!empty($this->getID())){
            $order_id = $this->getID();
            try {
                $sql = "UPDATE orders SET total = ?,paid = ?,customer_id = ? WHERE order_id = ?";
                $query = $dbc->prepare($sql);
                echo $sql." params ".$total,$paid,$customer_id,$order_id;
                $query->execute([$total,$paid,$customer_id,$order_id]);
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
        } else {
            try {
                $query = $dbc->prepare("INSERT INTO orders(hash,total,paid,customer_id) VALUES(?,?,?,?)");
                $query->execute([$hash,$total,$paid,$customer_id]);
                $last_id = $dbc->lastInsertId();;
                $this->setID($last_id);
                echo 'order_id '.$this->getID();
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
//            $query = "SELECT order_id FROM orders WHERE hash = '$hash'";
//            echo $query.'<br>';
//            $data = mysqli_query($dbc->connect(),$query) or die("Error fetching order_id");
//            $row = mysqli_fetch_array($data);
//            $this->setID($row['order_id']);
        }
        $db->close();

        return ['order_id' => $this->getID(),'messages' => ["Success"]];
    }

    public function addProducts($products){;
        $db = new DBC;
        $dbc = $db->connect();

        $order_id = $this->getID();

        $queryRows = [];
        //Create rows for multiple insert query
        foreach($products as $product) {
            $product_id = $product->getID();
            $quantity = $product->getQuantity();
            $queryRows[] = "(".$order_id.","."$product_id".",".$quantity.")";
        }
        try {
            $sql = "INSERT INTO orders_products(order_id,product_id,quantity) VALUES".implode(',', $queryRows);
            echo $sql;
            $dbc->query($sql);
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
        $db->close();
    }

    public function updateProducts($products){;
        // delete alle producten uit de database met order_id = int, Dan gewoon de nieuwe lijst weer invoegen onder dat order_id.
        $db = new DBC;
        $dbc = $db->connect();
        $order_id = $this->getID();

        try {
            $query = $dbc->prepare("DELETE FROM orders_products WHERE order_id = ?");
            $query->execute([$order_id]);
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
        $this->addProducts($products);

        $db->close();
    }

    public static function fetchSingle($id){
        $db = new DBC;
        $dbc = $db->connect();

        $id = trim((int)$id);

        try {
            $query = $dbc->prepare("SELECT * FROM orders WHERE order_id = ?");
            $query->execute([$id]);
            $row = $query->fetch();
            $hash = $row['hash'];
            $total= $row['total'];
            $paid = $row['paid'];
            $customer_id = $row['customer_id'];
            $order = new Order(
                $customer_id,
                $total,
                $paid,
                $hash
            );
            $order->setID($row['order_id']);
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

        $db->close();
        // Returns an array which contains all the contact objects. Which are then passed from the controller to the view.
        return $order;
    }

    public static function exists($mail){
        $db = new DBC;
        $dbc = $db->connect();

        $mail = mysqli_real_escape_string($dbc, trim($mail));

        try {
            $query = $dbc->prepare("SELECT * FROM customers WHERE email = ?");
            $query->execute([$mail]);
            if($query->rowCount() == 1) {
                $row = $query->fetch();
                $db->close();
                return ['id' => $row['customer_id']];
            } else {
                $db->close();
                return false;
            }
        } catch (\PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

}

?>