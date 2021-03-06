<?php

namespace CMS\Models\Users;

use \Defuse\Crypto\Key;
use \Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;

use CMS\Core\Model\Model;
use \CMS\Models\DBC\DBC;
use CMS\Models\Files\Folders;


class Users extends Model{

	private $file_path = 'uploads/users/';
	private $thumb_path = 'uploads/thumbs/users/';

	public $primaryKey = 'user_id';

	public $table = 'users';

	protected $relations = [
		'users' => 'user_id'
	];

	protected $joins = [
	];

	protected $allowed = [
	    'role_id',
		'username',
		'album_id',
        'trashed',
        'approved',
	];

	protected $encrypted = [
		'first_name',
		'last_name',
		'email',
		'function',
	];

	protected $hidden = [
	    'user_id',
        'permission_id',
		'password',
		'protected_key',
		'album_id',
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
	public function get_id(){
		return $this->user_id;
	}
	protected function encrypt($value){
		if(file_exists('././keys/Shared/shared.txt')){
			$keyAscii = file_get_contents('././keys/Shared/shared.txt');
			$returnKey = Key::loadFromAsciiSafeString($keyAscii);

			return Crypto::encrypt($value,$returnKey);
		} else {
			echo "Shared Encryption key could not be found!";
			return false;
		}
	}
	protected function decrypt($value){
		if(file_exists('././keys/Shared/shared.txt')){
			$keyAscii = file_get_contents('././keys/Shared/shared.txt');
			$returnKey = Key::loadFromAsciiSafeString($keyAscii);

			return Crypto::decrypt($value,$returnKey);
		} else {
			echo "Shared Encryption key could not be found!";
			return false;
		}
	}

//	#Permissions
    public function getPermissions(array $permissions)
    {
        if(array_filter($permissions,'is_numeric')){
            return Permission::allWhere(['permission_id' => $permissions]);
        } else {
            return Permission::allWhere(['name' => $permissions]);
        }

    }
//
    public function givePermissionTo(array $permissions)
        {
            if(array_filter($permissions,'is_numeric')  && !empty($this->permissions())){
                if(!$this->sync(Permission::class,$this->permissions(),$permissions,'users_permissions')){
                    return false;
                }
                // flash Message in controller.
                return true;
            } else {
                $permissions = $this->getPermissions($permissions);
                return $this->saveMany($permissions,'users_permissions');
            }
    }

    public function revokePermissionTo(array $permissions){
        $permissions = $this->getPermissions($permissions);

        if(empty($permissions)){
            return false;
        }

        if(!$this->removeMany($permissions,'users_permissions')){
            return false;
        };

        // flash Message in controller.
        return true;

    }

    public function refreshPermissions(array $permissions = [])
    {
        $this->removeMany($this->permissions(),'users_permissions');

        if(empty($permissions)){
            return true;
        }

        return $this->givePermissionTo($permissions);

    }

    protected function hasPermission($permission): bool
    {
        if(!$this->permissions()){
            return false;
        }
        foreach ($this->permissions() as $user_permission){
            if($user_permission->name == $permission){
                return true;
            }
        }
        return false;
    }
    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }


    public function hasPermissionThroughRole($permission){
           // Get permissions through the users Roles
           $user_permissions = $this->ownsThroughMany(Permission::class,Role::class,'roles_permissions','users_roles');
           // Check if permission is assigned to the users Role
           foreach ($user_permissions as $user_permission){
              if($user_permission->name == $permission){
                  return true;
              }
           }
           return false;
    }

    #roles
    public function getRoles(array $roles)
    {
        if(array_filter($roles,'is_numeric')){
            return Role::allWhere(['role_id' => $roles]);
        } else {
            return Role::allWhere(['name' => $roles]);
        }

    }
    public function giveRoleTo(array $roles)
    {
        if(array_filter($roles,'is_numeric') && !empty($this->roles())){
            if(!$this->sync(Role::class,$this->roles(),$roles,'users_roles')){
                return false;
            }
            // flash Message in controller.
            return true;
        } else {
            $roles = $this->getRoles($roles);
            return $this->saveMany($roles,'users_roles');
        }
    }
    public function revokeRoleTo(array $roles){
        $roles = $this->getRoles($roles);

        if(empty($roles)){
            return false;
        }

        if(!$this->removeMany($roles,'users_permissions')){
            return false;
        };
        // flash Message in controller.
        return true;

    }

