<?php

session_start();
include_once("../includes/config.php");

include_once("../includes/db.php");

include_once("../includes/functions.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Generic Platform</title>

<link rel="stylesheet" href="css/style.css">

<script src="js/jquery-1.js"></script>

<script language="javascript" type="text/javascript" src="js/jquery.js"></script>

</head>

</head>

<body>

<div id="mainWrapper">

	<?php
	if(isUserLoggedin() && isAdmin()>0){
		?>
		<div id="headerWrapper">
			<div id="left_content">
			<a href="<?=BASE_URL_ADMIN?>">Genric Admin</a>
			<a href="<?=BASE_URL?>admin/category.php" style="margin-left:10%; color:#00CC00;">Category</a> &nbsp;
			</div>
			<div id="right_content">
			<a href="<?=BASE_URL?>form-actions.php?action=logout">Logout</a></div>
		</div>
		<?php
	}
	?>