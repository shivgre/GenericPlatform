<?php

include('../application/config.php');
include($GLOBALS['APP_DIR']."application/functions.php");
include($GLOBALS['DATABASE_APP_DIR']."db.php");

if(isset($_GET['action']) && $_GET['action'] == "delete"){
	$sql = 'Delete FROM project_child where id='.$_GET['pcid'];
	$result = mysql_query($sql);
	if($result){
		FlashMessage::add("Child record succesfully deleted.");
		echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."createProject.php?pid=".$_GET['pid']."&tab=script'>";
		exit;
	}	
}

//For inserting child records
if(isset($_POST['createchildrecord'])){
	$projectId = $_POST['pid'];
	$childName = $_POST['projectChildName'];
	$childDesc = $_POST['projectDescription'];
	
	$copy = isset($_POST['copy'])?1:0;
	$fork =isset($_POST['fork'])?1:0;
	$share = isset($_POST['share'])?1:0;
	$subscribe = isset($_POST['subscribe'])?1:0;
	
	$sql = "INSERT INTO project_child(pid, child, description, copy, fork, share, subscribe) VALUES(".$projectId.", '".$childName."', '".$childDesc."', ".$copy.", ".$fork.", ".$share.", ".$subscribe.")";
	$result = mysql_query($sql);
	if($result){
		FlashMessage::add("Child record succesfully added.");
		echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."createProject.php?pid=".$_POST['pid']."&tab=script'>";
		exit;
	}	
}

if(isset($_POST['updatechildrecord'])){
	$projectId = $_POST['projChildId'];
	$childName = $_POST['projectChildName'];
	$childDesc = $_POST['projectDescription'];
	
	$copy = isset($_POST['copy'])?1:0;
	$fork =isset($_POST['fork'])?1:0;
	$share = isset($_POST['share'])?1:0;
	$subscribe = isset($_POST['subscribe'])?1:0;
	
	$sql = "UPDATE project_child SET child='".$childName."', description='".$childDesc."' , copy = $copy, fork = $fork, share = $share, subscribe = $subscribe  WHERE id=".$projectId;
	$result = mysql_query($sql);
	if($result){
		FlashMessage::add("Child record succesfully Updated.");
		echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."createProject.php?pid=".$_POST['pid']."&tab=script'>";
		exit;
	}	
}

if(isset($_POST['childid']) && $_POST['childid']!=""){
	$permission = $_POST['permission'];
	$childid = $_POST['childid'];
	$value = $_POST['value'];
	
	$sql = "Update project_child set $permission=$value WHERE id=$childid";
	
	//echo $sql;exit;
	$result = mysql_query($sql);
	if($result){
		echo true;
	}else{
		echo false;
	}
}

if(isset($_REQUEST['action']) && $_REQUEST['action']=="sort"){
	
	$projectId = $_POST['projId'];
	$orderBy = $_POST['sortOn'];
	$orderWay = $_POST['orderby'];

	$sql = "SELECT * FROM project_child WHERE pid=".$projectId." ORDER BY ".$orderBy." ".$orderWay;
	$result = mysql_query($sql);
	if($result){
	$count = 1;
	while($row = mysql_fetch_array($result)){
					echo '<tr class="tablebody">
					<td><a class="btn btn-xs btn-success" href="'.BASE_URL.'createProject.php?pid='.$projectId.'&tab=script&action=update&pcid='.$row['id'].'">
					<i class="fa fa-pencil" id="'.$row['id'].'"></i></a></td>';
					echo '<td>'. $count++ . '</td>';
					echo '<td>'. $row['child'] . '</td>';
					echo '<td>'. $row['description'] . '</td>';
					if($row['child_visibility'] == 0){
						echo "<td><input type='checkbox' name='visibility' value='0' class='permission' id='visibility_".$row['id']."'></td>";
					}else{
						echo "<td><input type='checkbox' name='visibility' value='1' class='permission' id='visibility_".$row['id']."' checked='checked' ></td>";
					}
					if($row['copy'] == 0){
						echo "<td><input type='checkbox' name='copy' value='0' class='permission' id='copy_".$row['id']."'></td>";
					}else{
						echo "<td><input type='checkbox' name='copy' value='1' class='permission' id='copy_".$row['id']."' checked='checked' ></td>";
					}
					if($row['fork'] == 0){
						echo "<td><input type='checkbox' name='fork' value='0' class='permission' id='fork_".$row['id']."'></td>";
					}else{
						echo "<td><input type='checkbox' name='fork' value='1' class='permission' id='fork_".$row['id']."' checked='checked' ></td>";
					}
					if($row['share'] == 0){
						echo "<td><input type='checkbox' name='share' value='0' class='permission' id='share_".$row['id']."' ></td>";
					}else{
						echo "<td><input type='checkbox' name='share' value='1' class='permission' id='share_".$row['id']."' checked='checked' ></td>";
					}
					if($row['subscribe'] == 0){
						echo "<td><input type='checkbox' name='subscribe' value='0' class='permission' id='subscribe_".$row['id']."'></td>";
					}else{
						echo "<td><input type='checkbox' name='subscribe' value='1' class='permission' id='subscribe_".$row['id']."' checked='checked' ></td>";
					}
					echo '<td>
							<a class="btn btn-xs btn-success" href="'.CHILD_FILES_URL.'projectChildPages-actions.php?pid='.$_GET['pid'].'&tab=script&action=delete&pcid='.$row['id'].'" onclick="return confirm(\'Sure you want to delete?\');">
					<i class="fa fa-times" id="'.$row['id'].'"></i></a></td>';
					echo '</tr>';
				}
				
	}
	if(!mysql_num_rows($result)) {
		//die(mysql_error()); // TODO: better error handling
		echo "<p>No Child records for this project has been created yet.</p>";
	}
}

?>
