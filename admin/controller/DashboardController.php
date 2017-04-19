<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;
use CMS\Models\Users\Users;
use CMS\Models\Posts\Post;
use CMS\Models\Pages\Page;

class Dashboard extends Controller {
	use UserActions;

	public function index($params = null){
		$posts = Post::all()->grab();
		$pages = Page::all()->grab();
		$users = Users::all()->grab();
		$views = ['actions' => 'shared/manage-content.php'];
		if(!empty($_POST)) {
			$this->UserActions($_POST['dbt']);
		}
		// view takes: page_title,[array of main view files],params from the router,array of data from model
		$this->view('Dashboard',['admin.php'],$params,[ 'posts' => $posts,'pages' => $pages,'users' => $users,'view' => $views,'trashed' => 0]);	
	}
}
?>