<?php

use CMS\Models\Controller\Controller;
use \CMS\Models\Users\Users as Usr;
use \CMS\Models\DBC;

class Users extends Controller {

	use \CMS\Models\Actions\UserActions;

	public function index($params = null){
		$users = Usr::all();
		$this->UserActions('users');
		$this->view(
			'Users',
			['users/users.php'],
			$params,
			[
				'users' => $users,
				'trashed' => 0,
				'js' => [JS.'checkAll.js']
			]
		);
	}
	public function AddUser($params = null){
		if(!isset($_POST['submit'])){
			$user = new Usr();
			$this->view(
				'Add User',
				['users/add-edit-user.php'],
				$params,
				['user' => [$user]]
			);
		} else {
			$user = new Usr($_POST);
			$add = $user->addUser($_POST['new_password'],$_POST['new_password_again']);
			$this->view(
				'Add User',
				['users/add-edit-user.php'],
				$params,
				[
					'user' => $user,
					'output_form' => $add['output_form'],
					'errors' => $add['errors'],
					'messages' => $add['messages']
				]
			);
		}
	}
	public function DeletedUsers($params = null){
		$users = Usr::fetchUsers('users',1);
		$this->UserActions('users');
		$this->view(
			'Deleted Users',
			['users/users.php'],
			$params,
			[
				'users' => $users,
				'trashed' => 1,
				'js' => [JS.'/checkAll.js']
			]
		);
	}
	public function EditUsers($params = null){
		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			$file_dest = 'files/';
			$thumb_dest= 'files/thumbs/';
			Usr::addProfileIMG($file_dest,$thumb_dest,$params);
		}

		$user = Usr::single($params[0]);

		if(!isset($_POST['submit'])){
			$this->view(
				'Edit User',
				['users/add-edit-user.php'],
				$params,
				[
					'user' => $user,
					'output_form' => true
				]
			);
		} else {
			$user = $user[0];
			$user->request = $_POST;
			$edit = $user->edit();
			if(isset($_POST['new_password']) && isset($_POST['new_password_again'])){
				$edit = $user->edit($_POST['id'],$_POST['username'],$_POST['new_password'],$_POST['new_password_again']);
			} else {
				$edit = $user->edit($_POST['id'],$_POST['username']);
			}
			$this->view(
				'Edit User',
				['users/add-edit-user.php'],
				$params,
				[
					'user' => [$user],
					'output_form' => $edit['output_form'],
					'errors' => $edit['errors'],
					'messages' => $edit['messages']
				]
			);
		}
	}
	public function Profile($params = null){
		if(empty($params)){
			$params = [$_SESSION['user_id'],$_SESSION['username']];
		}
		$user = Usr::fetchSingle('users',$params[0]);;
		$this->view(
			'User',
			['users/profile.php'],
			$params,
			['user' => $user]
		);
	}
}
?>