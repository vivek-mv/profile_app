<?php
require_once('testDB.php');
/**
* Class that handles Insert, Update, Delete and Display Operations.
* @access public
*/
class DbOperations {
	private $conn = null;
	
	// Constructor
	public function __construct() {
		$db = Database::getInstance();
		$this->conn = $db->getConnection();
	}


	/**
     *Deletes the employee record 
     *
     * @access public
     * @param String 
     * @return boolean
     */
	public function delete($employeeId) {
		//Query to delete a row from the registration database with the respective employee id
        $deleteAddress = "DELETE FROM address WHERE eid=" . $employeeId . ";";
        $deleteCommMode = "DELETE FROM commMedium WHERE empId=" . $employeeId . ";";
        $deleteEmployee = "DELETE FROM employee WHERE eid=" . $employeeId . ";";

        //Select the employee image from the employee table and remove it from profile_pic dir.
        $image ="SELECT employee.photo FROM employee WHERE eid=" . $employeeId . ";";

        $delImage = mysqli_query($this->conn, $image);

        if ( !$delImage ) {
        	return false;
        }
        
        $getImg = $delImage->fetch_assoc();
        
        if ( !empty($getImg["photo"])  && !unlink(APP_PATH . "/profile_pic/".$getImg["photo"]) ) {

            return false;
        }

        //Delete employee address
        if ( !mysqli_query($this->conn, $deleteAddress) ) {

        	return false;
        }
        //Delete employee communication medium
        if ( !mysqli_query($this->conn, $deleteCommMode) ) {

        	return false;
        }
        //Delete employee details
        if ( !mysqli_query($this->conn, $deleteEmployee) ) {

        	return false;
        }
        //Return true if delete is successfull
        return true;

	}

}

?>