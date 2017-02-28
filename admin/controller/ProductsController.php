<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;
use CMS\Models\Products\Product;
use CMS\Models\Files\File;
use CMS\Models\Files\Folders;

class Products extends Controller
{
    use UserActions;

    public function index($params = null)
    {
        $products = Product::allWhere(['trashed' => 0]);
        $this->UserActions($products[0]);
        $this->view(
            'Products',
            ['products/products.php'],
            $params,
            [
                'products' => $products,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function deleted($params = null)
    {
        $products = Product::allWhere(['trashed' => 1]);
        $this->UserActions($products[0]);
        $this->view(
            'Products',
            ['products/products.php'],
            $params,
            ['products' => $products,
                'trashed' => 1,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function create($params = null)
    {
        $product = new Product();

        if (isset($_POST['submit'])) {
            $product = new Product($_POST);
            $add = $product->add();
        }
        $this->view(
            'Add Product',
            ['products/add-edit-product.php'],
            $params,
            [
                'product' => $product,
                'output_form' => $add['output_form'],
                'errors' => $add['errors'],
                'messages' => $add['messages']
            ]
        );
    }

    public function edit($params = null)
    {
        $product = Product::one($params[0]);

        if (isset($_POST['submit'])) {
            $product->request = $_POST;
            $product->user_id = $_SESSION['user_id'];
            $edit = $product->edit();
        }
        $this->view(
            'Edit Product',
            ['products/add-edit-product.php'],
            $params,
            [
                'product' => $product,
                'output_form' => $edit['output_form'],
                'errors' => $edit['errors'],
                'messages' => $edit['messages']
            ]
        );
    }

    public function info($params = null)
    {
        $this->UserActions('products');

        if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
            # ik heb nu de products folder name in de het formulier staan, dit kan ik ook hier regelen doo het pad meteen goed aante geven
            $file_dest = 'uploads/';
            $thumb_dest = 'uploads/thumbs/';
            Product::addProductIMG($file_dest, $thumb_dest, $params);
        }

        if (isset($_GET['delete'])) {
            File::delete_files($_GET['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            File::downloadFiles();
        }

        $product = Product::one($params[0]);
        $folders = Folders::allWhere(['parent_id'=>$product->album_id]);
        $this->view(
            'Product ' . $params[1],
            ['products/view-product.php'],
            $params,
            [
                'product' => $product,
                'folders' => $folders,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }
}

?>