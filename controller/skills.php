<?php

class Skills extends Controller {

	public function index($params = null){
		$this->view('Skills',['skills.php'],$params);	
	}
}

?>