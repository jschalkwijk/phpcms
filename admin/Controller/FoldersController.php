<?php
    namespace Controller;
    use CMS\Models\Actions\UserActions;
    use CMS\Models\Controller\Controller;
    use CMS\Models\Files\Folder;
    use CMS\Core\FileSystem\FileSystem;
    use CMS\Models\Files\File;

    class FoldersController extends Controller
    {
        use UserActions;
        public function index($response,$params = null)
        {
            $folders = Folder::all();
            $this->view('Folders',['files/folders.php'],$params,['folders' => $folders]);
        }
        public function create(){

        }

        public function edit($response,$params)
        {
            $folder = Folder::one($params['id']);
            $folders = Folder::all();
            if (isset($_POST['submit'])) {
                $folder->patch($_POST);

                if($_POST['parent_id'] != $folder->get_id()){

                    $destination = Folder::one($_POST['parent_id']);
                    if((new FileSystem())->moveDirectory($_SERVER['DOCUMENT_ROOT'].'/admin/'.$folder->path, $_SERVER['DOCUMENT_ROOT'].'/admin/'.$destination->path.'/'.$folder->name,true)){
                        $new_path = $destination->path.'/'.$folder->name;

//                    $folder->patch($_POST);
                        $folder->patch([
                            'path' => $new_path,
                        ]);
                    }
                }
                $folder->savePatch();
                $files = File::allWhere(['folder_id' => $folder->get_id()]);
                foreach ($files as $file) {
                    $name = $file->file_name;
                    $file->patch(['path' => $new_path.'/'.$name,'thumb_path' => $new_path.'/thumbs/'.$name])->savePatch();
                }
                $folder->renameRecursion($folder->children(),$folder);
                header("Location: ".ADMIN.$folder->table.'/'.$folder->get_id().'/'.$folder->name);
            }
            $this->view('Folders',['files/edit-folder.php'],$params,['folder' => $folder,'folders' => $folders]);

        }

        public function show($response,$params = null)
        {
            $folder = Folder::one($params['id']);
            $folders = Folder::all();;
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