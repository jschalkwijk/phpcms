<?php
namespace CMS\Models\Contacts;

// Get User key for encryption
use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception as Ex;
use CMS\Models\DBC\DBC;
use CMS\Models\File\FileUpload;

class Contact {
	private $id;
	private $first_name;
	private $last_name;
	private $phone_1;
	private $phone_2;
	private $email_1;
	private $email_2;
	private $dob;
	private $street;
	private $street_num;
	private $street_num_add;
	private $zip;
	private $notes;
	private $trashed;
	private $img_path;
	
	public function __construct($first_name,$last_name,$phone_1 = null,$phone_2 = null,$email_1 = null,$email_2 = null,$dob = null,$street = null,$street_num = null,$street_num_add = null,$zip = null,$notes = null,$trashed = null,$img_path = null){
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->phone_1 = $phone_1;
		$this->phone_2 = $phone_2;
		$this->email_1 = $email_1;
		$this->email_2 = $email_2;
		$this->dob = $dob;
		$this->street = $street_num;
		$this->street_num = $street_num;
		$this->street_num_add = $street_num_add;
		$this->zip = $zip;
		$this->notes = $notes;
		$this->trashed = $trashed;
		$this->img_path = $img_path;
	}
	
	public function setID($id){
		$this->id = $id;
	}
	
