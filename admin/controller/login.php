<?php

use CMS\model\Controller\Controller;

class Login extends Controller {
	public function index($params = null){
		$this->view('Login',['login.php'],$params);
	}
}

?>