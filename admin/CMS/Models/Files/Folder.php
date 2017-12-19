<?php
namespace CMS\Models\Files;

use CMS\Core\FileSystem\FileSystem;
use CMS\Core\Model\Model;
use CMS\Models\Categories\Categories;
use CMS\Models\DBC\DBC;
use CMS\Models\Actions\FileActions;
use CMS\Models\Products\Product;

/*
 * als ik in een user of product foto's moet uploaden kan ik toch ook in de user table een
 * folder_id row aanmaken, zodat ik meteen het pad van de gelinkte folder kan fetchen.

 * */
class Folder extends Model {
	use FileActions;

	protected $primaryKey = 'folder_id';
	public $table = 'folders';

	protected $relations = [
		'users' => 'user_id'
	];

	// either use the joins var to join in related tables or use the relationsship functions.
	// I would use join if you just need a few row items. Only use the relation functions if you
	// really need all of it. To just display a category name for example just join that value.
	protected $joins = [
		'users' => ['username']
	];

	protected $allowed = [
		'name',
		'description',
		'parent_id',
		'type',
		'path',
		'user_id',
	];

	public function setID($id){
		$this->folder_id = $id;
	}
	public function get_id(){
		return $this->folder_id;
	}

	#Relations
	public function files()
	{
		return $this->owns('CMS\Models\Files\File');

	}

    public function products()
    {
		return $this->ownsThrough(Product::class,Categories::class);
    }

//    public function category()
//    {
//        return $this->ownedBy('CMS\Models\Categories\Categories','category_id','category_id');
//    }

	/**
	 * @param array $r
	 * @param null $alternativePath
	 * @return Folder
     */
	public static function create(array $r, $alternativePath = null): Folder {
		$create = false;
		$folder = new Folder($r);
		$folder->user_id = $_SESSION['user_id'];;
		if(!isset($r['parent']) && !empty($r['name'])) {
			$create = true;
			if($alternativePath != null){
				$folder->path = $alternativePath.'/'.$folder->name;
			} else {
				$folder->path = "uploads/".$folder->name;
			}
		} else if(!isset($r['parent']) && empty($r['name']) && $r['destination'] == 0){
			header("Location: ".ADMIN."files");
		}
		// if: upload to destination folder instead of current parent folder.
		// else: upload to new folder inside the destination folder.
		if($r['destination'] != 0 && !empty($r['name']) ){
			$create = true;
			$parent = Folder::one($r['destination']);
			$folder->path = $parent->path.'/'.$folder->name;
			$folder->parent_id = $parent->get_id();
		} else if($r['destination'] != 0 && empty($r['name'])){
			$folder = Folder::one($r['destination']);
		} else if($r['destination'] == 0 && empty($r['name']) && isset($r['parent'])){
			$folder = Folder::one($r['parent']);
		}  else if($r['destination'] == 0 && isset($r['parent']) && !empty($r['name'])){
			$create = true;
			$parent = Folder::one($r['parent']);
			$folder->path = $parent->path.'/'.$folder->name;
			$folder->parent_id = $parent->get_id();
		}


		if ($create) {
			$result = (new FileSystem)->makeDirectory($folder->path.'/thumbs', 0775,true);
			if ($result) {
				$folder->save();
				$folder->setID($folder->lastInsertId);
			}
		}
		return $folder;
	}

