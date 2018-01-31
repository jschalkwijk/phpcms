<?php
namespace CMS\Models\Controller;

use CMS\Core\Auth\Auth;
use CMS\Core\CoreController\CoreController;
use CMS\Models\Basket\Basket;
use CMS\Models\Support\SessionStorage;

class Controller extends CoreController
{
	protected $cart;
	protected $basket;
	protected $currentUser;
	public $user;
	public function __construct()
	{
		if(empty($this->cart)){
			$this->cart = new SessionStorage('cart');
			$this->basket = new Basket($this->cart);
		}
		$this->currentUser = $_SESSION['user_id'];
		$this->user = Auth::user();
	}
}


?>