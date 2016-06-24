<?php 
    //Enable error reporting
    ini_set('error_reporting', E_ALL);
    
    if ( isset($_GET['message']) && $_GET['message'] == 'register' ) {

        $message = 'Successfully Registered';
    }else if ( isset($_GET['message']) && $_GET['message'] == 1 ) {

        $message = 'Your image size exceeds the limit, please upload an image which is less than 2 MB';
    }else {

        $message = 'Welcome to employee registration portal';
    }

    //Setup Navigation links
    $navLink1 = 'registration_form.php';
    $navLink1Name = 'SIGN UP';

    $navLink2 = 'login.php';
    $navLink2Name = 'LOG IN';

    //Start the session
    session_start();
    if ( isset($_SESSION['employeeId']) ) {

        //Create a greeting message with respect to time of the day
        $time = date("H");
        if( $time < 12 ) {

            $wish = 'Good morning';
        } else if ( $time >= 12 && $time < 16) {

            $wish = 'Good afternoon';
        } else if ( $time >= 16 ) {

            $wish = 'Good evening';
        }

        if ( isset($_GET['message']) && $_GET['message'] == 'update' ) {
            $wish = 'You have successfully updated your details';
        }
        if ( isset($_GET['message']) && $_GET['message'] == '1' ) {
            $wish = 'Your image size exceeds the limit, please upload an image which is less than 2 MB';
        }
        
        $message = 'Welcome ' . $_SESSION['employeeFirstName'] . ' , ' . $wish;

        //Change Navigation links
        $navLink1 = 'details.php';
        $navLink1Name = 'DETAILS';

        $navLink2 = 'logout.php';
        $navLink2Name = 'LOG OUT';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>HOME</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
            integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
            <div class="row">
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2 text-center">
                    <h2>
                    <?php 
                        echo $message;
                    ?>
                    </h2>
                </div>
            </div>
        </div>
    </body>
</html>