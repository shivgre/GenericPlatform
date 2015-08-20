<?php
if(isset($_GET['action']) && $_GET['action'] == 'update'){
	$pcid = $_GET['pcid'];
	$sql = "SELECT * FROM project_child WHERE id=".$pcid;
	//echo $sql;exit;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	//print_r($row);exit;
}
?>
<div class="container">
	<div class="row">
		<div class="row">
			<h3><?=(isset($_GET['action']) && $_GET['action'] == 'update')?"Update":"Create a"?> Child Record</h3>
		</div>
		<form method="POST" action='<?=CHILD_FILES_URL?>projectChildPages-actions.php'>
			<div class="form-group ">
				<label for="inputFName">Project Child Name</label>
				<input type="text" class="form-control" required="required" id="projectChildName" value="<?=(isset($_GET['action']) && $_GET['action'] == 'update')?$row['child']:""?>" name="projectChildName" placeholder="Project Child Name">
				<input type="hidden" name="projChildId" value="<?=(isset($_GET['action']) && $_GET['action'] == 'update')?$row['id']:""?>">
				</div>
			<div class="form-group">
				<label for="inputLName">Decription</label>
				<textarea class="form-control" required="required" id="projectDescription" name="projectDescription" placeholder="Project Child Description"><?=(isset($_GET['action']) && $_GET['action'] == 'update')?$row['description']:""?></textarea>
				</div>
			
			<div class="form-group">
				<input type="checkbox" name="copy" value="1"  <?=(isset($_GET['action']) && $_GET['action'] == 'update' && $row['copy'] == '1')?"Checked":""?> />				
				<label for="inputLName">Copy</label>
				</div>
				<div class="form-group">
				<input type="checkbox" name="fork" value="1"  <?=(isset($_GET['action']) && $_GET['action'] == 'update' && $row['fork'] == '1')?"Checked":""?> />				
				<label for="inputLName">Fork</label>
				</div>
				<div class="form-group">
				<input type="checkbox" name="share" value="1" <?=(isset($_GET['action']) && $_GET['action'] == 'update' && $row['share'] == '1')?"Checked":""?> />
				<label for="inputLName">Share</label>
				</div>
				<div class="form-group">
				<input type="checkbox" name="subscribe" value="1"  <?=(isset($_GET['action']) && $_GET['action'] == 'update' && $row['subscribe'] == '1')?"Checked":""?> />
				<label for="inputLName">Subscribe</label>
				</div>
				
			<div class="form-actions">
			<input type="hidden" name="pid" value="<?=$_GET['pid']?>" />
				<button type="submit" class="btn btn-success" name="<?=(isset($_GET['action']) && $_GET['action'] == 'update')?"updatechildrecord":"createchildrecord"?>">
				<?=(isset($_GET['action']) && $_GET['action'] == 'update')?"Save":"Create"?>
				</button>
				<a class="btn btn-default" href="<?=BASE_URL?>createProject.php?pid=<?=$_GET['pid']?>&tab=script">Cancel</a> </div>
		</form>
	</div>
	<!-- /row -->
</div>
<!-- /container -->
