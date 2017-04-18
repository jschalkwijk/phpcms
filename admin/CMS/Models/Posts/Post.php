<?php
	namespace CMS\Models\Posts;

	use CMS\Core\Model\Model;

	class Post extends Model {

		public $primaryKey = 'post_id';

		public $table = 'posts';

		protected $relations = [
			'categories' => 'category_id',
			'users' => 'user_id'
		];

        // either use the joins var to join in related tables or use the relationsship functions.
        // I would use join if you just need a few row items. Only use the relation functions if you
        // really need all of it. To just display a category name for example just join that value.
		protected $joins = [
			'categories' => ['title'],
			'users' => ['username']
		];

		protected $allowed = [
			'title',
			'description',
			'content',
			'category_id',
			'trashed',
			'approved'
		];

	protected  $hidden = [
		'user_id'
	];
        # Relations

        public function category()
        {
            return $this->ownedBy('CMS\Models\Categories\Categories');
        }
		public function tags()
		{
//			return $this->grab("SELECT * FROM taggables RIGHT JOIN tags ON taggables.tag_id = tags.tag_id WHERE taggable_type = 'post' AND taggable_id = $this->post_id ORDER BY tags.tag_id DESC");
        	return $this->morpheus('CMS\Models\Tag\Tag');
		}
        ## end

		public function getlink(){
			return preg_replace("/[\s-]+/", "-", $this->title);
		}

		public function get_id(){
			return $this->post_id;
		}
		public function setID($id){
			$this->post_id = $id;
		}
		public function getTable(){
			return $this->table;
		}

		public function getCatType(){
			return $this->category_type;
		}
		public function setTitle($title){
			$this->title = $title;
		}

	}
	?>