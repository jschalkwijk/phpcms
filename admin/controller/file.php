<?php

use CMS\model\Controller\Controller;
use CMS\model\File\Folders;
use CMS\model\File\File as F;

class File extends Controller
{

    public function index($params = null)
    {
        if (isset($_GET['delete-albums'])) {
            Folders::delete_album($_GET['checkbox']);
        }
        if (isset($_GET['delete'])) {
            F::delete_files($_GET['checkbox']);
        }
        $this->view(
            'Albums',
            [
                'files/add-files.php',
                'files/albums.php'
            ],
            $params
        );
    }

    public function albums($params = null)
    {
        if (isset($_GET['delete-albums'])) {
            Folders::delete_album($_GET['checkbox']);
        }
        if (isset($_GET['delete'])) {
            F::delete_files($_GET['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            F::downloadFiles();
        }
        $this->view(
            'Albums',
            [
                'files/add-files.php',
                'files/albums.php'
            ],
            $params,
            ['js' => [JS . 'checkAll.js']]
        );
    }
}

?>