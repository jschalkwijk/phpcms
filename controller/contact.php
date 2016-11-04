<?php
use CMS\Models\Controller\Controller;

class Contact extends Controller {

	public function index($params = null){
		$this->view('Contact',['contact.php'],$params);	
	}
}

?>