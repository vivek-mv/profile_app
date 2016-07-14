<?php
    session_start();
    if ( isset($_SESSION['employeeId']) ) {
        header("Location:index.php");
        exit();
    }
    require_once('validation.php');

    require_once('header.php');

    require_once('acl.php');

    //Setup Navigation links
    $header = new Header();
    $header->setNavLinks('registration_form.php', 'SIGN UP', 'login.php', 'LOG IN');

    if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
        $error = 0;
        if ( empty($_POST['email']) ) {
            $emailErr = 'Email cannot be empty';
            $error++;
        }

        if ( empty($_POST['password']) ) {
            $passwordErr = 'Password cannot be empty';
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

        $password = Validation::getCorrectData($_POST["password"]);
        
        if ( Validation::validatePassword($password) ) {
            $passwordErr = 'Only letters and numbers allowed';
            $error++;
        }
        
        if ( Validation::validateLength($password, 11) ) {
            $passwordErr = 'Only 11 characters allowed';
            $error++;
        }

        if ( $error == 0 ) {
            require_once('dbOperations.php');
            $dbOperations = new DbOperations();
            $query = "SELECT employee.eid, employee.firstName, employee.roleId FROM employee WHERE employee.email = '" . $email . "' AND 
                employee.password = '" . md5($password) . "'";

            $employeeData = $dbOperations->executeSql($query);

            if ( $employeeData->num_rows == 0 ) {
                $loginErr = 'Invalid login details';
            } else {

                $employee = $employeeData->fetch_assoc();
                $_SESSION['employeeId'] = $employee['eid'];
                $_SESSION['roleId'] = $employee['roleId'];
                $_SESSION['employeeFirstName'] = $employee['firstName'];

                //Get the user permissions and store in $_SESSION['userPermissions']
                $acl = new Acl();
                $acl->getResourcePermission($_SESSION['roleId']);
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
        <title>Log In</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
            integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <style type="text/css">
            .error{
                color: red;
            }
        </style>
    </head>
    <body>
        <?php $header->renderHeader(); ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                    <form action="login.php" method="post" role="form" class="form-vertical"
                          onsubmit="checkRequired();return validation.noError;">
                        <fieldset>
                            <legend>Login In</legend>
                            <span class="error">
                                        <?php 
                                            if( !empty($loginErr) ) {
                                                echo $loginErr;
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
                                        <input  name="email" type="email" placeholder="example@mail.com"
                                            class="form-control input-md email required"
                                        <?php
                                            if( isset($email) ) {
                                                echo 'value='.$email;
                                            }
                                        ?>
                                        >
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
                                        <input  name="password" type="password" placeholder="Password"
                                            class="form-control input-md password required"
                                        <?php
                                            if( isset($password) ) {
                                                echo 'value='.$password;
                                            }
                                        ?>
                                        >
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
        <script src="https://code.jquery.com/jquery-3.0.0.min.js"
                integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="
                crossorigin="anonymous">
        </script>
        <script type="text/javascript" src="js/constants.js"></script>
        <script type="text/javascript" src="js/validate.js"></script>
    </body>
</html>