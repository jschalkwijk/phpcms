<?php
class Search extends Controller {
	public function Index($params = null){
		$content = new template_Template('Search',['search.php'],$params);
	}
}
?>