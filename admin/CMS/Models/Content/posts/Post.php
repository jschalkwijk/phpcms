<?php
namespace CMS\Models\Content\Posts;

use CMS\Models\Content\Categories;
use CMS\Core\Model\Model;

class Post extends Model {

	protected $primaryKey = 'post_id';

	public $table = 'posts';

    protected $relations = [
        'categories' => 'category_id',
        'users' => 'user_id'
    ];

    protected $joins = [
        'categories' => ['title','description'],
        'users' => ['username']
    ];

    protected $allowed = [
        'title',
		'description',
        'content',
		'category_id',
    ];

//	protected  $hidden = [
//		'user_id'
//	];

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

    public function add()
    {
        if(!empty($this->category)) {
            $category = Categories::addCategory($this->category,'post');
			$this->category_id = $category['category_id'];
			// add value to the request to be run by the prepareQuery
			// otherwise it won't be seen added when save() is called.
			$this->request['category_id'] = $this->category_id;
        }
		$this->hidden['user_id'] = $_SESSION['user_id'];
		print_r($this->request);
        if(!empty($this->title) && !empty($this->content) && !empty($this->category_id)) {
			$this->save();
			$messages[] = 'Your post has been added/edited.<br />';
			$output_form = true;
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
			$output_form = true;
		};

		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
    }

	public function edit()
	{
		if(!empty($this->category)) {
			$category = Categories::addCategory($this->category,'post');
			$this->category_id = $category['category_id'];
			// replace the current value to the request to be run by the prepareQuery
			// otherwise it won't be seen added when save() is called.
			$this->request['category_id'] = $this->category_id;
		}
		$this->hidden['user_id'] = $this->user_id;
		print_r($this->request);
        //update modelbut do not save it yet before check.
        $this->patch();
		if(!empty($this->title) && !empty($this->content) && !empty($this->category_id)) {
            $this->savePatch();
			$messages[] = 'Your post has been added/edited.<br />';
			$output_form = true;
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
			$output_form = true;
		};

		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
	}

}
?>