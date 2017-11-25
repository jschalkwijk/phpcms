<?php
    namespace CMS\Models\Comments;

    use CMS\Core\Model\Model;

    class Comment extends Model {

        public $primaryKey = 'comment_id';

        public $table = 'comments';

        protected $relations = [
            'users' => 'user_id',
            'posts' => 'post_id',
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

        public function post()
        {
            return $this->ownedBy('CMS\Models\Posts\Post');
        }

//        public function replies()
//        {
//            return $this->owns('CMS\Models\Comments\Reply');
//        }

        public function user()
        {
            return $this->ownedBy('CMS\Models\Users\Users');
        }

        public function getlink(){
            return preg_replace("/[\s-]+/", "-", $this->title);
        }

        public function get_id(){
            return $this->comment_id;
        }
        public function setID($id){
            $this->comment_id = $id;
        }
        public function getTable(){
            return $this->table;
        }
    }
    ?>