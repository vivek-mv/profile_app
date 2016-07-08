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
        <style>
            .sort{
                cursor: pointer;
            }
        </style>
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
                <div class="col-md-offset-5">
                    <nav>
                        <ul class="pagination">
                            <li class="page">
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page"><a href="#">1</a></li>
                            <li class="page"><a href="#">2</a></li>
                            <li class="page"><a href="#">3</a></li>
                            <li class="page"><a href="#">4</a></li>
                            <li class="page"><a href="#">5</a></li>
                            <li class="page">
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
        <script type="text/javascript" src="js/search.js"></script>
    </body>
</html>



