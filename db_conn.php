<?php
//include constants.php
require_once('constants.php');

/**
 * Mysql database class - only one connection alowed
 * @access public
 * @package void
 * @subpackage void
 * @category void
 * @author vivek
 * @link void
 */
Class Database {
    private $connection;
    private static $instance;

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
        $this->connection = new mysqli(HOST, USER, PASSWORD, DBNAME);
    }

    // Get mysqli connection
    public function getConnection() {
        return $this->connection;
    }
}

?>