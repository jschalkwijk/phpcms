<?php

namespace CMS\model\Order;

use CMS\model\Support\StorageInterface;
use CMS\model\Customer\Customer;

class CustomerDetails{
    protected $storage;
    protected $customer;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function add(Customer $customer)
    {
        // update session with customer and quantity
        $this->update($customer);
    }

    public function update(Customer $customer)
    {
        // Standaard op nul zetten omdat we een nieuwe klant hebben, bij bestaande klanten
        // halen we het ID uit de DB. OOk bij het aanpassen van de email moeten we geen nieuwe klant toevoegen
        // maar de bestaande aanpassen.
        $this->storage->set(0,[
            'id' => (int) $customer->getID(),
            'name' => $customer->getName(),
            'email' => $customer->getMail(),
            'phone' => $customer->getPhone(),
            'address1' => $customer->getAddress1(),
            'address2' => $customer->getAddress2(),
            'city' => $customer->getCity(),
            'postal_code' => $customer->getPostalCode()
        ]);
    }

    public function remove(Customer $customer)
    {
        // remove item from the basket session
        $this->storage->unsetIndex($customer->getID());
    }

    public function has(Customer $customer)
    {
        // Check if the customer is already in the basket.
        return $this->storage->exists($customer->getID());
    }

    public function get(Customer $customer)
    {
        // get a customer from the basket session by ID
        return $this->storage->get($customer->getID());
    }

    public function single(){
        if(!empty($this->storage->all())) {
            foreach ($this->storage->all() as $customer) {
                $id = $customer['id'];
            }
            // voordat de order geplaatst is kan ik ook alle info uit de customer session halen,
            // dan hoef ik er nog geen echt aan te maken in de database. Pas als we naar betaling gaan.
            // als ze al een account hebben dan heb ik alleen customerID nodig.
            // If customer exist doe het onderstaande, anders maken we er een aan zonder ID, in de controller mpet dit worden aangepast
            // bij de details functie en de payment functie. straks testen
            $customer = Customer::fetchSingle($id);
            return $customer;
        }
    }

    public function clear() {
        // Removes the entire basket session
        $this->storage->clear();
    }
}
?>