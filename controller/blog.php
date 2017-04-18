<?php
	use CMS\Models\Controller\Controller;
	use CMS\Models\Posts\Post;
	use CMS\Models\Pages\Page;
	use CMS\Models\Categories\Categories;

class Blog extends Controller {

	public function index($params = null){
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
	 	$posts = $post->select()->join()->where(['category_id' => $params[0]])->grab();

		$meta = Categories::one($params[0]);
		// view takes: page_title,[array of (optional: multiple)view files],params from the router,array of data from model
		$this->view($params[1],['blog.php'],$params,['posts' => $posts,'meta' => $meta]);
	}
	
	public function Post($params = null){
		$post = Post::one($params[0]);
		$this->view(
            $params[1],
            ['single-post.php'],
            $params,
            ['post' => $post,'meta' => $post]
        );
	}
}
?>