<?php

use CMS\Models\Controller\Controller;
use \CMS\Models\Users\Users as User;
use \CMS\Models\DBC;

class Users extends Controller {

	use \CMS\Models\Actions\UserActions;

	public function index($params = null){
		$users = User::allWhere(['trashed' => 0]);
		$this->UserActions($users[0]);
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
	public function create($params = null){
		if(!isset($_POST['submit'])){
			$user = new User();
			$this->view(
				'Add User',
				['users/add-edit-user.php'],
				$params,
				['user' => $user]
			);
		} else {
			$user = new User($_POST);
			$add = $user->add();
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
	public function deleted($params = null){
		$users = User::allWhere(['trashed' => 1]);
        $this->UserActions($users[0]);
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
	public function edit($params = null){
		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			$file_dest = 'files/';
			$thumb_dest= 'files/thumbs/';
			User::addProfileIMG($file_dest,$thumb_dest,$params);
		}

		$user = User::one($params[0]);
		$messages = [];

		if(isset($_POST['submit'])){
			$user->request = $_POST;
			$user->edit();
		}

		$this->view(
			'Edit User',
			['users/add-edit-user.php'],
			$params,
			[
				'user' => $user,
				'messages' => $messages,
			]
		);
	}
	public function Profile($params = null){
		if(empty($params)){
			$params = [$_SESSION['user_id'],$_SESSION['username']];
		}
		$user = User::one($params[0]);;
		$this->view(
			'User',
			['users/profile.php'],
			$params,
			['user' => $user]
		);
	}
}
?>