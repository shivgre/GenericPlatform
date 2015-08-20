<?php
session_start();
include_once("../includes/db.php");
include_once("../includes/functions.php");

/************************************/
/**********Add New User*************/
/************************************/
if(isset($_POST["reg_submit"]) && $_POST["reg_submit"]!=""){
	$uname = mysql_real_escape_string($_POST["uname"]);
	$password = mysql_real_escape_string($_POST["password"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$country = mysql_real_escape_string($_POST["country"]);
	$company = mysql_real_escape_string($_POST["company"]);
	$date_added = date('Y-m-d H:i:s');
	$login_ip = get_client_ip();
	//print_r($login_ip);exit;
	
	$query = "INSERT INTO users(uname, password, email, country, company, date_added, login_ip) VALUES('".$uname."', '".$password."', '".$email."', '".$country."', '".$company."', '".$date_added."', '".$login_ip."')";
	
	$result = mysql_query($query);
	if($result){
		FlashMessage::add('New User Added Successfull.');
		header("location:index.php");
		exit();
	}
	else{
		FlashMessage::add('New User Could Not Be Added Successfull.');
		header("location:index.php");
		exit();
	}
}
/************************************/
/**********Update User*************/
/************************************/
	if(isset($_POST["update_reg_submit"]) && $_POST["update_reg_submit"]!=""){
		$uid = mysql_real_escape_string($_POST["uid"]);
		$uname = mysql_real_escape_string($_POST["uname"]);
		$email = mysql_real_escape_string($_POST["email"]);
		$country = mysql_real_escape_string($_POST["country"]);
		$isActive = mysql_real_escape_string($_POST["active"]);
		$level = mysql_real_escape_string($_POST["level"]);
		$company = mysql_real_escape_string($_POST["company"]);
		
		
		$query = "UPDATE users SET uname='".$uname."' , email='".$email."', country='".$country."', company='".$company."', isActive=".$isActive.", level=".$level." WHERE uid = $uid";
			
		$result = mysql_query($query);
		if($result){
			FlashMessage::add('User Updated Successfull.');
			header("location:index.php");
		}
		else{
			FlashMessage::add('User Update not successfull.');
			header("location:index.php");
		}
	}
	
/************************************/
/**********Delete User*************/
/************************************/
	if(isset($_GET["action"]) && $_GET["action"]=="delete"){
		$uid = $_GET["userId"];
		//Remove User Projects
		$query = "SELECT * FROM projects where uid=$uid";
		$projects = mysql_query($query);
		if(mysql_num_rows($projects)>0){
			while($project = mysql_fetch_array($projects)){
				if($project["projectImage"] !="" && unlink("../img/".$project["projectImage"]) && unlink("../img/thumb_".$project["projectImage"])){
					mysql_query("Delete from projects where pid=".$project["pid"]);
				}
				else{
					mysql_query("Delete from projects where pid=".$project["pid"]);
				}
			}
		}
		
		$query = "select * from users where uid=$uid";
		$result = mysql_query($query);
		$users = mysql_fetch_array($result);
		
		if($users["image"]!=""){
			unlink("../img/".$users["image"]);
			unlink("../img/thumb_".$users["image"]);
		}
		
		$query = "Delete from users where uid=$uid";
		$result = mysql_query($query);
		
		if($result){			
			FlashMessage::add('User Deleted Successfull.');
			header("location:index.php");
		}
		else{
			FlashMessage::add('User Deleted not successfull.');
			header("location:index.php");
		}
	}

?>