    public function refreshRoles(array $roles = [])
    {
        $this->removeMany($this->roles(),'users_roles');

        if(empty($permissions)){
            return true;
        }

        return $this->giveRoleTo($roles);

    }

    public function hasRole(...$roles): bool
    {
        foreach ($this->roles() as $user_roles){
            if(in_array($user_roles->name,$roles)){
                return true;
            }
        }
        return false;
    }
    public function hasRoleTo($permission)
    {
        return $this->hasRoleThroughPermission($permission) || $this->hasRole($permission);
    }


    public function hasRoleThroughPermission($role){
        // Get permissions through the users Roles
        $user_roles = $this->ownsThroughMany(Role::class,Permission::class,'users_roles','roles_permissions');
        // Check if permission is assigned to the users Role
        foreach ($user_roles as $user_role){
            if($user_role->name == $role){
                return true;
            }
        }
        return false;
    }

    #relations
    public function roles()
    {
        return $this->ownedByMany(Role::class,'users_roles');
    }
    public function permissions()
    {
        return $this->ownedByMany(Permission::class,'users_permissions');
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


			$username = trim($this->username);
			$password = trim($this->password);
			$password_again = trim($this->password_again);
			# Create Folder
			$folder_id = Folders::auto_create_folder($username,$this->file_path.$username,$this->thumb_path.$username,'Users');
			Folders::auto_create_folder("$username"."\\'s ".'Contacts',$this->file_path.$username.'/'.$username.'\'s '.'Contacts',$this->thumb_path.$username.'/'.$username.'\'s '.'Contacts','Users',$username);

			if(!empty($username) && !empty($password) && !empty($password_again)) {
				if($password === $password_again) {
					$hash = password_hash($password, PASSWORD_BCRYPT);
					$this->password = $hash;
					$this->folder_id = $folder_id;
//					foreach($this->request as $k => $v) {
//						if(in_array($k,$this->encrypted)) {
//							$this->request[$k] = $this->encrypt($v);
//						}
//					}
					$protected_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);
					$protected_key_encoded = $protected_key->saveToAsciiSafeString();
					$this->protected_key = $protected_key_encoded;
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
		$messages = array();

		$username = trim($_POST['username']);
		$old_username = trim($_POST['old_username']);
		$password = $_POST['password'];
		$password_again = $_POST['password_again'];
		try {
            $query = $dbc->prepare("SELECT * FROM users WHERE username = ? AND user_id != {$this->user_id}");
			$query->execute([$username]);
        } catch (\PDOException $e){
            echo $e->getMessage();
            exit();
        }

		// Initial patch of objkect with the request values. if the main check fails when you enter no or incorrect values
		// The values you have entered will be remembered so the user doesn't have to retype everything.
		// if you leave this out the object is reset to its initial values.
		//Because we fetch all the values with methods that decrypt, we have to encrypt all the alues before the initial patch.
//		foreach ($this->request as $k => $v) {
//			if (in_array($k, $this->encrypted)) {
//				$this->request[$k] = $this->encrypt($v);
//			}
//		}
		$this->patch($_POST);
                                 
		if((!empty($username)) && ($query->rowCount() === 0 || ($query->rowCount() === 1 && ($username === $old_username)))){

		    $new_password = trim($password);
			$new_password_again = trim($password_again);

			/*
			 * TODO : if a patch is successfully saved, the values array should be deleted inside the Model
			 * automatically to avoid conflicts. For example in this case we need to patch twice for the password.
			 */
			$this->values = [];
			if (!empty($new_password) && !empty($new_password_again)) {
				if ($new_password === $new_password_again) {
					$hash = password_hash($new_password, PASSWORD_BCRYPT);
					$this->password = $hash;
					$this->patch(['password' => $this->password]);
					$messages[] = 'You password has been successfully changed.';
				} else {
					$messages[] = 'Passwords do not match, please retype your password correctly.';
					$output_form = true;
					return ['output_form' => $output_form,'messages' => $messages];
				}
			}

			$this->savePatch();
			// Confirm success with the user
			$messages[] =  '<p>The user <strong>' . $this->username. '</strong> was successfully edited.';

			return ['output_form' => $output_form,'messages' => $messages];
		} else {
			$output_form = true;
			$messages[] = "Username is empty or already exists, please use another username.";
			return ['output_form' => $output_form,'messages' => $messages];
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