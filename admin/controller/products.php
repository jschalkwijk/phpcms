<?php

class Products extends Controller{
	use Actions_UserActions;
	
	public function index($params = null){
		$this->UserActions('products');
		$products = Products_Product::fetchAll('products',0);
		$this->view('Products',['products.php'],$params,['products' => $products['products'],'trashed' => 0,'js' => [JS.'checkAll.js']]);
	}
	
	public function deletedProducts($params = null){
		$this->UserActions('products');
		$products = Products_Product::fetchAll('products',1);
		$this->view('Products',['products.php'],$params,['products' => $products['products'],'trashed' => 1,'js' => [JS.'checkAll.js']]);
	}
	
	public function addProduct($params = null){
		if(isset($_POST['submit_product'])){
			$product = new Products_Product($_POST['name'],$_POST['category'],$_POST['description'],$_POST['price'],$_POST['quantity'],null);
			$add = $product->addProduct();
			$this->view('Add Product',['add-edit-product.php'],$params,['product' => $product, 'output_form' => $add['output_form'] ,'errors' => $add['errors'], 'messages' => $add['messages']]);
		} else {
			$product = new Products_Product(null,null,null,null,null,null);
			$this->view('Add Product',['add-edit-product.php'],$params,['product' => $product]);
		}
	}
	
	public function editProduct($params = null){
		if(isset($_POST['submit_product'])){
			$product = new Products_Product($_POST['name'],$_POST['category'],$_POST['description'],$_POST['price'],$_POST['quantity'],null);
			$edit = $product->addProduct($params[0],$_POST['confirm']);
			$this->view('Edit Product',['add-edit-product.php'],$params,['product' => $product, 'output_form' => $edit['output_form'] ,'errors' => $edit['errors'], 'messages' => $edit['messages']]);
		} else {
			$product = Products_Product::fetchSingle($params[0]);
			$this->view('Edit Product',['add-edit-product.php'],$params,['product' => $product]);
		}
	}
	
	public function info($params = null) {
		$this->UserActions('products');

		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			# ik heb nu de products folder name in de het formulier staan, dit kan ik ook hier regelen doo het pad meteen goed aante geven
			$file_dest = 'files/';
			$thumb_dest= 'files/thumbs/';
			Products_Product::addProductIMG($file_dest,$thumb_dest,$params);
		}

		if (isset($_GET['delete'])) {
			$delete_file = File_File::delete_files($_GET['checkbox']);
		}
		if(isset($_GET['download_files'])){
			$download_file = File_File::downloadFiles();
		}
		
		$product = Products_Product::fetchSingle($params[0]);
		$this->view('Product '.$params[1],['view-product.php'],$params,['product' => $product,'js' => [JS.'checkAll.js']]);
	}
}

?>