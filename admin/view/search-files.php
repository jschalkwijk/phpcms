<?php
	use CMS\model\File\File;
	use CMS\model\File\FileWriter;
?>

<form class="search"action="<?= ADMIN."search"; ?>" method="post">
	<input type="text" name="search" placeholder="Search files"/>
	<button type="submit" name="search-file">Search</button>
</form>
<?php
	$dbc = new DBC;
	$output = '';
	$doc = ['txt','doc','docx','odt'];
	$img = ['jpg','jpeg','png'];
	if(isset($_POST['search-file'])){
		$searchq = mysqli_real_escape_string($dbc->connect(),trim($_POST['search']));
		// replaces everything that is not a number or letter with nothing
		$searchq = preg_replace("#[^0-9a-z]#i"," ",$searchq);
		$searchTerms = explode(' ', $searchq);
		$searchTermBits = array();
		foreach ($searchTerms as $term) {
			$term = trim($term);
			if (!empty($term)) {
				$searchTermBits[] = "name LIKE '%$term%'";
			}
		}
		echo '<form method="get" action="'.ADMIN.'search">';
					$files = File::fetchFilesBySearch($searchTermBits,0);
					FileWriter::write($files,ADMIN.'view/singleFile.php',$doc,$img);
				echo '<div class="left">';
					echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
					echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';	
				echo '</div>';
			echo '</form>';
	}
	if (isset($_GET['delete'])) {
		// gets id's as string or array?
		$checkbox = $_GET['checkbox'];
		$multiple = implode(",",$checkbox);
		// The part where we delete the files
		// REMEMBER! This part always has to come before the actual record deletion because if you delete the records first, 
		// there is a risk that the query doesn't know what records to select simply because they are not there anymore ;)
		$query = "SELECT file_name,album_name FROM files WHERE id IN({$multiple})";
		// It's as easy as this:
		$data = mysqli_query($dbc->connect(), $query);
		foreach ( $data as $delete) {
			unlink(FILES.$delete['album_name'].'/'.$delete['file_name']);
			unlink(FILES.'/thumbs/'.$delete['album_name'].'/'.'thumb_'.$delete['file_name']);
		}
		// This would do the job
		mysqli_query ($dbc->connect(),"DELETE FROM files WHERE id IN({$multiple})" );
		$dbc->disconnect();
	}
	
?>