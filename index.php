<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "cfg.php";
require_once CLASSES."gpro.php";
$gpro=new gpro;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="script/css/gproStyle.css">
        <!-- Favicons -->
        <link rel="apple-touch-icon" href="script/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="script/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="script/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="script/favicons/manifest.json">
        <link rel="icon" href="script/favicons/favicon.ico">
        <meta name="theme-color" content="#712cf9">
        <?php echo gpro::$HTML["METAS"];?><?php echo gpro::$HTML["LINKS"];?><?php echo gpro::$HTML["NO_INDEX_LINKS"];?>
        <link href="script/css/bootstrap.min.css" rel="stylesheet">
        <script src="script/js/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="script/bootstrap-icons-1.8.1/bootstrap-icons.css">
        <title><?php echo gpro::$HTML["TITLE"];?></title>
    </head>
    <body>    
        <?php echo gpro::$HTML["NAVIGATION_BLOCK"];?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo gpro::$HTML["MAIN_BLOCK"];?>
        </div>
        
        <?php echo gpro::$HTML["FOOTER_BLOCK"];?>
        <?php echo gpro::$HTML["BODY_SCRIPT_BLOCK"];?>
        <script src="script/js/gproscript.js"></script>
        <script src="script/js/bootstrap.bundle.min.js"></script>
    </body>
</html>