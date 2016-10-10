<?php
use Jorn\admin\model\Controller\Controller;

class Albums extends Controller {
	public function index($params = null){
		$content = $this->view('Albums',['add-files.php','albums.php'],$params);
	}
}
?>