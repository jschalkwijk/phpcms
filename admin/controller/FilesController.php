<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Files\Folders;
use CMS\Models\Files\File;

class Files extends Controller
{

    public function index($params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            File::delete_files($_POST['checkbox']);
        }
        $files = File::allWhere(['album_id' => $params[0]]);

        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params,
            ['files' => $files]
        );
    }

    public function albums($params = null)
    {
        if (isset($_POST['delete-albums'])) {
            Folders::delete_album($_POST['checkbox']);
        }
        if (isset($_POST['delete'])) {
            File::delete_files($_POST['checkbox']);
        }
        if (isset($_GET['download_files'])) {
            File::downloadFiles();
        }
        $files = File::allWhere(['album_id' => $params[0]]);
        $this->view(
            'Albums',
            [
                'files/albums.php'
            ],
            $params,
            ['files' => $files],
            ['js' => [JS . 'checkAll.js']]
        );
    }
}

?>