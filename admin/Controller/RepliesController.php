<?php

    namespace Controller;

    use CMS\Models\Actions\Actions;
    use CMS\Models\Comments\Replies\Reply;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Actions\UserActions;


    class RepliesController extends Controller
    {
        use UserActions;

//        public function index($response,$params = null)
//        {
//            $replies = Reply::allWhere(['trashed' => 0]);
//
//            $this->view(
//                'Replies',
//                [
//                    'replies/replies.php'
//                ],
//                $params,
//                [
//                    'replies' => $replies,
//                    'trashed' => 0,
//                    'js' => [JS . 'checkAll.js']
//                ]
//            );
//        }
//        //
//        public function deleted($response,$params = null)
//        {
//            $replies = Reply::allWhere(['trashed' => 1]);
//            $this->view('Deleted Replies', ['replies/replies.php'], $params, ['replies' => $replies,'trashed' => 1, 'js' => [JS . 'checkAll.js']]);
//        }
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
            $reply = new Reply();
            $this->UserActions($reply);
            header("Location: ".ADMIN.$reply->table);
        }

        public function approve($response,$params)
        {
            $reply = Reply::one($params['id']);
            Actions::approve_selected($reply,$params['id']);
            header("Location: ".ADMIN.$reply->table);
        }

        public function hide($response,$params)
        {
            $reply = Reply::one($params['id']);
            Actions::hide_selected($reply,$params['id']);
            header("Location: ".ADMIN.$reply->table);
        }

        public function trash($response,$params)
        {
            $reply = Reply::one($params['id']);
            Actions::trash_selected($reply,$params['id']);
            header("Location: ".ADMIN.$reply->table.'/deleted');
        }

        public function destroy($response,$params)
        {
            $reply = Reply::one($params['id']);
            $reply->delete();
            header("Location: ".ADMIN.$reply->table.'/deleted');
        }
    }

    ?>