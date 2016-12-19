<?php
	use CMS\Models\Controller\Controller;
	use CMS\Models\Pages\Page;

class Skills extends Controller {

	public function index($params = null){
		$meta = Page::slug($params[0]);
		$this->view('Skills',['skills.php'],$params,['meta' => $meta]);
	}
}

?>