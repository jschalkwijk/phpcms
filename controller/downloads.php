<?php
use CMS\Models\Controller\Controller;

class Downloads extends Controller {

	public function index($params = null){
		$this->view('Downloads',['downloads.php'],$params);	
	}
}

?>