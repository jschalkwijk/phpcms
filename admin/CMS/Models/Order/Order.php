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
            $sql = "UPDATE orders SET total = ?,paid = ?,customer_id = ? WHERE order_id = ?";
            $query = $dbc->prepare($sql);
            echo $sql." params ".$total,$paid,$customer_id,$order_id;
            if($query){
                $query->bind_param("diii",$total,$paid,$customer_id,$order_id);
                $query->execute();
                $query->close();
            } else {
                $db->sqlERROR();
            }
        } else {
            $query = $dbc->prepare("INSERT INTO orders(hash,total,paid,customer_id) VALUES(?,?,?,?)");
            if($query){
                $query->bind_param("sdii",$hash,$total,$paid,$customer_id);
                $query->execute();
                $last_id = $query->insert_id;
                $query->close();
                $this->setID($last_id);
                echo 'order_id '.$this->getID();
            } else {
                $db->sqlERROR();
            }
//            $query = "SELECT order_id FROM orders WHERE hash = '$hash'";
//            echo $query.'<br>';
//            $data = mysqli_query($dbc->connect(),$query) or die("Error fetching order_id");
//            $row = mysqli_fetch_array($data);
//            $this->setID($row['order_id']);
        }
        $dbc->close();

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
        $sql = "INSERT INTO orders_products(order_id,product_id,quantity) VALUES".implode(',', $queryRows);
        echo $sql;
        $query = $dbc->query($sql);
        $dbc->close();
    }

    public function updateProducts($products){;
        // delete alle producten uit de database met order_id = int, Dan gewoon de nieuwe lijst weer invoegen onder dat order_id.
        $db = new DBC;
        $dbc = $db->connect();
        $order_id = $this->getID();

        $query = $dbc->prepare("DELETE FROM orders_products WHERE order_id = ?");
        if($query){
            $query->bind_param("i",$order_id);
            $query->execute();
            $query->close();
        } else {
            $db->sqlERROR();
        }

        $this->addProducts($products);

        $dbc->close();
    }

    public static function fetchSingle($id){
        $db = new DBC;
        $dbc = $db->connect();

        $id = mysqli_real_escape_string($dbc, trim((int)$id));

        $query = $dbc->prepare("SELECT * FROM orders WHERE order_id = ?");
        if($query){
            $query->bind_param("i",$id);
            $query->execute();
            $data = $query->get_result();
            $query->close();
            $row = $data->fetch_array();
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
        } else {
            $db->sqlERROR();
        }

        $dbc->close();
        // Returns an array which contains all the contact objects. Which are then passed from the controller to the view.
        return $order;
    }

    public static function exists($mail){
        $db = new DBC;
        $dbc = $db->connect();

        $mail = mysqli_real_escape_string($dbc, trim($mail));
        $query = $dbc->prepare("SELECT * FROM customers WHERE email = ?");
        if($query){
            $query->bind_param("s",$mail);
            $query->execute();
            $data = $query->get_result();
            $query->close();
            if($data->num_rows == 1) {
                $row = $data->fetch_row();
                $dbc->close();
                return ['id' => $row['customer_id']];
            } else {
                $dbc->close();
                return false;
            }
        } else {
            $db->sqlERROR();
            $dbc->close();
            return false;
        }
    }

}

?>