	public function getID(){
		return $this->id;
	}
	public function getFirstName(){
		return $this->first_name;
	}
	public function getLastName(){
		return $this->last_name;
	}
	public function getPhone1(){
		return $this->phone_1;
	}
	public function getPhone2(){
		return $this->phone_2;
	}
	public function getMail1(){
		return $this->email_1;
	}
	public function getMail2(){
		return $this->email_2;
	}
	public function getDOB(){
		return $this->dob;
	}
	public function getStreet(){
		return $this->street;
	}
	public function getStreetNum(){
		return $this->street_num;
	}
	public function getStreetNumAdd(){
		return $this->street_num_add;
	}
	public function getZip(){
		return $this->zip;
	}
	public function getNotes(){
		return $this->notes;
	}
	public function getTrashed(){
		return $this->trashed;
	}
	public function getContactImg(){
		return $this->img_path;
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	// This function uses AES 128 encryption to encrypt contacts. (DEFUSE library github.com)
	// If a user is created it also has a encryption key created which will be used here.
	public function addContact(){
		$db = new DBC;
		$dbc = $db->connect();

		$errors = array();
		$messages = array();
		$output_form = false;
		$user_id = $_SESSION['user_id'];

		// Checking for an existing Key and asign it to the variable.
		// or exit script is not found.
		if(file_exists('././keys/User/'.$_SESSION['username'].'.txt')){
			$user_key  = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt'); 
		} else {
			$errors[] = "Encryption key could not be found!";
			return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
		}

		// Working from the inside out:
		// We call the Crypto function to encrypt the message to binary data using the Users key.
		// To store data in the database we need to convert the binary to hexadecimal.
		//the database row must be set to VARBINARY in order to contain the data.
		try {
			$first_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->first_name)),$user_key ));
			$last_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->last_name)),$user_key ));
			$phone_1 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->phone_1)),$user_key ));
			$phone_2 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->phone_2)),$user_key ));
			$email_1 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->email_1)),$user_key ));
			$email_2 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->email_2)),$user_key ));
			$dob = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->dob)),$user_key ));
			$street = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street)),$user_key ));
			$street_num = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street_num)),$user_key ));
			$street_num_add = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street_num_add)),$user_key ));
			$zip = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->zip)),$user_key ));
			$notes = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->notes)),$user_key ));
		} catch (Ex\CryptoTestFailedException $ex) {
		    die('Cannot safely perform encryption');
		} catch (Ex\CannotPerformOperationException $ex) {
		    die('Cannot safely perform encryption');
		}
		// Adding the encrypted data to the database.
		if(!empty($first_name) && (!empty($email_1)) || !empty($phone_1)){
			$query = $dbc->prepare("INSERT INTO contacts(first_name,last_name,phone_1,phone_2,email_1,email_2,dob,street,street_num,street_num_add,zip,notes,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
			if($query) {
				$query->bind_param("ssssssssssssi", $first_name, $last_name, $phone_1, $phone_2, $email_1, $email_2, $dob, $street, $street_num, $street_num_add, $zip, $notes, $user_id);
				$query->execute();
				$id = $query->insert_id;
				$query->close();
			} else {
				$db->sqlERROR();
			}
			$messages[] = 'The new contact '.'<a href="/admin/contacts/info/'.$id.'/'.$first_name.'">'.$first_name.'</a>'.' is successfully created.';
		} else {
			$errors[] = 'You have to at least fill in a first name and </br> a email or phone number to add a new contact.';
			$output_form = true;
		}
		$dbc->close();
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	public function editContact($id){
		$db = new DBC;
		$dbc = $db->connect();

		$errors = array();
		$messages = array();
		$output_form = false;
		
		if(file_exists('././keys/User/'.$_SESSION['username'].'.txt')){
			$user_key = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt'); 
		} else {
			$errors[] = "Encryption key could not be found!";
			return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
		}
		// Because creating a new object in the controller doesn't allow us to insert an ID, we have to set the id
		// corresponding to the contact. This is passed by the controller.
		$this->setID($id);
		// Working from the inside out:
		// We call the Crypto function to encrypt the message to binary data using the Users key.
		// To store data in the database we need to convert the binary to hexadecimal.
		//the database row must be set to VARBINARY in order to contain the data.
		$id = mysqli_real_escape_string($dbc,trim((int)$this->getID()));
		$first_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->first_name)),$user_key ));
		$last_name = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->last_name)),$user_key ));
		$phone_1 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->phone_1)),$user_key ));
		$phone_2 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->phone_2)),$user_key ));
		$email_1 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->email_1)),$user_key ));
		$email_2 = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->email_2)),$user_key ));
		$dob = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->dob)),$user_key ));
		$street = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street)),$user_key ));
		$street_num = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street_num)),$user_key ));
		$street_num_add = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->street_num_add)),$user_key ));
		$zip = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->zip)),$user_key ));
		$notes = Crypto::binTohex(Crypto::encrypt(mysqli_real_escape_string($dbc,trim($this->notes)),$user_key ));
		// Adding the updated data to the DB.
		if(!empty($first_name) && (!empty($email_1)) || !empty($phone_1)){
			$query = $dbc->prepare("UPDATE contacts SET first_name = ?,last_name = ?,phone_1 = ?,phone_2 = ?,
			email_1 = ?,email_2 = ?,dob = ? ,street = ?,street_num = ?,street_num_add = ?,zip = ?,notes = ? WHERE contact_id = ?");
			if($query) {
				$query->bind_param("ssssssssssssi", $first_name, $last_name, $phone_1, $phone_2, $email_1, $email_2, $dob, $street, $street_num, $street_num_add, $zip, $notes, $id);
				$query->execute();
				$query->close();
			} else {
				$db->sqlERROR();
			}
			$messages[] = 'The contact '.'<a href="/admin/contacts/info/'.$id.'/'.$first_name.'">'.$first_name.'</a>'.' is successfully edited.';
		} else {
			$errors[] = 'You have to at least fill in a first name and </br> a email or phone number to edit a contact.';
			$output_form = true;
		}
		$dbc->close();
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return['output_form' => $output_form,'messages' => $messages,'errors' => $errors];
	}

	public static function addProfileIMG($file_dest,$thumb_dest,$params){
		$upload = new FileUpload($file_dest,$thumb_dest,$params);
		$img_path = $upload->getImgPath();
		$thumb_path = $upload->getThumbPath();
		$db = new DBC;
		$dbc = $db->connect();
		$query = $dbc->prepare("UPDATE contacts SET img_path = '$thumb_path' WHERE contact_id = ?");
		$query->bind_param("si",$thumb_path,$params[0]);
		$query->execute();
		$query->close();
		$dbc->close();
	}

	// Fetches all contacts from the current user logged in.
	// It take the DB table name and a Flag to display either trashed or non deleted contacts
	// We have to decrypt the data to display it to the user.
	// For each contact a new object is created an pushed to the contact arry which will be returned to the controller.
	public static function fetchAll($dbt,$trashed) {

		$db = new DBC;
		$dbc = $db->connect();

		$user_id = $_SESSION['user_id'];
		$query = $dbc->prepare("SELECT * FROM ".$dbt." WHERE trashed = ? AND user_id = ? ORDER BY contact_id DESC");

		if($query) {
			$query->bind_param("ii", $trashed, $user_id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
		} else {
			$db->sqlERROR();
		}
		$contacts = array();
		
		$user_key  = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt');

		// Working from the inside out
		// To decrypt data from the database we need to convert the hexadecimal to binary.
		// We call the Crypto function to decrypt the message to readable data using the Users key.

		while($row = $data->fetch_array()){
			$first_name = Crypto::decrypt(Crypto::hexTobin($row['first_name']),$user_key );
			$last_name = Crypto::decrypt(Crypto::hexTobin($row['last_name']),$user_key );
			$phone_1 = Crypto::decrypt(Crypto::hexTobin($row['phone_1']),$user_key );
			$phone_2 = Crypto::decrypt(Crypto::hexTobin($row['phone_2']),$user_key );
			$email_1 = Crypto::decrypt(Crypto::hexTobin($row['email_1']),$user_key );
			$email_2 = Crypto::decrypt(Crypto::hexTobin($row['email_2']),$user_key );
			$dob = Crypto::decrypt(Crypto::hexTobin($row['dob']),$user_key );
			$street = Crypto::decrypt(Crypto::hexTobin($row['street']),$user_key );
			$street_num = Crypto::decrypt(Crypto::hexTobin($row['street_num']),$user_key );
			$street_num_add = Crypto::decrypt(Crypto::hexTobin($row['street_num_add']),$user_key );
			$zip = Crypto::decrypt(Crypto::hexTobin($row['zip']),$user_key );
			$notes = Crypto::decrypt(Crypto::hexTobin($row['notes']),$user_key );
			
			$contact = new Contact(
				$first_name,
				$last_name,
				$phone_1,
				$phone_2,
				$email_1,
				$email_2,
				$dob,
				$street,
				$street_num,
				$street_num_add,
				$zip,
				$notes						
			);
			// Because creating a new object in the controller doesn't allow us to insert an ID, we have to set the id
			// ourselves. It's fetched from the database.
			$contact->setID($row['contact_id']);
			// adds every object to the posts array. We can access each object and its methods seperatly.
			$contacts[] = $contact;
		}
		$dbc->close();

		// Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
		return $contacts;
	}

	// Fetches a single contact from the current user logged in.
	// It take the DB table name and the id of the contact we want to see.
	// We have to decrypt the data to display it to the user.
	
	public static function fetchSingle($dbt,$id) {
		// static classes can be accessed directly.
		//the method does not use properties or methods in the class.
		//you dont have to instantiate an object just to get a simple function.
		$db = new DBC;
		$dbc = $db->connect();

		$query = $dbc->prepare("SELECT * FROM ".$dbt." WHERE contact_id = ?");
		if($query) {
			$query->bind_param("i", $id);
			$query->execute();
			$data = $query->get_result();
			$query->close();
		} else {
			$db->sqlERROR();
		}
		$user_key  = file_get_contents('././keys/User/'.$_SESSION['username'].'.txt');
		// Working from the inside out
		// To decrypt data from the database we need to convert the hexadecimal to binary.
		// We call the Crypto function to decrypt the message to readable data using the Users key.

		while($row = $data->fetch_array()){
			$first_name = Crypto::decrypt(Crypto::hexTobin($row['first_name']),$user_key );
			$last_name = Crypto::decrypt(Crypto::hexTobin($row['last_name']),$user_key );
			$phone_1 = Crypto::decrypt(Crypto::hexTobin($row['phone_1']),$user_key );
			$phone_2 = Crypto::decrypt(Crypto::hexTobin($row['phone_2']),$user_key );
			$email_1 = Crypto::decrypt(Crypto::hexTobin($row['email_1']),$user_key );
			$email_2 = Crypto::decrypt(Crypto::hexTobin($row['email_2']),$user_key );
			$dob = Crypto::decrypt(Crypto::hexTobin($row['dob']),$user_key );
			$street = Crypto::decrypt(Crypto::hexTobin($row['street']),$user_key );
			$street_num = Crypto::decrypt(Crypto::hexTobin($row['street_num']),$user_key );
			$street_num_add = Crypto::decrypt(Crypto::hexTobin($row['street_num_add']),$user_key );
			$zip = Crypto::decrypt(Crypto::hexTobin($row['zip']),$user_key );
			$notes = Crypto::decrypt(Crypto::hexTobin($row['notes']),$user_key );
			
			$contact = new Contact(
				$first_name,
				$last_name,
				$phone_1,
				$phone_2,
				$email_1,
				$email_2,
				$dob,
				$street,
				$street_num,
				$street_num_add,
				$zip,
				$notes,		
				$row['trashed'],
				$row['img_path']
			);
			// Because creating a new object in the controller doesn't allow us to insert an ID, we have to set the id
			// ourselves. It's fetched from the database.
			$contact->setID($row['contact_id']);
			// adds every object to the posts array. We can acces each object and its methods seperatly.
		}
		$dbc->close();
		// Returns an array wich contains all the contact objects. Which are then passed from the controller to the view.
		return $contact;
	}
}
?>