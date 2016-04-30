<?php

class Categories extends Controller{
	
	use actions_UserActions;

	public function index($params = null){
		$this->UserActions('categories');
		if(isset($_POST['submit'])){
			$author = $_SESSION['username'];
			$add = content_Categories::addCategory($_POST['category'],'post');
			$categories = content_Categories::fetchAll('categories',0);
			$view = ['actions' => 'view/manage_content.php'];
			$this->view('Categories',['add-category.php','categories.php'],$params,['errors' => $add['errors'],'categories' => $categories, 'view' => $view,'trashed' => 0]);
		} else {
			$categories = content_Categories::fetchAll('categories',0);
			$view = ['actions' => 'view/manage_content.php'];
			$this->view('Categories',['add-category.php','categories.php'],$params,['categories' => $categories,'view' => $view,'trashed' => 0]);
		}
	}
	//
	public function DeletedCategories($params = null){
		$this->UserActions('categories');
		if(isset($_POST['submit'])){
			$author = $_SESSION['username'];
			$add = content_Categories::addCategory($_POST['category']);
			$categories = content_Categories::fetchAll('categories',1);
			$view = ['actions' => 'view/manage_content.php'];
			$this->view('Categories',['add-category.php','categories.php'],$params,['errors' => $add['errors'],'categories' => $categories, 'view' => $view,'trashed' => 1]);
		} else {
			$categories = content_Categories::fetchAll('categories',1);
			$view = ['actions' => 'view/manage_content.php'];
			$this->view('Categories',['add-category.php','categories.php'],$params,['categories' => $categories,'view' => $view,'trashed' => 1]);
		}
	}
	//
	public function EditCategories($params = null){
		if(isset($_POST['submit'])){
			$category = new content_Categories($_POST['title'],$_POST['cat_desc'],$_SESSION['username']);
			$edit = $category->editCategory($_POST['id'],$_POST['old_title'],$_POST['confirm']);
			$this->view('Edit Categories',['edit-categories.php'],$params,['category' => $category,'output_form' => $edit['output_form'],'errors' => $edit['errors'],'messages' => $edit['messages']]);
		} else {
			$category = content_Categories::fetchSingle('categories',$params[0]);
			$this->view('Edit Categories',['edit-categories.php'],$params,['category' => $category,'output_form' => true]);
		}

	}
}	
?>