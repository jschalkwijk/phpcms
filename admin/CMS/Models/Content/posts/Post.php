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

    protected $allowed =[
        'title',
        'content'
    ];

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

		$id = trim((int)$this->post_id);
		$title = trim($this->title);
		$category = trim($this->category);
		$post_desc = trim($this->description);
		// !!! We cant use the content with the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
		$content = trim($this->content);
		$author = $this->author;

		$table = $this->table;

		if(!empty($category)) {
			$category = Categories::addCategory($category,'post');
			$category_id = $category['category_id'];
		} else if(isset($_POST['cat_name'])) {
			$category_id = trim($_POST['cat_name']);
		}

		if (!empty($title) && !empty($content)) {
			if($confirm == "Yes"){
				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
                try {
                    $query = $dbc->prepare("UPDATE ".$table." SET title = ?,description = ?,content = ?,category_id = ? WHERE post_id = ?");
                    $query->execute([$title,$post_desc,$content,$category_id,$id]);
                } catch(\PDOException $e){
                    echo $e->getMessage();
                }
			} else {
				// !!! We cant use the contentiwth the Prepare statement, otherwise allhtml chars will be escaped an cant be used for inserted media etc.
                try {
                    $query = $dbc->prepare("INSERT INTO " . $table . "(title,description,content,author,category_id,date) VALUES(?,?,?,?,?,NOW())");
                    $query->execute([$title,$post_desc,$content,$author,$category_id]);
                } catch(\PDOException $e){
                    echo $e->getMessage();
                }
			}
			$messages[] = 'Your post has been added/edited.<br />';
		} else if (empty($title) || empty($content)) {
				$errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
				$output_form = true;
		}
        $db->close();

		return ['output_form' => $output_form, 'errors' => $errors, 'messages' => $messages];
	}
//    public function addPost($id = null,$cat_name = null,$confirm = null)
//    {
//        if(!empty($_POST['category'])) {
//            $category = Categories::addCategory($_POST['category'],'post');
//            $_POST['category_id'] = $category['category_id'];
//        } else if(isset($_POST['cat_name'])) {
//            $_POST['category_id']  = trim($_POST['cat_name']);
//        }
//        $this->add($_POST);
//    }
}
?>