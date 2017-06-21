<?php
    namespace Controller;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Files\Folder;

    class FoldersController extends Controller
    {
        public function index($response,$params = null)
        {
            $folders = Folder::all()->grab();
            $this->view('Folders',['files/folders.php'],$params,['folders' => $folders]);
        }
        public function create(){

        }
        public function edit(){

        }

        public function action()
        {
            
        }
    }