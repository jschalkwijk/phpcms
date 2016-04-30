<?php
class Downloads {
	public function Index($params = null){
			$content = new template_Template('Downloads',['add-download.php','downloads.php'],$params);
		}
	public function DeletedDownloads($params = null){
			$content = new template_Template('Deleted Downloads',['deleted-downloads.php'],$params);
		}
}
?>