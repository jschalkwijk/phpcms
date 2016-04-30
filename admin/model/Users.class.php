<?php

namespace model\users;
//// Get User key for encryption
//use \Defuse\Crypto\Crypto;
//use \Defuse\Crypto\Exception as Ex;
//
//class Users{

// Get User key for encryption
use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception as Ex;

class Users{

	private $id = 0;
	private $username;
	private $first_name;
	private $last_name;
	private $function;
	private $email;
	private $rights;
	private $approved;
	private $trashed;
	private $img_path;
	private $album_id;
	public $dbt;
	private $file_path = 'files/users/';
	private $thumb_path = 'files/thumbs/users/';

	public function __construct($username,$first_name,$last_name,$email,$function,$rights,$img_path = null,$album_id,$approved = null,$trashed = null,$dbt = null) {
		$this->username = $username;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->function = $function;
		$this->rights = $rights;
		$this->img_path = $img_path;
		$this->album_id = $album_id;
		$this->approved = $approved;
		$this->trashed = $trashed;
		$this->dbt= $dbt;
	}
	// setter function that set the assingned variables. Can only be accessed with these fynctions, vars themselfs are private.
	public function getUserName(){
		 return $this->username;
	}
	public function getFirstName(){
		 return $this->first_name;
	}
	public function getLastName(){
		 return $this->last_name;
	}
	public function getMail(){
		 return $this->email;
	}
	public function getFunction(){
		 return $this->function;
	}
	public function getRights(){
		 return $this->rights;
	}
	public function getApproved(){
		 return $this->approved;
	}
	public function getTrashed(){
		 return $this->trashed;
	}
	public function getID(){
		return $this->id;
	}
	public function getDbt(){
		return $this->dbt;
	}
	public function getUserImg(){
		return $this->img_path;
	}
	public function getAlbumID(){
		return $this->album_id;
	}
	public function setUserName($username){
		$this->username = $username;
	}
	public function setFirstName($first_name){
		$this->username = $first_name;
	}
	public function setLastName($last_name){
		$this->last_name = $last_name;
	}
	public function setEmail($email){
		 $this->email = $email;
	}
	public function setFunction($function){
		 $this->function = $function;
	}
	public function setRights($rights){
		 $this->rights = $rights;
	}
	public function setID($id){
		 $this->id = $id;
	}
	public function setDbt(){
		 $this->dbt;
	}
	public static function fetchUsers($dbt,$trashed) {
		$dbc = new DBC;
		$query = "SELECT * FROM ".$dbt." WHERE trashed = ".$trashed." ORDER BY user_id DESC";
		$data = mysqli_query($dbc->connect(),$query)or die ("Error connecting to server");
		$users = array();

		if(file_exists('././keys/Shared/shared.txt')){
				$shared_key = file_get_contents('././keys/Shared/shared.txt');
		} else {
			$errors[] = "Shared Encryption key could not be found!";
			return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
			exit();
		}

		while($row = mysqli_fetch_array($data)){
			$username = $row['username'];
			$first_name = Crypto::decrypt(Crypto::hexTobin($row['first_name']),$shared_key);
			$last_name = Crypto::decrypt(Crypto::hexTobin($row['last_name']),$shared_key);
			$email = Crypto::decrypt(Crypto::hexTobin($row['email']),$shared_key);
			$function = Crypto::decrypt(Crypto::hexTobin($row['function']),$shared_key);
			$rights = Crypto::decrypt(Crypto::hexTobin($row['rights']),$shared_key);
			$user = new users_Users(
				$username,
				$first_name,
				$last_name,
				$email,
				$function,
				$rights,
				$row['img_path'],
				$row['album_id'],
				$row['approved'],
				$row['trashed'],
				$dbt
			);
			$user->setID($row['user_id']);
			$users[] = $user;
		}
		return $users;
		$dbc-disconnect();
	}

