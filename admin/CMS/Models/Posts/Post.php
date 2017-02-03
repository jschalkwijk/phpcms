<?php
	namespace CMS\Models\Posts;

	use CMS\Models\Categories\Categories as Cat;
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
			'users' => ['username','first_name','last_name']
		];

		protected $allowed = [
			'title',
			'description',
			'content',
			'category_id',
			'trashed',
			'approved'
		];

//	protected  $hidden = [
//		'user_id'
//	];
        # Relations

        public function category()
        {
            return $this->ownedBy('CMS\Models\Categories\Categories','category_id');
        }
		public function tags()
		{
//			return $this->newQuery("SELECT * FROM taggables RIGHT JOIN tags ON taggables.tag_id = tags.tag_id WHERE taggable_type = 'post' AND taggable_id = $this->post_id ORDER BY tags.tag_id DESC");
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

//		public function add()
//		{
//			if(!empty($this->category)) {
//				$category = new Cat(['title' => $this->category,'type' => $this->cat_type]);
//				$add = $category->add();
//				// add value to the request to be run by the prepareQuery
//				// otherwise it won't be seen added when save() is called.
//				$this->request['category_id'] = $add['category_id'];
//			}
//			$this->hidden['user_id'] = $_SESSION['user_id'];
//			print_r($this->request);
//			if(!empty($this->title) && !empty($this->content) && (!empty($this->category_id) || !empty($this->category) )) {
//				$this->save();
//				$messages[] = 'Your post has been added/edited.<br />';
//				$output_form = true;
//			} else {
//				$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
//				$output_form = false;
//			};
//
//			return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
//		}
//
//		public function edit()
//		{
//			if(!empty($this->request['category'])) {
//				echo "Hello!";
//				$category = new Cat(['title' => $this->request['category'],'type' => $this->request['cat_type']]);
//				$add = $category->add();
//				// add value to the request to be run by the prepareQuery
//				// otherwise it won't be seen added when save() is called.
//				$this->request['category_id'] = $add['category_id'];
//			}
//			$this->hidden['user_id'] = $this->user_id;
//			print_r($this->request);
//			//update model but do not save it yet before check.
//			$this->patch();
//			if(!empty($this->title) && !empty($this->content) && !empty($this->category_id)) {
//				$this->savePatch();
//				$messages[] = 'Your post has been added/edited.<br />';
//				$output_form = true;
//			} else {
//				$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
//				$output_form = true;
//			};
//
//			return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
//		}

	}
	?>