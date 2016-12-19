<?php
use CMS\Models\Controller\Controller;
use CMS\Models\Posts\Post;
use CMS\Models\Pages\Page;
	use CMS\Models\Categories\Categories;
class Blog extends Controller {

	public function index($params = null){
		// render the model like this:
		//$blog = $this->model('Blog');
		//$posts = $blog->displayPosts();	
		//or directly with the autoloader.
		// the fetchAll method return an object array with DB data.
		$posts = Post::all(0);
		$meta = Page::slug($params[0]);
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view(
            'Blog',
            ['blog.php'],
            $params,
            ['posts' => $posts,'meta' => $meta]
        );
	}

	public function Categories($params = null)
	{
		$categories = Categories::all(0);
		$meta = Page::slug('Categories');
		$this->view('Categories',['categories.php'],$params,['categories' => $categories,'meta' => $meta]);
	}
	public function Category($params = null){
		$post = new Post();
		$query = $post->select().$post->from().$post->where(['category_id' => $params[0]]);
		$posts = $post->newQuery($query);

		$meta = Categories::single($params[0]);
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view($params[1],['blog.php'],$params,['posts' => $posts,'meta' => $meta]);
	}
	
	public function Post($params = null){
		$post = Post::single($params[0]);
		$this->view(
            $params[1],
            ['single-post.php'],
            $params,
            ['post' => $post,'meta' => $post]
        );
	}
}
?>