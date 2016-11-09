<?php

use CMS\Models\Controller\Controller;
use CMS\Models\File\Folders;
use CMS\Models\File\File as F;

class File extends Controller
{

    public function index($params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            F::delete_files($_POST['checkbox']);
        }
        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params
        );
    }

    public function albums($params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            F::delete_files($_POST['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            F::downloadFiles();
        }
        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params,
            ['js' => [JS . 'checkAll.js']]
        );
    }
}

?>