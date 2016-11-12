<?php
namespace CMS\Models\Content;

use CMS\Models\DBC\DBC;


class Categories extends Content{
	private $id;
	protected $category;
	protected $description;
	protected $author;
	
	public function __construct($category,$description,$author){
		$this->category = $category;
		$this->description = $description;
		$this->author = $author;
	}
	
	#SETTERS
	public function setID($id){
		$this->id = $id;
	}
	
	#GETTERS
	public function getID(){
		return $this->id;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getAuthor(){
		return $this->author;
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.

	public function editCategory($id,$old_title,$confirm){
		$db = new DBC();
		$dbc = $db->connect();

		$messages = array();
		$errors = array();
		$output_form = false;
		
		$this->setID($id);
		
		if ($confirm === 'Yes') {

			$id = trim((int)$this->getID());
			$category = trim($this->getCategory());
			$old_title = trim($old_title);
			$cat_desc = trim($this->getDescription());
			$author = trim($this->getAuthor());
			// Edit the post data from the database
            try {
                $query = $dbc->prepare("UPDATE categories SET title = ?,description = ?,author = ? WHERE categories.category_id = ?");
                $query->execute([$category,$cat_desc,$author,$id]);
            } catch(\PDOException $e){
                echo $e->getMessage();
            }
            try {
                $query2 = $dbc->prepare("UPDATE posts SET category = ? WHERE category = ?");
                $query2->execute([$category,$old_title]);
            } catch(\PDOException $e){
                echo $e->getMessage();
            }

			$db->close();

			// Confirm success with the user
			$messages[] = '<p>The category with title ' . $category . ' was successfully edited.';
		}
		else {
			$errors[] ='<p class="error">The post was not edited.</p>';
			$output_form = true;
		}
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return ['old_title' => $old_title,'output_form' => $output_form,'messages' => $messages, 'errors' => $errors];
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	// It also updates the category table if a category is created.
	public static function addCategory($category,$type) {
		$errors = array();
		if(!empty($category)) {
			$db = new DBC();
			$dbc = $db->connect();

			$author = $_SESSION['username'];
			$category = trim(htmlentities($category));

			try {
                $query = $dbc->prepare("INSERT INTO categories(title,author,type) VALUES(?,?,?)");
				$query->execute([$category,$author,$type]);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
			try {
                $query = $dbc->prepare("SELECT category_id,title FROM categories WHERE title = ?");
				$query->execute([$category]);
				$row = $query->fetch();
				$category_id = $row['category_id'];
				$category = $row['title'];
			} catch (\PDOException $e) {
				echo $e->getMessage();
			}

			$db->close();
		} else {
			$errors[] = 'You forgot to type in a category name.';
		}
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return ['errors' => $errors,'category_id' => $category_id,'category_name' => $category]; 
	}
	// Returns a list of options with all the categories that are of the defined type.
	// like type: post, type: product, type: page.
	// We want to automatically have the te current category selected if we update a post
	// This function is called inside a select form type:
	/*
	  <select id="categories" name="cat_name">
		<option name="none" value="None">None</option>
		<?php $category = content_Categories::getSelected($post->getCategory(),'post'); >
	  </select>
	*/

	public static function getSelected($selected_cat,$type) {
		$db = new DBC;
		$dbc = $db->connect();
		try {
            $query = $dbc->prepare("SELECT * FROM categories WHERE trashed = 0 AND type = ?");
			$query->execute([$type]);

			while ($row = $query->fetch()) {
				if ($selected_cat == $row['category_id']) {
					echo '<option value="' . $row['category_id'] . '" selected="selected">' . $row['title'] . '</option>';
				} else {
					echo '<option value="' . $row['category_id'] . '">' . $row['title'] . '</option>';
				}
			}
		} catch (\PDOException $e) {
			echo $e->getMessage();
            exit();
		}
		$db->close();
	}
	//
}
?>