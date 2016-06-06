<?php
class Search extends Controller {
	public function Index($params = null){
		$content = new Template_Template('Search',['search.php'],$params);
	}
}
?>