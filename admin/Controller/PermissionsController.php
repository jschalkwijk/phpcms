<?php
    namespace Controller;

    use CMS\Models\Controller\Controller;
    use CMS\Models\Support\Error;
    use CMS\Models\Support\Session;
    use CMS\Models\Users\Permission;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Actions\Actions;
    use CMS\Models\Users\Role;

    class PermissionsController extends Controller {
        use UserActions;

        public function index($response,$params)
        {
            $permissions = Permission::all();
            $this->view('Permissions',['permissions/permissions.php'],$params,['permissions' => $permissions]);
        }

        public function show($response,$params){
            $permission = Permission::one($params['id']);
            $this->view('Permission '.$permission->name,['permissions/show.php'],$params,['permission' => $permission]);
        }

        public function create($response,$params)
        {
            $permission = new Permission($_POST);
            $errors = new Error();
            if (isset($_POST['submit']) && !empty($permission->name)) {
                if (!empty($permission->name)) {
                    $permission->save();
                    $permission->permission_id = $permission->lastInsertId;
                    if(!$permission->saveMany(Role::allWhere(['role_id' => $_POST['checkbox']]),'roles_permissions')) {
                        $errors->push([
                            'Roles are not saved',
                        ]);
                    }
                }
            } else {
                $errors->push([
                    'You forgot to fill in a name',
                ]);
            }

            $this->view(
                'Create Permission',
                ['permissions/create.php'],
                $params,
                [
                    'permission' => $permission,
                    'roles' => Role::all(),
                    'errors' => $errors->errors()
                ]
            );

        }

        public function store($response,$params)
        {

        }

        public function edit($response,$params)
        {
            $permission = Permission::one($params['id']);
            $errors = new Error();

            if (isset($_POST['submit'])) {
                $permission->patch($_POST);
                if (!empty($permission->name)){
                    $permission->savePatch();
                    if(!$permission->sync(Role::class,$permission->roles(),$_POST['checkbox'],'roles_permissions')) {
                        $errors->push([
                            'Roles are not saved',
                        ]);
                    }
                }
            } else {
                $errors->push([
                    'You forgot to fill in a name',
                ]);
            }

            $roles = $permission->roles();
            $currentRoles = [];
            foreach ($roles as $role) {
                $currentRoles[] = $role->role_id;
            };

            $this->view(
                'Edit Permission '.$permission->name,
                ['permissions/create.php'],
                $params,
                [
                    'permission' => $permission,
                    'roles' => Role::all(),
                    'currentRoles' => $currentRoles,
                    'errors' => $errors,
                ]
            );
        }

        public function update($response,$params)
        {

        }

        public function action($response, $params)
        {
            $permission = Permission::build();

            $this->UserActions($permission);
            header("Location: ".ADMIN.$permission->table);
        }

        public function approve($response,$params)
        {
            $permission = Permission::one($params['id']);
            Actions::approve_selected($permission,$params['id']);
            header("Location: ".ADMIN.$permission->table);
        }

        public function hide($response,$params)
        {
            $permission = Permission::one($params['id']);
            Actions::hide_selected($permission,$params['id']);
            header("Location: ".ADMIN.$permission->table);
        }

        public function trash($response,$params)
        {
            $permission = Permission::one($params['id']);
            Actions::trash_selected($permission,$params['id']);
            header("Location: ".ADMIN.$permission->table.'/deleted');
        }

        public function destroy($response,$params)
        {
            $permission = Permission::one($params['id']);
            $permission->delete();
            header("Location: ".ADMIN.$permission->table.'/deleted');
        }
    }