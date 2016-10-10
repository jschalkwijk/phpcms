<?php

use Jorn\admin\model\Controller\Controller;

class Downloads {
	public function Index($params = null){
			$content = new Template_Template('Downloads',['add-download.php','downloads.php'],$params);
		}
	public function DeletedDownloads($params = null){
			$content = new Template_Template('Deleted Downloads',['deleted-downloads.php'],$params);
		}
}
?>