<?php
namespace Controller;

use CMS\Models\Actions\Actions;
use CMS\Models\Comments\Comment;
use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;

class CommentsController extends Controller
{
    use UserActions;

    public function index($response,$params = null)
    {
        $comments = Comment::joined()->where(['trashed' => 0])->orderBy('comment_id','DESC')->grab();
        // $comments = Post::allWhere(['trashed' => 0]);
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
        // view takes: page_title,[array of view files],params from the router,array of data from model
        $this->view(
            'Comment',
            [
                'comments/comments.php'
            ],
            $params,
            [
                'comments' => $comments,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }
    //
    public function deleted($response,$params = null)
    {
        $comments = Comment::allWhere(['trashed' => 1]);
        $this->view('Deleted Comment', ['comments/comments.php'], $params, ['comments' => $comments, 'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
    }
    //
    public function create($response,$params = null)
    {
        
    }

    //
    public function edit($response,$params = null)
    {
    }

    public function action($response, $params)
    {
        $comment = new Comment();
        $this->UserActions($comment);
        header("Location: ".ADMIN.$comment->table);
    }

    public function approve($response,$params)
    {
        $comment = Comment::one($params['id']);
        Actions::approve_selected($comment,$params['id']);
        header("Location: ".ADMIN.$comment->table);
    }

    public function hide($response,$params)
    {
        $comment = Comment::one($params['id']);
        Actions::hide_selected($comment,$params['id']);
        header("Location: ".ADMIN.$comment->table);
    }

    public function trash($response,$params)
    {
        $comment = Comment::one($params['id']);
        Actions::trash_selected($comment,$params['id']);
        header("Location: ".ADMIN.$comment->table.'/deleted');
    }

    public function destroy($response,$params)
    {
        $comment = Comment::one($params['id']);
        $comment->delete();
        header("Location: ".ADMIN.$comment->table.'/deleted');
    }
}

?>