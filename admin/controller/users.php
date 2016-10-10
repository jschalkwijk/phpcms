<?php

use Jorn\admin\model\Controller\Controller;
use \Jorn\admin\model\Users\Users as Usr;
use \Jorn\admin\model\DBC;

class Users extends Controller {

	use \Jorn\admin\model\Actions\UserActions;

	public function index($params = null){
		$users = Usr::fetchUsers('users',0);
		$this->UserActions('users');
		$this->view('Users',['users.php'],$params,['users' => $users,'trashed' => 0,'js' => [JS.'checkAll.js']]);
	}
	public function AddUser($params = null){
		if(isset($_POST['submit'])){
			$user = new Usr($_POST['username'],$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['function'],$_POST['rights']);
			$add = $user->addUser($_POST['new_password'],$_POST['new_password_again']);
			$this->view('Add User',['add-edit-user.php'],$params,['user' => $user, 'output_form' => $add['output_form'],'errors' => $add['errors'],'messages' => $add['messages']]);
		} else {
			$user = new Usr(null,null,null,null,null,null,null,null);
			$this->view('Add User',['add-edit-user.php'],$params,['user' => $user]);
		}
	}
	public function DeletedUsers($params = null){
		$users = Usr::fetchUsers('users',1);
		$this->UserActions('users');
		$this->view('Deleted Users',['users.php'],$params,['users' => $users,'trashed' => 1,'js' => [JS.'/checkAll.js']]);
	}
	public function EditUsers($params = null){
		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			$file_dest = 'files/';
			$thumb_dest= 'files/thumbs/';
			Usr::addProfileIMG($file_dest,$thumb_dest,$params);
		}

		if(isset($_POST['submit'])){
			$user = new Usr($_POST['username'],$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['function'],$_POST['rights']);
			if(isset($_POST['new_password']) && isset($_POST['new_password_again'])){
				$edit = $user->editUser($_POST['id'],$_POST['username'],$_POST['new_password'],$_POST['new_password_again']);
			} else {
				$edit = $user->editUser($_POST['id'],$_POST['username']);
			}
			$this->view('Edit User',['add-edit-user.php'],$params,['user' => $user, 'output_form' => $edit['output_form'],'errors' => $edit['errors'],'messages' => $edit['messages']]);
		} else {
			$user = Usr::fetchSingle('users',$params[0]);
			$this->view('Edit User',['add-edit-user.php'],$params,['user' => $user,'output_form' => true]);
		}
	}
	public function Profile($params = null){
		if(empty($params)){
			$params = [$_SESSION['user_id'],$_SESSION['username']];
		}
		$user = Usr::fetchSingle('users',$params[0]);;
		$this->view('User',['profile.php'],$params,['user' => $user]);
	}
}
?>