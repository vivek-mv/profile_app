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

    require_once('logErrors.php');

    require_once('header.php');

    require_once('checkPermissions.php');

    //Check for user permissions
    $checkPermission = new CheckPermissions();
    if ( !$checkPermission->isAllowed('details','view') && !$checkPermission->isAllowed('details','all') ) {
        echo 'Sorry you are not authorised to access this page';
        exit();
    }
    //Setup Navigation links
    $header = new Header();
    $header->setNavLinks('details.php', 'DETAILS', 'logout.php', 'LOG OUT');
    
    //Display error message if delete fails in the same page
    if ( isset($_GET["Message"]) && $_GET["Message"] == 1 ) {

        echo 'Sorry delete failed !, please try after some time';
    }
    
    //Create DbOperations object which handles all the database operations
    $dbOperations = new DbOperations();
    
    //When user clicks the delete button in the details listing page
    if (isset($_GET["userAction"]) && $_GET["userAction"] == "delete") {

        if ( !$checkPermission->isAllowed('details','delete') && !$checkPermission->isAllowed('details','all') ) {
            echo 'Sorry you are not authorised to delete the data';
            exit();
        }

        $deleteSuccess = $dbOperations->delete($_GET['userId']);

        if ( $deleteSuccess ) {

            if ( $_SESSION['employeeId'] == $_GET['userId'] ) {

                //If delete is successfull then redirect to the logout page
                header("Location:logout.php");
                exit();
            } else {
                header("Location:details.php");
                exit();
            }

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
        <style>
            .sort{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <noscript>
            This site uses javascript to serve its full functionality. Please enable javascript . Thank You :)
        </noscript>
        <div class="container">
           <?php $header->renderHeader(); ?>
            <div class="row">
                <div class="col-md-offset-8 col-md-4">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control getData" placeholder="Search by name or email ...">
                            <span class="input-group-btn ">
                                <button class="btn btn-default " type="submit">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <h2>Registered Employees</h2>
                <?php
                $employeeDetails = $dbOperations->selectAllEmployees();

                if ( $employeeDetails === false ) {

                    // log the error to error logs file
                    logError('Database error occured while fetching the details of all employees 
                            in detailss.php ');

                    echo '<h1> Sorry your request could not be processed, please try after some time :( </h1>';
                    exit();
                }
                //When no data is present in the table,display message
                if ( $employeeDetails->num_rows == 0 ) {

                    echo '<h1> Sorry ! Nothing to display </h1>';
                    exit();
                }
                ?>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
                    <div id="noRecords" class="collapse"><h2>Sorry, No records found.</h2></div>
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th class="sort">
                                Name
                                <span class="glyphicon glyphicon-triangle-top"></span>
                            </th>
                            <th>Gender</th>
                            <th>D.O.B</th>
                            <th>Phone</th>
                            <th class="sort">
                                Email
                                <span class="glyphicon glyphicon-triangle-top"></span>
                            </th>
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
                        <tbody id="tablebody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div id="showRowMsg" class="col-md-3"></div>
                <div class="col-md-offset-5">
                    <nav>
                        <ul class="pagination appendBtn">

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" src="js/search.js"></script>
    </body>
</html>



