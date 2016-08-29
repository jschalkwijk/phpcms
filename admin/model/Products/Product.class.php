<?php

class Products_Product {
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

		$query = $dbc->prepare("SELECT ".$dbt.".*, categories.title as category FROM ".$dbt." LEFT JOIN categories ON ".$dbt.".category_id = categories.categorie_id  WHERE ".$dbt.".trashed = ? ORDER BY product_id DESC");
		if($query){
			$query->bind_param("i",$trashed);
			$query->execute();
			$data = $query->get_result();
			$query->close();
		} else {
			$db->sqlERROR();
		}

		$products = array();

		while($row = $data->fetch_array()){
			$product = new products_Product(
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
		$dbc->close();
		return ['products' => $products];
	}

	public static function fetchAllByID($ids){
		$db = new DBC;
		$dbc = $db->connect();

		$multiple = implode(",",$ids);
		$query = $dbc->query("SELECT products.*, categories.title as category FROM products LEFT JOIN categories ON products.category_id = categories.categorie_id WHERE product_id IN({$multiple})");
		if(!$query){ $db->sqlERROR(); };

		$products = array();

		while($row = $query->fetch_array()){
			$product = new products_Product(
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
		$dbc->close();
		return $products;
	}

	public static function fetchSingle($id){
		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->prepare("SELECT products.*, categories.title as category FROM products LEFT JOIN categories ON products.category_id = categories.categorie_id WHERE product_id = ? ORDER BY product_id DESC");
		if($query){
			$query->bind_param("i",$id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
		} else {
			$db->sqlERROR();
		}

		while($row = $data->fetch_array()){
			$product = new products_Product(
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
		$dbc->close();
		return $product;
	}

	public static function addProductIMG($file_dest,$thumb_dest,$params){
		$db = new DBC;
		$dbc = $db->connect();

		$upload = new files_FileUpload($file_dest,$thumb_dest,$params,true);
		$img_path = $upload->getImgPath();
		$thumb_path = $upload->getThumbPath();

		$query = $dbc->prepare("UPDATE products SET img_path = '$thumb_path' WHERE product_id = ?");

		if($query){
			$query->bind_param("i",$params[0]);
			$query->execute();
			$query->close();
		} else {
			$db->sqlERROR();
		}

		$dbc->close();
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
			$id = mysqli_real_escape_string($dbc,trim((int)$this->getID()));
		};

		$name = mysqli_real_escape_string($dbc, trim($this->name));
		$category = mysqli_real_escape_string($dbc, trim($this->category));
		$description = mysqli_real_escape_string($dbc, trim($this->description));
		$price = mysqli_real_escape_string($dbc, trim((float)($this->price)));
		$quantity = mysqli_real_escape_string($dbc, trim((int)$this->quantity));

		# create product category, set type of category group
		# create product category file folder.
		if(!empty($category)) {
			$category = content_Categories::addCategory($category,'product');
			$category_name = $category['category_name'];
			$category_id = $category['category_id'];
			if(!file_exists($this->file_path.$category_name)) {
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
				File_Folders::auto_create_folder($category_name,$this->file_path,$this->thumb_path,'Products');
			}
		} else {
			$category_id = mysqli_real_escape_string($dbc,trim((int)$_POST['cat_name']));
			$query = $dbc->prepare("SELECT title FROM categories WHERE categorie_id = ?");
			if($query){
				$query->bind_param("i",$category_id);
				$query->execute();
				$cat_name = $query->get_result();
				$query->close();
				$row = $cat_name->fetch_array();
				$category_name = $row['title'];
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
			} else {
				$db->sqlERROR();
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
			$album_id = File_Folders::auto_create_folder($name,$this->file_path.$name,$this->thumb_path.$name,'Products',$category_name);
		}

		if(!empty($name) && !empty($price)){

			if($confirm == 'Yes'){
				$query = $dbc->prepare("UPDATE products SET name = ?, category_id = ?, description = ?, price = ?, quantity = ? WHERE product_id = ?");
				if($query){
					$query->bind_param("sisdii",$name,$category_id,$description,$price,$quantity,$id);
					$query->execute();
					$query->close();
				} else {
					$db->sqlERROR();
				}

			} else {
				$query = $dbc->prepare("INSERT into products (name,category_id,description,price,album_id,quantity) VALUES(?,?,?,?,?,?)");
				if($query){
					$query->bind_param("sisdii",$name,$category_id,$description,$price,$album_id,$quantity);
					$query->execute();
					$query->close();
				} else {
					$db->sqlERROR();
				}
			}

			$output_form = false;
			$messages[] = "Product {$name} successfully added/edited.";
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (name,price,quantity).";
		}

		$dbc->close();

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