<?php
namespace CMS\model\Controller;

use CMS\model\Support\SessionStorage;
use CMS\model\Basket\Basket;

class Controller {
	private $errors;
	private $tpl_name = 'default';
	private $content;
	private $jscripts;
	protected $cart;
	protected $basket;
	public $error = '';

	public function model($model){
		require('../admin/model/'.$model.'.model.php');
		return new $model();
	}

	public function view($page_title,$file_paths = [],$params,$data = [],$jscripts = null) {
		// takes an array with the file paths
		$this->content = $file_paths;
		$this->jscripts = $jscripts;
		$tpl_name = $this->tpl_name;

		if(empty($this->cart)){
			$this->cart = new SessionStorage('cart');
			$this->basket = new Basket($this->cart);
		}

		require_once('templates/'.$tpl_name.'/header.php');
		require_once('templates/'.$tpl_name.'/nav.php');
		require_once('templates/'.$tpl_name.'/content-top.php');

		foreach ($this->content as $content){
			if(file_exists('view/'.$content)){
				require_once('view/'.$content);
			} else {
				$this->errors[] = 'Sorry, view/'.$content.' does not exist!';
			}
		}
		if(!empty($this->errors)){
			echo implode('<br />',$this->errors);
		}
		require_once('templates/'.$tpl_name.'/content-bottom.php');
		require_once('templates/'.$tpl_name.'/footer.php');
	}

	public function err($params = null){
		$this->view('Sorry, page doesn\'t exist.',['404.php'],$params,['error' => $this->error]);
	}

}


?>