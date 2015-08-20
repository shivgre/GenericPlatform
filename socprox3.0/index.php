<?php
require 'config/dbConfig.php';
require 'lib/functions/appFunctions.php';
if(isset($_REQUEST["pname"])){	
  $request=$_REQUEST["pname"];
  $GLOBALS['tbl'] = $_REQUEST['pname'];
}else {
 $request="index";	
}
include 'templates/main_layout.php';
?>


