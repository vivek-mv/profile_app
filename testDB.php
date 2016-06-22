<?php
//include constants.php
require_once('constants.php');

/**
* Mysql database class - only one connection alowed
* @access public
*/
class Database {
	private $connection;
	private static $instance; 
	private $host = HOST;
	private $username = USER;
	private $password = PASSWORD;
	private $database = DBNAME;
	
	/**
     * Get an instance of the Database
     *
     * @access public
     * @param no parameter 
     * @return instance of this class
     */
	public static function getInstance() {
		if(!self::$instance) {
		// If no instance then make one
			self::$instance = new self();
		}
		return self::$instance;
	}
	// Constructor
	private function __construct() {
		$this->connection = new mysqli($this->host, $this->username, 
			$this->password, $this->database);
	}

	// Get mysqli connection
	public function getConnection() {
		return $this->connection;
	}
}

?>