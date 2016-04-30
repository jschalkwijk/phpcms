<?php
class Pages extends Controller {
	
	use actions_UserActions;
	
	public function index($params = null){
		$pages = content_Content::fetchAll('pages',0);
		$action = '/admin/pages';
		// Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
		$this->UserActions('pages');
		// view takes: page_title,[array of view files],params from the router,array of data from model
		$view = ['search' => 'view/search-page.php','actions' => 'view/manage_content.php'];
		$this->view('pages',['pages.php'],$params,[ 'pages' => $pages , 'action' => $action,'trashed' => 0,'js' => ['/admin/js/checkAll.js']]);
	}

	public function AddPage($params = null){
		if (isset($_POST['submit'])){
			$page = new content_pages_Page($_POST['page_title'],$_POST['page_desc'],null,$_POST['page_content'],$_SESSION['username'],'pages');
			$add = $page->addPage($_POST['back-end'],$_POST['front-end'],$_POST['sub_page']);
			$this->view('Add Post',['add-edit-page.php'],$params,['output_form' => $add['output_form'] ,'page' => $page, 'messages' => $add['messages']]);
		} else {
			$page = new content_posts_Post(null,null,null,null,null,'pages');
			$this->view('Add Post',['add-edit-page.php'],$params,['page' => $page]);
		}
	}

	public function DeletedPages($params = null){
		$action = '/admin/pages/deleted-pages';
		$delete = $this->UserActions('pages');
		$pages = content_Content::fetchAll('pages',1);
		$this->view('Deleted Pages',['pages.php'],$params,[ 'pages' => $pages , 'action' => $action,'trashed' => 1,'messages' => $delete['messages'],'js' => ['/admin/js/checkAll.js']]);

	}

	public function EditPages($params = null){
		if(isset($_POST['submit'])){
			$page = new content_posts_Page($_POST['page_title'],$_POST['page_desc'],null,$_POST['page_content'],$_SESSION['username'],'pages');
			$edit = $page->addPage($_POST['back-end'],$_POST['front-end'],$_POST['sub_page'],$_POST['id']);
			$this->view('Edit Page',['add-edit-post.php'],$params,['page' => $page,'output_form' => $edit['output_form'],'messages' => $edit['messages']]);
		} else {
			$page = content_Content::fetchSingle('pages',$params[0]);
			$this->view('Edit Page',['add-edit-page.php'],$params,['page' => $page,'output_form' => true]);
		}

	}
	
}
?>