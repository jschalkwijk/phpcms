<?php

namespace Controller;

use CMS\Models\Actions\Actions;
use CMS\Models\Posts\Post;
use CMS\Models\Controller\Controller;
use CMS\Models\Tag\Tag;
use CMS\Models\Actions\UserActions;
use CMS\Models\Categories\Categories as Cat;

class PostsController extends Controller
{
    public $messages = [];
    use UserActions;

    public function index($response,$params = null)
    {
        $posts = Post::joined()->where(['trashed' => 0])->orderBy('post_id','DESC')->grab();
        // $posts = Post::allWhere(['trashed' => 0]);
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
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
    public function deleted($response,$params = null)
    {
        $posts = Post::allWhere(['trashed' => 1]);
        $view = ['search' => 'view/search/search-post.php', 'actions' => 'view/shared/manage-content.php'];
        $this->view('Deleted Posts', ['posts/posts.php'], $params, ['posts' => $posts, 'view' => $view, 'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
    }
    //
    public function show($response,$params = null)
    {
        $post = Post::one($params['id']);

        $this->view(
            $post->title,
            ['posts/show.php'],
            $params,
            [
                'post' => $post,
            ]
        );
    }
    public function create($response,$params = null)
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
    public function edit($response,$params = null)
    {
        $scripts = [
            JS . 'tinyMCEsettings.js',
            JS . 'mceAddons.js',
            JS . 'checkAll.js'
        ];

       if(!isset($_SESSION['invalid_edit'])){
           $post = Post::one($params['id']);
       } else {
           $post = $_SESSION['invalid_edit'];
           unset($_SESSION['invalid_edit']);
       }
        /*  if I redirect to update, I can prevent the self locking problem, but the I can't return to the edit
            page if the validation doesn't checkout. Maybe if I allow a Model to past to the edit controller method I can send the patched Method
            before saving, or set a session holding the edited model and retrieve that when returning to the edit page.
        */

        if(date('Y-m-d H:i:s') > $post->locked_till ){
            $tags = Tag::allWhere(['type' => 'post']);
            $selectedTag = [];

            foreach ($post->tags() as $tag) {
                $selectedTag[] = $tag->tag_id;
            };

//            if (isset($_POST['submit'])) {
//                $post->patch($_POST);
//                if (!empty($post->category)) {
//                    $category = new Cat(['title' => $post->category, 'type' => $post->cat_type]);
//                    $add = $category->save();
//                    // add value to the request to be run by the prepareQuery
//                    // otherwise it won't be seen added when save() is called.
//
//                    $post->patch(['category_id' => $category->lastInsertId]);
//                }
//
//
//                if (!empty($post->title) && !empty($post->content) && !empty($post->category_id)) {
//                    $post->user_id = $this->currentUser;
//                    /*  if I redirect to update, I can prevent the self locking problem, but the I can't return to the edit
//                    page if the validation doesn't checkout.*/
//                    $post->locked_till = date('Y-m-d H:i:s',strtotime("-10 seconds"));
//                    $post->savePatch();
//                    header("Location: ".ADMIN."posts");
//                } else {
//                    $this->messages[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
//                };
//            };

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
        } else {
//            header("Location: ".ADMIN.$post->table.'/locked');
            return "locked";
        }
    }

    public function update($response,$params = null)
    {
        $post = Post::one($params['id']);
        if (isset($_POST['submit'])) {
            $post->patch($_POST);
            if (!empty($post->category)) {
                $category = new Cat(['title' => $post->category, 'type' => $post->cat_type]);
                $add = $category->save();
                // add value to the request to be run by the prepareQuery
                // otherwise it won't be seen added when save() is called.

                $post->patch(['category_id' => $category->lastInsertId]);
            }

            if (!empty($post->title) && !empty($post->content) && !empty($post->category_id)) {
                $post->user_id = $this->currentUser;
                $post->locked_till = date('Y-m-d H:i:s',strtotime("-10 seconds"));
                $post->savePatch();
                header("Location: ".ADMIN."posts");
            } else {
                $this->messages[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
                // set back the lock on the post, otherwise you cannot access the edit page for 5 seconds because the lock
                // is still active.
                $post->patch(['locked_till' => date('Y-m-d H:i:s',strtotime("-10 seconds"))])->savePatch();
                //store the unsaved object in the session so all edited parts are saved when returning to the edit page for editting.
                $_SESSION['invalid_edit'] = $post;
                header("Location: ".ADMIN."posts/edit/".$post->post_id);
            };
        };
    }
    public function locked(){

    }
    public function action($response, $params)
    {
        $post = new Post();
        $this->UserActions($post);
        header("Location: ".ADMIN.$post->table);
    }

    public function approve($response,$params)
    {
        $post = Post::one($params['id']);
        Actions::approve_selected($post,$params['id']);
        header("Location: ".ADMIN.$post->table);
    }

    public function hide($response,$params)
    {
        $post = Post::one($params['id']);
        Actions::hide_selected($post,$params['id']);
        header("Location: ".ADMIN.$post->table);
    }

    public function trash($response,$params)
    {
        $post = Post::one($params['id']);
        Actions::trash_selected($post,$params['id']);
        header("Location: ".ADMIN.$post->table.'/deleted');
    }

    public function destroy($response,$params)
    {
        $post = Post::one($params['id']);
        $post->delete();
        header("Location: ".ADMIN.$post->table.'/deleted');
    }
}

?>