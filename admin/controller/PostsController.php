<?php

use CMS\Models\Posts\Post;
use CMS\Models\Controller\Controller;
use CMS\Models\Tag\Tag;
use CMS\Models\Actions\UserActions;
class Posts extends Controller
{

    use UserActions;

    public function index($params = null)
    {
        $posts = Post::allWhere(['trashed' => 0]);
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
        $this->UserActions($posts[0]);
        // view takes: page_title,[array of view files],params from the router,array of data from model
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view(
            'Posts',
            [
                'posts/posts-nav.php',
                'posts/posts.php'
            ],
            $params,
            [
                'posts' => $posts,
                'view' => $view,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }
    //
    public function create($params = null)
    {
        $scripts = [
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];
        $tags = Tag::all();

        if (!isset($_POST['submit'])) {
            $post = new Post();
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => [$post],
                    'tags' => $tags,
                    'js' => $scripts
                ]
            );
        } else {
            $post = new Post($_POST);
            $add = $post->add();
            $this->view(
                'Add Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'post' => [$post],
                    'tags' => $tags,
                    'errors' => $add['errors'],
                    'messages' => $add['messages'],
                    'js' => $scripts
                ]
            );
        }
    }

    //
    public function deleted($params = null)
    {
        $posts = Post::allWhere(['trashed' => 1]);
        $this->UserActions($posts[0]);
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view('Deleted Posts', ['posts/posts.php'], $params, ['posts' => $posts, 'view' => $view, 'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
    }

    //
    public function edit($params = null)
    {
        $scripts = [
            JS . 'tinyMCEsettings.js',
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];

        $post = Post::one($params[0]);
        $tags = Tag::allWhere(['type' => 'post']);
        $selectedTag = [];
        foreach ($post[0]->tags() as $tag) {
            $selectedTag[] = $tag->tag_id;
        };

        if (!isset($_POST['submit'])) {
            $this->view(
                'Edit Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => $post,
                    'tags' => $tags,
                    'selectedTag' => $selectedTag,
                    'output_form' => true,
                    'js' => $scripts
                ]
            );
        } else {
            $post = $post[0];
            $post->request = $_POST;
            $post->user_id = $_SESSION['user_id'];
            $edit = $post->edit();
            $this->view(
                'Edit Post',
                ['posts/add-edit-post.php'],
                $params,
                [
                    'post' => [$post],
                    'tags' => $tags,
                    'selectedTag' => $selectedTag,
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