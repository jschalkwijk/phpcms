<?php
namespace Jorn\admin\model\Actions;

use Jorn\admin\model\DBC\DBC;
use ZipArchive;
// Used by files_File and File_Folders
trait FileActions{
	// used in File_Folders:
	public static function removeRows($id){
		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->prepare("SELECT album_id FROM albums WHERE parent_id = ?");
		if($query) {
			$query->bind_param("i", $id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
			$row = $data->fetch_array();
		} else {
			$db->sqlERROR();
		}
		$folders_id = array();

		(!empty($row['album_id']))? $folders_id = [$id,$row['album_id']] : $folders_id = [$id];
		// checks if the row from the db is not empty,
		// if not, selects the id to the parent id,row[id], so we can get,
		// all children from the top deleted album.
		/* Example:
		 * $id = 5 (Folder Users)
		 * $row[album_id] = 24 ( user admin has parent_id 5, the folder Users)
		 * Then again we check if there are folders with a parent_id of 24
		 * if there is, add it to the array of folder_id's to delete.
		 * In this case there is.
		 * $row['album_id'] = 22 (admins contacts folder) has a parent_id of 24
		 * This goes on until there are no folders left with a parent_id of 22 in this case.
		*/
		while(!empty($row['album_id'])){
			$id = $row['album_id'];
			$query = $dbc->prepare("SELECT album_id FROM albums WHERE parent_id = ?");
			if($query) {
				$query->bind_param("i", $id);
				$query->execute();
				$data = $query->get_result();
				$query->close();
				$row = $data->fetch_array();
				// because we now have a new row[album_id], we need to check again if its empty,
				// if it is not, push it to the array.
				//if it is, don't push it, en the loop will end with the while clause.
				
				/* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				if(!empty($row['album_id'])){
					// if there are more then one child,tehre are more rows, with more album_id's to add
					while ($row = $data->fetch_array()) {
						$folders_id[] = $row['album_id'];
					}
				}
				*/
				if(!empty($row['album_id'])){
					$folders_id[] = $row['album_id'];
				}
			} else {
				$db->sqlERROR();
			}
		}

		// Create s string with all the album id's.
		$multiple = implode(",",$folders_id);
		// deleting rows from database.
		$del_albums = $dbc->query("DELETE FROM albums WHERE album_id IN({$multiple})");
		$del_files = $dbc->query("DELETE FROM files WHERE album_id IN({$multiple})");

		$dbc->close();
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
		$query = $dbc->query("SELECT file_id,path,thumb_path FROM files WHERE file_id IN({$multiple})");
		if(!$query){ $db->sqlERROR(); };
		// It's as easy as this:

		// Deleting files.
		// You cant us aforeach with the mysqli oop approach: http://stackoverflow.com/questions/20190760/error-illegal-string-offset-in-php
		while ($delete = $query->fetch_array()) {
			unlink($delete['path']);
			unlink($delete['thumb_path']);
		}
		// Deleting records
		mysqli_query ($dbc,"DELETE FROM files WHERE file_id IN({$multiple})" );
		$dbc->close();
//		header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&album='.$_GET['album']);
	}

	// used in files_Files:
	public static function downloadFiles(){
		$db = new DBC;
		$dbc = $db->connect();

		$checkbox = $_GET['checkbox'];
		$multiple = implode(",",$checkbox);
		$query = $dbc->query("SELECT path FROM files WHERE file_id IN({$multiple})");
		if(!$query){ $db->sqlERROR(); };
		// It's as easy as this:
		$data = $query->fetch_array();

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
		
		$dbc->close();
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