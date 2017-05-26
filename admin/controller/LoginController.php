<?php
	namespace Controller;

use CMS\Models\Controller\Controller;

class LoginController extends Controller {
	public function index($response,$params = null){
		$this->view('Login',['login.php'],$params);
	}
}

?>