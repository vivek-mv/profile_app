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

	/**
     *Selects all the details of all the employees
     *
     * @access public
     * @param void 
     * @return mysqli result object if success or bool(false) if fails
     */
	public function selectAllEmployees() {
		
        //Get the records of all the employees
        $selectEmpDetails = "SELECT employee.eid, employee.firstName, employee.middleName,
            employee.lastName, employee.gender, employee.dob, employee.mobile, employee.landline,
            employee.email, employee.maritalStatus, employee.employment, employee.employer,
            employee.photo, commMedium.empId, commMedium.msg, commMedium.email AS comm_email, 
            commMedium.call, commMedium.any, address.eid, address.type, address.street, address.city ,
            address.state, address.zip, address.fax FROM employee JOIN commMedium ON 
            employee.eid = commMedium.empId JOIN address ON  employee.eid = address.eid";
        
        $employeeDetails = mysqli_query($this->conn, $selectEmpDetails);
        return $employeeDetails;
	}

    /**
     *Selects employee details a particular employee using employee id.(and address type when its provided)
     *
     * @access public
     * @param String, Integer 
     * @return mysqli result object if success or bool(false) if fails
     */
    public function selectEmployee($employeeId, $addressType = 0) {

        if ( $addressType === 0 ) {

            $selectEmpDetails = "SELECT employee.eid, employee.prefix, employee.firstName, employee.middleName, employee.lastName, employee.gender, employee.dob, employee.mobile, employee.landline, employee.email,
            employee.maritalStatus, employee.employment, employee.employer, employee.note, employee.photo,
            commMedium.empId, commMedium.msg, commMedium.email AS comm_email, commMedium.call , commMedium.any 
            FROM employee JOIN commMedium ON employee.eid = commMedium.empId WHERE eid =" . $employeeId;
            $details = mysqli_query($this->conn, $selectEmpDetails);

            return $details;
        }

        $addressQuery = "SELECT address.eid , address.type , address.street , address.city ,
            address.state , address.zip , address.fax FROM address
            WHERE address.eid =" . $employeeId . " AND address.type = " . $addressType;
        $address = mysqli_query($this->conn, $addressQuery); 

        return $address;
    }

    /**
     *Inserts the employee data into the database
     *
     * @access public
     * @param String, Array, Integer 
     * @return mysqli result object if success or bool(false) if fails
     */
    public function insert($tableName, $data, $employeeId = 0) {

        if ( $tableName == 'employee' && $employeeId === 0 ) {
            $insertEmp = "INSERT INTO employee (`prefix`, `firstName`, `middleName`, `lastName`, `gender`, `dob`, `mobile`,
                `landline`, `email`, `maritalStatus`, `employment`, `employer`, `photo`, `note`)
                VALUES ('".$data['prefix']."', '".$data['firstName']."', '".$data['middleName']."', '".$data['lastName']."',
                 '".$data['gender']."', '".$data['dob']."', '".$data['mobile']."', '".$data['landline']."',
                '".$data['email']."', '".$data['maritalStatus']."' ,'".$data['employment']."', '".$data['employer']."',
                 '".$data['photo']."', '".$data['note']."')";
            
            $insertEmployee = $this->conn->query($insertEmp);
            if ( $insertEmployee === TRUE ) {
                //if success then return the last insert id
                return $this->conn->insert_id;
            }else {
                return false;
            }
        }

        if ( $tableName == 'address' && $employeeId != 0 ) {
            // insert residence and office address
            $insertAdd = "INSERT INTO address (`eid`,`type`,`street`,`city`,`state`,`zip`,`fax`)
                VALUES ('".$data['employeeId']."','1','".$data['residenceStreet']."','".$data['resedenceCity']."',
                    '".$data['resedenceState']."','".$data['residenceZip']."','".$data['residenceFax']."') ,
                     ('".$data['employeeId']."','2','".$data['officeStreet']."','".$data['officeCity']."',
                    '".$data['officeState']."','".$data['officeZip']."','".$data['officeFax']."')";
            $address = $this->conn->query($insertAdd);

            if ( $address == true ) {
                return true;
            }else {
                return false;
            }
        }

        if ( $tableName == 'commMedium' && $employeeId != 0 ) {

            $insertCommMedium = "INSERT INTO commMedium (`empId`,`msg`,`email`,`call`,`any`)
                VALUES ('".$data['employeeId']."','".$data['message']."','".$data['comEmail']."',
                    '".$data['call']."','".$data['any']."')";

            $commMedium = $this->conn->query($insertCommMedium);

            if ( $commMedium == true ) {
                return true;
            }else {
                return false;
            }
        }
    }

}

?>