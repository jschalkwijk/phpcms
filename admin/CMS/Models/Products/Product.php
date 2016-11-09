<?php
namespace CMS\Models\Products;

use CMS\Models\DBC\DBC;
use CMS\Models\File\FileUpload;
use CMS\Models\File\Folders;
use CMS\Models\Content\Categories;

class Product {
	// will get the TRAIT actions that the user can perform like edit,delete,approve
	private $id;
	private $name;
	private $category;
	private $price;
	private $quantity;
	private $description;
	private $discount_price;
	private $savings;
	private $tax_percentage = 21;
	private $tax;
	private $total;
	private $approved;
	private $img_path;
	private $album_id;
	private $file_path = 'files/products/';
	private $thumb_path = 'files/thumbs/products/';
	protected $maxStock;

	public function __construct($name,$category,$description,$price,$quantity,$img_path,$album_id = null,$approved = null){
		$this->name = $name;
		$this->category = $category;
		$this->price = $price;
		$this->description = $description;
		$this->quantity = $quantity;
		$this->img_path = $img_path;
		$this->album_id = $album_id;
		$this->approved = $approved;
	}

	public function setID($id){
		$this->id = (int)$id;
	}
	public function getID(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}

	public function getCategory(){
		return $this->category;
	}

	public function getPrice(){
		return $this->price;
	}

	public function getQuantity(){
		return $this->quantity;
	}

