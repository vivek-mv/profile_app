<?php

    require_once('dbOperations.php');

    //Enable error reporting
    ini_set('error_reporting', E_ALL);

    session_start();
    //Check for user's session
    if ( !isset($_SESSION['employeeId']) ) {
        header('Location:index.php?message=2');
        exit();
    }

    /**
     * Handles the ajax requests and returns the response
     * @access public
     * @package void
     * @subpackage void
     * @category void
     * @author vivek
     * @link void
     */
    Class AjaxHandler {

        /**
         * Handles the search requests
         *
         * @access public
         * @param String $data
         * @return String
         */
        public function getSearchResult($searchData) {
            $query = "SELECT employee.eid, employee.firstName, employee.middleName,
                employee.lastName, employee.gender, employee.dob, employee.mobile, employee.landline,
                employee.email, employee.maritalStatus, employee.employment, employee.employer,
                employee.photo, commMedium.msg, commMedium.email 
                AS comm_email, commMedium.call, commMedium.any, residence.street AS rStreet,
                residence.city AS rCity,residence.state AS rState, residence.zip AS rZip, residence.fax AS rFax,
                office.street AS oStreet, office.city AS oCity ,office.state AS oState, office.zip AS oZip, 
                office.fax AS oFax
                FROM employee 
                JOIN commMedium 
                ON employee.eid = commMedium.empId 
                JOIN address AS residence
                ON  employee.eid = residence.eid AND residence.type = 1
                JOIN address AS office
                ON  employee.eid = office.eid AND  office.type = 2
                WHERE (employee.firstName LIKE '%$searchData%') OR (employee.email LIKE '%$searchData%')
                ";
            $dbOperations = new DbOperations();
            $employeeDetails = $dbOperations->executeSql($query);
            while($row = $employeeDetails->fetch_assoc()){
                if ( !empty($row['photo']) ) {
                    $row['photo'] = '<img src="profile_pic/'.$row['photo'].'" alt="profile pic " height="150" width="150">';
                }
                $json[] = $row;
            }
           echo (json_encode($json));
            //print_r(json_decode(json_encode($json)));echo '</pre>';
        }
        
    }

    $ajaxHandler = new AjaxHandler();
    $ajaxHandler->getSearchResult($_POST['data']);

?>










