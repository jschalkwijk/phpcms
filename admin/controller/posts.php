<?php

use CMS\Models\Content\Content;
use CMS\Models\Content\Posts\Post;
use CMS\Models\Controller\Controller;

class Posts extends Controller
{
    // import useractions trait
    use \CMS\Models\Actions\UserActions;

    public function index($params = null)
    {
        $posts = Content::fetchAll('posts', 0);
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
            $post = new Post(null, null, null, null, null, 'posts');
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => $post,
                    'js' => $scripts
                ]
            );
        } else {
            $post = new Post($_POST['title'], $_POST['post_desc'], $_POST['category'], $_POST['content'], $_SESSION['username'], 'posts');
            $add = $post->addPost();
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'post' => $post, 'errors' => $add['errors'],
                    'messages' => $add['messages'],
                    'js' => $scripts
                ]
            );
        }
    }

    //
    public function DeletedPosts($params = null)
    {
        $posts = Content::fetchAll('posts', 1);
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
            $post = Content::fetchSingle('posts', $params[0]);
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
            $post = new Post($_POST['title'], $_POST['post_desc'], $_POST['category'], $_POST['content'], $_SESSION['username'], 'posts');
            $edit = $post->addPost($_POST['id'], $_POST['cat_name'], $_POST['confirm']);
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