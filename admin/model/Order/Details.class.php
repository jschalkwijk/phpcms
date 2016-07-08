<?php

class Order_Details{
    protected $storage;
    protected $customer;

    public function __construct(Support_StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function add(Customer_Customer $customer)
    {
        // update session with customer and quantity
        $this->update($customer);
    }

    public function update(Customer_Customer $customer)
    {
        // Standaard op nul zetten omdat we een nieuwe klant hebben, bij bestaande klanten
        // halen we het ID uit de DB.
        $this->storage->set(0,[
            'customer_id' => $customer->getID(),
            'name' => $customer->getName(),
            'email' => $customer->getMail(),
            'phone' => $customer->getPhone(),
            'address1' => $customer->getAddress1(),
            'address2' => $customer->getAddress2(),
            'city' => $customer->getCity(),
            'postal_code' => $customer->getPostalCode()
        ]);
    }

    public function remove(Customer_Customer $customer)
    {
        // remove item from the basket session
        $this->storage->unsetcustomer($customer->getID());
    }

    public function has(Customer_Customer $customer)
    {
        // Check if the customer is already in the basket.
        return $this->storage->exists($customer->getID());
    }

    public function get()
    {
        // get a customer from the basket session by ID
        return $this->storage->get(0);
    }

    public function clear() {
        // Removes the entire basket session
        $this->storage->clear();
    }
}
?>