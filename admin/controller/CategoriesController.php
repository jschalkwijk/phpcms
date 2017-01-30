<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;
use \CMS\Models\Categories\Categories as Cat;

class Categories extends Controller
{

    use UserActions;

    public function index($params = null)
    {
        $categories = Cat::allWhere(['trashed'=>0]);
        $this->UserActions($categories[0]);
        if (isset($_POST['submit'])) {
            $category = new Cat($_POST);
            $add = $category->add();

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
        $categories = Cat::allWhere(['trashed'=>1]);
        $this->UserActions('categories');
        if (isset($_POST['submit'])) {
            $category = new Cat($_POST);
            $add = $category->add();
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
        $category = Cat::single($params[0]);
        if (!isset($_POST['submit'])) {
            $this->view(
                'Edit Categories',
                ['categories/edit-categories.php'],
                $params,
                [
                    'category' => $category,
                    'output_form' => true
                ]
            );
        } else {
            $category = $category[0];
            $category->request = $_POST;
            $category->user_id = $_SESSION['user_id'];
            $edit = $category->edit();
            $this->view(
                'Edit Categories',
                ['categories/edit-categories.php'],
                $params,
                [
                    'category' => [$category],
                    'output_form' => $edit['output_form'],
                    'errors' => $edit['errors'],
                    'messages' => $edit['messages']
                ]
            );
        }

    }
}

?>