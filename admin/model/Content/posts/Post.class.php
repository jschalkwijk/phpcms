<?php
class Content_Posts_Post extends Content_Content{
	public $category_type;
	
	public function __construct($title,$description,$category,$content,$author,$dbt = null,$date = null,$approved = null,$trashed = null){
		parent::__construct($title,$description,$category,$content,$author,$dbt,$date = null,$approved = null,$trashed = null);
		$this->category_type = 'post';
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
		$content = mysqli_real_escape_string($dbc,trim($this->content));
		$author = $this->author;
		
		$dbt = $this->dbt;
		
		if(!empty($category)) {
			$category = content_Categories::addCategory($category,'post');
			$category_id = $category['category_id'];
		} else if(isset($_POST['cat_name'])) {
			$category_id = mysqli_real_escape_string($dbc,trim($_POST['cat_name']));
		}

		if (!empty($title) && !empty($content)) {
			if($confirm == "Yes"){
				$query = $dbc->prepare("UPDATE ".$dbt." SET title = ?,description = ?,content = ?,category_id = ? WHERE post_id = ?");
				$query->bind_param("sssii",$title,$post_desc,$content,$category_id,$id);
			} else {
				$query = $dbc->prepare("INSERT INTO ".$dbt."(title,description,content,author,category_id,date) VALUES(?,?,?,?,?,NOW())");
				$query->bind_param("ssssi",$title,$post_desc,$content,$author,$category_id);
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