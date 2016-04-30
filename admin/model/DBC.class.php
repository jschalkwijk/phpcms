<?php

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
	public function connect(){
		$this->dbc = mysqli_connect($this->host, $this->user, $this->password, $this->name) or die('Error connecting to server');
		return $this->dbc;
	}
	// Disconnects from your database.
	public function disconnect(){
		mysqli_close($this->dbc);
	}
}

?>