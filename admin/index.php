<?php 
include("includes/header.php"); 
if(isUserLoggedin() && isAdmin()>0){
	include_once("home.php");
}
else{
	include_once("login.php");
}

include("includes/footer.php"); 
?>