	public function setQuantity($num){
		$this->quantity = $num;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getTax(){
		$this->tax = ($this->price * $this->tax_percentage) / 100;
		return $this->tax;
	}

	public function getApproved(){
		return $this->approved;
	}

	public function getTrashed(){
		return $this->approved;
	}

	public function getProductImg(){
		return $this->img_path;
	}

	public function getAlbumID(){
		return $this->album_id;
	}

	protected function discount($percentage){
		$this->discount_price = ($this->price / 100) * $percentage;
		$this->savings = $this->price * $percentage;
	}

	public function getDiscount(){
		return $this->discount_price;
	}

	public function getSavings(){
		return $this->savings;
	}

	public function total(){
		return $this->price + $this->getTax();
	}

	public static function fetchAll($dbt,$trashed){
		$db = new DBC;
		$dbc = $db->connect();

		try {
            $query = $dbc->prepare("SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.category_id  WHERE ".$dbt.".trashed = ? ORDER BY product_id DESC");
            $query->execute([$trashed]);
			$data = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

		$products = array();

		foreach ($data as $row){
			$product = new Product(
				$row['name'],
				$row['category'],
				$row['description'],
				$row['price'],
				$row['quantity'],
				$row['img_path'],
				$row['album_id'],
				$row['approved']
			);
			$product->setID($row['product_id']);
			$products[] = $product;
		}
		$db->close();
		return ['products' => $products];
	}

	public static function fetchAllByID($ids){
		$db = new DBC;
		$dbc = $db->connect();

		$multiple = implode(",",$ids);
        try{
		    $query = $dbc->query("SELECT products.*, categories.title as category FROM products LEFT JOIN categories ON products.category_id = categories.category_id WHERE product_id IN({$multiple})");
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

		$products = array();

		foreach($query->fetchAll() as $row){
			$product = new Product(
				$row['name'],
				$row['category'],
				$row['description'],
				$row['price'],
				$row['quantity'],
				$row['img_path'],
				$row['album_id'],
				$row['approved']
			);
			$product->setID($row['product_id']);
			$products[] = $product;
		}
		$db->close();
		return $products;
	}

	public static function fetchSingle($id){
		$db = new DBC;
		$dbc = $db->connect();

		try {
            $query = $dbc->prepare("SELECT products.*, categories.title as category FROM products LEFT JOIN categories ON products.category_id = categories.category_id WHERE product_id = ? ORDER BY product_id DESC");
            $query->execute([$id]);
            $data = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

		foreach ($data as $row){
			$product = new Product(
				$row['name'],
				$row['category'],
				$row['description'],
				$row['price'],
				$row['quantity'],
				$row['img_path'],
				$row['album_id'],
				$row['approved']
			);
			$product->setID($row['product_id']);
		}
		$db->close();
		return $product;
	}

	public static function addProductIMG($file_dest,$thumb_dest,$params){
		$db = new DBC;
		$dbc = $db->connect();

		$upload = new FileUpload($file_dest,$thumb_dest,$params,true);
		$img_path = $upload->getImgPath();
		$thumb_path = $upload->getThumbPath();
        
        try {
		    $query = $dbc->prepare("UPDATE products SET img_path = '$thumb_path' WHERE product_id = ?");
			$query->execute([$params[0]]);;
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
		$db->close();
	}

	public function addProduct($id = null,$confirm = null){
		$db = new DBC;
		$dbc = $db->connect();

		$output_form = true;
		$errors = [];
		$messages = [];

		/*
		 *	If we edit an existing product using this function we pass the id and the objects ID form the GET params through the controller.
		 * 	Also a new object is created and we need to set the ID again to update the right product in the DB.
		 *  else a new product will be created. See queries below.
		*/

		if($id != null){
			$this->setID($id);
			$id = trim((int)$this->getID());
		};

		$name =  trim($this->name);
		$category =  trim($this->category);
		$description =  trim($this->description);
		$price =  trim((float)($this->price));
		$quantity =  trim((int)$this->quantity);

		# create product category, set type of category group
		# create product category file folder.
		if(!empty($category)) {
			$category = Categories::addCategory($category,'product');
			$category_name = $category['category_name'];
			$category_id = $category['category_id'];
			if(!file_exists($this->file_path.$category_name)) {
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
				Folders::auto_create_folder($category_name,$this->file_path,$this->thumb_path,'Products');
			}
		} else {
			$category_id = trim((int)$_POST['cat_name']);
			try {			
                $query = $dbc->prepare("SELECT title FROM categories WHERE category_id = ?");
                $query->execute([$category_id]);
				$row = $query->fetch();
				$category_name = $row['title'];
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
            } catch (\PDOException $e){
                echo $e->getMessage();
            }

			// CATEGORIE ID WORDT DOORGEGEVEN MAAR IK MOET HET ALBUYM ID HEBBEN VAN DIE CATEGORIE..
			echo $this->file_path;
			echo $this->thumb_path;
		}

		/*
                if(isset($_POST['cat_name'])) {
                    $cat_name = mysqli_real_escape_string($dbc->connect(),trim($_POST['cat_name']));
                    ($cat_name == 'None')? $category_id = mysqli_real_escape_string($dbc->connect(),trim($_POST['category'])) : $category_id = mysqli_real_escape_string($dbc->connect(),trim($cat_name));
                }
        */
		//create new product file folder inside Products folder.
		if(!file_exists($this->file_path.$name)) {
			$album_id = Folders::auto_create_folder($name,$this->file_path.$name,$this->thumb_path.$name,'Products',$category_name);
		}

		if(!empty($name) && !empty($price)){

			if($confirm == 'Yes'){
				try {
                    $query = $dbc->prepare("UPDATE products SET name = ?, category_id = ?, description = ?, price = ?, quantity = ? WHERE product_id = ?");
					$query->execute([$name,$category_id,$description,$price,$quantity,$id]);
                } catch (\PDOException $e){
                    echo $e->getMessage();
                }

			} else {
                try {
				    $query = $dbc->prepare("INSERT into products (name,category_id,description,price,album_id,quantity) VALUES(?,?,?,?,?,?)");
					$query->execute([$name,$category_id,$description,$price,$album_id,$quantity]);
                } catch (\PDOException $e){
                    echo $e->getMessage();
                }
			}

			$output_form = false;
			$messages[] = "Product {$name} successfully added/edited.";
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (name,price,quantity).";
		}

		$db->close();

		return ['output_form' => $output_form,'messages' => $messages, 'errors' => $errors];
	}

	// SHOPPING CART

	public function lowStock()
	{
		if($this->outOfStock()){
			return false;
		}

		return (bool) $this->quantity <= 5;

	}
	public function outOfStock()
	{
		return (bool) ($this->quantity == 0);
	}
	public function inStock()
	{
		return $this->quantity >= 1;
	}
	public function hasStock($quantity){
		if($this->quantity >= $quantity){
			return true;
		}
	}
	// total of the product price * the quantity orderd.
	public function productTotal(){
		return $this->getQuantity() * $this->Total();
	}

	public function setMaxStock($num){
		$this->maxStock = $num;
	}
	public function maxStock(){
		return $this->maxStock;
	}
}
?>