<?php
namespace CMS\model\Content;

use CMS\model\DBC\DBC;


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

			$id = mysqli_real_escape_string($dbc,trim((int)$this->getID()));
			$category = mysqli_real_escape_string($dbc,trim($this->getCategory()));
			$old_title = mysqli_real_escape_string($dbc,trim($old_title));
			$cat_desc = mysqli_real_escape_string($dbc,trim($this->getDescription()));
			$author = mysqli_real_escape_string($dbc,trim($this->getAuthor()));
			// Edit the post data from the database
			$query = $dbc->prepare("UPDATE categories SET title = ?,description = ?,author = ? WHERE categories.categorie_id = ?");
			if ($query) {
				$query->bind_param("sssi",$category,$cat_desc,$author,$id);
				$query->execute();
				$query->close();
			} else {
				$db->sqlERROR();
			}
			$query2 = $dbc->prepare("UPDATE posts SET category = ? WHERE category = ?");
			if ($query2) {
				$query->bind_param("ss",$category,$old_title);
				$query->execute();
				$query->close();
			} else {
				$db->sqlERROR();
			}

			$dbc->close();

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
			$category = mysqli_real_escape_string($dbc,trim(htmlentities($category)));
			$query = $dbc->prepare("INSERT INTO categories(title,author,type) VALUES(?,?,?)");
			if($query){
				$query->bind_param("sss",$category,$author,$type);
				$query->execute();
				$query->close();
			} else {
				$db->sqlERROR();
			}

			$query = $dbc->prepare("SELECT categorie_id,title FROM categories WHERE title = ?");
			if($query){
				$query->bind_param("s",$category);
				$query->execute();
				$data = $query->get_result();
				$query->close();

				$row = $data->fetch_array();
				$category_id = $row['categorie_id'];
				$category = $row['title'];
			} else {
				$db->sqlERROR();
			}

			$dbc->close();
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
		$categories = array();

		$query = $dbc->prepare("SELECT * FROM categories WHERE trashed = 0 AND type = ?");
		if ($query) {
			$query->bind_param("s",$type);
			$query->execute();
			$data = $query->get_result();

			while ($row = $data->fetch_array()) {
				if ($selected_cat == $row['title']) {
					echo '<option value="' . $row['categorie_id'] . '" selected="selected">' . $row['title'] . '</option>';
				} else {
					echo '<option value="' . $row['categorie_id'] . '">' . $row['title'] . '</option>';
				}
			}
		} else {
			$db->sqlERROR();
		}
		$dbc->close();
	}
	//
}
?>