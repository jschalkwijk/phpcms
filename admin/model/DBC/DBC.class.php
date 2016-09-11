<?php
namespace Jorn\admin\model\DBC;
use mysqli;

class DBC {
	/*
	 * Simple DataBase Connection Class
	 * Uses the constants defined in the config.php file
	*/
	private $host = DB_HOST;
	private $user = DB_USER;
	private $password = DB_PASSWORD;
	private $name = DB_NAME;
	private $dbc;

	// Connects to your database
//	public function connect(){
//		$this->dbc = mysqli_connect($this->host, $this->user, $this->password, $this->name) or die('Error connecting to server');
//		return $this->dbc;
//	}
	public function connect(){
		// Create connection
		$this->dbc = new mysqli($this->host, $this->user, $this->password, $this->name);

		// Check connection
		if ($this->dbc->connect_error) {
			$this->connectERROR();
		}
		return $this->dbc;
	}
	// Disconnects from your database.
	public function disconnect(){
		mysqli_close($this->dbc);
	}

	public function sqlERROR(){
		echo "There is an error in your sql query (" . $this->dbc->errno . ") " . $this->dbc->error;
	}

	public function  connectERROR(){
		echo "Failed to connect to MySQL: (" . $this->dbc->connect_errno . ") " . $this->dbc->connect_error;
	}
}

?>