	public static function fetchSingle($dbt,$id) {
		$dbc = new DBC;
		$query = "SELECT * FROM ".$dbt." WHERE user_id = $id";
		$data = mysqli_query($dbc->connect(),$query) or die("Error fetching single user");

	/*
	if(file_exists('././keys/User/'.$_SESSION['username'].'.txt')){
				$user_key = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt');
	} else {../../../../craft_server
		$errors[] = "Encryption key could not be found!";
		return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
		exit();
	}
*/
		if(file_exists('././keys/Shared/shared.txt')){
				$shared_key = file_get_contents('././keys/Shared/shared.txt');
		} else {
			$errors[] = "Shared Encryption key could not be found!";
			return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
			exit();
		}

		while($row = mysqli_fetch_array($data)){
			$username = $row['username'];
			$first_name = Crypto::decrypt(Crypto::hexTobin($row['first_name']),$shared_key);
			$last_name = Crypto::decrypt(Crypto::hexTobin($row['last_name']),$shared_key);
			$email = Crypto::decrypt(Crypto::hexTobin($row['email']),$shared_key);
			$function = Crypto::decrypt(Crypto::hexTobin($row['function']),$shared_key);
			$rights = Crypto::decrypt(Crypto::hexTobin($row['rights']),$shared_key);
			$user = new users_Users(
				$username,
				$first_name,
				$last_name,
				$email,
				$function,
				$rights,
				$row['img_path'],
				$row['album_id'],
				$row['approved'],
				$row['trashed'],
				$dbt
			);
			$user->setID($row['user_id']);
		}
		 return $user;
	}

