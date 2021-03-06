<?php
namespace CMS\Models\Actions;

use CMS\Models\DBC\DBC;
use ZipArchive;

// Used by files_File and File_Folders
trait FileActions{
	// used in File_Folders:
	public static function deleteRecursive($parents){
		$db = new DBC;
		$dbc = $db->connect();

		//(!empty($row['folder_id']))? $folders_id = [$id,$row['folder_id']] : $folders_id = [$id];
		// checks if the row from the db is not empty,
		// if not, selects the id to the parent id,row[id], so we can get,
		// all children from the top deleted album.
		/* Example:
		 * $id = 5 (Folder Users)
		 * $row[folder_id] = 24 ( user admin has parent_id 5, the folder Users)
		 * Then again we check if there are folders with a parent_id of 24
		 * if there is, add it to the array of folder_id's to delete.
		 * In this case there is.
		 * $row['folder_id'] = 22 (admins contacts folder) has a parent_id of 24
		 * This goes on until there are no folders left with a parent_id of 22 in this case.
		*/
        $parents = is_array($parents) ? $parents : [$parents];
		$folders = array();
		foreach ($parents as $parent){
			$folders[] = $parent;
		}
		
		while(sizeof($parents) > 0){
			$placeholders = substr(str_repeat("?, ",count($parents)),0,-2);
			try{
                $sql = "SELECT folder_id FROM folders WHERE parent_id IN ({$placeholders})";
                echo $sql."<br />";
                $query = $dbc->prepare($sql);
				$query->execute($parents);
				$data = $query->fetchAll();
				// because we now have a new row[folder_id], we need to check again if its empty,
				// if it is not, push it to the array.
				//if it is, don't push it, en the loop will end with the while clause.
				$parents = array();
				foreach($data as $row){
						// For each rows doen! multiple albims ids might be returned
						$folders[] = $row['folder_id'];
						$parents[] = $row['folder_id'];
				}
				$placeholders = substr(str_repeat("?, ",count($parents)),0,-2);
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
		}

		// Create s string with all the album id's.
		$multiple = implode(",",$folders);
		// deleting rows from database.
		$dbc->query("DELETE FROM folders WHERE folder_id IN({$multiple})");
		$dbc->query("DELETE FROM files WHERE folder_id IN({$multiple})");

		$db->close();
	}

	/* used in files_Files:
	 * Receives checkbox input with file_id's
	*/
	static public function delete_files($checkbox){
		$db = new DBC;
		$dbc = $db->connect();

		$multiple = implode(",",$checkbox);
		/*
		 * The part where we delete the files
		 * REMEMBER! This part always has to come before the actual record deletion because if you delete the records first,
		 * there is a risk that the query doesn't know what records to select simply because they are not there anymore
		*/
        try {
		    $query = $dbc->query("SELECT file_id,path,thumb_path FROM files WHERE file_id IN({$multiple})");
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            $result = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
		// It's as easy as this:

		// Deleting files.
		// You cant us aforeach with the mysqli oop approach: http://stackoverflow.com/questions/20190760/error-illegal-string-offset-in-php
		foreach($result as $delete) {
			unlink($delete['path']);
			unlink($delete['thumb_path']);
		}
		// Deleting records
		$dbc->query("DELETE FROM files WHERE file_id IN({$multiple})");
		$db->close();
//		header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&album='.$_GET['album']);
	}

	// used in files_Files:
	public static function downloadFiles(){
		$db = new DBC;
		$dbc = $db->connect();

		$checkbox = $_GET['checkbox'];
		$multiple = implode(",",$checkbox);
		try {
            $query = $dbc->query("SELECT path FROM files WHERE file_id IN({$multiple})");
            $data = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
		$file_download = array();
		$zip = new ZipArchive();
		$zip_name = 'files-'.time().".zip";
		$zip->open($zip_name,ZipArchive::CREATE);

        foreach ($data as $file) {
			if(file_exists($file['path'])){	
				//$zip->addFile($file['path'],$file['path']);
				$zip->addFromString(basename($file['path']), file_get_contents($file['path']));
			} else  {
				echo 'File does not exist.';
			}
		}
		$zip->close();
		
		// set header for download.
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zip_name);
		//header('Content-Length: '.filesize($zip_name));
		// file must be read to download.
		readfile($zip_name);
		
		// remove zip
		if(file_exists($zip_name)){
			unlink($zip_name);
		}
		
		$db->close();
	}

	/* used in File_Folders:
	 * Recursively delete folders from the server.
	*/
	public static function removeDir($path) {
	 	$files = glob($path . '/*');
		foreach ($files as $file) {
			is_dir($file) ? self::removeDir($file) : unlink($file);
		}
		rmdir($path);
		return;
		}
}
?>