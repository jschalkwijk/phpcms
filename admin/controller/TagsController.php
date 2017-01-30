<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 11-01-17
     * Time: 16:11
     */
    use CMS\Models\Controller\Controller;
    use CMS\Models\Tag\Tag;

    class Tags extends Controller{
        public $messages = [];

        public function index($params = null)
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

        public function create()
        {
            $tag = new Tag($_POST);
            if(isset($_POST['submit'])){
                if(!empty($tag->title) ) {
                    $tag->hidden['user_id'] = $this->currentUser;
                    $tag->save();
                    header("Location: ".ADMIN."tags");
                } else {
                    $this->messages[] = "Please fill in all the required fields.";
                }
            }
            $this->view('Create Tag',['tags/create.php'],null,['tag' => $tag, 'messages' => $this->messages]);
        }

        public function edit($params = null)
        {
            $tag = Tag::single($params[0])[0];
            if(isset($_POST['submit'])){
                $tag->patch($_POST);
                if(!empty($tag->title) ) {
                    $tag->hidden['user_id'] = $this->currentUser;
                    $tag->savePatch();
                    header("Location: ".ADMIN."tags");
                } else {
                    $this->messages[] = "Please fill in all the required fields.";
                }
            }
            $this->view('Edit Tag',['tags/edit.php'],null,['tag' => $tag, 'messages' => $this->messages]);
        }
    }