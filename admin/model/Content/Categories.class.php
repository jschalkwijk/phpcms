<?php

class Content_Categories extends Content_Content{
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
		$dbc = new DBC;
		
		$messages = array();
		$errors = array();
		$output_form = false;
		
		$this->setID($id);
		
		if ($confirm === 'Yes') {

			$id = mysqli_real_escape_string($dbc->connect(),trim((int)$this->getID()));
			$category = mysqli_real_escape_string($dbc->connect(),trim($this->getCategory()));
			$old_title = mysqli_real_escape_string($dbc->connect(),trim($old_title));
			$cat_desc = mysqli_real_escape_string($dbc->connect(),trim($this->getDescription()));
			$author = mysqli_real_escape_string($dbc->connect(),trim($this->getAuthor()));
			// Edit the post data from the database
			$query = "UPDATE categories SET title = '$category',description = '$cat_desc',author = '$author' WHERE categories.id = $id";
			mysqli_query($dbc->connect(), $query) or die('Error connecting to database.');
			$query2 = "UPDATE posts SET category = '$category' WHERE category = '$old_title'";
			mysqli_query($dbc->connect(), $query2) or die('Error connecting to database.');
			$dbc->disconnect();

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
			$dbc = new DBC;
			$author = $_SESSION['username'];
			$category = mysqli_real_escape_string($dbc->connect(),trim(htmlentities($category)));
			$query = "INSERT INTO categories(title,author,type) VALUES('$category','$author','$type')";
			mysqli_query($dbc->connect(),$query) or die('Error connecting to database');
			
			$query = "SELECT categorie_id,title FROM categories WHERE title = '$category'";
			
			$data = mysqli_query($dbc->connect(),$query) or die('Error connecting to database...');
			$row = mysqli_fetch_array($data);
			$category_id = $row['categorie_id'];
			$category = $row['title'];
			$dbc->disconnect();
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
		$dbc = new DBC;
		$categories = array();
		$query = "SELECT * FROM categories WHERE trashed = 0 AND type = '$type'";
		$data = mysqli_query($dbc->connect(),$query) or die("Error connecting to database");
		
		while($row = mysqli_fetch_array($data)) {
			if($selected_cat == $row['title'] ) {
					echo '<option value="'.$row['categorie_id'].'" selected="selected">'.$row['title'].'</option>';
			} else {
				echo '<option value="'.$row['categorie_id'].'">'.$row['title'].'</option>';
			}
		}
	}
	//
}
?>