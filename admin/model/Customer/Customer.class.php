<?php

class Customer_Customer
{
    private $id;
    private $name;
    private $mail;
    private $address1;
    private $address2;
    private $city;
    private $postal_code;

    public function __construct($name,$mail,$address1,$address2 = null,$city,$postal_code)
    {
        $this->name = $name;
        $this->mail = $mail;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->postal_code = $postal_code;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    protected function add(){
        $dbc = new DBC;
        $exists = $this->exists();
        if(!$exists) {
            $name = mysqli_real_escape_string($dbc->connect(), trim($this->name));
            $mail = mysqli_real_escape_string($dbc->connect(), trim($this->mail));
            $address1 = mysqli_real_escape_string($dbc->connect(), trim($this->address1));
            $address2 = mysqli_real_escape_string($dbc->connect(), trim($this->address2));
            $city = mysqli_real_escape_string($dbc->connect(), trim($this->city));
            $postal_code = mysqli_real_escape_string($dbc->connect(), trim($this->postal_code));

            $query = "INSERT INTO customers('name','email','address1','address2','city','postal_code') VALUES('$name','$mail','$address1','$address2','$city','$postal_code') LIMIT 1"
            mysqli_query($dbc, $query) or die('Error inserting new customer');
       } else {
            $customer = $this->fetchSingle($exists['id']);
            // TO DO: change the address, a existing customer with no account could be moved to another address
//            $address1 = mysqli_real_escape_string($dbc->connect(), trim($this->address1));
//            $address2 = mysqli_real_escape_string($dbc->connect(), trim($this->address2));
//            $city = mysqli_real_escape_string($dbc->connect(), trim($this->city));
//            $postal_code = mysqli_real_escape_string($dbc->connect(), trim($this->postal_code));
        }
    }
    public function fetchSingle($id){
        $dbc = new DBC;
        $query = "SELECT * FROM customers WHERE customer_id = $id";
        $data = mysqli_query($dbc->connect(),$query) or die ('Error checking for existings customer')
        $row = mysqli_fetch_array($data);
        $name = $row['name'];
        $mail = $row['mail'];
        $address1 = $row['address1'];
        $address2 = $row['address2'];
        $city = $row['city)'];
        $postal_code = $row['postal_code'];

        $customer = new Customer_Customer(
            $name,
            $mail,
            $address1,
            $address2,
            $city,
            $postal_code
        )
        $customer->setID($row['customer_id']);
        $dbc->disconnect();
		// Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
		return $customer;
    }
    public function exists(){
        $dbc = new DBC;
        $mail = mysqli_real_escape_string($dbc, trim($this->mail));
        $query = "SELECT * FROM customers WHERE mail = '$mail'";
        $data = mysqli_query($dbc,$query) or die ('Error checking for existings customer')
        if(mysqli_num_rows($data) == 1) {
            $row = mysqli_fetch_array($data);
            return ['id' => $row['customer_id']]
        } else {
            return false;
        }
    }

}

?>