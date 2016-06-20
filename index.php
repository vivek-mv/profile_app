<?php 
    //Enable error reporting
    ini_set('error_reporting', E_ALL);
    
    if ( isset($_GET['message']) && $_GET['message'] == 'update' ) {

        $message = 'You have successfully updated your details';
    }else if ( isset($_GET['message']) && $_GET['message'] == 'register' ) {

        $message = 'Successfully Registered';
    }else {

        $message = 'Welcome to employee registration portal';
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
                    <a class="navbar-brand" href="index.php">VIVEK</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="registration_form.php">SIGN UP</a></li>
                        <li><a href="#">LOG IN</a></li>
                        <li><a href="details.php">DETAILS</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
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