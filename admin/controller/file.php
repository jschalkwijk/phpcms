<?php

use Jorn\admin\model\Controller\Controller;
use Jorn\admin\model\File\Folders;
use Jorn\admin\model\File\File as F;

class File extends Controller {

	public function index($params = null){
		if(isset($_GET['delete-albums'])){
			Folders::delete_album($_GET['checkbox']);
		}
		if (isset($_GET['delete'])) {
			F::delete_files($_GET['checkbox']);
		}
		$this->view('Albums',['add-files.php','albums.php'],$params);
	}

	public function albums($params = null){
		if(isset($_GET['delete-albums'])){
			Folders::delete_album($_GET['checkbox']);
		}
		if (isset($_GET['delete'])) {
			F::delete_files($_GET['checkbox']);
		}
		if(isset($_GET['download_files'])){
			F::downloadFiles();
		}
		$this->view('Albums',['add-files.php','albums.php'],$params,['js' => [JS.'checkAll.js']]);
	}
}
?>