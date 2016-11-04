<?php

namespace CMS\Models\Customer;

use CMS\Models\DBC\DBC;

class Customer
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
        $db = new DBC;
        $dbc = $db->connect();

        $messages = [];
        if(!empty($this->getName()) && !empty($this->getMail()) && !empty($this->getAddress1()) && !empty($this->getCity()) && !empty($this->getPostalCode())) {
            $exists = $this->exists($this->getMail());
            if (!$exists) {
                $name = mysqli_real_escape_string($dbc, trim($this->getName()));
                $mail = mysqli_real_escape_string($dbc, trim($this->getMail()));
                $phone = mysqli_real_escape_string($dbc, trim($this->getPhone()));
                $address1 = mysqli_real_escape_string($dbc, trim($this->getAddress1()));
                $address2 = mysqli_real_escape_string($dbc, trim($this->getAddress2()));
                $city = mysqli_real_escape_string($dbc, trim($this->getCity()));
                $postal_code = mysqli_real_escape_string($dbc, trim($this->getPostalCode()));

                $query = $dbc->prepare("INSERT INTO customers(name,email,phone,address1,address2,city,postal) VALUES(?,?,?,?,?,?,?)");
                if($query) {
                    $query->bind_param("sssssss", $name, $mail, $phone, $address1, $address2, $city, $postal_code);
                    $query->execute();
                    $query->close();
                } else {
                    $db->sqlERROR();
                }

                $query = $dbc->prepare("SELECT customer_id FROM customers WHERE email = ?");
                if($query) {
                    $query->bind_param("s", $mail);
                    $query->execute();
                    $data = $query->get_result();
                    $query->close();
                    $row = $data->fetch_array();
                } else {
                    $db->sqlERROR();
                }

                $this->setID($row['customer_id']);
                echo 'customer_id '.$this->getID().'<br>';

                $dbc->close();
                $messages[] = "Customer added";

                return ['customer_id' => $this->getID(),'messages' => $messages];
            } else {
                $customer = $this->fetchSingle($exists['id']);
                // TO DO: change the address, a existing customer with no account could be moved to another address
                $customer->setName(mysqli_real_escape_string($dbc, trim($this->name)));
                $customer->setPhone(mysqli_real_escape_string($dbc, trim($this->phone)));
                $customer->setAddress1(mysqli_real_escape_string($dbc, trim($this->address1)));
                $customer->setAddress2(mysqli_real_escape_string($dbc, trim($this->address2)));
                $customer->setCity(mysqli_real_escape_string($dbc, trim($this->city)));
                $customer->setPostalCode(mysqli_real_escape_string($dbc, trim($this->postal_code)));

                $id = $customer->getID();
                $name = $customer->getName();
                $phone = $customer->getPhone();
                $address1 = $this->getAddress1();
                $address2 = $this->getAddress2();
                $city = $this->getCity();
                $postal_code = $this->getPostalCode();

                $query = $dbc->prepare("UPDATE customers SET name = ?,phone = ?,address1 = ?,address2 = ?,city = ?,postal = ? WHERE customer_id = ?");
                if($query) {
                    $query->bind_param("ssssssi", $name, $phone, $address1, $address2, $city, $postal_code, $id);
                    $query->execute();
                    $query->close();
                } else {
                    $db->sqlERROR();
                }
                $dbc->close();

                $messages[] = "Please check if your personal details and address are correct before proceeding the payment.";
                return ['customer_id' => $id,'messages' => $messages];
            }
        } else {
            $messages[] = "Please fill in all of the required fields.";
            return ['messages' => $messages];
        }
    }
    public static function fetchSingle($id){
        $db = new DBC;
        $dbc = $db->connect();

        $id = mysqli_real_escape_string($dbc, trim((int)$id));
        $query = $dbc->prepare("SELECT * FROM customers WHERE customer_id = ?");
        if($query) {
            $query->bind_param("i",$id);
            $query->execute();
            $data = $query->get_result();
            $row = $data->fetch_array();
            $name = $row['name'];
            $mail = $row['email'];
            $phone = $row['phone'];
            $address1 = $row['address1'];
            $address2 = $row['address2'];
            $city = $row['city'];
            $postal_code = $row['postal'];

            $customer = new Customer(
                $name,
                $mail,
                $phone,
                $address1,
                $address2,
                $city,
                $postal_code
            );
            $customer->setID($row['customer_id']);
        } else {
            $db->sqlERROR();
        }

        $dbc->close();
		// Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
		return $customer;
    }
    public static function exists($mail){
        $dbc = new DBC;
        $mail = mysqli_real_escape_string($dbc->connect(), trim($mail));
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