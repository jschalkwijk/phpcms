<?php
use CMS\Models\Controller\Controller;

class Skills extends Controller {

	public function index($params = null){
		$this->view('Skills',['skills.php'],$params);	
	}
}

?>