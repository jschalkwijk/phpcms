<?php

use CMS\model\Controller\Controller;

class Example extends Controller {
	public function index($params = null){
		$this->view('Example Crypto',['example.php'],$params);
	}
}
?>