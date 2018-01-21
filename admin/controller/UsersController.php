<?php

	namespace Controller;
	use CMS\Models\Controller\Controller;
    use CMS\Models\Users\Permission;
    use CMS\Models\Users\Role;
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
			$user->add();
			$user->user_id = $user->lastInsertId;
			$user->givePermissionTo($_POST['permissions[]']);
		}
		$this->view(
			'Add User',
			['users/add-edit-user.php'],
			$params,
			[
				'user' => $user,
				'permissions' => Permission::all(),
				'roles' => Role::all(),
			]
		);
	}

    public function store($response,$params = null)
    {

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
		$roles = $user->roles();
        foreach ($roles as $role) {
            $currentRoles[] = $role->role_id;
            foreach ($role->permissions() as $permission){
                $currentPermissions[] = $permission->permission_id;
            }
        };
        foreach ($user->permissions() as $customPermission){
            $customPermissions[] = $customPermission->permission_id;
        }

		if(isset($_POST['submit'])){
			$user->request = $_POST;
			$user->edit();
            $user->givePermissionTo($_POST['permissions[]']);
//			print_r($user);
		}

		$this->view(
			'Edit User',
			['users/add-edit-user.php'],
			$params,
			[
				'user' => $user,
                'permissions' => Permission::all(),
                'roles' => Role::all(),
                'currentRoles' => $currentRoles,
                'currentPermissions' => $currentPermissions,
                'customPermissions' => $customPermissions,
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