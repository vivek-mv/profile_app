<?php

    if ( !(isset($_POST['ajax']) && $_POST['ajax'] == 1) ) {
        header("Location:index.php");
        exit();
    }
    session_start();
    //Check for user's session
    if ( !isset($_SESSION['employeeId']) ) {
        echo '{"error" : "1", "error_msg" : "Unauthorized", "error_code" : "401"}';
        exit();
    }

    require_once('dbOperations.php');

    //Enable error reporting
    ini_set('error_reporting', E_ALL);

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
        public function getSearchResult($searchData, $sortOrder, $orderBy ) {
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
                ORDER BY $orderBy $sortOrder
                ";
            $dbOperations = new DbOperations();
            $employeeDetails = $dbOperations->executeSql($query);

            $json = [];
            while($row = $employeeDetails->fetch_assoc()){

                //check for gender
                if ( $row['gender'] == 'm' ) {

                    $row['gender'] = 'Male';
                } else if ( $row['gender'] == 'f' ) {

                    $row['gender'] = 'Female';
                } else {

                    $row['gender'] = 'Others';
                }

                //build the image url if image is present
                if ( !empty($row['photo']) ) {
                    $row['photo'] = '<img src="profile_pic/'.$row['photo'].'" alt="profile pic "height="150" width="150">';
                }

                //show the edit and delete button for the loged in user
                if ( $row['eid'] == $_SESSION['employeeId'] ) {

                    $row['edit'] = "<a href='registration_form.php?userId=" . $row["eid"] . "&userAction=update' target='_self' >"
                        ."<span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";

                    $row['delete'] = "<a href='details.php?userId=" . $row["eid"] . "&userAction=delete' target='_self' >"
                        ."<span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";
                } else {
                    $row['edit'] = '';

                    $row['delete'] = '';
                }

                //check for D.O.B
                if ( $row["dob"] == '0000-00-00' ) {
                    $row["dob"] = '';

                } else {
                    $row["dob"] =  date_format( new DateTime( $row["dob"] ), 'd-m-Y' );
                }

                //check for phone numbers
                $row['phone'] = '';
                if ( $row['mobile'] !== '' ) {
                    $row['phone'] .= $row["mobile"] . "(M)<br>";
                }

                if ( $row['landline'] !== '' ) {
                    $row['phone'] .= $row["landline"] . "(L)";
                }

                //check for employment
                if ( $row["employment"] == 'employed' && !empty($row["employer"]) ) {
                    $row["employment"] = ucfirst( $row["employment"] ) . " in " . ucfirst( $row["employer"] ) ;

                } else {
                    $row["employment"] =  ucfirst( $row["employment"] ) ;
                }

                //check for comm. medium
                $row['medium'] = '';
                if ( $row["msg"] == 1 ) {
                    $row['medium'] .= "Message";
                }

                if ( $row["comm_email"] == 1 ) {
                    $row['medium'] .= "<br>Email";
                }

                if ( $row["call"] == 1 ) {
                    $row['medium'] .= "<br>Phone";
                }

                if ( $row["any"] == 1 ) {
                    $row['medium'] .= "<br>Any";
                }

                //build residence address
                $row['residenceAddress'] = $row["rStreet"] . "<br>" . $row["rCity"] . "," . $row["rZip"]
                    . "<br>" . $row["rState"] ;

                //build office address
                $row['officeAddress'] = $row["oStreet"] . "<br>" . $row["oCity"] . "," . $row["oZip"]
                    . "<br>" . $row["oState"] ;

                //store all the rows in an array
                $json[] = $row;
            }

            if ( @$json != null ) {
                //send the json data
                echo (json_encode($json));
            } else {
                echo '{"error" : "1", "error_msg" : "noRecords", "error_code" : "404"}';
            }

        }
        
    }

    $ajaxHandler = new AjaxHandler();
    $ajaxHandler->getSearchResult($_POST['data'],$_POST['order'],$_POST['sortBy']);
?>










