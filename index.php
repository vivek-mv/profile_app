<?php 
    //Enable error reporting
    ini_set('error_reporting', E_ALL);

     if ( isset($_GET['message']) ) {
         if ( $_GET['message'] == 1 ) {

             $message = 'It seems that you are trying to upload a very large file,<br>
            . Please upload an image of size less than 2 MB ';
         } else if ( $_GET['message'] == 2 ) {

             $message = 'Please login to access your account';
         }
     } else {

        $message = 'Welcome to employee registration portal';
     }
    require_once('header.php');
    //Setup Navigation links
    $header = new Header();
    $header->setNavLinks('registration_form.php', 'SIGN UP', 'login.php', 'LOG IN');

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
            $wish = 'You have successfully updated the details';
        }
        if ( isset($_GET['message']) && $_GET['message'] == '1' ) {
            $wish = 'It seems that you are trying to upload a very large file <br>
                Please upload an image of size less than 2 MB';
        }

        if ( isset($_GET['message']) && $_GET['message'] == '2' ) {
            $wish = 'You can only access your account';
        }

        $message = 'Welcome ' . $_SESSION['employeeFirstName'] . ' , ' . $wish;

        //Change Navigation links
        $header->setNavLinks('details.php', 'DETAILS', 'logout.php', 'LOG OUT');
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
        <noscript>
            This site uses javascript to serve its full functionality. Please enable javascript . Thank You :)
        </noscript>
        <?php $header->renderHeader(); ?>
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
    <script type="text/javascript"></script>
    </body>
</html>