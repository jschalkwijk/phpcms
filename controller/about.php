<?php
	use CMS\Models\Controller\Controller;
	use CMS\Models\Pages\Page;

	class About extends Controller {

	public function index($params = null){
//		$page = Page::slug($params[0]);
		$page = Page::single(74);

		$this->view('About',['about.php'],$params,['page' => $page]);
	}
}

?>