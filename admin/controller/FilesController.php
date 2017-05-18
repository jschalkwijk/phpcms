<?php

namespace Controller;

use CMS\Models\Controller\Controller;
use CMS\Models\Files\Folders;
use CMS\Models\Files\File;

class FilesController extends Controller
{

    public function index($response,$params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            File::delete_files($_POST['checkbox']);
        }
        $folders = Folders::allWhere(['parent_id'=>0]);
//        foreach($folders as $folder){
//            print_r($folder);
//        }
        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params,
            ['folders' => $folders]
        );
    }

    public function albums($params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            print_r($_POST['checkbox']);
            File::delete_files($_POST['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            File::downloadFiles();
        }
        $folder = Folders::one($params[0]);
        $folders = $folder->subFolders();
        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params,
            ['folder' => $folder,'folders' => $folders],
            ['js' => [JS . 'checkAll.js']]
        );
    }
}

?>