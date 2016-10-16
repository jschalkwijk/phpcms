<?php

use Jorn\model\DBC;
class posts_Post {
	private $id = 0;
	protected $title;
	protected $description;
	protected $category_id;
	protected $category;
	protected $content;
	protected $author;
	private $date;

	// To create a new post, $post = new Post(enter variables). Vars with null are not required.
	// This is like a template for child classes to use.
	public function __construct($title,$description,$category_id,$category,$content,$author,$date) {
		$this->title = $title;
		$this->description = $description;
		$this->category_id = $category_id;
		$this->category = $category;
		$this->content = $content;
		$this->author = $author;
		$this->date = $date;
	}
	// getter function that get the assingned variables. Can only be accessed with these fynctions, vars themselfs are private.
	public function getTitle(){
		return $this->title;
	}
	public function getLink(){
		return preg_replace("/[\s-]+/", "-", $this->title);
	}
	public function getDescription(){
		return $this->description;
	}
	public function getCategoryID(){
		return $this->category_id;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getContent(){
		return $this->content;
	}
	public function getAuthor(){
		return $this->author;
	}
	public function getDate(){
		return $this->date;
	}
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;
	}
	// this method will generate a new Post object from data of the database.
	// It takes a database table name, f.e. 'posts', and 0,1 to state if it should display
	// trashed or non trashed content.
	// when it gets the data from the database, it will create a new Post object for every
	// row that is getting fetched. We add the row data to the Post class.
	// At last the ID is set in the Object and the Post object is pushed into an Array.
	// This array will be used to write out the single or all posts.
	public static function fetchAll($dbt,$trashed) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$db = new DBC();
		$dbc = $db->connect();
		$id_row = substr($dbt, 0, -1).'_id';

		if($dbt != 'categories'){
			$query = $dbc->prepare("SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.categorie_id WHERE ".$dbt.".trashed = ? ORDER BY ".$id_row." DESC");
			(!$query) ? $db->sqlERROR() : $query->bind_param("i",$trashed);

		} else {
			$query = $dbc->prepare("SELECT * FROM ".$dbt." WHERE ".$dbt.".trashed = ? ORDER BY ".$id_row." DESC");
			$query->bind_param("i",$trashed);
		}

		$posts = array();

		if ($query) {
			$query->execute();
			$data = $query->get_result();
			$query->close();
			//		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
			while($row = $data->fetch_array()){
				$post = new posts_Post(
					$row['title'],
					$row['description'],
					$row['category_id'],
					$row['category'],
					$row['content'],
					$row['author'],
					$dbt,
					$row['date'],
					$row['approved'],
					$row['trashed']
				);
				// Because creating a new object in the controller doesn't allow us to insert an ID, we have to set the id
				// ourselves. It's fetched from the database.
				$post->setID($row[$id_row]);
				// adds every object to the posts array. We can acces each object and its methods seperatly.
				$posts[] = $post;
			}
		}
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		$dbc->close();
		return $posts;
	}

	// Fetches a single post or page.
	// It take the DB table name and the id of the content we want to see.
	// Creates an object which will be returned to the controller.
	public static function fetchSingle($dbt,$id) {
		$dbc = new DBC;
		$id = mysqli_real_escape_string($dbc->connect(),trim((int)$id));
		$id_row = substr($dbt, 0, -1).'_id';
		if($dbt != 'categories'){
			$query = "SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.categorie_id WHERE ".$id_row." = $id";
		} else {
			$query = "SELECT * FROM ".$dbt." WHERE ".$id_row." = $id";
		}

		$data = mysqli_query($dbc->connect(),$query) or die ("Error connecting to server");
		while($row = mysqli_fetch_array($data)){
			$post = new posts_Post(
				$row['title'],
				$row['description'],
				$row['category_id'],
				$row['category'],
				$row['content'],
				$row['author'],
				$dbt,
				$row['date'],
				$row['approved'],
				$row['trashed']
			);
			// Because creating a new object in the controller doesn't allow us to insert an ID, we have to set the id
			// ourselves. It's fetched from the database.
			$post->setID($row[$id_row]);
			// adds every object to the posts array. We can acces each object and its methods seperatly.
		}
		$dbc->disconnect();
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return $post;
	}
	
	public static function fetchByCategory($dbt,$category_id) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$id = mysqli_real_escape_string($dbc->connect(),trim((int)$category_id));
		$query = "SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.categorie_id WHERE category_id = $category_id ORDER BY ".$id_row." DESC ";

//		$query = "SELECT * FROM ".$dbt." WHERE approved = 1 AND category_id = '".$category_id."' ORDER BY ".$id_row." DESC";
		echo $query;
		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$posts = array();
		while($row = mysqli_fetch_array($data)){
			$post = new posts_Post(
				$row['title'],
				$row['description'],
				$row['category_id'],
				$row['category'],
				null,
				$row['author'],
				$row['date']
			);
			$post->setID($row[$id_row]);
			// adds every object to the posts array. We can acces each object and its methods seperatly.
			$posts[] = $post;
		}
		return $posts;
		$dbc->disconnect();
	}
}
?>