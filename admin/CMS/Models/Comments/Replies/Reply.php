<?php
    namespace CMS\Models\Comments\Replies;

    use CMS\Core\Model\Model;

    class Reply extends Model {

        public $primaryKey = 'reply_id';

        public $table = 'replies';

        protected $relations = [
            'users' => 'user_id',
            'comments' => 'comment_id',
        ];

        protected $allowed = [
            'content',
            'trashed',
            'approved'
        ];

        protected  $hidden = [
            'user_id',
            'post_id',
        ];
        # Relations

        public function comment()
        {
            return $this->ownedBy('CMS\Models\Posts\Comment');
        }

        public function user()
        {
            return $this->ownedBy('CMS\Models\Users\Users');
        }

        public function getlink(){
            return preg_replace("/[\s-]+/", "-", $this->title);
        }

        public function get_id(){
            return $this->reply_id;
        }
        public function setID($id){
            $this->reply_id = $id;
        }
        public function getTable(){
            return $this->table;
        }
    }
    ?>