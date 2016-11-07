<?php
namespace CMS\Models\Content\Posts;

use CMS\Models\DBC\DBC;
use CMS\Models\Content\Categories;
use CMS\Core\Model\Model;

class Post extends Model{

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

	public function getlink(){
		return preg_replace("/[\s-]+/", "-", $this->title);
	}

	public function get_id(){
		return $this->post_id;
	}
	public function getTable(){
		return $this->table;
	}

	public function getCatType(){
		return $this->category_type;
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	// The type of the category is set above as an extra object item
	public function addPost($id = null,$cat_name = null,$confirm = null){
		
		$db = new DBC;
		$dbc = $db->connect();

		$output_form = false;
		$errors = array();
		$messages = array();
		
		$this->setID($id);
		
		$id = mysqli_real_escape_string($dbc,trim((int)$this->getID()));
		$title = mysqli_real_escape_string($dbc,trim($this->title));
		$category = mysqli_real_escape_string($dbc,trim($this->category));
		$post_desc = mysqli_real_escape_string($dbc,trim($this->description));
		// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
		$content = mysqli_real_escape_string($dbc,trim($this->content));
		$author = $this->author;
		
		$dbt = $this->dbt;
		
		if(!empty($category)) {
			$category = Categories::addCategory($category,'post');
			$category_id = $category['category_id'];
		} else if(isset($_POST['cat_name'])) {
			$category_id = mysqli_real_escape_string($dbc,trim($_POST['cat_name']));
		}

		if (!empty($title) && !empty($content)) {
			if($confirm == "Yes"){
				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
				$query = $dbc->prepare("UPDATE ".$dbt." SET title = ?,description = ?,content = '".$content."',category_id = ? WHERE post_id = ?");
				if($query) {
					$query->bind_param("ssii", $title, $post_desc, $category_id, $id);
				} else {
					$db->sqlERROR();
				}
			} else {
				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
				$query = $dbc->prepare("INSERT INTO " . $dbt . "(title,description,content,author,category_id,date) VALUES(?,?,'" . $content . "',?,?,NOW())");
				if($query) {
					$query->bind_param("sssi", $title, $post_desc, $author, $category_id);
				} else {
					$db->sqlERROR();
				}
			}

			$query->execute();
			$query->close();

			$messages[] = 'Your post has been added/edited.<br />';
		} else if (empty($title) || empty($content)) {
				$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
				$output_form = true;
		}
		$dbc->close();

		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
	}
	
}
?>