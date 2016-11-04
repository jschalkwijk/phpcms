<?php

use CMS\model\Controller\Controller;

class Search extends Controller {
	public function Index($params = null){
		$content = $this->view('Search',['search/search.php'],$params);
	}
}
?>