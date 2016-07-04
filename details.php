<?php
    /*This page lists all the registered employees
    *This page also handles the delete functionality,
    *which deletes the respective employee record
    *from the database.
    */

    //Enable error reporting
    ini_set('error_reporting', E_ALL);

    session_start();
    //Check for user's session
    if ( !isset($_SESSION['employeeId']) ) {
        header('Location:index.php?message=2');
        exit();
    }

    //include the constants file
    require_once('constants.php');

    require_once('dbOperations.php');
    
    //Display error message if delete fails in the same page
    if ( isset($_GET["Message"]) && $_GET["Message"] == 1 ) {

        echo 'Sorry delete failed !, please try after some time';
    }
    
    //Create DbOperations object which handles all the database operations
    $dbOperations = new DbOperations();
    
    //When user clicks the delete button in the details listing page
    if (isset($_GET["userAction"]) && $_GET["userAction"] == "delete") {
        
        $deleteSuccess = $dbOperations->delete($_GET['userId']);
        
        if ( $deleteSuccess ) {
        //If delete is successfull then redirect to the same page
        header("Location:details.php");
        exit();
        } else {
            //If delete fails 
            header("Location:details.php?Message=1");
            exit();
        }
        
    }
  
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Registered Employees</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
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
                            <li><a href="details.php">DETAILS</a></li>
                            <li><a href="logout.php">LOG OUT</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                    <h2>Registered Employees</h2>
                    <?php
                        $employeeDetails = $dbOperations->selectAllEmployees();

                        if ( $employeeDetails === false ) {
                            echo '<h1> Sorry your request could not be processed, please try after some time :( </h1>';
                            exit();
                        }
                        //When no data is present in the table,display message
                        if ( $employeeDetails->num_rows == 0 ) {

                            echo '<h1> Sorry ! Nothing to display </h1>';
                            exit();
                        }
                    ?>

                    <table class="table table-striped table-responsive">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>D.O.B</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Marital Status</th>
                            <th>Employment </th>
                            <th>Comm. Mode</th>
                            <th title = "Address Residence">Address (R)</th>
                            <th title = "Address Office">Address (O)</th>
                            <th>Photo</th>
                            <th>Update</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                            //Make employeeId = 0 ,so that if condition returns true for the first time.
                            $employeeId = 0;
                            //Fetch all the records and loop through them
                            while ( $row = $employeeDetails->fetch_assoc() ) {

                                if ( $employeeId != $row["empId"] ) {

                                    echo "<tr>";
                                    echo "<td>" . $row["firstName"] ." ". $row["middleName"] ." ". $row["lastName"] . "</td>";

                                    if ( $row["gender"] == 'm' ) {
                                        echo "<td>Male</td>";

                                    } else if ( $row["gender"] == 'f' ) {
                                        echo "<td>Female</td>";

                                    } else {
                                        echo "<td>Others</td>";
                                    }

                                    if ( $row["dob"] == '0000-00-00' ) {
                                        echo "<td></td>";

                                    } else {
                                        echo "<td>" . date_format( new DateTime( $row["dob"] ), 'd-m-Y' ) . "</td>";
                                    }

                                    echo "<td>";

                                    if ( $row["mobile"] !== '' ) {
                                        echo $row["mobile"] . "(M)<br>";
                                    }

                                    if ( $row["landline"] !== '' ) {
                                        echo $row["landline"] . "(L)";
                                    }

                                    echo "</td>";

                                    echo "<td>" . $row["email"] . "</td>";

                                    echo "<td>" . ucfirst( $row["maritalStatus"] ) . "</td>";

                                    if ( $row["employment"] == 'employed' && !empty($row["employer"]) ) {
                                        echo "<td>" . ucfirst( $row["employment"] ) . " in " . ucfirst( $row["employer"] ) . "</td>";

                                    } else {
                                        echo "<td>" . ucfirst( $row["employment"] ) . "</td>";
                                    }

                                    echo "<td>";

                                    if ( $row["msg"] == 1 ) {
                                        echo "Message";
                                    }

                                    if ( $row["comm_email"] == 1 ) {
                                        echo "<br>Email";
                                    }

                                    if ( $row["call"] == 1 ) {
                                        echo "<br>Phone";
                                    }

                                    if ( $row["any"] == 1 ) {
                                        echo "<br>Any";
                                    }

                                    echo "</td>";
                                }

                                //when address is residence
                                if ( $row["type"] == 1 ) {
                                    echo "<td>" . $row["street"] . "<br>" . $row["city"] . "," . $row["zip"] 
                                    . "<br>" . $row["state"] . "</td>";
                                }

                                //when address is office
                                if ( $row["type"] == 2 ) {
                                    echo "<td>" . $row["street"] . "<br>" . $row["city"] . "," . $row["zip"] 
                                    . "<br>" . $row["state"] . "</td>";
                                    echo '<td>';

                                    //Display photo only if photo is present
                                    if ( !empty($row["photo"]) ) {
                                        echo '<img src="profile_pic/'.$row["photo"].'" alt="profile pic " 
                                            height="150" width="150" >';
                                    }

                                    echo '</td><td>';

                                    //Display edit and delete option to the loged in user only
                                    if ( $row['eid'] == $_SESSION['employeeId'] ) {

                                        echo "<a href='registration_form.php?userId=" . $row["eid"] . "&userAction=update' target='_self' >
                                        <span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";
                                    }
                                    echo "</td>";

                                    if ( $row['eid'] == $_SESSION['employeeId'] ) {
                                        echo "<td><a href='details.php?userId=" . $row["eid"] . "&userAction=delete' target='_self' > 
                                            <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";
                                    }
                                    
                                    echo "</td>";

                                }

                                if ( $employeeId == $row["empId"] ) {
                                    echo "</tr>";
                                }

                                $employeeId = $row["empId"];
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>



