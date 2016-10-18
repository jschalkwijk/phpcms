<?php
namespace CMS\model\Content\Pages;

use CMS\model\Content\Content;
use CMS\model\DBC\DBC;

class Page extends Content{
	private $url;
	
	public function __construct($title,$description,$category = null,$content,$author,$dbt = null,$date = null,$approved = null,$trashed = null){
		parent::__construct($title,$description,$category,$content,$author,$dbt = null,$date = null,$approved = null,$trashed = null);
		// add own variables.
		//$this->url = $url;
	}

	public function addPage($back,$front,$sub_page,$id = null){
		$db = new DBC;
		$dbc = $db->connect();
		$output_form = false;
		$messages = array();

		$this->setID($id);

		$id = mysqli_real_escape_string($dbc,trim((int)$this->getID()));
		$page_title = mysqli_real_escape_string($dbc,trim($this->title));
		$page_desc = mysqli_real_escape_string($dbc,trim($this->description));
		$page_content = mysqli_real_escape_string($dbc,trim($this->content));
		$author = $this->author;
		$tpl_URL = mysqli_real_escape_string($dbc,trim($_POST['template']));
		$author = $_SESSION['username'];

		if(empty($page_content)) {
			$messages[] = 'Add content';
			$output_form = true;
		}
		if(empty($page_title)) {
			$messages[] =  'Add a title';
			$output_form = true;
		}
		if(empty($page_title) && empty($page_content)) {
			$messages[] =  'Add a title and content';
			$output_form = true;
		}
		if(empty($front) && empty($back)){
			$messages[] = "You have to specify if it's a front- or back-end page";
			$output_form = true;
		}

		if (!empty($page_title) && !empty($page_content) && (!empty($front) || !empty($back) )) {

			$query = $dbc->prepare("INSERT INTO pages(title,description,content,author,date) VALUES(?,?,?,?,NOW())");
			if ($query) {
				$query->bind_param("ssss",$page_title,$page_desc,$page_content,$author);
				$query->execute();
				$query->close();
				//save content to database, and then call back the data to display on the new page
			} else {
				$db->sqlERROR();
			}
			$query2 = $dbc->prepare("SELECT page_id FROM pages WHERE title = ?");
			if($query2){
				$query2->bind_param("s",$page_title);
				$query2->execute();
				$page = $query2->get_result();
				$query2->close();
			} else {
				$db->sqlERROR();
			}

			while ($row = $page->fetch_array()) {
				$pageID = $row['page_id'];
				// This placeholder needs to be placed in the template so we can replace it.

				$placeholder1 = "{title}";
				$placeholder2 = "{content}";
				// creates a string from the file
				if (empty($_POST['template'])) {
					$controller = file_get_contents('empty-controller.php');
				} else {
					$template = file_get_contents('view/custom/'.$tpl_URL);
				}

				if(!empty($front)){
					$ctrlPath = "../controller/";
				}
				if(!empty($back)){
					$ctrlPath = "controller/";
				}


				if($sub_page != 'None'){
					$create_path = $this->create_path($sub_page,$page_title);
					$path = $create_path['path'];
					$parent_id = $create_path['parent_id'];
					$fh = fopen($ctrlPath.$create_path['controller'].'.php', 'r+');
					$method = file_get_contents('empty-method.php');
					$new_method = str_replace([$placeholder1,$placeholder2],[$page_title,$pageID],$method);
					fseek($fh, -4, SEEK_END);
					fwrite($fh, $new_method);
					fclose($fh);
				} else {
					$path = $page_title;
					/*Creates a new string called new_page which containes the content of single-page.php
						 and replaces the placeholder with the created ID above where the content is stored.
					*/
					$parent_id = 0;
					$new_ctrl = str_replace([$placeholder1,$placeholder2],[$page_title,$pageID],$controller);
					// new file name
					$new_ctrl_name = $page_title.'.php';
					// file procces, open new file which is named like the posted pageURl.
					$fp = fopen($ctrlPath.$new_ctrl_name,"w");
					// write the new file with the content string. this adds it to admin folder, how to place in root folder?
					fwrite($fp,$new_ctrl);
					fclose($fp);
				}

				// add link to page table for the menu
				$query3 = $dbc->prepare("UPDATE pages SET path = ?,parent_id = ? WHERE pages.page_id = ?");
				if($query3) {
					$query3->bind_param("sii", $path, $parent_id, $pageID);
					$query3->execute();
					print_r($query3);
				} else {
					$db->sqlERROR();
				}

				$messages[] = 'The new page had been created, please approve the page before displaying it';
			}
		}

		$dbc->close();

		return ['output_form' => $output_form,'messages' => $messages];
	}
	public static function getSelection($selected_cat) {
		$db = new DBC;
		$dbc = $db->connect();

		$categories = array();

		$query = $dbc->query("SELECT * FROM pages WHERE trashed = 0");
		(!$query) ? $db->sqlERROR() : "";

		while($row = $query->fetch_array()) {
			if($selected_cat == $row['title'] ) {
				echo '<option value="'.$row['page_id'].'" selected="selected">'.$row['title'].'</option>';
			} else {
				echo '<option value="'.$row['page_id'].'">'.$row['title'].'</option>';
			}
		}
	}

