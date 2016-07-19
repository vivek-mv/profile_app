<?php
//start the session
session_start();

require_once('checkPermissions.php');
//Check for user permissions
$checkPermission = new CheckPermissions();
if ( !$checkPermission->isAllowed('dashboard','view') && !$checkPermission->isAllowed('dashboard','all') ) {
    echo 'Sorry you are not authorised to access this page';
    exit();
}

require_once('header.php');
//Setup Navigation links
$header = new Header();
$header->setNavLinks('details.php', 'DETAILS', 'logout.php', 'LOG OUT');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
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

            <?php if ( $checkPermission->isAllowed('dashboard','view') || $checkPermission->isAllowed('dashboard','all')) {?>
                <br><button class="btn btn-primary">VIEW</button><br>
            <?php } ?>

            <?php if ( $checkPermission->isAllowed('dashboard','add') || $checkPermission->isAllowed('dashboard','all')) {?>
                <br><button id="add" class="btn btn-primary">ADD</button><br>
            <?php } ?>
            <?php if ( $checkPermission->isAllowed('dashboard','edit') || $checkPermission->isAllowed('dashboard','all')) {?>
                <br><button class="btn btn-primary">EDIT</button><br>
            <?php } ?>

             <?php if ( $checkPermission->isAllowed('dashboard','delete') || $checkPermission->isAllowed('dashboard','all')) {?>
                 <br><button class="btn btn-primary">DELETE</button><br>
             <?php } ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="js/addPermissions.js"></script>
</body>
</html>