	/* used by view/add-files.php to get the selected folder and optional folders to
	 * upload files to.
	 * Main folders don't have a parent folder so the parent_id = 0.
	 * If the folder_id != empty we search for it's child folders and put them in the form to select.
	 * */
	public static function get_albums($folder_id,$album_name) {
		$db = new DBC;
		$dbc = $db->connect();

		($folder_id != null) ? $id = trim((int)$folder_id) : $id = 0;
		try {
            $album_query = $dbc->prepare("SELECT folder_id,name FROM folders WHERE parent_id = ? OR folder_id = ?");
            $album_query->execute([$id, $id]);
			$albums_data = $album_query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
		echo '<select id="albums" name="folder_id">';
		if(!isset($album_name)){
			echo '<option value="0">None</option>';
		}
		foreach($albums_data as $row){
			echo '<option value="' . $row['folder_id'] . '">' . $row['name'] . '</option>';
		}

		// Join to select all products with matching folder_id's
		//$album_query = "SELECT folder_id FROM products WHERE product_id = $id ";
//		$album_query = "SELECT products.*, albums.name as name FROM products LEFT JOIN albums ON products.folder_id = albums.folder_id WHERE albums.folder_id = $id";
//		echo $album_query;
//		$albums_data = mysqli_query($dbc->connect(),$album_query) or die("Error connecting to database");
//		echo '<select id="albums" name="album_name">';
//		if(!isset($album_name)){
//			echo '<option name="none" value="None">None</option>';
//		} else {
//			while($row = mysqli_fetch_array($albums_data)) {
//				echo '<option value="'.$row['folder_id'].'">'.$row['name'].'</option>';
//			}
//		}

		echo '</select>';
		echo '<label for="select">Albums</label>';
		$db->close();
	}

	// Deletes the albums selected. Makes use of the removeDir and removeRows Traits.
	public static function delete_album($checkbox){
		$db = new DBC;
		$dbc = $db->connect();

		// get values from the checkboxes, these are the ID's of the Albums or subfolders.
		$multiple = implode(",",$checkbox);
		try {
        $query = $dbc->query("SELECT folder_id,path,name FROM folders WHERE folder_id IN({$multiple})");
        $data = $query->fetchAll();
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

		foreach($data as $row) {
			// recursive deleting function. Deletes al folders/files and subfolders/files from server.
			Folder::removeDir('./././'.$row['path']);
			Folder::removeDir('./././'.$row['path'].'/thumbs');
		}
		Folder::removeRows($checkbox);
		$db->close();
	}
	
	/*
	 *	we use this function in a slightly different way then in the file_upload class because we cant
	 *	make use of the GET params here since we are not in the main folder structure but in the product creation.
	*/
	public static function auto_create_folder($album_name,$file_dest,$thumb_dest,$main_folder,$category_name = null) {
		$db = new DBC;
		$dbc = $db->connect();

		$author = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		
		/*
		 *	if there is no category set, we don't pass the categories id and use the default products folder.
		 *	for insurance we have to check its id and use that as the parent folder.
		 *	if it is set, it means we have created a category already and the folders parent should be the category folder.
		*/
		if($category_name === null){
			try {
                $query = $dbc->prepare("SELECT folder_id FROM folders WHERE name = ?");
				$query->execute([$main_folder]);
				$row = $query->fetch();
				$parent_id = $row['folder_id'];
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
		} else {
			$sql = "SELECT folder_id FROM folders WHERE name = ?";
			echo $sql.'<br />';
			try {
                $query = $dbc->prepare($sql);
                $query->execute([$category_name]);
				$row = $query->fetch();
				$parent_id = $row['folder_id'];
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
		}
	
		if(!file_exists($file_dest)){
			try {
                $sql = "INSERT INTO folders(name,author,parent_id,path,user_id) VALUES(?,?,?,?,?)";
                echo 'file dest: '.$file_dest.'<br />';
                echo 'thumbs dest: '.$file_dest.'/thumbs'.'<br />';
                $query = $dbc->prepare($sql);
                $query->execute([$album_name,$author,$parent_id,$file_dest,$user_id]);
				mkdir($file_dest,0744);
				mkdir($file_dest.'/thumbs',0744);
            } catch (\PDOException $e){
                echo $e->getMessage();
            }
		}
		
		try {
            $query = $dbc->prepare("SELECT folder_id FROM folders WHERE name = ?");
            $query->execute([$album_name]);
			$row = $query->fetch();
			$folder_id = $row['folder_id'];
        } catch (\PDOException $e){
            echo $e->getMessage();
        }

		$db->close();
		return $folder_id;
	}
	
	// we use this function in a slightly different way then in the file_upload class because we cant
	// make use of the GET params here since we are not in the main folder structure but in the product/user creation.
	public static function auto_create_path($album_name,$parent_id){
		$db = new DBC;
		$dbc = $db->connect();

        try {
		    $query = $dbc->prepare("SELECT path FROM folders WHERE folder_id = ?");
			$query->execute([$parent_id]);
			$row = $query->fetch();
			if($row['path'] === $album_name){
				$path = $album_name;
			} else {
				$path = $row['path'].'/'.$album_name;
			}
            $db->close();
			return $path;
        } catch (\PDOException $e){
            $db->close();
            echo $e->getMessage();
            return false;
        }
	}
    // Rename Recursively
    public function renameRecursion(Array $children,Folder $destination)
    {
        foreach ($children as $folder) {
            $new_path = $destination->path.'/'.$folder->name;
            
//                    $folder->patch($_POST);
            $folder->patch(['path' => $new_path])->savePatch();
            $files = File::allWhere(['folder_id' => $folder->get_id()]);
            foreach ($files as $file) {
                $name = $file->file_name;
                $file->patch(['path' => $new_path.'/'.$name,'thumb_path' => $new_path.'/thumbs/'.$name])->savePatch();
            }

            if (!empty($folder->children())) {
                $this->renameRecursion($folder->children(),$folder);
            }
        }
    }

}

?>