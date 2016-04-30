<?php
// Used by files_File and files_Folders
trait files_FileActions{
	// used in files_Folders:
	public static function removeRows($id){
		$dbc = new DBC;
		$query = "SELECT album_id FROM albums WHERE parent_id = $id";
		$data = mysqli_query($dbc->connect(),$query);
		$row = mysqli_fetch_array($data);
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
			$query = "SELECT album_id FROM albums WHERE parent_id = $id";
			$data = mysqli_query($dbc->connect(),$query);
			$row = mysqli_fetch_array($data);
			// because we now have a new row[album_id], we need to check again if its empty,
			// if it is not, push it to the array.
			//if it is, don't push it, en the loop will end with the while clause.
			if(!empty($row['album_id'])){
				$folders_id[] = $row['album_id'];
			}	
		}

		// Create s string with all the album id's.
		$multiple = implode(",",$folders_id);
		// deleting rows from database.
		$del_albums = "DELETE FROM albums WHERE album_id IN({$multiple})";
		$del_files = "DELETE FROM files WHERE album_id IN({$multiple})";
		mysqli_query ($dbc->connect(),$del_albums) or die('Error connecting to database');
		mysqli_query ($dbc->connect(),$del_files) or die('Error connecting to database');
		$dbc->disconnect();
	}

	/* used in files_Files:
	 * Receives checkbox input with file_id's
	*/
	static public function delete_files($checkbox){
		$dbc = new DBC;
		$multiple = implode(",",$checkbox);
		/*
		 * The part where we delete the files
		 * REMEMBER! This part always has to come before the actual record deletion because if you delete the records first,
		 * there is a risk that the query doesn't know what records to select simply because they are not there anymore
		*/
		$query = "SELECT file_id,path,thumb_path FROM files WHERE file_id IN({$multiple})";
		// It's as easy as this:
		$data = mysqli_query($dbc->connect(), $query) or die('Error connecting to database.');
		// Deleting files.
		foreach ($data as $delete) {
			unlink($delete['path']);
			unlink($delete['thumb_path']);
		}
		// Deleting records
		mysqli_query ($dbc->connect(),"DELETE FROM files WHERE file_id IN({$multiple})" );
		$dbc->disconnect();
		header('Location: '.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&album='.$_GET['album']);
	}

	// used in files_Files:
	public static function downloadFiles(){
		$dbc = new DBC;
		$checkbox = $_GET['checkbox'];
		$multiple = implode(",",$checkbox);
		$query = "SELECT path FROM files WHERE file_id IN({$multiple})";
		// It's as easy as this:
		$data = mysqli_query($dbc->connect(), $query) or die('Error connecting to database.');
		$file_download = array();
		$zip = new ZipArchive();
		$zip_name = 'Craft-files-'.time().".zip";
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
		
		$dbc->disconnect();
	}

	/* used in files_Folders:
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