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
			// add value to the request to be run by the prepareQuery
			// otherwise it won't be seen added when save() is called.
			$this->request['category_id'] = $this->category_id;
		}
		$this->hidden['user_id'] = $this->user_id;
		print_r($this->request);
		if(!empty($this->title) && !empty($this->content) && !empty($this->category_id)) {
			$this->change();
			$messages[] = 'Your post has been added/edited.<br />';
			$output_form = true;
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
			$output_form = true;
		};

		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	// The type of the category is set above as an extra object item
//	public function addPost($id = null,$cat_name = null,$confirm = null){
//
//		$db = new DBC;
//		$dbc = $db->connect();
//
//		$output_form = false;
//		$errors = array();
//		$messages = array();
//
//		$this->setID($id);
//
//		$id = trim((int)$this->post_id);
//		$title = trim($this->title);
//		$category = trim($this->category);
//		$post_desc = trim($this->description);
//		// !!! We cant use the content with the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
//		$content = trim($this->content);
//		$author = $this->author;
//
//		$table = $this->table;
//
//		if(!empty($category)) {
//			$category = Categories::addCategory($category,'post');
//			$category_id = $category['category_id'];
//		} else if(isset($_POST['cat_name'])) {
//			$category_id = trim($_POST['cat_name']);
//		}
//
//		if (!empty($title) && !empty($content)) {
//			if($confirm == "Yes"){
//				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
//                try {
//                    $query = $dbc->prepare("UPDATE ".$table." SET title = ?,description = ?,content = ?,category_id = ? WHERE post_id = ?");
//                    $query->execute([$title,$post_desc,$content,$category_id,$id]);
//                } catch(\PDOException $e){
//                    echo $e->getMessage();
//                }
//			} else {
//				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
//                try {
//                    $query = $dbc->prepare("INSERT INTO " . $table . "(title,description,content,author,category_id,date) VALUES(?,?,?,?,?,NOW())");
//                    $query->execute([$title,$post_desc,$content,$author,$category_id]);
//                } catch(\PDOException $e){
//                    echo $e->getMessage();
//                }
//			}
//			$messages[] = 'Your post has been added/edited.<br />';
//		} else if (empty($title) || empty($content)) {
//				$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
//				$output_form = true;
//		}
//        $db->close();
//
//		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
//	}
}
?>