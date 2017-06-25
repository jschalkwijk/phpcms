<?php

namespace Controller;

use CMS\Models\Controller\Controller;
use CMS\Models\Files\Folder;
use CMS\Models\Files\File;
use CMS\Models\Files\FileUpload;

class UploadsController extends Controller
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
        if (isset($_POST['submit']) || !empty($_FILES['files']['name'][0])) {
            $folder = Folder::create($_POST);
        }
        //TODO: now the folder is created upload the files.

        if (!empty($_FILES['files']['name'][0])) {
            $files = $_FILES['files'];
            /* for each file name in the array we store the key of the array and the corresponding value as
            position and name like [0] = file1.png, [1] = file2.png
            this loop will run 5 times, if 5 files are selected, 7 times is 7 etc.
            */
            $uploaded = array();
            $failed = array();
            $allowed = ['txt', 'jpg', 'jpeg', 'png', 'pdf', 'zip', 'mp4', 'mp3', 'doc', 'docx', 'odt', 'csv'];

            foreach ($files['name'] as $position => $file_name) {
                $file_tmp = $files['tmp_name'][$position];
                $file_size = $files['size'][$position];
                $file_error = $files['error'][$position];
                // from the filename rip the extension with explode at the dot.
                $file_ext = explode('.', $file_name);
                $file_ext = strtolower(end($file_ext));
                // if the extension is in the allowed array continue else error.
                if (in_array($file_ext, $allowed)) {
                    // if there is no error (0 stands for no error, 1 is a error)
                    if ($file_error === 0) {
                        if ($file_size <= 43500000) {
                            // create unique id so there will be no overwriting files on the server
                            $file_name_new = uniqid('', true) . '.' . $file_ext;
                            $thumb_name = 'thumb_' . $file_name_new;


                            // If upload paths contains 's etc we have to remove the \ (backslash) which is created automaticly.
                            // To insert the path in the Database we have to keep the \ (backslash) otherwise the query will fail.
                            // because we want to maybe insert a " ' " like in Person's Contacts, we need to change the ' to '', to escape the '. otherwise it will fail.
                            $destination = str_replace(array("\\","'"),array("","''"),$folder->path);
                            $thumb_path = str_replace(array("\\","'"),array("","''"),$folder->path.'/thumbs');
                            $user_id = $_SESSION['user_id'];
                            if(!empty($folder->get_id())) {
                                if(move_uploaded_file($file_tmp,$destination.'/'.$file_name_new)) {
                                    // add to uploade array, we dont use the new filename because thats all numbers,
                                    // we use the original file name, we store both the original and new file name to the DB.
                                    $uploaded[$position] = $file_name;
//                                    try {
//                                        $sql = "INSERT INTO files(name,type,file_name,thumb_name,album_id,date,path,thumb_path,user_id) VALUES(?,?,?,?,?,NOW(),?,?,?)";
//                                        echo $sql;
//                                        $query = $dbc->prepare($sql);
//                                        $query->execute([$file_name, $file_ext, $file_name_new, $thumb_name, $id, $destination, $thumb_path, $user_id]);;
//                                        return true;
//                                    } catch (\PDOException $e){
//                                        echo $e->getMessage();
//                                    }
                                } else {
                                    return false;
                                }
                            } else {
                                echo "<h1>Not uploaded</h1>";
                            }


//                            // move uploaded file to destination folder
//                            if ($this->uploadFile($file_tmp, $file_name, $file_ext, $file_name_new, $thumb_name, $position, $album_id)) {
//                                $uploaded[] = $file_name;
//                                if (!$this->createThumb($file_name_new, $thumb_name, $thumb_dest)) {
//                                    $failed[] = 'Thumbnails failed to be created';
//                                }
//                            } else {
//                                // add to failed array if it cant upload, etc.
//                                $failed[$position] = "{$file_name} failed to upload.";
//                            }
                        } else {
                            $failed[$position] = "{$file_name} is too large.";
                        }
                    } else {
                        $failed[$position] = "{$file_name} errored with code '{$file_error}'";
                    }
                } else {
                    $failed[$position] = "{$file_name} file extension '{$file_ext}' is not allowed";
                }
            }
        } else {
            $failed[] = "No file('s) selected";
        }
        if (!empty($uploaded)) {
            // show uploaded files from the array
            echo implode(",", $uploaded) . ' is added to the database.';
        }
        if (!empty($failed)) {
            echo implode(",", $failed);
        }
        if (!empty($errors)) {
            echo implode(",", $errors);
        }
    }
}

?>