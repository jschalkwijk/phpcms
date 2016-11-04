<?php

use CMS\model\Controller\Controller;
use CMS\model\Actions\UserActions;
use \CMS\model\Content\Categories as Cat;

class Categories extends Controller
{

    use UserActions;

    public function index($params = null)
    {
        $this->UserActions('categories');
        if (isset($_POST['submit'])) {
            $author = $_SESSION['username'];
            $add = Cat::addCategory($_POST['category'], 'post');
            $categories = Cat::fetchAll('categories', 0);
            $view = ['actions' => 'view/shared/manage-content.php'];
            $this->view(
                'Categories',
                ['categories/categories.php'],
                $params,
                [
                    'errors' => $add['errors'],
                    'categories' => $categories,
                    'view' => $view, 'trashed' => 0
                ]
            );
        } else {
            $categories = Cat::fetchAll('categories', 0);
            $view = ['actions' => 'manage_content.php'];
            $this->view(
                'Categories',
                ['categories/categories.php'],
                $params,
                [
                    'categories' => $categories,
                    'view' => $view,
                    'trashed' => 0
                ]
            );
        }
    }

    //
    public function DeletedCategories($params = null)
    {
        $this->UserActions('categories');
        if (isset($_POST['submit'])) {
            $author = $_SESSION['username'];
            $add = Cat::addCategory($_POST['category']);
            $categories = Cat::fetchAll('categories', 1);
            $view = ['actions' => 'view/shared/manage-content.php'];
            $this->view(
                'Categories',
                ['categories/categories.php'],
                $params,
                [
                    'errors' => $add['errors'],
                    'categories' => $categories,
                    'view' => $view,
                    'trashed' => 1
                ]
            );
        } else {
            $categories = Cat::fetchAll('categories', 1);
            $view = ['actions' => 'view/shared/manage-content.php'];
            $this->view(
                'Categories',
                ['categories/categories.php'],
                $params,
                [
                    'categories' => $categories,
                    'view' => $view,
                    'trashed' => 1
                ]
            );
        }
    }

    //
    public function EditCategories($params = null)
    {
        if (isset($_POST['submit'])) {
            $category = new Cat($_POST['title'], $_POST['cat_desc'], $_SESSION['username']);
            $edit = $category->editCategory($_POST['id'], $_POST['old_title'], $_POST['confirm']);
            $this->view(
                'Edit Categories',
                ['categories/edit-categories.php'],
                $params,
                [
                    'category' => $category,
                    'output_form' => $edit['output_form'],
                    'errors' => $edit['errors'],
                    'messages' => $edit['messages']
                ]
            );
        } else {
            $category = Cat::fetchSingle('categories', $params[0]);
            $this->view(
                'Edit Categories',
                ['categories/edit-categories.php'],
                $params,
                [
                    'category' => $category,
                    'output_form' => true
                ]
            );
        }

    }
}

?>