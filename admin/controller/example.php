<?php
class Example extends Controller {
	public function index($params = null){
		$content = $this->view('Example Crypto',['example.php'],$params);
	}
}
?>