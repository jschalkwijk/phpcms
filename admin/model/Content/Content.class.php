<?php
namespace Jorn\admin\model\Content;

use Jorn\admin\model\DBC\DBC;
// content management/user management/file management/product management/client management.
class Content{
	// will get the TRAIT actions that the user can perform like edit,delete,approve
	
	private $id = 0;
	protected $title;
	protected $description;
	protected $category;
	protected $content;
	protected $author;
	public $dbt;
	private $date;
	private $approved;
	private $trashed;

	// To create a new post, $post = new Post(enter variables). Vars with null are not required.
	// This is like a template for child classes to use.
	public function __construct($title,$description,$category,$content,$author = null,$dbt = null,$date = null,$approved = null,$trashed = null) {
		$this->title = $title;
		$this->description = $description;
		$this->category = $category;
		$this->content = $content;
		$this->author = $author;
		$this->dbt = $dbt;
		$this->date = $date;
		$this->approved = $approved;
		$this->trashed = $trashed;
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
	public function getApproved(){
		return $this->approved;
	}
	public function getTrashed(){
		return $this->trashed;
	}
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;
	}
	public function getDbt(){
		return $this->dbt;
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
			while ($row = $data->fetch_array()) {
				$post = new Content(
					$row['title'],
					$row['description'],
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
		$dbc->close();
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return $posts;
	}

	// Fetches a single post or page.
	// It take the DB table name and the id of the content we want to see.
	// Creates an object which will be returned to the controller.
	public static function fetchSingle($dbt,$id) {
		$db = new DBC();
		$dbc = $db->connect();
		$id = mysqli_real_escape_string($dbc,trim((int)$id));
		$id_row = substr($dbt, 0, -1).'_id';

		if($dbt != 'categories'){
			$query = $dbc->prepare("SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.categorie_id WHERE ".$id_row." = ?");
			(!$query) ? $db->sqlERROR() : $query->bind_param("i",$id);

		} else {
			$query = $dbc->prepare("SELECT * FROM ".$dbt." WHERE ".$id_row." = ?");
			$query->bind_param("i",$id);
		}

		if ($query) {
			$query->execute();
			$data = $query->get_result();
			$query->close();
			//		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
			while ($row = $data->fetch_array()) {
				$post = new Content(
					$row['title'],
					$row['description'],
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
		}

		$dbc->close();
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return $post;
	}
	
	public static function searchContent($dbt,$data) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$posts = array();
		$id_row = substr($dbt, 0, -1).'_id';
		while($row = mysqli_fetch_array($data)){
			$post = new Content(
				$row['title'],
				$row['description'],
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
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return $posts;
	}

	public function seoUrl($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
}
?>