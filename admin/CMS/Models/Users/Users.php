<?php

namespace CMS\Models\Users;

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
		'album_id',
		'password',
	];

	protected $encrypted = [
		'first_name',
		'last_name',
		'email',
		'function',
		'rights',
	];

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

	public function add(){
		$db = new DBC;
		$dbc = $db->connect();

		$username = trim($this->username);
		$output_form = false;
		$errors = array();
		$messages = array();

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


			$username = trim($this->username);
			$password = trim($this->password);
			$password_again = trim($this->password_again);
			# Create Folder
			$album_id = Folders::auto_create_folder($username,$this->file_path.$username,$this->thumb_path.$username,'Users');
			Folders::auto_create_folder("$username"."\\'s ".'Contacts',$this->file_path.$username.'/'.$username.'\'s '.'Contacts',$this->thumb_path.$username.'/'.$username.'\'s '.'Contacts','Users',$username);

			if(!empty($username) && !empty($password) && !empty($password_again)) {
				if($password === $password_again) {
					$this->request['new_password'] = password_hash($password, PASSWORD_BCRYPT);
					$this->request['album_id'] = $album_id;
					foreach($this->request as $k => $v) {
						if(in_array($k,$this->encrypted)) {
							$this->request[$k] = $this->encrypt($v);
						}
					}
					if($this->save()){
						$messages[] = '<p class="container">New user <strong>'.$username.'</strong> has been successfully added.';
					} else {
						$errors[] = $this->sqlError;
					}
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

	public function edit(){
		$db = new DBC;
		$dbc = $db->connect();

		$output_form = false;
		$errors = array();
		$messages = array();

		$username = trim($this->request['username']);
		$old_username = trim($this->request['old_username']);
		$password = $this->request['password'];
		$password_again = $this->request['password_again'];
		try {
            $query = $dbc->prepare("SELECT * FROM users WHERE username = ?");
			$query->execute([$username]);
        } catch (\PDOException $e){
            echo $e->getMessage();
            exit();
        }
        $this->patch();
		if($query->rowCount() === 0 || ($query->rowCount() === 1 && ($username === $old_username))){
			$new_password = trim($password);
			$new_password_again = trim($password_again);

			// save edited object to database
			if ($this->request['confirm'] == 'Yes') {
                foreach ($this->request as $k => $v) {
                    if (in_array($k, $this->encrypted)) {
                        $this->request[$k] = $this->encrypt($v);
                    }
                }

                $this->savePatch();
                if (!empty($new_password) && !empty($new_password_again)) {
                    if ($new_password === $new_password_again) {
                        $hash = password_hash($new_password, PASSWORD_BCRYPT);
                        $this->patch(['password' => $hash]);
                        $this->savePatch();
                        $messages[] = 'You password has been successfully changed.';
                    } else {
                        $errors[] = 'Passwords do not match, please retype your password correctly.';
                        $output_form = true;
                    }
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