<?php
include_once($_SERVER['DOCUMENT_ROOT']."/generic/application/config.php");
include_once(INCLUDE_APP_DIR."functions.php");
include_once(DATABASE_APP_DIR."db.php");

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
	$visibility = $_POST['visibility'];
	if($visibility){
		$sql = "Update project_child set child_visibility=false WHERE id=".$_POST['childid'];
	}else{
		$sql = "Update project_child SET child_visibility=true WHERE id=".$_POST['childid'];
	}
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
			<td><a class="btn btn-xs btn-success" href="'.BASE_URL.'createProject.php?pid='.$_GET['pid'].'&tab=script&action=update&pcid='.$row['id'].'">
			<i class="fa fa-pencil" id="'.$row['id'].'"></i></a></td>';
			echo '<td>'. $count++ . '</td>';
			echo '<td>'. $row['child'] . '</td>';
			echo '<td>'. $row['description'] . '</td>';
			if($row['child_visibility'] == 0){
				echo "<td><input type='button' name='visibility' value='Private' class='visibility' id='".$row['id']."' data-id='0'></td>";
			}else{
				echo "<td><input type='button' name='visibility' value='Public' class='visibility' id='".$row['id']."' data-id='1'></td>";
			}
			echo '
			<td >'.$row['copy'].'</td><td>'.$row['fork'].'</td><td>'.$row['share'].'</td><td>'.$row['subscribe'].'</td>
			<td>
			<a class="btn btn-xs btn-success" href="'.CHILD_FILES_URL.'projectChildPages-actions.php?pid='.$row['pid'].'&tab=script&action=delete&pcid='.$row['id'].'" onclick="return confirm(\'Sure you want to delete?\');">
			<i class="fa fa-times" id="'.$row['id'].'"></i></a></td>';
			echo '</tr>';
		}
	}else{
		echo false;
	}
}

?>
