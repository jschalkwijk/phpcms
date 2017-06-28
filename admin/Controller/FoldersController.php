<?php
    namespace Controller;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Files\Folder;

    class FoldersController extends Controller
    {
        use UserActions;
        public function index($response,$params = null)
        {
            $folders = Folder::all()->grab();
            $this->view('Folders',['files/folders.php'],$params,['folders' => $folders]);
        }
        public function create(){

        }
        public function edit(){

        }

        public function action($response, $params)
        {
            $this->UserActions(new Folder());
        }
    }