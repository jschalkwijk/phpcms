<?php
    namespace Controller;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Files\Folder;
    use CMS\Core\FileSystem\FileSystem;

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
        public function show($response,$params = null)
        {
            $folder = Folder::one($params['id']);
            $folders = $folder->children();
            $this->view(
                $folder->name,
                [
                    'files/folders.php'
                ],
                $params,
                ['folder' => $folder,'folders' => $folders],
                ['js' => [JS . 'checkAll.js']]
            );
        }

        public function action($response, $params)
        {
            $this->UserActions(new Folder());
        }

        public function destroy($response,$params)
        {
            $folder = Folder::one($params['id']);
            (new FileSystem())->removeDirectory($folder->path);
            Folder::deleteRecursive($params['id']);
        }
    }