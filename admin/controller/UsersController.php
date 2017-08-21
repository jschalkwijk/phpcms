<?php

	namespace Controller;
	use CMS\Models\Controller\Controller;
	use \CMS\Models\Users\Users as User;
	use CMS\Models\Actions\Actions;

class UsersController extends Controller {

	use \CMS\Models\Actions\UserActions;

	public function index($response,$params = null){
		$users = User::allWhere(['trashed' => 0]);
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
	public function create($response,$params = null){
		$user = new User();
		if(isset($_POST['submit'])){
			$user = new User($_POST);
			$add = $user->add();
		}
		$this->view(
			'Add User',
			['users/add-edit-user.php'],
			$params,
			[
				'user' => $user,
				'messages' => $add['messages'],
			]
		);
	}
	public function deleted($response,$params = null){
		$users = User::allWhere(['trashed' => 1]);
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
	public function edit($response,$params = null){
		if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
			$file_dest = 'files/';
			$thumb_dest= 'files/thumbs/';
			User::addProfileIMG($file_dest,$thumb_dest,$params);
		}

		$user = User::one($params['id']);

		if(isset($_POST['submit'])){
			$user->request = $_POST;
			$add = $user->edit();
//			print_r($user);
		}

		$this->view(
			'Edit User',
			['users/add-edit-user.php'],
			$params,
			[
				'user' => $user,
				'messages' => $add['messages'],
			]
		);
	}
	public function Profile($response,$params = null){
		if(empty($params)){
			$params = [$_SESSION['user_id'],$_SESSION['username']];
		}
		$user = User::one($params['id']);;
		$this->view(
			'User',
			['users/profile.php'],
			$params,
			['user' => $user]
		);
	}

    public function action($response, $params)
    {
        $this->UserActions(new User());

    }

    public function approve($response,$params)
    {
        $user = User::one($params['id']);
        Actions::approve_selected($user,$params['id']);
        header("Location: ".ADMIN.$user->table);
    }

    public function hide($response,$params)
    {
        $user = User::one($params['id']);
        Actions::hide_selected($user,$params['id']);
        header("Location: ".ADMIN.$user->table);
    }

    public function trash($response,$params)
    {
        $user = User::one($params['id']);
        Actions::trash_selected($user,$params['id']);
        header("Location: ".ADMIN.$user->table.'/deleted');
    }

    public function destroy($response,$params)
    {
        $user = User::one($params['id']);
        $user->delete();
        header("Location: ".ADMIN.$user->table.'/deleted');
    }
}
?>