<?php

use CMS\Models\Content\Posts\Post;
use CMS\Models\Controller\Controller;

class Posts extends Controller
{

    use \CMS\Models\Actions\UserActions;

    public function index($params = null)
    {
        $posts = Post::all();
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
        $this->UserActions('posts');
        // view takes: page_title,[array of view files],params from the router,array of data from model
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view('Posts', ['posts/posts-nav.php', 'posts/posts.php'], $params, ['posts' => $posts, 'view' => $view, 'trashed' => 0, 'js' => [JS . 'checkAll.js']]);
    }
    //
    public function AddPost($params = null)
    {
        $scripts = [
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];

        if (!isset($_POST['submit'])) {
            $post = new Post();
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => [$post],
                    'js' => $scripts
                ]
            );
        } else {
            $post = new Post($_POST);
//            $post->setTitle('Jorn');
//            $post->description = $_POST['description'];
//            $post->category = $_POST['category'];
//            $post->content = $_POST['content'];
//            $post->username = $_SESSION['username'];

            $add = $post->add();
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'post' => [$post], 'errors' => $add['errors'],
                    'messages' => $add['messages'],
                    'js' => $scripts
                ]
            );
        }
    }

    //
    public function DeletedPosts($params = null)
    {
        $posts = Post::all(1);
        $this->UserActions('posts');
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view('Deleted Posts', ['posts/posts.php'], $params, ['posts' => $posts, 'view' => $view, 'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
    }

    //
    public function EditPosts($params = null)
    {
        $scripts = [
            JS . 'tinyMCEsettings.js',
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];

        if (!isset($_POST['submit'])) {
            $post = Post::single($params[0]);

            $this->view(
                'Edit Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => $post,
                    'output_form' => true,
                    'js' => $scripts
                ]
            );
        } else {
           // $post = new Post($_POST['title'], $_POST['post_desc'], $_POST['category'], $_POST['content'], $_SESSION['username'], 'posts');
            $post = new Post($_POST);
//            $post->setTitle($_POST['title']);
//            $post->description = $_POST['description'];
//            $post->category = $_POST['category'];
//            $post->content = $_POST['content'];
            $post->user_id = $_SESSION['user_id'];
            //$edit = $post->edit($_POST['id'], $_POST['cat_name'], $_POST['confirm']);
            $edit = $post->edit();
            $this->view(
                'Edit Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => $post,
                    'output_form' => $edit['output_form'],
                    'errors' => $edit['errors'],
                    'messages' => $edit['messages'],
                    'js' => $scripts
                ]
            );
        }
    }
}

?>