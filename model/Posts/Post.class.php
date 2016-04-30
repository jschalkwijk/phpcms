<?php
class posts_Post {
	private $id = 0;
	protected $title;
	protected $description;
	protected $category;
	protected $content;
	protected $author;
	private $date;

	// To create a new post, $post = new Post(enter variables). Vars with null are not required.
	// This is like a template for child classes to use.
	public function __construct($title,$description,$category,$content,$author,$date) {
		$this->title = $title;
		$this->description = $description;
		$this->category = $category;
		$this->content = $content;
		$this->author = $author;
		$this->date = $date;
	}
	// getter function that get the assingned variables. Can only be accessed with these fynctions, vars themselfs are private.
	public function getTitle(){
		return $this->title;
	}
	public function getDescription(){
		return $this->description;
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
	public static function fetchAll($dbt) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "SELECT * FROM ".$dbt." WHERE trashed = 0 AND approved = 1 ORDER BY ".$id_row." DESC";

		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$posts = array();
		while($row = mysqli_fetch_array($data)){
			$post = new posts_Post(
				$row['title'],
				$row['description'],
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
	
	public static function fetchByCategory($dbt,$category) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$dbc = new DBC;
		$id_row = substr($dbt, 0, -1).'_id';
		$id = mysqli_real_escape_string($dbc->connect(),trim($category));
		$query = "SELECT * FROM ".$dbt." WHERE trashed = 0 AND approved = 1 AND category = '".$category."' ORDER BY ".$id_row." DESC";
		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$posts = array();
		while($row = mysqli_fetch_array($data)){
			$post = new posts_Post(
				$row['title'],
				$row['description'],
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
	
	public static function fetchSingle($dbt,$id) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$dbc = new DBC;
		$id = mysqli_real_escape_string($dbc->connect(),trim((int)$id));
		$id_row = substr($dbt, 0, -1).'_id';
		$query = "SELECT * FROM ".$dbt." WHERE ".$id_row." = $id";
		$data = mysqli_query($dbc->connect(),$query) or die ("Error connecting to server");
		while($row = mysqli_fetch_array($data)){
			$post = new posts_Post(
				$row['title'],
				null,
				$row['category'],
				$row['content'],
				$row['author'],
				$row['date']
			);
			$post->setID($row[$id_row]);
			// adds every object to the posts array. We can acces each object and its methods seperatly.
		}
		$dbc->disconnect();
		return $post;
	}
}
?>