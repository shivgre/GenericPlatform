<?php
session_start();
include_once("../includes/db.php");
include_once("../includes/functions.php");

/************************************/
/**********Add New Category*************/
/************************************/
if(isset($_POST["Category_submit"]) && $_POST["Category_submit"]!=""){
	$cname = mysql_real_escape_string($_POST["cname"]);
	$catDescription = mysql_real_escape_string($_POST["description"]);
		
	$query = "INSERT INTO project_categories(project_categeory_name, project_category_descr) VALUES('".$cname."', '".$catDescription."')";
	$result = mysql_query($query);
	if($result){
		FlashMessage::add('New Category Added Successfull.');
		header("location:category.php");
		exit();
	}
	else{
		FlashMessage::add('New Category Not Added.');
		header("location:category-form.php");
	}
}
/************************************/
/**********Update Category*************/
/************************************/
	if(isset($_POST["update_category_submit"]) && $_POST["update_category_submit"]!=""){
		$cat_id = mysql_real_escape_string($_POST["cat_id"]);
		$cat_name = mysql_real_escape_string($_POST["cname"]);
		$cat_description = mysql_real_escape_string($_POST["description"]);
		
		
		$query = "UPDATE project_categories SET project_categeory_name='".$cat_name."', project_category_descr='".$cat_description."' WHERE project_category_id = $cat_id";
			
		$result = mysql_query($query);
		if($result){
			FlashMessage::add('Category Updated Successfull.');
			header("location:category-form.php?catId=$cat_id");
		}
		else{
			FlashMessage::add('Category Update not successfull.');
			header("location:category-form.php?catId=$cat_id");
		}
	}
	
/************************************/
/**********Delete Category*************/
/************************************/
	if(isset($_GET["action"]) && $_GET["action"]=="delete"){
		$cat_id = $_GET["catID"];
				
		$query = "Delete from project_categories where project_category_id=$cat_id";
		$result = mysql_query($query);
		
		if($result){			
			FlashMessage::add('Category Deleted Successfull.');
			header("location:category.php");
		}
		else{
			FlashMessage::add('Category Deleted not successfull.');
			header("location:category.php");
		}
	}

?>