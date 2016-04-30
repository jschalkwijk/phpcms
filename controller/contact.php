<?php

class Contact extends Controller {

	public function index($params = null){
		$this->view('Contact',['contact.php'],$params);	
	}
}

?>