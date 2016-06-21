<?php
    /*This page lists all the registered employees
    *This page also handles the delete functionality,
    *which deletes the respective employee record
    *from the database.
    */

    //Enable error reporting
    ini_set('error_reporting', E_ALL);
    
    //include db connection
    require_once('db_conn.php');
    //include the constants file
    require_once('constants.php');

    require_once('dbOperations.php');
    
    //Display error message if delete fails in the same page
    if ( isset($_GET["Message"]) && $_GET["Message"] == 1 ) {

        echo "Sorry delete failed !, please try after some time ";
    }
    
    //When user clicks the delete button in the details listing page
    if (isset($_GET["userAction"]) && $_GET["userAction"] == "delete") {
        
        $dbOperations = new DbOperations();
        $deleteSuccess = $dbOperations->delete($_GET['userId']);
        
        if ( $deleteSuccess ) {
        //If delete is successfull then redirect to the same page
        header("Location:details.php");
        exit();
        } else {
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
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" 
                    aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">VIVEK</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="registration_form.php">SIGN UP</a>
                        </li>
                        <li><a href="#">LOG IN</a>
                        </li>
                        <li><a href="details.php">DETAILS</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="row">
                <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 ">
                    <h2>Registered Employees</h2>
              		<?php
                        //Get the records of all the employees
                        $selectEmpDetails = "SELECT employee.eid, employee.firstName, employee.middleName,
                            employee.lastName, employee.gender, employee.dob, employee.mobile, employee.landline,
                            employee.email, employee.maritalStatus, employee.employment, employee.employer,
                            employee.photo, commMedium.empId, commMedium.msg, commMedium.email AS comm_email, 
                            commMedium.call, commMedium.any, address.eid, address.type, address.street, address.city ,
                            address.state, address.zip, address.fax FROM employee JOIN commMedium ON 
                            employee.eid = commMedium.empId JOIN address ON  employee.eid = address.eid";
                        
                        $employeeDetails = mysqli_query($conn, $selectEmpDetails) or 
                                   header("Location:registration_form.php?dbErr=1");

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
                                    echo "<td>" . date_format( new DateTime( $row["dob"] ), 'd-m-Y' ) . "</td>";
                                    echo "<td>" . $row["mobile"] . "(M)<br>" . $row["landline"] . "(L)</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td>" . ucfirst( $row["maritalStatus"] ) . "</td>";

                                    if ( $row["employment"] == 'employed' ) {
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
                                    if( !empty($row["photo"]) ) {
                                        echo '<img src="profile_pic/'.$row["photo"].'" alt="profile pic " 
                                            height="150" width="150" >';
                                    }

                                    echo '</td>';
                                    echo "<td><a href='registration_form.php?userId=" . $row["eid"] . "&userAction=update' target='_self' >
                                        <span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a></td>";

                                    echo "<td><a href='details.php?userId=" . $row["eid"] . "&userAction=delete' target='_self' > 
                                        <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a></td>";

                                }

                                if ( $employeeId == $row["eid"] ) {
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



