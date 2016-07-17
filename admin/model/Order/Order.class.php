<?php

class Order_Order{
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
        $dbc = new DBC;
        $newHash = $this->newHash();
        $hash = $newHash;
        $total = $this->getTotal();
        $paid = $this->getPaid();
        $customer_id = $this->getCustomerId();

        if(!empty($this->getID())){
            $order_id = $this->getID();
            $query = "UPDATE orders SET total = $total,paid = '$paid',customer_id = $customer_id WHERE order_id = $order_id";
            echo $query;
            mysqli_query($dbc->connect(),$query) or die("Error updating existing order!");
        } else {
            $query = "INSERT INTO orders(hash,total,paid,customer_id) VALUES('$hash',$total,'$paid',$customer_id)";
            mysqli_query($dbc->connect(),$query) or die("Error inserting new order!");
            echo $query.'<br>';
            $query = "SELECT order_id FROM orders WHERE hash = '$hash'";
            echo $query.'<br>';
            $data = mysqli_query($dbc->connect(),$query) or die("Error fetching order_id");
            $row = mysqli_fetch_array($data);
            $this->setID($row['order_id']);
            echo 'order_id '.$this->getID();
        }
        $dbc->disconnect();

        return ['order_id' => $this->getID(),'messages' => ["Success"]];
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

    public function updateProducts($products){;
        // delete alle producten uit de database met order_id = int, Dan gewoon de nieuwe lijst weer invoegen onder dat order_id.
        $dbc = new DBC;
        $order_id = $this->getID();

        $query = "DELETE FROM orders_products WHERE order_id = $order_id";
        mysqli_query($dbc->connect(),$query) or die('Error deleting products for revised order');

        $this->addProducts($products);

        $dbc->disconnect();
    }

    public static function fetchSingle($id){
        $dbc = new DBC;
        $id = mysqli_real_escape_string($dbc->connect(), trim((int)$id));
        $query = "SELECT * FROM orders WHERE order_id = $id";
        $data = mysqli_query($dbc->connect(),$query) or die ('Error checking for existing order');
        $row = mysqli_fetch_array($data);
        $hash = $row['hash'];
        $total= $row['total'];
        $paid = $row['paid'];
        $customer_id = $row['customer_id'];

        $order = new Order_Order(
            $customer_id,
            $total,
            $paid,
            $hash
        );
        $order->setID($row['order_id']);
        $dbc->disconnect();
        // Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
        return $order;
    }

    public static function exists($mail){
        $dbc = new DBC;
        $mail = mysqli_real_escape_string($dbc->connect(), trim($mail));
        $query = "SELECT * FROM customers WHERE email = '$mail'";
        $data = mysqli_query($dbc->connect(),$query) or die ('Error checking for existing customer');
        if(mysqli_num_rows($data) == 1) {
            $row = mysqli_fetch_array($data);
            $dbc->disconnect();
            return ['id' => $row['customer_id']];
        } else {
            $dbc->disconnect();
            return false;
        }
    }

}

?>