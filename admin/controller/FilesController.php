<?php

namespace Controller;

use CMS\Models\Controller\Controller;
use CMS\Models\Files\Folder;
use CMS\Models\Files\File;
use CMS\Models\Files\FileUpload;

class FilesController extends Controller
{

    public function index($response,$params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folder::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            File::delete_files($_POST['checkbox']);
        }
        $folders = Folder::allWhere(['parent_id'=>0]);
//        foreach($folders as $folder){
//            print_r($folder);
//        }
        $this->view(
            'Albums',
            [
                'files/folders.php'
            ],
            $params,
            ['folders' => $folders]
        );
    }

    public function albums($response,$params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folder::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            print_r($_POST['checkbox']);
            File::delete_files($_POST['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            File::downloadFiles();
        }
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

    public function create($response,$params = null)
    {

        $file_dest = 'uploads/';
        $thumb_dest= 'uploads/thumbs/';
        //print_r($params);
        if (isset($_POST['submit_file']) || !empty($_FILES['files']['name'][0])) {
            $upload = new FileUpload($file_dest,$thumb_dest,$params);
        }
        header("Location: ".ADMIN.'files' );
    }
}

?>