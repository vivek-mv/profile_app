<?php 
    if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
        $error = 0;
        if ( empty($_POST['email']) ) {
            $emailErr = "Email cannot be empty";
            $error++;
        }

        if ( empty($_POST['password']) ) {
            $passwordErr = "Password cannot be empty";
            $error++;
        }

        /**
         * Performs validation for the form input fields
         *
         * @access public
         * @param String $data
         * @return String
         */
        function getCorrectData($data) {

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $email = getCorrectData($_POST["email"]);
        
        if ( !preg_match("/^[a-zA-Z0-9@.]*$/", $email) ) {
            $emailErr = 'Invalid email format';
            $error++;
        }

        if ( strlen($email) > 50 ) {
            $emailErr = 'Only 50 characters allowed';
            $error++;
        }

        $password = getCorrectData($_POST["password"]);
        
        if ( !preg_match("/^[a-zA-Z0-9]*$/", $password) ) {
            $passwordErr = 'Only letters and numbers allowed';
            $error++;
        }
        
        if ( strlen($password) > 11 ) {
            $passwordErr = 'Only 11 characters allowed';
            $error++;
        }

        if ( $error == 0 ) {
            require_once('dbOperations.php');
            $dbOperations = new DbOperations();
            $query = "SELECT * FROM employee WHERE employee.email = '" . $email . "' AND 
                employee.password = '" . $password . "'";
            $employeeData = $dbOperations->executeSql($query);

            if ( $employeeData->num_rows == 0 ) {
                $loginErr = 'Invalid login details';
            }else {
                session_start();
                $employee = $employeeData->fetch_assoc();
                $_SESSION['employeeId'] = $employee['eid'];
                $_SESSION['employeeFirstName'] = $employee['firstName'];
                header("Location:index.php");
                exit();
            }
        } 
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LOGIN</title>
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
                        <li><a href="registration_form.php">SIGN UP</a></li>
                        <li><a href="login.php">LOG IN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                    <form action="login.php" method="post" role="form" class="form-vertical">
                        <fieldset>
                            <legend>Login In</legend>
                            <span class="error">
                                        <?php 
                                            if( !empty($loginErr) ) {
                                                echo "*".$loginErr;
                                            }
                                        ?>
                                        </span>
                            <div class="well">
                                <!-- Input field for email -->
                                <div class="form-group">
                                    <label for="firstName">Email</label>  
                                    <div>
                                        <span class="error">
                                        <?php 
                                            if( !empty($emailErr) ) {
                                                echo "*".$emailErr;
                                            }
                                        ?>
                                        </span>
                                        <input  name="email" type="email" placeholder="example@mail.com" class="form-control input-md" 
                                        <?php
                                            if( isset($email) ) {
                                                echo 'value='.$email;
                                            }
                                        ?>
                                        required >
                                    </div>
                                </div>
                                <!-- Input field for password -->
                                <div class="form-group">
                                    <label for="password">Password</label>  
                                    <div>
                                        <span class="error"> 
                                        <?php 
                                            if( !empty($passwordErr) ) {
                                                echo "*".$passwordErr;
                                            }
                                        ?>
                                        </span>
                                        <input  name="password" type="password" placeholder="Password" class="form-control input-md" 
                                        <?php
                                            if( isset($password) ) {
                                                echo 'value='.$password;
                                            }
                                        ?>
                                        required >
                                    </div>
                                </div>
                                <div class="text-center">
                                    <input type="submit" name="submit" value="LOGIN" class="btn btn-primary">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>