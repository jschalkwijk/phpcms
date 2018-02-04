<?php
namespace CMS\Core\CoreController;

use CMS\Core\Auth\Auth;

class CoreController {
	private $errors;
	private $tpl_name = 'default';
	private $content;
	private $jscripts;
	protected $user;

	public $error = '';

	public function model($model){
		require('../../Models/'.$model);
		return new $model();
	}

	public function view($page_title,$file_paths = [],$params = null,$data = [],$jscripts = null) {
		// takes an array with the file paths
		$this->content = $file_paths;
		$this->jscripts = $jscripts;
		$tpl_name = $this->tpl_name;
        $this->user = Auth::user();
		require_once('templates/'.$tpl_name.'/header.php');
		require_once('templates/'.$tpl_name.'/nav.php');
//		require_once('templates/'.$tpl_name.'/content-top.php');

		foreach ($this->content as $content){
			if(file_exists('view/'.$content)){
				require_once('view/'.$content);
			} else {
				$this->errors[] = 'Sorry, view/'.$content.' does not exist!';
			}
		}
		if(!empty($this->errors)){
			echo implode('<br />',$this->errors);
		}
//		require_once('templates/'.$tpl_name.'/content-bottom.php');
		require_once('templates/'.$tpl_name.'/footer.php');
	}

	public function err($params = null){
		$this->view('Sorry, page doesn\'t exist.',['404.php'],$params,['error' => $this->error]);
	}

}


?>