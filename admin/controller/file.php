<?php
class File extends Controller {

	public function index($params = null){
		if(isset($_GET['delete-albums'])){
			$delete_album = files_Folders::delete_album($_GET['checkbox']);
		}
		if (isset($_GET['delete'])) {
			$delete_file = files_File::delete_files($_GET['checkbox']);
		}
		$this->view('Albums',['add-files.php','albums.php'],$params);
	}

	public function albums($params = null){
		if(isset($_GET['delete-albums'])){
			$delete_album = files_Folders::delete_album($_GET['checkbox']);
		}
		if (isset($_GET['delete'])) {
			$delete_file = files_File::delete_files($_GET['checkbox']);
		}
		if(isset($_GET['download_files'])){
			$download_file = files_File::downloadFiles();
		}
		$this->view('Albums',['add-files.php','albums.php'],$params,['js' => ['/admin/js/checkAll.js']]);
	}
}
?>