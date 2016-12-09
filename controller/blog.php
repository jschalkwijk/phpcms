<?php
use CMS\Models\Controller\Controller;
use CMS\Models\Posts\Post;
class Blog extends Controller {

	public function index($params = null){
		// render the model like this:
		//$blog = $this->model('Blog');
		//$posts = $blog->displayPosts();	
		//or directly with the autoloader.
		// the fetchAll method return an object array with DB data.
		$posts = Post::all(0);
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view(
            'Blog',
            ['blog.php'],
            $params,
            ['posts' => $posts]
        );
	}
	
	public function Category($params = null){
		$posts = Content::fetchByCategory('posts',$params[0]);
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view($params[0],['blog.php'],$params,['posts' => $posts]);	
	}
	
	public function Post($params = null){
		$post = Post::single($params[0]);
		$this->view(
            $params[1],
            ['single-post.php'],
            $params,
            ['post' => $post]
        );
	}
}
?>