	public static function create_path($page_id,$page_title){
		$db = new DBC;
		$dbc = $db->connect();
		$query = $dbc->prepare("SELECT title,page_id,path FROM pages WHERE page_id = ?");
		if($query) {
			$query->bind_param("i", $page_id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
			$row = $data->fetch_row();
			$parent_title = $row['title'];
			$page_id = $row['page_id'];
			$path = $row['path'] . '/' . $page_title;
		} else {
			$db->sqlERROR();
		}
		$dbc->close();

		return ['controller' => $parent_title,'parent_id' => $page_id,'path' => $path];
	}

	public static function deletePage($multiple){
		$db = new DBC;
		$dbc = $db->connect();
		$query = $dbc->prepare("SELECT p1.*,p2.title as pname FROM pages p1 INNER JOIN pages p2 ON p1.parent_id = p2.page_id WHERE p1.page_id IN({$multiple})");
		if($query) {
			$query->execute();
			$data = $query->get_result();
			$query->close();
			if($data->num_rows == 0){
				// if num rows is zero, do normal query to the page to delete the title. because otherwise we wont get it.
				$query = $dbc->prepare("SELECT * FROM pages WHERE page_id IN({$multiple})");
				if($query) {
					$query->execute();
					$data = $query->get_result();
					$query->close();
				} else {
					$db->sqlERROR();
				}
			}
		} else {
			$db->sqlERROR();
		}

		while ($row = $data->fetch_array()) {
			if ($row['parent_id'] != 0 && $row['parent_id'] != $row['page_id']) {
				print_r($row['pname']);
				//read controller
				$controller = file_get_contents('./././controller/'.$row['pname'].'.php');
				// read delete-method format
				$method = file_get_contents('./././delete-method.txt');
				//replace tags with method specific content
				$title_tag = "{title}";
				$contentID_tag = "{content}";
				$delete_method = str_replace([$title_tag, $contentID_tag], [$row['title'], $row['page_id']], $method);
				//replace method with empty string
				$updated_controller = str_replace($delete_method, "", $controller);
				// remove empty lines after deleting the method.
				$updated_controller = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $updated_controller);
				//write the new string to controller file
				file_put_contents('./././controller/'.$row['pname'].'.php', $updated_controller);
				$message = 'The sub-page of '.$row['pname'].', with title: '.$row['title'].' is deleted permanently';
				$flag = true;
			} else {
				if (unlink('./././controller/'.$row['title'].'.php')) {
					if ($row['parent_id'] == 0) {
						$page_id = $row['page_id'];
						$query = $dbc->prepare("DELETE FROM pages WHERE parent_id = ?");
						if($query) {
							$query->bind_param("i", $page_id);
							$query->execute();
							print_r($query);
							$query->close();
						} else {
							$db->sqlERROR();
						}
					}
					$message = 'The main page with title: '.$row['title'].' is deleted permanently';
					$flag = true;
				} else {
					$message = 'Oops, something went wrong, the page is deleted from the filesystem but NOT deleted from the database, please contact your administrator.';
					$flag = false;
				}
			}
		}
		$dbc->close();
		return ['message' => $message, 'flag' => $flag];
	}
}
?>