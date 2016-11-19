<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;
use CMS\Models\Users\Users;
use CMS\Models\Content\Posts\Post;
use CMS\Models\Content\Pages\Page;

class Dashboard extends Controller {
	use UserActions;

	public function index($params = null){
		$posts = Post::all();
		$pages = Page::all();
		$users = Users::all();
		$views = ['actions' => 'shared/manage-content.php'];
		if(!empty($_POST)) {
			$this->UserActions($_POST['dbt']);
		}
		// view takes: page_title,[array of main view files],params from the router,array of data from model
		$this->view('Dashboard',['admin.php'],$params,[ 'posts' => $posts,'pages' => $pages,'users' => $users,'view' => $views,'trashed' => 0]);	
	}
}
?>