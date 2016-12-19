<?php
use CMS\Models\Controller\Controller;
use CMS\Models\Pages\Page;
class Contact extends Controller {

	public function index($params = null){
		$meta = Page::slug($params[0]);
		$this->view('Contact',['contact.php'],$params,['meta' => $meta]);
	}
}

?>