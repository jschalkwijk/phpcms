<?php
// implements abstract class StorageInterface
namespace CMS\model\Support;

use CMS\model\Support\StorageInterface as Store;

class SessionStorage implements Store
{
    protected $basket;

    public function __construct($basket = "default")
    {
        // Create new session ['default']
        if(!isset($_SESSION[$basket])){
            $_SESSION[$basket] = [];
        }

        $this->basket = $basket;
    }

    public function set($index,$value)
    {
        // Set new product to current basket session which holds the ID as key, and as values, the ID and quantity
        // $_SESSION['default']['10',[ 'product_id' => 10, 'quantity' => 1,]]
        $_SESSION[$this->basket][$index] = $value;
    }

    public function get($index)
    {
        // Get a product by it's ID, if it exists.
        if(!$this->exists($index)) {
            return null;
        }

        return $_SESSION[$this->basket][$index];
    }

    public function exists($index)
    {
        // Check if a product is in the current basket session
        // $_SESSION['default']['10']
        return isset($_SESSION[$this->basket][$index]);
    }

    public function all()
    {
        // returns all the products in the basket session as array
        return $_SESSION[$this->basket];
    }

    public function unsetIndex($index)
    {
        // Remove a product from the session by it's ID, if it exist
        if($this->exists($index)){
            unset($_SESSION[$this->basket][$index]);
        }
    }

    public function clear(){
        // Remove entire basket session by it's name
        unset($_SESSION[$this->basket]);
    }

    public function count(){
        // ount all basket items in the session
        return count($this->all());
    }
}

?>