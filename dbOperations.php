<?php
require_once('db_conn.php');

/**
 * Class that handles Insert, Update, Delete and Display Operations.
 * @access public
 * @package void
 * @subpackage void
 * @category void
 * @author vivek
 * @link void
 */
class DbOperations {
    private $conn = null;

    /**
     *Constructor
     *
     * @access public
     * @param void 
     * @return void
     */
    public function __construct() {

        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     *Takes a query string and executes the query
     *
     * @access public
     * @param String 
     * @return boolean/object
     */
    public function executeSql($query) {

        return mysqli_query($this->conn, $query);
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

        $delImage = $this->executeSql($image);

        if ( !$delImage ) {
            return false;
        }
        
        $getImg = $delImage->fetch_assoc();
        
        if ( !empty($getImg["photo"])  && !unlink(APP_PATH . "/profile_pic/".$getImg["photo"]) ) {

            return false;
        }

        //Delete employee address,communication medium, and details
        if ( !$this->executeSql($deleteAddress) ||  !$this->executeSql($deleteCommMode)
            || !$this->executeSql($deleteEmployee) ) {

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
     * @return object/boolean
     */
    public function selectAllEmployees() {

        //Get the records of all the employees
        $selectEmpDetails =
            "SELECT employee.eid, employee.firstName, employee.middleName,
                employee.lastName, employee.gender, employee.dob, employee.mobile, employee.landline,
                employee.email, employee.maritalStatus, employee.employment, employee.employer,
                employee.photo, commMedium.empId, commMedium.msg, commMedium.email AS comm_email, 
                commMedium.call, commMedium.any, address.eid, address.type, address.street, address.city ,
                address.state, address.zip, address.fax 
            FROM employee 
            JOIN commMedium ON employee.eid = commMedium.empId 
            JOIN address ON  employee.eid = address.eid";
        
        $employeeDetails = $this->executeSql($selectEmpDetails);
        return $employeeDetails;
    }

    /**
     *Selects employee details a particular employee using employee id.(and address type when its provided)
     *
     * @access public
     * @param String
     * @param Integer 
     * @return object/boolean
     */
    public function selectEmployee($employeeId, $addressType = 0) {

        if ( $addressType === 0 ) {

            $selectEmpDetails =
                "SELECT employee.eid, employee.prefix, employee.firstName, employee.middleName,
                        employee.lastName, employee.gender, employee.dob, employee.mobile,
                        employee.landline, employee.email, employee.password, employee.maritalStatus,
                        employee.employment, employee.employer, employee.note, employee.photo, commMedium.empId, 
                        commMedium.msg, commMedium.email AS comm_email, commMedium.call, commMedium.any
                FROM employee
                JOIN commMedium ON employee.eid = commMedium.empId
                WHERE eid =" . $employeeId;

            $details = $this->executeSql($selectEmpDetails);

            return $details;
        }

        $addressQuery =
            "SELECT address.eid , address.type , address.street , address.city ,
              address.state , address.zip , address.fax 
            FROM address
            WHERE address.eid =" . $employeeId . " 
            AND address.type = " . $addressType;

        $address = $this->executeSql($addressQuery); 

        return $address;
    }

    /**
     *Inserts the employee data into the database
     *
     * @access public
     * @param String
     * @param Array
     * @param Integer 
     * @return object/boolean
     */
    public function insert($tableName, $data, $employeeId = 0) {

        if ( $tableName == 'employee' && $employeeId === 0 ) {
            $insertEmp = "
                INSERT INTO employee (`prefix`, `firstName`, `middleName`, `lastName`, `gender`,
                `dob`, `mobile`,`landline`, `email`, `password`, `maritalStatus`, `employment`, `employer`, `photo`, `note`)
                VALUES ('".$data['prefix']."', '".$data['firstName']."', '".$data['middleName']."',
                '".$data['lastName']."', '".$data['gender']."', '".$data['dob']."', '".$data['mobile']."',
                '".$data['landline']."','".$data['email']."','".$data['password']."', '".$data['maritalStatus']."' ,
                '".$data['employment']."', '".$data['employer']."','".$data['photo']."', '".$data['note']."')";
            
            $insertEmployee = $this->executeSql($insertEmp);
            if ( $insertEmployee ) {
                //if success then return the last insert id
                return $this->conn->insert_id;
            }

            return false;

        }

        if ( $tableName == 'address' && $employeeId != 0 ) {
            // insert residence and office address
            $insertAdd =
                "INSERT INTO address (`eid`,`type`,`street`,`city`,`state`,`zip`,`fax`)
                VALUES ('".$data['employeeId']."','1','".$data['residenceStreet']."','".$data['resedenceCity']."',
                    '".$data['resedenceState']."','".$data['residenceZip']."','".$data['residenceFax']."') ,
                     ('".$data['employeeId']."','2','".$data['officeStreet']."','".$data['officeCity']."',
                    '".$data['officeState']."','".$data['officeZip']."','".$data['officeFax']."')";
            
            $address = $this->executeSql($insertAdd);

            return $address;
        }

        if ( $tableName == 'commMedium' && $employeeId != 0 ) {

            $insertCommMedium =
                "INSERT INTO commMedium (`empId`,`msg`,`email`,`call`,`any`)
                VALUES ('".$data['employeeId']."','".$data['message']."','".$data['comEmail']."',
                    '".$data['call']."','".$data['any']."')";

            $commMedium = $this->executeSql($insertCommMedium);

            return $commMedium;
        }
    }

    /**
     *Updates the employee data into the database
     *
     * @access public
     * @param String
     * @param Array
     * @param Integer 
     * @param Integer
     * @return object/boolean
     */
    public function update($tableName, $data, $employeeId, $addressType = 0) {
        
        if ( $tableName == 'address' && $addressType == 1) {

            //Update residence address
            $updateResidenceAdd =
                "UPDATE address 
                SET street = '" . $data['rstreet']
                . "', city ='" . $data['rcity'] ."',state = '" . $data['rstate'] . "' ,
                zip = '" . $data['rzip'] . "', fax = '" . $data['rfax'] . "' 
                WHERE eid = " . $employeeId . " && type = ".$addressType;
            
            $updateAdd = $this->executeSql($updateResidenceAdd);
            return $updateAdd;
        }

        if ( $tableName == 'address' && $addressType == 2 ) {
            //Update office address
            $updateOfficeAdd =
                "UPDATE address SET street = '" . $data['ostreet'] . "', city ='"
                . $data['ocity'] . "',state = '" . $data['ostate'] . "' , zip = '" . $data['ozip']
                . "', fax = '" . $data['ofax'] ."' where eid = " . $employeeId . " && type = "
                .$addressType;

            $updateAdd = $this->executeSql($updateOfficeAdd);
            return $updateAdd;
        }

        if ( $tableName == 'commMedium' ) {
            $updateCommMedium =
                "UPDATE commMedium SET msg ='" . $data['msg']
                . "' , email ='" . $data['email'] . "',`call` ='" . $data['call']
                . "' , any ='" . $data['any'] . "' 
                WHERE empId =" . $employeeId;

            $updateComm = $this->executeSql($updateCommMedium);
            return $updateComm;
        }

        if ( $tableName == 'employee' ) {
            //update employee details
            $updateEmpDetails =
                "UPDATE employee 
                SET prefix = '" . $data['prefix'] . "' , firstName = '" .
                $data['firstName'] . "' , middleName = '" . $data['middleName'] . "' , lastName = '" .
                $data['lastName'] . "' ,  gender = '" . $data['gender'] ."' , dob = '" . $data['dob'] . "' ,
                mobile = '" . $data['mobile'] . "' , landline='" . $data['landline'] . "', email ='" 
                . $data['email'] . "', password = '" . $data['password'] . "' , maritalStatus= '" . 
                $data['maritalStatus'] . "' ,employment = '" .$data['employment'] . "' ,employer='" .
                $data['employer'] ."'".$data['insertImage'] . ",note= '" . $data['note'] . "' 
                WHERE eid = " .
                $employeeId;

            $updateEmployee = $this->executeSql($updateEmpDetails);
            return $updateEmployee;
        }
    }

    /** Checks whether email already exists or not
     * @param String
     * @return Boolean
     */
    public function checkEmailPresent($email) {

        $checkEmail = "SELECT * FROM employee WHERE employee.email =  '" . $email . "'";
        $checkEmailPresent = $this->executeSql($checkEmail);

        return (!$checkEmailPresent->num_rows == 0) ;

    }
}

//Check if email present of not when ajax request is comming
if ( isset($_POST['data']) && isset($_POST['code']) ) {
    $email = htmlspecialchars($_POST['data']);
    $dbo = new DbOperations();
    $emailPresent = $dbo->checkEmailPresent($email);
    if ( $emailPresent ) {
        echo '1';
        exit();
    } else {
        echo '0';
        exit();
    }
}
?>