	public function addUser($password,$password_again){
		$dbc = new DBC;
		$output_form = false;
		$errors = array();
		$messages = array();

		$username = mysqli_real_escape_string($dbc->connect(),trim($this->username));
		$query = "SELECT * FROM users WHERE username = '$username'";
		$data = mysqli_query($dbc->connect(),$query) or die("Error connecting to database");

		if(mysqli_num_rows($data) === 0){
			// Create user specific Encryption key.
			try {
			    $user_key = Crypto::createNewRandomKey();
			    $file = fopen('././keys/User/'.$username.'.txt','w');
			    fwrite($file, $user_key);
			    fclose($file);
			} catch (Ex\CryptoTestFailedException $ex) {
			    die('Cannot safely create a key');
			} catch (Ex\CannotPerformOperationException $ex) {
			    die('Cannot safely create a key');
			}
			// Get shared encryption key. This key is used to encrypt the user data that admins and backend users
			// need to see from one another. So the data is protected in the database, but accessesable for the baclkend users.
			if(file_exists('././keys/Shared/shared.txt')){
				$key = file_get_contents('././keys/Shared/shared.txt');
			} else {
				$errors[] = "Shared Encryption key could not be found!";
				return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
				exit();
			}

			try {
				$username = mysqli_real_escape_string($dbc->connect(),trim($this->username));
				$password = mysqli_real_escape_string($dbc->connect(),trim($password));
				$password_again = mysqli_real_escape_string($dbc->connect(),trim($password_again));
				$first_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->first_name)),$key));
				$last_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->last_name)),$key));
				$email = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->email)),$key));
				$function = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->email)),$key));
				$rights = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->rights)),$key));
				$token = md5(uniqid(mt_rand(),true));
			} catch (Ex\CryptoTestFailedException $ex) {
			    die('Cannot safely perform encryption');
			} catch (Ex\CannotPerformOperationException $ex) {
			    die('Cannot safely perform encryption');
			}

			# Create Folder
			$album_id = files_Folders::auto_create_folder($username,$this->file_path.$username,$this->thumb_path.$username,'Users');
			$contacts = files_Folders::auto_create_folder("$username"."\'s ".'Contacts',$this->file_path.$username.'/'.$username.'\'s '.'Contacts',$this->thumb_path.$username.'/'.$username.'\'s '.'Contacts','Users',$username);

			if(!empty($username) && !empty($password) && !empty($password_again)) {
				if($password === $password_again) {
					$encrypt = new Encrypt;
					$encrypted_password = $encrypt->password_encrypt($password);
					$query = "INSERT into users(username,password,first_name,last_name,email,function,rights,album_id,token) VALUES('$username','$encrypted_password','$first_name','$last_name','$email','$function','$rights',$album_id,'$token')";
					mysqli_query($dbc->connect(),$query) or die('Error connecting to database');
					$messages[] = '<p class="container">New user <strong>'.$username.'</strong> has been successfully added.';
				} else {
					$errors[] = 'Passwords do not match, please retype your password correctly.';
					$output_form = true;
				}
			} else {
				$errors[] = 'Fill in all the required fields';
				$output_form = true;
			}

			$dbc->disconnect();

			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		} else {
			$output_form = true;
			$errors[] = "Username already exists, please use another username.";
			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		}
	}

	public function editUser($id,$old_username,$new_password = null,$new_password_again = null){
		$dbc = new DBC;
		$output_form = false;
		$errors = array();
		$messages = array();

			/*
	if(file_exists('././keys/User/'.$_SESSION['username'].'.txt')){
				$user_key = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt'); 
	} else {
		$errors[] = "Encryption key could not be found!";
		return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
		exit();
	}
*/
		if(file_exists('././keys/Shared/shared.txt')){
				$shared_key = file_get_contents('././keys/Shared/shared.txt');
		} else {
			$errors[] = "Shared Encryption key could not be found!";
			return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
			exit();
		}

		$username = mysqli_real_escape_string($dbc->connect(),trim($this->getUserName()));
		$query = "SELECT * FROM users WHERE username = '$username'";
		$data = mysqli_query($dbc->connect(),$query) or die("Error connecting to database");

		if(mysqli_num_rows($data) === 0 || (mysqli_num_rows($data) === 1 && ($username === $old_username))){
			$this->setID($id);
			$user_id = $this->getID();
			$id = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim((int)$this->getID())),$shared_key));
			$username = mysqli_real_escape_string($dbc->connect(),trim($this->getUserName()));
			$first_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->getFirstName())),$shared_key));
			$last_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->getLastName())),$shared_key));
			$email = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->getMail())),$shared_key));
			$function = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->getFunction())),$shared_key));
			$rights = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc->connect(),trim($this->getRights())),$shared_key));
			$new_password = mysqli_real_escape_string($dbc->connect(),trim($new_password));
			$new_password_again = mysqli_real_escape_string($dbc->connect(),trim($new_password_again));

			// save edited object to database
			if ($_POST['confirm'] == 'Yes') {
				$query1 = "UPDATE users SET username = '$username',first_name = '$first_name',last_name = '$last_name',".
				"email = '$email',function = '$function',rights = '$rights' WHERE users.user_id = '$user_id'";
				echo $query1;
				mysqli_query($dbc->connect(), $query1) or die('Error connecting to database.');
				if(!empty($new_password) && !empty($new_password_again) )
					if($new_password === $new_password_again) {
						$encrypt = new Encrypt;
						$encrypted_password = $encrypt->password_encrypt($new_password);
						$query2 = "UPDATE users SET password = '$encrypted_password' WHERE users.user_id = '$id'";
						mysqli_query($dbc->connect(), $query2) or die('Error connecting to database.');
						$messages[] = 'You password has been successfully changed.';
					} else {
					$errors[] = 'Passwords do not match, please retype your password correctly.';
					$output_form = true;
				}
				$dbc->disconnect();
				// Confirm success with the user
				$messages[] =  '<p>The user <strong>' . $username. '</strong> was successfully edited.';
			}
			else {
			 	$errors[] = '<p class="error">The user was not edited.</p>';
			 	$output_form = true;
			}
			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		} else {
			$output_form = true;
			$errors[] = "Username already exists, please use another username.";
			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		}
	}

	public static function addProfileIMG($file_dest,$thumb_dest,$params){
		$upload = new files_FileUpload($file_dest,$thumb_dest,$params,true);
		$img_path = $upload->getImgPath();
		$thumb_path = $upload->getThumbPath();
		$dbc = new DBC;
		$query = "UPDATE users SET img_path = '$thumb_path' WHERE user_id = $params[0]";
		mysqli_query($dbc->connect(),$query) or die ('Error updating user profile image');
		$dbc->disconnect();
	}
}
?>