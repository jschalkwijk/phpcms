<?php
	use CMS\model\Controller\Controller;
	class iLearnMore extends Controller {

	public function index($params = null){
		$this->view('I Learn More',['i-learn-more.php'],$params);	
	}
}

?>