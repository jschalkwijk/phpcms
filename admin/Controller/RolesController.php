<?php
    namespace Controller;
    use CMS\Models\Controller\Controller as Controller;
    use CMS\Core\Auth\Auth;

    use CMS\Models\Users\Permission;
    use CMS\Models\Users\Role;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Actions\Actions;
    use CMS\Models\Support\SessionStorage;
    use CMS\Models\Support\Session;
    use CMS\Models\Support\Error;

    class RolesController extends Controller {
        use UserActions;

        public function index($response,$params)
        {
            $roles = Role::all();
            $this->view('Roles', ['roles/roles.php'], $params, ['roles' => $roles]);
        }

        public function show($response,$params){
            $role = Role::one($params['id']);
            $this->view('Role '.$role->name,['roles/show.php'],$params,['role' => $role]);
        }

        public function create($response,$params)
        {
            $role = new Role($_POST);
            $permissions = Permission::all();

            if(isset($_POST['submit'])){
                if(isset($_POST['name']) && !empty($_POST['name'])){
                    if($role->save()) {
                        $permissions = Permission::allWhere(['permission_id' => $_POST['checkbox']]);

                        $role->role_id = $role->lastInsertId;

                        if (empty($permissions)) {
                            return false;
                        }

                        if (!$role->saveMany($permissions, 'roles_permissions')) {
                            return false;
                        };

                        header("Location: " . ADMIN.$role->table);
                    }
                } else {
                    Session::flash("status", "Role not Updated.<br />");
                    new Error([
                        'You forgot to fill in the one or more required fields',
                    ]);
                }
            }

            $this->view('Create Role',['roles/create.php'],$params,['role'=>$role,'permissions'=>$permissions,'errors' => (new Error())->errors()]);
        }

        public function store($response,$params)
        {

        }

        public function edit($response,$params)
        {
            $permissions = Permission::all();

            if(!SessionStorage::getByName('old')->exists($params['id'])){
                $role = Role::one($params['id']);
            } else {
                $role = SessionStorage::getByName('old')->get($params['id']);
                SessionStorage::getByName('old')->unsetIndex($role->role_id);
            }
            foreach ($role->permissions() as $permission) {
                $currentPermissions[] = $permission->permission_id;
            };
            $this->view(
                    'Edit Role',
                    ['roles/create.php'],
                    $params,
                    [
                        'role' => $role,
                        'permissions' => $permissions,
                        'currentPermissions' => $currentPermissions,
                        'errors' => (new Error())->errors(),
                    ]
                );

        }

        public function update($response,$params)
        {
            $role = Role::one($params['id']);
            if (isset($_POST['submit'])) {
                $role->patch($_POST);
                if (!empty($role->name)) {

                    if($role->savePatch()) {
                        $role->sync(Permission::class,$role->permissions,$_POST['checkbox'],'roles_permissions');
                    }

                    if (isset($_SESSION['old'][$role->role_id])) {
                        SessionStorage::getByName('old')->unsetIndex($role->role_id);
                    }

                    header("Location: " . ADMIN . "roles");
                } else {
                    Session::flash("status", "Role not Updated.<br />");
                    (new Error([
                        'You forgot to fill in the one or more required fields',
                    ]));

                    //store the unsaved object in the session so all edited parts are saved when returning to the edit page for editting.

                    (new SessionStorage('old'))->set($role->role_id, $role);

                    header("Location: " . ADMIN . "roles/edit/" . $role->role_id);
                };
            }
        }

        public function action($response, $params)
        {
            $role = Role::build();

            $this->UserActions($role);
            header("Location: ".ADMIN.$role->table);
        }

        public function approve($response,$params)
        {
            $role = Role::one($params['id']);
            Actions::approve_selected($role,$params['id']);
            header("Location: ".ADMIN.$role->table);
        }

        public function hide($response,$params)
        {
            $role = Role::one($params['id']);
            Actions::hide_selected($role,$params['id']);
            header("Location: ".ADMIN.$role->table);
        }

        public function trash($response,$params)
        {
            $role = Role::one($params['id']);
            Actions::trash_selected($role,$params['id']);
            header("Location: ".ADMIN.$role->table.'/deleted');
        }

        public function destroy($response,$params)
        {
            $role = Role::one($params['id']);
            $role->delete();
            header("Location: ".ADMIN.$role->table);
        }
    }