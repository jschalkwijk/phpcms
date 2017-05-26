<?php
    namespace Controller;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Tag\Tag;

    class TagsController extends Controller{
        public $messages = [];

        public function index($response,$params = null)
        {
            $tags = Tag::all();
            $this->view(
                'Tags',
                [
                    'tags/tags.php'
                ],
                $params,[
                'tags' => $tags
                ]
            );
        }

        public function create($response)
        {
            $tag = new Tag($_POST);
            if(isset($_POST['submit'])){
                if(!empty($tag->title) ) {
                    $tag->user_id = $this->currentUser;
                    $tag->save();
                    header("Location: ".ADMIN."tags");
                } else {
                    $this->messages[] = "Please fill in all the required fields.";
                }
            }
            $this->view('Create Tag',['tags/create.php'],null,['tag' => $tag, 'messages' => $this->messages]);
        }

        public function edit($response,$params = null)
        {
            $tag = Tag::one($params['id']);
            if(isset($_POST['submit'])){
                $tag->patch($_POST);
                if(!empty($tag->title) ) {
                    $tag->user_ids = $this->currentUser;
                    $tag->savePatch();
                    header("Location: ".ADMIN."tags");
                } else {
                    $this->messages[] = "Please fill in all the required fields.";
                }
            }
            $this->view('Edit Tag',['tags/edit.php'],null,['tag' => $tag, 'messages' => $this->messages]);
        }
    }