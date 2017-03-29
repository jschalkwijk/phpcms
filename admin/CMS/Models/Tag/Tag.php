<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 11-01-17
     * Time: 15:59
     */

    namespace CMS\Models\Tag;


    use CMS\Core\Model\Model;

    class Tag extends Model
    {
        protected $primaryKey = "tag_id";

        public $table = 'tags';

        protected $relations = [
            'users' => 'user_id'
        ];

        protected $pivotTable = "taggables";

        protected $joins = [
            'users' => ['username']
        ];
        public $type = 'post';

        protected $allowed = [
            'title',
        ];

        public $hidden = [
            'user_id',
            'type',
        ];

        #SETTERS
        public function setID($id){
            $this->tag_id = $id;
        }

        #GETTERS
        public function id(){
            return $this->tag_id;
        }
        public function get_id(){
            return $this->tag_id;
        }

        public function getlink(){
            return preg_replace("/[\s-]+/", "-", $this->title);
        }
    }