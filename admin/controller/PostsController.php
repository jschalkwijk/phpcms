<?php

use CMS\Models\Posts\Post;
use CMS\Models\Controller\Controller;
use CMS\Models\Tag\Tag;
use CMS\Models\Actions\UserActions;
use CMS\Models\Categories\Categories as Cat;

class Posts extends Controller
{
    public $messages = [];
    use UserActions;

    public function index($params = null)
    {
        $posts = Post::joined()->where(['trashed' => 0])->orderBy('post_id','DESC')->grab();
        // $posts = Post::allWhere(['trashed' => 0]);
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
    public function deleted($params = null)
    {
        $posts = Post::allWhere(['trashed' => 1]);
        $this->UserActions($posts[0]);
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view('Deleted Posts', ['posts/posts.php'], $params, ['posts' => $posts, 'view' => $view, 'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
    }
    //
    public function create($params = null)
    {
        $scripts = [
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];

        $tags = Tag::all();
        $post = new Post($_POST);

        if (isset($_POST['submit'])) {
            if(!empty($post->category)) {
                $category = new Cat(['title' => $post->category,'type' => $post->cat_type]);
                $category->save();
                // add value to the request to be run by the prepareQuery
                // otherwise it won't be seen added when save() is called.
                $post->request['category_id'] = $category->lastInsertId;
            }
            $post->user_id = $this->currentUser;
            print_r($post->request);
            if(!empty($post->title) && !empty($post->content) && (!empty($post->category_id) || !empty($post->category) )) {
                $post->save();
                header("Location: ".ADMIN."posts");
            } else {
                $this->messages[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
            };
        }

        $this->view(
            'Add Post',
            ['posts/add-edit-post.php'],
            $params,
            [
                'post' => $post,
                'tags' => $tags,
                'messages' => $this->messages,
                'js' => $scripts
            ]
        );
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

        foreach ($post->tags() as $tag) {
            $selectedTag[] = $tag->tag_id;
        };

        if (isset($_POST['submit'])) {
            $post->patch($_POST);
            if (!empty($post->category)) {
                echo "Hello!";
                $category = new Cat(['title' => $post->category, 'type' => $post->cat_type]);
                $add = $category->save();
                // add value to the request to be run by the prepareQuery
                // otherwise it won't be seen added when save() is called.

                $post->patch(['category_id' => $category->lastInsertId]);
            }
            $post->user_id = $this->currentUser;
            print_r($post->request);
            //update model but do not save it yet before check.

            if (!empty($post->title) && !empty($post->content) && !empty($post->category_id)) {
                $post->savePatch();
                header("Location: ".ADMIN."posts");
            } else {
                $this->messages[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
            };
        }

        $this->view(
            'Edit Post',
            ['posts/add-edit-post.php'],
            $params,
            [
                'post' => $post,
                'tags' => $tags,
                'selectedTag' => $selectedTag,
                'messages' => $this->messages,
                'js' => $scripts
            ]
        );
    }
}

?>