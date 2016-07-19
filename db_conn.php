<?php
//include constants.php
require_once('constants.php');

require_once('logErrors.php');

/**
 * Mysql database class - only one connection allowed
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
        $this->connection = @new mysqli(HOST, USER, PASSWORD, DBNAME);

        if (mysqli_connect_errno())
        {
            // log the error to error logs file
            logError('Database error occured in db_conn.php : ' . mysqli_connect_error());

            echo "Currently we are facing some server issues , Please come back after some time";
            exit();
        }
    }

    // Get mysqli connection
    public function getConnection() {
        return $this->connection;
    }
}

?>