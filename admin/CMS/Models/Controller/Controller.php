<?php
namespace CMS\Models\Controller;

use CMS\Core\CoreController\CoreController;
use CMS\Models\Basket\Basket;
use CMS\Models\Support\SessionStorage;

class Controller extends CoreController
{
	protected $cart;
	protected $basket;

	public function __construct()
	{
		if(empty($this->cart)){
			$this->cart = new SessionStorage('cart');
			$this->basket = new Basket($this->cart);
		}
	}
}


?>