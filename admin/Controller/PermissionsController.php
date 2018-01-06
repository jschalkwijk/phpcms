<?php
    namespace Controller;

    use CMS\Models\Controller\Controller;
    use CMS\Models\Users\Permission;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Actions\Actions;

    class PermissionsController extends Controller {
        use UserActions;

        public function index($response,$params)
        {

        }

        public function show($response,$params){

        }

        public function create($response,$params)
        {

        }

        public function store($response,$params)
        {

        }

        public function edit($response,$params)
        {

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