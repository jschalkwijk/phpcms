<?php
class Controller{
	private $errors;
	private $tpl_name = 'default';
	public $error = array();

	// Takes a class name that references to a model called from the controllers
	// Returns a new model.class.php form /model/
	public function model($model){
		require('../model/'.$model.'.model.php');
		return new $model();
	}

	/*
	 * Renders the template: Takes a page title, a array of paths to specified view.php files
	 * from the views folder. This can be one file or multiple files. ['test.php','test2.html']
	 * It takes the parameters if specified, otherwise it needs to be null. Parameters can be
	 * used in view files. Then it takes an array of Data, these can be objects which is more
	 * commen but also e single string. Always define a Key/Value pair. These canbe used inside a view
	 * file to display data given by a model.
	 * Check a basic controller for an example.
	 *
	 * As you can see, the template files reside in the template folder under the templates name.
	 * For now the name is specified above in $this->tpl_name.
	*/
	public function view($page_title,$file_paths,$params,$data = []) {	
	// takes an array with the file paths
		$this->content = $file_paths;
		$tpl_name = $this->tpl_name;
		require_once('templates/'.$tpl_name.'/header.php');
		require_once('templates/'.$tpl_name.'/nav.php');
		require_once('templates/'.$tpl_name.'/content-top.php');
		
		foreach ($file_paths as $content){
			if(file_exists('view/'.$content)){
				require_once('view/'.$content);
			} else {
				$this->errors[] = 'Sorry, view/'.$content.' does not exist!';
			}
		}
		if(!empty($this->errors)){
			echo implode('<br />',$this->errors);
		}
		require_once('templates/'.$tpl_name.'/content-bottom.php');
		require_once('templates/'.$tpl_name.'/footer.php');
	}

	/*
	 * if a controller that doesn't exist is beimg calles by the index.php App class, the
	 * err method is called to display the custom 404 page.
	 *
	 */
	public function err($params = null){
		$this->view('Sorry, page doesn\'t exist.',['404.php'],$params,['error' => $this->error]);	
	}

}


?>