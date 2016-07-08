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

    public function __construct($name,$mail,$phone,$address1,$address2 = null,$city,$postal_code)
    {
        $this->id = 0;
        $this->name = $name;
        $this->mail = $mail;
        $this->phone = $phone;
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city = $city;
        $this->postal_code = $postal_code;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }
    public function setAddress1($address){
        $this->address1 = $address;
    }

    public function getAddress1()
    {
        return $this->address1;
    }
    public function setAddress2($address){
        $this->address2 = $address;
    }

    public function getAddress2()
    {
        return $this->address2;
    }
    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    public function getPostalCode()
    {
        return $this->postal_code;
    }
    public function add(){
        $dbc = new DBC;
        $messages = [];
        if(!empty($this->getName()) && !empty($this->getMail()) && !empty($this->getAddress1()) && !empty($this->getCity()) && !empty($this->getPostalCode())) {
            $exists = $this->exists();
            if (!$exists) {
                $name = mysqli_real_escape_string($dbc->connect(), trim($this->getName()));
                $mail = mysqli_real_escape_string($dbc->connect(), trim($this->getMail()));
                $phone = mysqli_real_escape_string($dbc->connect(), trim($this->getPhone()));
                $address1 = mysqli_real_escape_string($dbc->connect(), trim($this->getAddress1()));
                $address2 = mysqli_real_escape_string($dbc->connect(), trim($this->getAddress2()));
                $city = mysqli_real_escape_string($dbc->connect(), trim($this->getCity()));
                $postal_code = mysqli_real_escape_string($dbc->connect(), trim($this->getPostalCode()));

                $query = "INSERT INTO customers(name,email,phone,address1,address2,city,postal) VALUES('$name','$mail','$phone','$address1','$address2','$city','$postal_code')";
                mysqli_query($dbc->connect(), $query) or die('Error inserting new customer');

                $last_id = mysqli_insert_id($dbc->connect());
                $dbc->disconnect();
                $messages[] = "Customer added";

                return ['customer_id' => $last_id,'messages' => $messages];
            } else {
                $customer = $this->fetchSingle($exists['id']);
                // TO DO: change the address, a existing customer with no account could be moved to another address
                $customer->setPhone(mysqli_real_escape_string($dbc->connect(), trim($this->phone)));
                $customer->setAddress1(mysqli_real_escape_string($dbc->connect(), trim($this->address1)));
                $customer->setAddress2(mysqli_real_escape_string($dbc->connect(), trim($this->address2)));
                $customer->setCity(mysqli_real_escape_string($dbc->connect(), trim($this->city)));
                $customer->setPostalCode(mysqli_real_escape_string($dbc->connect(), trim($this->postal_code)));

                $id = $customer->getID();
                $phone = $customer->getPhone();
                $address1 = $this->getAddress1();
                $address2 = $this->getAddress2();
                $city = $this->getCity();
                $postal_code = $this->getPostalCode();

                $query = "UPDATE customers SET phone = '$phone',address1 = '$address1',address2 = '$address2',city = '$city',postal = '$postal_code' WHERE customer_id = $id";
                mysqli_query($dbc->connect(), $query) or die('Error updating existing customer');
                $dbc->disconnect();

                $messages[] = "Please check if your personal details and address are correct before proceeding the payment.";
                return ['customer_id' => $id,'messages' => $messages];
            }
        } else {
            $messages[] = "Please fill in all of the required fields.";
            return ['messages' => $messages];
        }
    }
    public function fetchSingle($id){
        $dbc = new DBC;
        $id = mysqli_real_escape_string($dbc->connect(), trim($id));
        $query = "SELECT * FROM customers WHERE customer_id = $id";
        $data = mysqli_query($dbc->connect(),$query) or die ('Error checking for existing customer');
        $row = mysqli_fetch_array($data);
        $name = $row['name'];
        $mail = $row['email'];
        $phone = $row['phone'];
        $address1 = $row['address1'];
        $address2 = $row['address2'];
        $city = $row['city'];
        $postal_code = $row['postal'];

        $customer = new Customer_Customer(
            $name,
            $mail,
            $phone,
            $address1,
            $address2,
            $city,
            $postal_code
        );
        $customer->setID($row['customer_id']);
        $dbc->disconnect();
		// Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
		return $customer;
    }
    public function exists(){
        $dbc = new DBC;
        $mail = mysqli_real_escape_string($dbc->connect(), trim($this->mail));
        $query = "SELECT * FROM customers WHERE email = '$mail'";
        $data = mysqli_query($dbc->connect(),$query) or die ('Error checking for existings customer');
        if(mysqli_num_rows($data) == 1) {
            $row = mysqli_fetch_array($data);
            $dbc->disconnect();
            return ['id' => $row['customer_id']];
        } else {
            $dbc->disconnect();
            return false;
        }
    }

    public function getOrders(){

    }

}

?>