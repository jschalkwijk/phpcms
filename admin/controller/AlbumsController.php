<?php
	namespace Controller;

use CMS\Models\Controller\Controller;

class AlbumsController extends Controller {
	public function index($response,$params = null){
		$this->view('Albums',['add-files.php','albums.php'],$params);
	}
}
?>