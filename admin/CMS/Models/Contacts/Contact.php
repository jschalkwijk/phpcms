<?php
    namespace CMS\Models\Contacts;

// Get User key for encryption
    use \Defuse\Crypto\Crypto;
    use \Defuse\Crypto\Exception as Ex;
    use CMS\Models\DBC\DBC;
    use CMS\Models\Files\FileUpload;
    use CMS\Core\Model\Model;
    use Defuse\Crypto\KeyProtectedByPassword;

    class Contact extends Model
    {

        protected $primaryKey = "contact_id";
        public $table = 'contacts';
        protected $relations = [
            'users' => 'user_id',
        ];
        protected $joins = [
            'users' => ['username']
        ];

        public function get_id()
        {
            return $this->contact_id;
        }

        protected $allowed = [
            'img_path',
            'trashed',
            'approved'
        ];
        protected $hidden = [
            'user_id',
        ];
        protected $encrypted = [
            'first_name',
            'last_name',
            'phone_1',
            'phone_2',
            'email_1',
            'email_2',
            'dob',
            'street',
            'street_num',
            'street_num_add',
            'zip',
            'notes'
        ];

        public function firstName()
        {
            if (isset($this->first_name)) {
                return $this->decrypt($this->first_name);
            } else {
                return false;
            }
        }

        public function lastName()
        {
            if (isset($this->last_name)) {
                return $this->decrypt($this->last_name);
            } else {
                return false;
            }
        }

        public function mail1()
        {
            if (isset($this->email)) {
                return $this->decrypt($this->email_1);
            } else {
                return false;
            }
        }

        public function mail2()
        {
            if (isset($this->email)) {
                return $this->decrypt($this->email_2);
            } else {
                return false;
            }
        }

        public function phone1()
        {
            if (isset($this->phone_1)) {
                return $this->decrypt($this->phone_1);
            } else {
                return false;
            }
        }

        public function phone2()
        {
            if (isset($this->email)) {
                return $this->decrypt($this->phone_2);
            } else {
                return false;
            }
        }

        public function street()
        {
            if (isset($this->street)) {
                return $this->decrypt($this->street);
            } else {
                return false;
            }
        }
        public function streetNum()
        {
            if (isset($this->street_num)) {
                return $this->decrypt($this->street_num);
            } else {
                return false;
            }
        }

        public function streetNumAddition()
        {
            if (isset($this->street_num_add)) {
                return $this->decrypt($this->street_num_add);
            } else {
                return false;
            }
        }
        public function zip()
        {
            if (isset($this->zip)) {
                return $this->decrypt($this->zip);
            } else {
                return false;
            }
        }
        public function dob()
        {
            if (isset($this->dob)) {
                return $this->decrypt($this->dob);
            } else {
                return false;
            }
        }
        public function notes()
        {
            if (isset($this->notes)) {
                return $this->decrypt($this->notes);
            } else {
                return false;
            }
        }

        protected function encrypt($value)
        {
            if (isset($_SESSION['key'])) {

                $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($_SESSION['key']);;
                // $returnKey = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
                $returnKey = $protected_key->unlockKey($_SESSION['password']);
                return Crypto::encrypt($value,$returnKey);
            } else {
                echo "Encryption key could not be found!";
                return false;
            }
        }

        protected function decrypt($value)
        {
            if (isset($_SESSION['key'])) {

                $protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($_SESSION['key']);;
                // $returnKey = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
                $returnKey = $protected_key->unlockKey($_SESSION['password']);
                return Crypto::decrypt($value,$returnKey);
            } else {
                echo "Encryption key could not be found!";
                return false;
            }
        }

        // Called from the controller.
        // First a new contact object needs to be created inside the controller which then is used
        // to call this function. As you can see, it is using the objects data. The objects data is formed
        // by Post values passed to the object inside the controller.
        // This function uses AES 128 encryption to encrypt contacts. (DEFUSE library github.com)
        // If a user is created it also has a encryption key created which will be used here.
        public function add()
        {
            $errors = array();
            $messages = array();
            $output_form = false;

            $first_name = $this->first_name;
            $phone_1 = $this->phone_1;
            $email_1 = $this->email_1;

            print_r($this->request);
            // Adding the encrypted data to the database.
            if (!empty($first_name) && (!empty($email_1)) || !empty($phone_1)) {
//                foreach($this as $k => $v) {
//                    if(in_array($k,$this->encrypted)) {
//                        $this->$k = $this->encrypt($v);
//                    }
//                }
                $this->user_id = $_SESSION['user_id'];

                $this->save();
                $id = $this->lastInsertId;
                $messages[] = 'The new contact ' . '<a href="/admin/contacts/info/' . $id . '/' . $first_name . '">' . $first_name . '</a>' . ' is successfully created.';
            } else {
                $errors[] = 'Fill in all the required fields';
                $output_form = true;
            }
            // We return an array which contains value that can be passed from the controller to the view.
            // If the form needs to be outputted, errors or success messages.
            return ['output_form' => $output_form, 'messages' => $messages, 'errors' => $errors];
        }

        // Called from the controller.
        // First a new contact object needs to be created inside the controller which then is used
        // to call this function. As you can see, it is using the objects data. The objects data is formed
        // by Post values passed to the object inside the controller.
        public function edit()
        {
            $errors = array();
            $messages = array();
            $output_form = false;

            $first_name = $this->first_name;
            $phone_1 = $this->phone_1;
            $email_1 = $this->email_1;
            $this->patch();
            // Adding the updated data to the DB.
            if (!empty($first_name) && (!empty($email_1)) || !empty($phone_1)) {
                $this->user_id = $_SESSION['user_id'];
//                foreach($this as $k => $v) {
//                    if(in_array($k,$this->encrypted)) {
//                        $this->$k = $this->encrypt($v);
//                    }
//                }
                $this->savePatch();
                $messages[] = 'The contact ' . '<a href="/admin/contacts/info/' . $this->user_id . '/' . $first_name . '">' . $first_name . '</a>' . ' is successfully edited.';
            } else {
                $errors[] = 'You have to at least fill in a first name and </br> a email or phone number to edit a contact.';
                $output_form = true;
            }
            // We return an array which contains value that can be passed from the controller to the view.
            // If the form needs to be outputted, errors or success messages.
            return ['output_form' => $output_form, 'messages' => $messages, 'errors' => $errors];
        }

        public static function addProfileIMG($file_dest, $thumb_dest, $params)
        {
            $upload = new FileUpload($file_dest, $thumb_dest, $params);
            $img_path = $upload->getImgPath();
            $thumb_path = $upload->getThumbPath();
            $db = new DBC;
            $dbc = $db->connect();
            try {
                $query = $dbc->prepare("UPDATE contacts SET img_path = '$thumb_path' WHERE contact_id = ?");
                $query->execute([$thumb_path, $params[0]]);;
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    ?>