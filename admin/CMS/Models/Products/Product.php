<?php
namespace CMS\Models\Products;

use CMS\Models\DBC\DBC;
use CMS\Models\File\FileUpload;
use CMS\Models\File\Folders;
use CMS\Models\Content\Categories;
use CMS\Core\Model\Model;

class Product extends Model {
	// will get the TRAIT actions that the user can perform like edit,delete,approve
    protected $primaryKey = "product_id";
    protected $table = "products";

    protected $relations = [
        'categories' => 'category_id',
        'users' => 'user_id'
    ];

    protected $joins = [
        'categories' => ['title','description'],
        'users' => ['username']
    ];

    protected $allowed = [
        'name',
        'description',
        'category_id',
        'quantity',
        'price',
    ];

    protected $discount_price;
	protected $savings;
	protected $tax_percentage = 21;
	protected $tax;
	private $total;

	protected $file_path = 'files/products/';
	protected $thumb_path = 'files/thumbs/products/';
	protected $maxStock;

	public function get_id(){
		return $this->product_id;
	}

	public function getTax(){
		$this->tax = ($this->price * $this->tax_percentage) / 100;
		return $this->tax;
	}

	protected function discount($percentage){
		$this->discount_price = ($this->price / 100) * $percentage;
		$this->savings = $this->price * $percentage;
	}

    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setQuantity($value)
    {
        $this->quantity = $value;
    }
	public function total(){
		return $this->price + $this->getTax();
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
				$row
			);
			$product->product_id = $row['product_id'];
			$products[] = $product;
		}
		$db->close();
		return $products;
	}
    public function edit(){
        $output_form = true;
        $errors = [];
        $messages = [];

        # create product category, set type of category group
        # create product category file folder.
        if(!empty($this->request['category'])) {
            $category = Categories::addCategory($this->request['category'],'product');
            $category_name = $category['category_name'];
            $category_id = $category['category_id'];
            if(!file_exists($this->file_path.$category_name)) {
                $this->file_path = $this->file_path.$category_name.'/';
                $this->thumb_path = $this->thumb_path.$category_name.'/';
                Folders::auto_create_folder($category_name,$this->file_path,$this->thumb_path,'Products');
            }
        } else {
            $this->category_id = trim((int)$this->request['category_id']);
            try {
                $db = new DBC;
                $dbc = $db->connect();
                $query = $dbc->prepare("SELECT title FROM categories WHERE category_id = ?");
                $query->execute([$this->category_id]);
                $row = $query->fetch();
                $category_name = $row['title'];
                $this->file_path = $this->file_path.$category_name.'/';
                $this->thumb_path = $this->thumb_path.$category_name.'/';
                $db->close();
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
//        if(!file_exists($this->file_path.$this->name)) {
//            $album_id = Folders::auto_create_folder($this->name,$this->file_path.$this->name,$this->thumb_path.$this->name,'Products',$category_name);
//        }

        $this->hidden['user_id'] = $this->user_id;
        print_r($this->request);
        $this->patch();
        if(!empty($this->name) && !empty($this->description) && !empty($this->category_id)) {
            $this->savePatch();
            $messages[] = 'Your post has been added/edited.<br />';
            $output_form = true;
        } else {
            $errors[] = "You forgot to fill in one or more of the required fields (title,content).<br />";
            $output_form = true;
        };

        return ['output_form' => $output_form,'messages' => $messages, 'errors' => $errors];
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

	public function add(){
		$output_form = true;
		$errors = [];
		$messages = [];

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
			$this->category_id = $category['category_id'];
            $this->request['category_id'] = $this->category_id;
			if(!file_exists($this->file_path.$category_name)) {
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
				Folders::auto_create_folder($category_name,$this->file_path,$this->thumb_path,'Products');
			}
		} else {
			try {
                $db = new DBC;
                $dbc = $db->connect();
                $query = $dbc->prepare("SELECT title FROM categories WHERE category_id = ?");
                $query->execute([$this->category_id]);
				$row = $query->fetch();
				$category_name = $row['title'];
				$this->file_path = $this->file_path.$category_name.'/';
				$this->thumb_path = $this->thumb_path.$category_name.'/';
                $db->close();
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
		    $this->request['album_id'] = $album_id;
        }

		if(!empty($name) && !empty($price)){
            $this->hidden['user_id'] = $_SESSION['user_id'];
            $this->save();
			$output_form = false;
			$messages[] = "Product {$name} successfully added.";
		} else {
			$errors[] = "You forgot to fill in one or more of the required fields (name,price,quantity).";
		}

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