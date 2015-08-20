<?php

session_start();
$_SESSION['lang'] = 'en';

require_once 'config.php';
include_once($GLOBALS['DATABASE_APP_DIR'] . "db.php");
include_once($GLOBALS['LANGUAGE_APP_DIR'] . $_SESSION['lang'] . ".php");
include_once($GLOBALS['APP_DIR'] . "application/functions.php");
include_once($GLOBALS["APP_DIR"] . "models/GenericDBFunctions.php");
include_once($GLOBALS["APP_DIR"] . "DDICT/masterFunctions.php");


//Calling function to generate Datadictionary
//generate_data_dictionary();
if (!isset($_SESSION["datadictionary"]) && empty($_SESSION["datadictionary"]) || $GLOBALS['session_set'] == 0)
{
  $GLOBALS['session_set'] = 1;
  //intake_array($GLOBALS);
}
//exit($GLOBALS["APP_DIR"]);
//include_once($GLOBALS["APP_DIR"] . "system/special_config.php");
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href='http://fonts.googleapis.com/css?family=Galdeano' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,400,600,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>bootstrap.min_1.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>carousel.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>font-awesome.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>common-responsive.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL ?>responsive.css">
    <script src="http://scrollrevealjs.org/js/scrollReveal.min.js?ver=0.2.0-rc.1"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>socprox3.0/resources/css/responsive.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>socprox3.0/resources/css/style.css">
    <script src="<?php echo BASE_URL ?>socprox3.0/resources/js/jquery-1.11.1.min.js"></script>
    <script src="<?php echo BASE_URL ?>socprox3.0/resources/js/jquery-ui.js"></script>
     <script src="http://malsup.github.com/jquery.form.js"></script> 
    

    

    <!-- CAPSTONE: Override Uploadcare text -->
    <script type="text/javascript">

      UPLOADCARE_PUBLIC_KEY = '4c3637988f9b93d343e8';
      UPLOADCARE_LOCALE_TRANSLATIONS = {
        ready: 'Update Profile Photo'
      };
      UPLOADCARE_LOCALE_TRANSLATIONS = {
        errors: {
          'portrait': "<?php echo ERROR_PORTRAIT ?>",
          'dimensions': "<?php echo ERROR_DIMENSIONS ?>"  // message for widget
        },
        dialog: {tabs: {preview: {error: {
                'portrait': {// messages for dialog's error page
                  title: "<?php echo ERROR_PORTRAIT_TITLE ?>",
                  text: "<?php echo ERROR_PORTRAIT_TEXT ?>",
                  back: "<?php echo BACK_BUTTON ?>"
                },
                'dimensions': {// messages for dialog's error page
                  title: "<?php echo ERROR_DIMENSIONS_TITLE ?>",
                  text: "<?php echo ERROR_DIMENSIONS_TEXT ?>",
                  back: "<?php echo BACK_BUTTON ?>"
                }
              }}}}
      };
      UPLOADCARE_PATH_VALUE = true;
      UPLOADCARE_CROP = "2:3";
    </script>
  </head>
  <body>
 
   
    <script type="text/javascript">
      (function ($) {
        "use strict";
        window.scrollReveal = new scrollReveal({reset: true, move: "50px"});
      })();

    </script>

    