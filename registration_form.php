<?php
    /*This page validates the form fields . 
    *If there are no errors ,then it performs
    *submit or update functionality.
    */

    //Enable error reporting
    ini_set('error_reporting', E_ALL);

    //Include Constants file 
    require_once('constants.php');
    //Include dbOperations file
    require_once('dbOperations.php');

    //dbOperations object 
    $dbOperations = new DbOperations();

    /**
     * Checks whether the employee details
     * are present or not,which is used for
     * retaining the form field values in
     * case of any errors
     * @access public
     * @param String 
     * @return Boolean
     */
    function checkedStatus( $key, $value ) {
        global $empDetails;

        if( (isset($empDetails[$key]) && $empDetails[$key] == $value) 
            || (!empty($_SESSION[$key]) && $_SESSION[$key] == $value)) {
            
            return true;
        }

        return false;
    }

    //Start session to store the form fields
    session_start();

    //Setup Navigation links
    $navLink1 = 'registration_form.php';
    $navLink1Name = 'SIGN UP';

    $navLink2 = 'login.php';
    $navLink2Name = 'LOG IN';

    if ( isset($_SESSION['employeeId']) ) {

        //Change Navigation links
        $navLink1 = 'details.php';
        $navLink1Name = 'DETAILS';

        $navLink2 = 'logout.php';
        $navLink2Name = 'LOG OUT';
    }

    //Check for any error messages from details page
    if ( (isset($_SESSION['dbErr'] ) && $_SESSION['dbErr'] == 1) || (isset($_GET["dbErr"]) && $_GET["dbErr"] == 1 ) ) {
        echo "Sorry , something bad happened .Please try after some time." ;
        session_unset();
        session_destroy();
    }

    //Destroy the session variable if registration form is opened for the first time
    if ( empty($_POST) && empty($_GET) && !empty($_SESSION) ) {
       
        //if loged in user is trying to access a fresh registration page
        if ( isset($_SESSION['employeeId']) ) {
            echo "<h3>Oops.. Looks like you lost your way.<br>
                Let us take you to the right place.
                <a href='index.php'>Click Here</a>
                </h3>";
            exit();
        }

        //Destroy any session that is present 
        session_unset();
        session_destroy();
    }

    //Check and set form action
    if( (isset($_GET['userAction']) && $_GET['userAction'] == 'update') 
        || ( isset($_GET["userId"]) && $_GET["userId"] > 0) ) {
        $form_action = 'registration_form.php?userId='.$_GET["userId"]; 
    } else {
        $form_action = 'registration_form.php';
    }
    
    //States array to store all the states for select state dropdown 
    $states = array(
        'Andaman and Nicobar Islands',
        'Andhra Pradesh',
        'Arunachal Pradesh',
        'Assam',
        'Bihar',
        'Chandigarh',
        'Chhattisgarh',
        'Dadra and Nagar Haveli',
        'Daman and Diu',
        'Delhi',
        'Goa',
        'Gujarat',
        'Haryana',
        'Himachal Pradesh',
        'Jammu and Kashmir',
        'Jharkhand',
        'Karnataka',
        'Kerala',
        'Lakshadweep',
        'Madhya Pradesh',
        'Maharashtra',
        'Manipur',
        'Meghalaya',
        'Mizoram',
        'Nagaland',
        'Orissa',
        'Pondicherry',
        'Punjab',
        'Rajasthan',
        'Sikkim',
        'Tamil Nadu',
        'Tripura',
        'Uttaranchal',
        'Uttar Pradesh',
        'West Bengal'
    );
  
    //Validate the input fields only if the request method is POST
    if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

        //Include the validation file
        require_once('validation.php');

        //Initialize error to check for any errors that occur during validation
        $error = 0;
        
        $prefix = Validation::getCorrectData($_POST["prefix"]);
        $_SESSION["prefix"] = $prefix;
        $firstName = Validation::getCorrectData($_POST["firstName"]);
        
        if ( !Validation::validateText($firstName) ) {
            $firstnameErr = 'Only letters and white space allowed';
            $error++;
        }

        if ( Validation::validateLength($firstName, 20) ) {
            $firstnameErr = 'Only 20 characters allowed';
            $error++;
        }
        
        $middleName = Validation::getCorrectData($_POST["middleName"]);
        
        if ( !Validation::validateText($middleName) ) {
            $middleNameErr = 'Only letters and white space allowed'; 
            $error++; 
        }

        if ( Validation::validateLength($middleName, 20) ) {
            $middleNameErr = 'Only 20 characters allowed';
            $error++;
        }
        
        $lastName = Validation::getCorrectData($_POST["lastName"]);
        
        if ( !Validation::validateText($lastName) ) {
            $lastNameErr = 'Only letters and white space allowed';
            $error++;
        }

        if ( Validation::validateLength($lastName, 20) ) {
            $lastNameErr = 'Only 20 characters allowed';
            $error++;
        }
        
        $gender = Validation::getCorrectData($_POST["gender"]);
        $_SESSION["gender"] = $gender;
        $dob = Validation::getCorrectData($_POST["dob"]);
        $_SESSION["dob"] = $dob;
        $mobile = Validation::getCorrectData($_POST["mobile"]);
        
        if ( Validation::validateNumber($mobile) ) {
            $mobileErr = 'Only numbers are allowed in the mobile field';
            $error++;
        }

        if ( Validation::validatePhone($mobile) ) {
            $mobileErr = 'Mobile number should be 10 digits';
            $error++;
        }
        
        $landline = Validation::getCorrectData($_POST["landline"]);
        
        if ( Validation::validateNumber($landline) ) {
            $landlineErr = 'Only numbers are allowed in the landline field';
            $error++;
        }

        if ( Validation::validatePhone($landline) ) {
            $landlineErr = 'Landline number should be 10 digits';
            $error++;
        }
        
        $email = Validation::getCorrectData($_POST["email"]);
        
        if ( Validation::validateEmail($email) ) {
            $emailErr = 'Invalid email format';
            $error++;
        }   

        if ( Validation::validateLength($email, 50) ) {
            $emailErr = 'Only 50 characters allowed';
            $error++;
        }

        //Check if email id is already present or not
        $checkEmail = "SELECT * FROM employee WHERE employee.email =  '" . $email . "'";
        $checkEmailPresent = $dbOperations->executeSql($checkEmail);
        
        if ( (!isset($_SESSION['employeeId'])) && (!$checkEmailPresent->num_rows == 0) ) {
            $emailErr = "Email already present";
            $error++;
        }

        $password = Validation::getCorrectData($_POST["password"]);
        
        if ( Validation::validatePassword($password) ) {
            $passwordErr = 'Only letters and numbers allowed';
            $error++;
        }

        if ( Validation::validateLength($password, 11) ) {
            $passwordErr = 'Only 11 characters allowed';
            $error++;
        }
        
        $maritalStatus = Validation::getCorrectData($_POST["maritalStatus"]);

        $_SESSION["maritalStatus"] = $maritalStatus;

        $employment = Validation::getCorrectData($_POST["employment"]);

        $_SESSION["employment"] = $employment;

        $employer = Validation::getCorrectData($_POST["employer"]);
        
        if ( !Validation::validateText($employer) ) {
            $employerErr = 'Only letters and white space allowed';
            $error++;
        }

        if ( Validation::validateLength($employer, 25) ) {
            $employerErr = 'Only 25 characters allowed';
            $error++;
        }

        //Set photo to empty string in case no image is provided by the user
        $photo="";
        
        //Get the size of post if its too lagre(large than the max. post size),than redirect to home page
        $postSize = $_SERVER['CONTENT_LENGTH'];

        //If the user upload any file greater than 8 MB then redirect to index.php
        if ( ($postSize > 15097152) || ($_FILES['image']['error'] == 1) ) { 
            $imageErr = 1;
            header("Location:index.php?message=".$imageErr);
            exit();
        }

        if( isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['size'] != 0 ){
          $file_name = $_FILES['image']['name'];
          $file_size = $_FILES['image']['size'];
          $file_tmp = $_FILES['image']['tmp_name'];
          $file_type = $_FILES['image']['type'];
          $path_parts = pathinfo($_FILES['image']['name']);
          $file_ext = strtolower( $path_parts['extension'] );
          
          $extensions = array("jpeg","jpg","png");
          
          if( in_array($file_ext,$extensions) === false ) {
            $imageErr = 'Extension not allowed, please choose a JPEG or PNG file.';
            $error++;
          }
          
          if ( $file_size > IMAGE_SIZE ) { 
             $imageErr ='File size must be less than'.IMAGE_SIZE_MB;
             $error++;
          }

          $photo = $file_name;
        }
        
        $residenceStreet = Validation::getCorrectData($_POST["residenceStreet"]);

        if ( Validation::validateLength($residenceStreet, 50) ) {
            $residenceStreetErr = 'Only 50 characters allowed';
            $error++;
        }
        $resedenceCity = Validation::getCorrectData($_POST["resedenceCity"]);
        
        if ( !Validation::validateText($resedenceCity) ) {
            $residenceCityErr = 'Only letters and white space allowed';
            $error++;
        }

        if ( Validation::validateLength($resedenceCity, 50) ) {
            $residenceCityErr = 'Only 50 characters allowed';
            $error++;
        }
        
        $resedenceState = Validation::getCorrectData($_POST["residenceState"]);
        $_SESSION["residenceState"] = $resedenceState;

        $residenceZip = Validation::getCorrectData($_POST["residenceZip"]);
        
        if ( Validation::validateNumber($residenceZip) ) {
            $residenceZipErr = 'Only numbers are allowed';
            $error++;
        }

        if ( Validation::validateZip($residenceZip) ) {
            $residenceZipErr = 'Zip number should be 6 digits';
            $error++;
        }
        
        $residenceFax = Validation::getCorrectData($_POST["residenceFax"]);

        if ( Validation::validateNumber($residenceFax) ) {
            $residenceFaxErr = 'Only numbers are allowed';
            $error++;
        }

        if ( Validation::validateFax($residenceFax) ) {
            $residenceFaxErr = 'Fax should be less than 15 digits';
            $error++;
        }

        $officeStreet = Validation::getCorrectData($_POST["officeStreet"]);

        if ( Validation::validateLength($officeStreet, 50) ) {
            $officeStreetErr = 'Only 50 characters allowed';
            $error++;
        }
        $officeCity = Validation::getCorrectData($_POST["officeCity"]);
        
        if ( !Validation::validateText($officeCity) ) {
            $officeCityErr = 'Only letters and white space allowed';
            $error++;
        }

        if ( Validation::validateLength($officeCity, 50) ) {
            $officeCityErr = 'Only 50 characters allowed';
            $error++;
        }
        
        $officeState = Validation::getCorrectData($_POST["officeState"]);
        $_SESSION["officeState"] = $officeState;
        $officeZip = Validation::getCorrectData($_POST["officeZip"]);
        
        if ( Validation::validateNumber($officeZip) ) {
            $officeZipErr = "Only numbers are allowed";
            $error++;
        }

        if ( Validation::validateZip($officeZip) ) {
            $officeZipErr = 'Zip number should be 6 digits';
            $error++;
        }
        
        $officeFax = Validation::getCorrectData($_POST["officeFax"]);

        if ( Validation::validateNumber($officeFax) ) {
            $officeFaxErr = "Only numbers are allowed";
            $error++;
        }

        if ( Validation::validateFax($officeFax) ) {
            $officeFaxErr = 'Fax should be less than 15 digits';
            $error++;
        }
        $note = Validation::getCorrectData($_POST["note"]);

        if ( Validation::validateLength($note, 150) ) {
            $noteErr = 'Only 150 characters allowed';
            $error++;
        }

        //Check for Communication Medium,if its empty then assign an empty array
        if( isset($_POST["commMed"]) ) {
           $commMedium = $_POST["commMed"];
           $_SESSION["commMedium"] = $commMedium;  
        }else{
            $commMedium=array();
        }
        

        //Insert the user data in the database if there are no errors.
        if( $error == 0 && $_POST["submit"] == "SUBMIT" ) {
            //Move the image to a specific folder
            move_uploaded_file($_FILES['image']['tmp_name'],APP_PATH."/profile_pic/".$_FILES['image']['name']);

            //Array to store employee details
            $empData = array( 'prefix' => $prefix, 'firstName' => $firstName, 'middleName' => $middleName,
                'lastName' => $lastName, 'gender' => $gender, 'dob' => $dob, 'mobile' => $mobile, 'landline' => $landline,
                'email' => $email, 'password' => md5($password), 'maritalStatus' => $maritalStatus, 'employment' => $employment, 'employer' => $employer,
                'photo' => $photo, 'note' => $note);
            //Insert the employee details and get the last insert id.
            $empID = $dbOperations->insert('employee', $empData);
            
            if ( $empID === false ) {
                $_SESSION['dbErr'] = 1;
                header("Location:registration_form.php");
                exit();    
            }

            //Array to store employye address
            $empAddressData = array( 'employeeId' => $empID, 'residenceStreet' => $residenceStreet, 'resedenceCity' => $resedenceCity,
                'resedenceState' => $resedenceState, 'residenceZip' => $residenceZip, 'residenceFax' => $residenceFax,
                'officeStreet' => $officeStreet, 'officeCity' => $officeCity, 'officeState' => $officeState, 'officeZip' => $officeZip,
                'officeFax' => $officeFax );
             
            //Insert the address
            $address = $dbOperations->insert('address', $empAddressData, $empID);

            if ( !$address ) {
                $_SESSION['dbErr'] = 1;
                header("Location:registration_form.php");
                exit();
            }
            
            // insert communication medium
            $msg = in_array("msg", $commMedium) ? 1 : 0;
            $comEmail = in_array("mail", $commMedium) ? 1 : 0;
            $call = in_array("phone", $commMedium) ? 1 : 0;
            $any = in_array("any", $commMedium) ? 1 : 0;

            //Array to store employee communication medium
            $commMediumData = array( 'employeeId' => $empID, 'message' => $msg, 'comEmail' => $comEmail,
                'call' => $call, 'any' => $any );
            
            $commMedium = $dbOperations->insert('commMedium', $commMediumData, $empID);
            if ( !$commMedium ) {
                $_SESSION['dbErr'] = 1;
                header("Location:registration_form.php");
                exit();
            }

            //Destroy the session 
            session_unset();
            session_destroy();

            //If successfully inserted ,then redirect to index page
            header("Location:index.php?message=register");
        }

        //If there are no errors and submit name is update
        if( $error == 0 && $_POST["submit"]=="UPDATE" ) {

            if( isset($_FILES['image']) && !empty($_FILES['image']['name']) && $_FILES['image']['size'] != 0) {
                move_uploaded_file($_FILES['image']['tmp_name'],APP_PATH ."/profile_pic/".$_FILES['image']['name']);

                $image ="SELECT employee.photo FROM employee WHERE eid=" . $_GET["userId"] . ";";

                $result = $dbOperations->executeSql($image) or 
                header("Location:details.php?Message=1");

                $row = $result->fetch_assoc();

                if ( !unlink(APP_PATH."/profile_pic/".$row["photo"]) ) {
                  header("Location:details.php?Message=1");
                }
            }

            $updateResidenceData = array( 'rstreet' => $residenceStreet, 'rcity' => $resedenceCity,
                'rstate' => $resedenceState, 'rzip' => $residenceZip, 'rfax' => $residenceFax);

            if ( !$dbOperations->update('address', $updateResidenceData, $_GET["userId"], 1) ) {
                header("Location:registration_form.php?dbErr=1");
            } 
            
            $updateOfficeData = array( 'ostreet' => $officeStreet, 'ocity' => $officeCity,
                'ostate' => $officeState, 'ozip' => $officeZip, 'ofax' => $officeFax);
            
            if ( !$dbOperations->update('address', $updateOfficeData, $_GET["userId"], 2) ) {
                header("Location:registration_form.php?dbErr=1");
            }
            
            // Update communication medium
            $msg = in_array("msg", $commMedium) ? 1 : 0;
            $comEmail = in_array("mail", $commMedium) ? 1 : 0;
            $call = in_array("phone", $commMedium) ? 1 : 0;
            $any = in_array("any", $commMedium) ? 1 : 0;
            
            $updateCommMedium = array( 'msg' => $msg, 'email' => $comEmail, 'call' => $call,
                'any' => $any );
                
            if ( !$dbOperations->update('commMedium', $updateCommMedium, $_GET["userId"] ) ) {
                header("Location:registration_form.php?dbErr=1");
            }
            
            //If photo is empty then dont update photo
            if( empty($photo) ) {
                $insertImage ="";
            }else {
                $insertImage = ", photo = '".$photo."'";
            }

            $updateEmpDetails = array( 'prefix' => $prefix, 'firstName' => $firstName, 'middleName' => $middleName,
                'lastName' => $lastName, 'gender' => $gender, 'dob' => $dob, 'mobile' => $mobile,
                'landline' => $landline, 'email' => $email, 'password' => md5($password), 'maritalStatus' => $maritalStatus,
                'employment' => $employment, 'employer' => $employer, 'insertImage' => $insertImage, 'note' => $note );
                
            if ( !$dbOperations->update('employee', $updateEmpDetails, $_GET["userId"] ) ) {
                header("Location:registration_form.php?dbErr=1");
            }
            
            //If update is successfull then redirect to index page
            header("Location:index.php?message=update");
        }
    }

    //When user clicks the update button in the details page,then this code is executed
    if ( isset($_GET["userId"]) && isset($_GET["userAction"]) ) {

        //Check for user's session
        if ( !isset($_SESSION['employeeId']) ) {
            echo '<h3>You are not authoried to access this page ! Please 
                <a href="login.php">login </a></h3>';
            exit();
        }

        $details = $dbOperations->selectEmployee($_GET["userId"]);
        if ( $details === false ) {
            header("Location:registration_form.php?dbErr=1");
            exit();
        }
        $empDetails = $details->fetch_assoc();
        
        $residence = $dbOperations->selectEmployee($_GET["userId"], 1);
        if ( $residence === false ) {
            header("Location:registration_form.php?dbErr=1");
            exit();
        }
        $empResidence = $residence->fetch_assoc();

        $office = $dbOperations->selectEmployee($_GET["userId"], 2);
        if ( $office === false ) {
            header("Location:registration_form.php?dbErr=1");
            exit();
        }
        $empOffice = $office->fetch_assoc();

        //set the image name into a session variable,so that image keeps showing if the update fails due to error
        $_SESSION['photo'] = $empDetails['photo'];      
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Form</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
            integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <style type="text/css">
            .error{
                color: red;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" 
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">HOME</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo "$navLink1";?> "><?php echo "$navLink1Name";?></a></li>
                        <li><a href="<?php echo "$navLink2";?>"><?php echo "$navLink2Name";?></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
        <h1><?php 
                if( (isset($_GET['userAction']) && $_GET['userAction']=='update') 
                    || ( isset($_GET["userId"]) && $_GET["userId"] > 0) ) {
                    echo "Please edit your data";
                }else {
                    echo "REGISTER";
                }
            ?>
        </h1>
        <form action=<?php echo $form_action; ?> method="post" role="form" class="form-horizontal" 
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <fieldset>
                        <legend>Personal Details</legend>
                        <div class="well">
                            <!-- Select dropdown for prefix -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="selectbasic1">Prefix</label>
                                <div class="col-md-7">
                                    <select id="selectbasic1" name="prefix" class="form-control" >
                                        <option value="mr" 
                                        <?php 
                                            if (  checkedStatus("prefix", "mr") ) {

                                                echo 'selected="selected"';
                                            } 
                                        ?> 
                                        >Mr
                                        </option>
                                        
                                        <option value="mis" 
                                        <?php 
                                            if ( checkedStatus("prefix","mis") ) {

                                                echo 'selected="selected"';
                                            } 
                                        ?> >Miss
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Input field for first name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="firstName">First Name</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php 
                                        if( !empty($firstnameErr) ) {
                                            echo "*".$firstnameErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="firstName" type="text" placeholder="First Name" class="form-control input-md" 
                                    <?php

                                        if( isset($empDetails["firstName"]) ) {
                                            echo 'value="'.$empDetails["firstName"].'"';
                                        }

                                        if( isset($firstName) ) {
                                            echo 'value="'.$firstName.'"';
                                        }
                                    ?> 
                                    required >
                                </div>
                            </div>
                            <!-- Input field for middle name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="middleName">Middle Name</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php 
                                        if( !empty($middleNameErr) ) {
                                            echo "*".$middleNameErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="middleName" type="text" placeholder="Middle Name" class="form-control input-md"
                                    <?php
                                        if( isset($empDetails["middleName"]) ) {
                                            echo 'value="'.$empDetails["middleName"].'"';
                                        } 
                                        if( isset($middleName) ) {
                                            echo 'value='.$middleName;
                                        }
                                    ?>
                                     >
                                </div>
                            </div>
                            <!-- Input field for last name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="lastName">Last Name</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($lastNameErr) ) {
                                            echo "*".$lastNameErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="lastName" type="text" placeholder="Last Name" class="form-control input-md" 
                                    <?php
                                        if( isset($empDetails["lastName"]) ) {
                                            echo 'value="'.$empDetails["lastName"].'"';
                                        } 
                                        if( isset($lastName) ) {
                                            echo 'value='.$lastName;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Radio button for gender -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="gender">Gender</label>
                                <div class="col-md-7"> 
                                    <label class="radio-inline">
                                    <input type="radio" name="gender" value="m" checked="checked">
                                    Male
                                    </label> 
                                    <label class="radio-inline">
                                    <input type="radio" name="gender" value="f" 
                                    <?php 
                                        if( checkedStatus("gender","f")) {

                                            echo 'checked="checked"';
                                        } 
                                    ?>
                                    >
                                    Female
                                    </label> 
                                    <label class="radio-inline">
                                    <input type="radio" name="gender" value="o"
                                    <?php 
                                        if( checkedStatus("gender", "o") ) {

                                            echo 'checked="checked"';
                                        } 
                                    ?>
                                    >
                                    Other
                                    </label>
                                </div>
                            </div>
                            <!-- Input field for date -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="datepicker">D.O.B</label>  
                                <div class="col-md-7">
                                    <input name="dob" type="date" placeholder="D.O.B" class="form-control input-md" 
                                    <?php
                                        if( isset($empDetails["dob"]) ) { 
                                            echo 'value="'.$empDetails["dob"].'"'; 
                                        }
                                        else if ( !empty($_SESSION["dob"]) ) {
                                            echo 'value="'.$_SESSION["dob"].'"';
                                        }
                                    ?> 
                                    >
                                </div>
                            </div>
                            <!-- Input field for mobile number -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="phone">Moblie</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php 
                                        if( !empty($mobileErr) ) {
                                            echo "*".$mobileErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="mobile" type="text" placeholder="9999-9999-9999" class="form-control input-md"
                                    <?php 
                                        if( isset($empDetails["mobile"]) ) {
                                            echo 'value="'.$empDetails["mobile"].'"';
                                        } 
                                        if( isset($mobile) ) {
                                            echo 'value='.$mobile;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for landline number -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="phone">Landline</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($landlineErr) ) {
                                            echo "*".$landlineErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="landline" type="text" placeholder="9999-99999999" class="form-control input-md"
                                    <?php
                                        if( isset($empDetails["landline"]) ) {
                                            echo 'value="'.$empDetails["landline"].'"';
                                        } 
                                        if( isset($landline) ) {
                                            echo 'value='.$landline;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for email -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="firstName">Email</label>  
                                <div class="col-md-7">
                                    <span class="error">
                                    <?php 
                                        if( !empty($emailErr) ) {
                                            echo "*".$emailErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="email" type="email" placeholder="example@mail.com" class="form-control input-md"
                                    <?php 
                                        if( isset($empDetails["email"]) ) {
                                            echo 'value="'.$empDetails["email"].'"';
                                        } 
                                        if( isset($email) ) {
                                            echo 'value="'.$email.'"';
                                        }
                                    ?>
                                    required >
                                </div>
                            </div>
                            <!-- Input field for password -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="password">Password</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php 
                                        if( !empty($passwordErr) ) {
                                            echo "*".$passwordErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="password" type="password" placeholder="Password" class="form-control input-md" required >
                                </div>
                            </div>
                            <!-- Radio button for marital status -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="m_status">Marital Status</label>
                                <div class="col-md-7"> 
                                    <label class="radio-inline">
                                    <input type="radio" name="maritalStatus" value="married" checked="checked">
                                    Married
                                    </label> 
                                    <label class="radio-inline">
                                    <input type="radio" name="maritalStatus" value="unmarried"
                                    <?php 
                                        if( checkedStatus("maritalStatus", "unmarried") ) {

                                            echo 'checked="checked"';
                                        }
                                    ?>
                                    >
                                    Unmarried
                                    </label> 
                                </div>
                            </div>
                            <!-- Radio button for employment -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="employment">Employment</label>
                                <div class="col-md-7"> 
                                    <label class="radio-inline">
                                    <input type="radio" name="employment" value="employed" checked="checked">
                                    Employed
                                    </label> 
                                    <label class="radio-inline">
                                    <input type="radio" name="employment" value="unemployed"
                                        <?php
                                            if( checkedStatus("employment", "unemployed") ) {

                                                echo 'checked="checked"';
                                            }
                                        ?>
                                        >
                                    Unemployed
                                    </label> 
                                </div>
                            </div>
                            <!-- Input field for employer -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="employer">Employer</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php 
                                        if( !empty($employerErr) ) {
                                            echo "*".$employerErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="employer" type="text" placeholder="Employer" class="form-control input-md"
                                    <?php
                                        if( isset($empDetails["employer"]) ) {
                                            echo 'value="'.$empDetails["employer"].'"';
                                        } 
                                        if( isset($employer) ) {
                                            echo 'value='.$employer;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for image upload --> 
                            <div class="form-group">
                                <label class="col-md-3 control-label">Upload Photo</label>
                                <div class="col-md-7">
                                    <span class="error">
                                    <?php
                                        if( !empty($imageErr) ) {
                                            echo "*".$imageErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="image" class="input-file" type="file" >
                                    <?php 
                                        if( isset($empDetails["photo"]) && !empty($empDetails["photo"]) ) { 
                                            echo '<img src="profile_pic/'.$empDetails["photo"].'"  alt="profile pic" 
                                                height="200" width="200" />';

                                        } else if ( isset($_SESSION['photo']) && $_SESSION['photo'] != "" ) {
                                            echo '<img src="profile_pic/'.$_SESSION['photo'].'"  alt="profile pic" 
                                                height="200" width="200" />';
                                        } 
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <fieldset>
                        <legend>Residence Address</legend>
                        <div class="well">
                            <!-- Input field for residence street -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Street</label>  
                                <div class="col-md-7">
                                    <span class="error">
                                    <?php
                                        if( !empty($residenceStreetErr) ) {
                                            echo "*".$residenceStreetErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="residenceStreet" type="text" placeholder="Street" class="form-control input-md"
                                    <?php
                                        if( isset($empResidence["street"]) ) {
                                            echo 'value="'.$empResidence["street"].'"';
                                        } 
                                        if( isset($residenceStreet) ) {
                                            echo 'value='.$residenceStreet;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for residence city-->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >City</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($residenceCityErr) ) {
                                            echo "*".$residenceCityErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="resedenceCity" type="text" placeholder="City" class="form-control input-md"
                                    <?php
                                        if( isset($empResidence["city"]) ) {
                                            echo 'value="'.$empResidence["city"].'"';
                                        } 
                                        if( isset($resedenceCity) ) {
                                            echo 'value='.$resedenceCity;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for residence state dropdown -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >State</label>
                                <div class="col-md-7">
                                    <select name="residenceState" class="form-control">
                                        <option value="">Select State</option>
                                        <?php
                                            //Here $states is the array,declared at the top of the page
                                            foreach ($states as $state_name) {

                                                echo '<option value="' . $state_name . '" ' 
                                                    . (( (isset($empResidence["state"]) && $empResidence["state"]==$state_name) 
                                                    || (!empty($_SESSION["residenceState"]) && $_SESSION["residenceState"] == $state_name))
                                                    ? ('selected="selected"') : ('')) . '>' . $state_name . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- input field for zip -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Zip</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($residenceZipErr) ) {
                                            echo "*".$residenceZipErr;
                                        }
                                    ?>
                                    </span>
                                    <input name="residenceZip" type="text" placeholder="Zip" class="form-control input-md"
                                    <?php 
                                        if( isset($empResidence["zip"]) ) {
                                            echo 'value="'.$empResidence["zip"].'"';
                                        } 
                                        if( isset($residenceZip) ) {
                                            echo 'value='.$residenceZip;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for residence fax -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Fax</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($residenceFaxErr) ) {
                                            echo "*".$residenceFaxErr;
                                        }
                                    ?>
                                    </span>
                                    <input name="residenceFax" type="text" placeholder="Fax" class="form-control input-md"
                                    <?php
                                        if( isset($empResidence["fax"]) ) {
                                            echo 'value="'.$empResidence["fax"].'"';
                                        } 
                                        if( isset($residenceFax) ) {
                                            echo 'value='.$residenceFax;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Office Address</legend>
                        <div class="well">
                            <!-- Input field for office street -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Street</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($officeStreetErr) ) {
                                            echo "*".$officeStreetErr;
                                        }
                                    ?>
                                    <input  name="officeStreet" type="text" placeholder="Street" class="form-control input-md"
                                    <?php 
                                        if( isset($empOffice["street"]) ) {
                                            echo 'value="'.$empOffice["street"].'"';
                                        } 
                                        if( isset($officeStreet) ) {
                                            echo 'value='.$officeStreet;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for office city -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >City</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($officeCityErr) ) {
                                            echo "*".$officeCityErr;
                                        }
                                    ?>
                                    </span>
                                    <input  name="officeCity" type="text" placeholder="City" class="form-control input-md"
                                    <?php 
                                        if( isset($empOffice["city"]) ) {
                                            echo 'value="'.$empOffice["city"].'"';
                                        } 
                                        if( isset($officeCity) ) {
                                            echo 'value='.$officeCity;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for office state dropdown -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >State</label>
                                <div class="col-md-7">
                                    <select name="officeState" class="form-control">
                                        <option value="">Select State</option>
                                        <?php
                                            //Here $states array is declared at the top of the page
                                            foreach ($states as $state_name) {

                                                echo '<option value="' . $state_name . '" ' . 
                                                    (( (isset($empOffice["state"]) && $empOffice["state"] == $state_name) 
                                                    || (!empty($_SESSION["officeState"]) && $_SESSION["officeState"] == $state_name) ) 
                                                    ? ('selected="selected"') : ('')) . '>' . $state_name . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Input field for office zip -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Zip</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($officeZipErr) ) {
                                            echo "*".$officeZipErr;
                                        }
                                    ?>
                                    </span>
                                    <input name="officeZip" type="text" placeholder="Zip" class="form-control input-md"
                                    <?php
                                        if( isset($empOffice["zip"]) ) {
                                            echo 'value="'.$empOffice["zip"].'"';
                                        } 
                                        if( isset($officeZip) ) {
                                            echo 'value='.$officeZip;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                            <!-- Input field for office fax -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" >Fax</label>  
                                <div class="col-md-7">
                                    <span class="error"> 
                                    <?php
                                        if( !empty($officeFaxErr) ) {
                                            echo "*".$officeFaxErr;
                                        }
                                    ?>
                                    </span>
                                    <input name="officeFax" type="text" placeholder="Fax" class="form-control input-md"
                                    <?php
                                        if( isset($empOffice["fax"]) ) {
                                            echo 'value="'.$empOffice["fax"].'"';
                                        } 
                                        if( isset($officeFax) ) {
                                            echo 'value='.$officeFax;
                                        }
                                    ?>
                                    >
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <fieldset>
                        <legend>Other Details</legend>
                        <div class="well">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <!-- Input field for note -->
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="textarea">Note</label>
                                        <div class="col-md-7">
                                            <span class="error"> 
                                            <?php
                                                if( !empty($noteErr) ) {
                                                    echo "*".$noteErr;
                                                }
                                            ?>
                                            </span>                     
                                            <textarea class="form-control" id="note" name="note" 
                                                rows="3"><?php if( isset($empDetails["note"]) ) {
                                                    echo $empDetails["note"];
                                                } 
                                                if( isset($note) ) {
                                                    echo $note;
                                                }
                                            ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
                                            <label>Communication medium:</label>
                                        </div>
                                        <div class="col-xs-9 col-sm-8 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
                                            <div class="checkbox-inline">
                                                <input type="checkbox" id="mail" name="commMed[]" value="mail"
                                                <?php
                                                    if( (isset($empDetails["comm_email"]) && $empDetails["comm_email"]=="1") 
                                                        || ( isset($_SESSION["commMedium"]) && in_array("mail", $_SESSION["commMedium"]) ? TRUE : FALSE) ) {
                                                            
                                                        echo 'checked';
                                                    }
                                                ?> 
                                                >
                                                <label for="mail">Mail</label>
                                            </div>
                                            <div class="checkbox-inline">
                                                <input type="checkbox" id="message" name="commMed[]" value="msg"
                                                <?php
                                                    if( (isset($empDetails["msg"]) && $empDetails["msg"] == "1") 
                                                        || ( isset($_SESSION["commMedium"]) && in_array("msg", $_SESSION["commMedium"]) ? TRUE : FALSE) ) {
                                                           
                                                            echo 'checked';
                                                    } 
                                                ?>
                                                >
                                                <label for="message">Message</label>
                                            </div>
                                            <div class="checkbox-inline">
                                                <input type="checkbox" id="phone" name="commMed[]" value="phone"
                                                <?php
                                                    if( (isset($empDetails["call"]) && $empDetails["call"] == "1") 
                                                        || ( isset($_SESSION["commMedium"]) && in_array("phone", $_SESSION["commMedium"]) ? TRUE : FALSE) ){

                                                        echo 'checked';
                                                    } 
                                                ?>
                                                >
                                                <label for="phone">Phone</label>
                                            </div>
                                            <div class="checkbox-inline">
                                                <input type="checkbox" id="any" name="commMed[]" value="any" 
                                                <?php 
                                                    if( (isset($empDetails["any"]) && $empDetails["any"] == "1") 
                                                        || ( isset($_SESSION["commMedium"]) && in_array("any", $_SESSION["commMedium"]) ? TRUE : FALSE) ) {

                                                        echo 'checked';
                                                    }
                                                ?>
                                                >
                                                <label for="any">Any</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row text-center">
                    <input type="submit" name="submit" value=
                    <?php 
                        if( (isset($_GET['userAction']) && $_GET['userAction']=='update') 
                            || ( isset($_GET["userId"]) && $_GET["userId"] > 0) ) {

                            echo 'UPDATE'; 
                        } else {
                            echo 'SUBMIT';
                        }
                        ?>
                        class="btn btn-primary"> &nbsp;  &nbsp;  &nbsp;
                    <input type= 
                    <?php 
                       if( (isset($_GET['userAction']) && $_GET['userAction']=='update') 
                            || ( isset($_GET["userId"]) && $_GET["userId"] > 0) ) {
                            
                            echo 'hidden'; } else {echo 'reset';
                        }
                        ?>
                        name="Reset" class="btn btn-danger">
                </div>
        </form>
        </div>
    </body>
    <html>