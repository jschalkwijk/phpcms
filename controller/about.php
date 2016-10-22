<?php
use CMS\model\Controller\Controller;

class About extends Controller {

	public function index($params = null){
		$this->view('About',['about.php'],$params);	
	}
}

?>