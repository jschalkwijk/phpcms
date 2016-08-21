<?php
class Search extends Controller {
	public function Index($params = null){
		$content = $this->view('Search',['search.php'],$params);
	}
}
?>