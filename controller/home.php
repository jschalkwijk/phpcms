<?php

class Home extends Controller {

	public function index($params = null){
		/*
		 * Render the model like this:
		 * $blog = $this->model('Blog');
		 * $posts = $blog->displayPosts();
		 * or directly with the autoloader defined in the config.php.
		 * the method will return a object array with data (mostly pulled from the DB).
		*/
		$helloWorld = example_Example::helloWorld();
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view('Home',['home.php'],$params,['hello' => $helloWorld['example']]);	
	}
}
?>