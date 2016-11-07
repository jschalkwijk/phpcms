<?php
namespace CMS\Models\DBC;
use PDO;
use PDOException;

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

	public function connect(){
        try
        {
            $this->dbc = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->password);
            // set the PDO error mode to exception
            $this->dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
		return $this->dbc;
	}
	// Disconnects from your database.
	public function close(){
		$this->dbc = null;
	}
}

?>