<?php

/**
 * Created by PhpStorm.
 * User: jorn
 * Date: 16-07-16
 * Time: 12:31
 */
namespace CMS\model\Order;
use CMS\model\Support\StorageInterface;

class OrderDetails
{
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function add(Order $order)
    {
        // update session with customer and quantity
        $this->update($order);
    }

    public function update(Order $order)
    {
        // Standaard op nul zetten omdat we een nieuwe klant hebben, bij bestaande klanten
        // halen we het ID uit de DB. Er is maar een plek nodig, er moeten geen meerdere orders of klanten aan een sessie
        // worden toegevoegd.
        $this->storage->set(0,[
            'id' => (int) $order->getID(),
            'hash' => $order->getHash(),
            'total' => $order->getTotal(),
            'paid' => $order->getPaid(),
            'customer_id' => $order->getCustomerId()
        ]);
    }

    public function remove(Order $order)
    {
        // remove item from the order session
        $this->storage->unsetIndex($order->getID());
    }

    public function has(Order $order)
    {
        // Check if the order is already in the basket.
        return $this->storage->exists($order->getID());
    }

    public function get(Order $order)
    {
        // get a order from the order session by ID
        return $this->storage->get($order->getID());
    }

    public function single(){
        if(!empty($this->storage->all())) {
            foreach ($this->storage->all() as $order) {
                $id = $order['id'];
            }
            // voordat de order geplaatst is kan ik ook alle info uit de customer session halen,
            // dan hoef ik er nog geen echt aan te maken in de database. Pas als we naar betaling gaan.
            // als ze al een account hebben dan heb ik alleen customerID nodig.
            // If customer exist doe het onderstaande, anders maken we er een aan zonder ID, in de controller mpet dit worden aangepast
            // bij de details functie en de payment functie. straks testen
            $order = Order::fetchSingle($id);
            return $order;
        }

    }

    public function clear() {
        // Removes the entire basket session
        $this->storage->clear();
    }
}

?>