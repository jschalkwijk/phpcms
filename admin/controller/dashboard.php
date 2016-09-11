<?php
use Jorn\admin\model\Content\Content;
use Jorn\admin\model\Actions\UserActions;
use Jorn\admin\model\Users\Users;

class Dashboard extends Controller {
	/*
public function index($params = null){
		$content = new template_Template('Dashboard',['admin.php'],$params);
	}
*/	use UserActions;

	public function index($params = null){
		// render the model like this:
		//$model = $this->model('Test');
		//$posts = $model->displayPosts();	
		//or directly with the autoloader.
		// the fetchAll method return an object array with DB data.
		
		$posts = Content::fetchAll('posts',0);
		$pages = Content::fetchAll('pages',0);
		$users = Users::fetchUsers('users',0);
		$views = ['actions' => 'view/manage_content.php'];
		if(!empty($_POST)) {
			$this->UserActions($_POST['dbt']);
		}
		// view takes: page_title,[array of main view files],params from the router,array of data from model
		$this->view('Dashboard',['admin.php'],$params,[ 'posts' => $posts,'pages' => $pages,'users' => $users,'view' => $views,'trashed' => 0]);	
	}
}
?>