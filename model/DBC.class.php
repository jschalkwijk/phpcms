<?php

class DBC {
	/*
	 * Simple DataBase Connection Class
	 * Using mysqli OOP approach
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
			echo "Failed to connect to MySQL: (" . $this->dbc->connect_errno . ") " . $this->dbc->connect_error;
		}
		return $this->dbc;
	}
	// Disconnects from your database.
	public function disconnect(){
		mysqli_close($this->dbc);
	}

	public function sqlERROR(){
		echo "Oops, we are very sorry! An error occurred while trying to get the results from the database. Please contact the site owner";
	}

	public function  connectERROR(){
		echo "Oops, we are very sorry! Failed to connect to the database. Please contact the site owner";
	}
}

?>