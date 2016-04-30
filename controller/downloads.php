<?php

class Downloads extends Controller {

	public function index($params = null){
		$this->view('Downloads',['downloads.php'],$params);	
	}
}

?>