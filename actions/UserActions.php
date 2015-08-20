<?php
session_start();
include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']."/application/config.php");
include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']."/models/UserModel.php");

if(isset($_REQUEST["action"]) && $_REQUEST["action"]="changeUserType"){
  
  if(USER_TYPES_ENABLED && USER_TYPES_SELF_CHANGE){
  	$updated = UserModel::updateUserType($_SESSION['uid'], $_REQUEST["type"]);
	echo "<meta http-equiv='refresh' content='0;url=".$_SERVER['HTTP_REFERER']."'>";
  }
  elseif(USER_TYPES_ENABLED && USER_TYPES_APPROVAL_NEEDED){
  	$updated = UserModel::updateUserTypeStatus($_SESSION['uid'], $_REQUEST["type"]);
	echo "<meta http-equiv='refresh' content='0;url=".$_SERVER['HTTP_REFERER']."'>";
  }  
  else{
  
  }
}
?>
