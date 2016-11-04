<?php
namespace CMS\Models\Basket;
use Exception;

class QuantityExc extends Exception
{
    protected $message = "You have added the maximum stock for this item";
}

?>