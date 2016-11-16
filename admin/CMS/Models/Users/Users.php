<?php

namespace CMS\Models\Users;
//// Get User key for encryption
//use \Defuse\Crypto\Crypto;
//use \Defuse\Crypto\Exception as Ex;
//
//class Users{

// Get User key for encryption
use CMS\Core\Model\Model;
use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception as Ex;
use \CMS\Models\DBC\DBC;
use CMS\Models\File\Folders;
use CMS\Models\File\FileUpload;

class Users extends Model{

	private $file_path = 'files/users/';
	private $thumb_path = 'files/thumbs/users/';

	protected $primaryKey = 'user_id';

	public $table = 'users';

	protected $relations = [
		'users' => 'user_id'
	];

	protected $joins = [
	];

	protected $allowed = [
		'username',
		'first_name',
		'last_name',
		'email',
		'function',
		'rights',
		'password',
	];

	protected $encrypted = [
		'first_name',
		'last_name',
		'email',
		'function',
		'rights',
	];


//	// setter function that set the assingned variables. Can only be accessed with these fynctions, vars themselfs are private.
//	public function getUserName(){
//		return $this->username;
//	}
	public function firstName(){
		if(isset($this->first_name)) {
			return $this->decrypt($this->first_name);
		} else {
			return false;
		}
	}
	public function lastName(){
		if(isset($this->last_name)) {
			return $this->decrypt($this->last_name);
		} else {
			return false;
		}
	}
	public function mail(){
		if(isset($this->email)) {
			return $this->decrypt($this->email);
		} else {
			return false;
		}
	}
	public function position(){
		if(isset($this->function)) {
			return $this->decrypt($this->function);
		} else {
			return false;
		}
	}
	public function rights(){
		if(isset($this->rights)) {
			return $this->decrypt($this->rights);
		} else {
			return false;
		}
	}
	public function get_id(){
		return $this->user_id;
	}
	protected function encrypt($value){
		if(file_exists('././keys/Shared/shared.txt')){
			$shared_key = file_get_contents('././keys/Shared/shared.txt');
			return Crypto::binTohex(Crypto::encrypt(trim($value),$shared_key));
		} else {
			echo "Shared Encryption key could not be found!";
			return false;
		}
	}
	protected function decrypt($value){
		if(file_exists('././keys/Shared/shared.txt')){
		$shared_key = file_get_contents('././keys/Shared/shared.txt');
		return Crypto::decrypt(Crypto::hexTobin($value),$shared_key);
		} else {
			echo "Shared Encryption key could not be found!";
			return false;
		}
	}

	public function add($password,$password_again){
		$db = new DBC;
		$dbc = $db->connect();

		$output_form = false;
		$errors = array();
		$messages = array();

		$username = trim($this->username);
		try {
            $query = $dbc->prepare("SELECT * FROM users WHERE username = ?");
			$query->execute([$username]);
        } catch (\PDOException $e){
            echo $e->getMessage();
            exit();
        }

		if($query->rowCount() === 0){
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
			// need to see from one another. So the data is protected in the database, but accessible for the backend users.
			if(file_exists('././keys/Shared/shared.txt')){
				$key = file_get_contents('././keys/Shared/shared.txt');
			} else {
				$errors[] = "Shared Encryption key could not be found!";
				return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
			}

			try {
				$username = trim($this->username);
				$password = trim($password);
				$password_again = trim($password_again);
				$first_name = Crypto::binTohex(Crypto::encrypt(trim($this->first_name),$key));
				$last_name = Crypto::binTohex(Crypto::encrypt(trim($this->last_name),$key));
				$email = Crypto::binTohex(Crypto::encrypt(trim($this->email),$key));
				$function = Crypto::binTohex(Crypto::encrypt(trim($this->email),$key));
				$rights = Crypto::binTohex(Crypto::encrypt(trim($this->rights),$key));
				$token = md5(uniqid(mt_rand(),true));
			} catch (Ex\CryptoTestFailedException $ex) {
				die('Cannot safely perform encryption');
			} catch (Ex\CannotPerformOperationException $ex) {
				die('Cannot safely perform encryption');
			}

			# Create Folder
			$album_id = Folders::auto_create_folder($username,$this->file_path.$username,$this->thumb_path.$username,'Users');
			$contacts = Folders::auto_create_folder("$username"."\'s ".'Contacts',$this->file_path.$username.'/'.$username.'\'s '.'Contacts',$this->thumb_path.$username.'/'.$username.'\'s '.'Contacts','Users',$username);

			if(!empty($username) && !empty($password) && !empty($password_again)) {
				if($password === $password_again) {
					$hash = password_hash($password, PASSWORD_BCRYPT);
					try {
                        $query = $dbc->prepare("INSERT into users(username,password,first_name,last_name,email,function,rights,album_id,token) VALUES(?,?,?,?,?,?,?,?,?)");
                        $query->execute([$username,$hash,$first_name,$last_name,$email,$function,$rights,$album_id,$token]);
                    } catch (\PDOException $e){
                        echo $e->getMessage();
                        exit();
                    }
					$messages[] = '<p class="container">New user <strong>'.$username.'</strong> has been successfully added.';
				} else {
					$errors[] = 'Passwords do not match, please retype your password correctly.';
					$output_form = true;
				}
			} else {
				$errors[] = 'Fill in all the required fields';
				$output_form = true;
			}

			$db->close();
			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		} else {
			$output_form = true;
			$errors[] = "Username already exists, please use another username.";
			return ['output_form' => $output_form,'errors' => $errors,'messages' => $messages];
		}
	}

	public function edit($id = null,$old_username = null,$new_password = null,$new_password_again = null){
		$db = new DBC;
		$dbc = $db->connect();

		$output_form = false;
		$errors = array();
		$messages = array();

		$username = trim($this->request['username']);
		try {
            $query = $dbc->prepare("SELECT * FROM users WHERE username = ?");
			$query->execute([$username]);
        } catch (\PDOException $e){
            echo $e->getMessage();
            exit();
        }

		if($query->rowCount() === 0 || ($query->rowCount() === 1 && ($username === $old_username))){
			$user_id = $this->user_id;
			$new_password = trim($new_password);
			$new_password_again = trim($new_password_again);

			// save edited object to database
			if ($_POST['confirm'] == 'Yes') {
				foreach($this->request as $k => $v) {
					if(in_array($k,$this->encrypted)) {
						$this->request[$k] = $this->encrypt($v);
					}
				}

                $this->patch();

				if(!empty($new_password) && !empty($new_password_again) )
					if($new_password === $new_password_again) {
						$hash = password_hash($new_password, PASSWORD_BCRYPT);
						$this->patch(['password' => $hash]);
						$messages[] = 'You password has been successfully changed.';
					} else {
						$errors[] = 'Passwords do not match, please retype your password correctly.';
						$output_form = true;
					}
				// Confirm success with the user
				$messages[] =  '<p>The user <strong>' . $this->username. '</strong> was successfully edited.';
			} else {
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
		$upload = new FileUpload($file_dest,$thumb_dest,$params,true);
		$img_path = $upload->getImgPath();
		$thumb_path = $upload->getThumbPath();
		$db = new DBC;
		$dbc = $db->connect();
        try {
		    $query = $dbc->prepare("UPDATE users SET img_path = ? WHERE user_id = ?");
			$query->execute([$thumb_path,$params[0]]);
        } catch (\PDOException $e){
            echo $e->getMessage();
            exit();
        }

		$db->close();
	}
}
?>