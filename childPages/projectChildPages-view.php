<div class="container">
	<div class="row">
		<h3><?=PROJECT_SCRIPT?></h3>
		<?php
		echo '<p>
			<a class="btn btn-xs btn-success" href="'.BASE_URL.'createProject.php?pid='.$_GET['pid'].'&tab=script&action=update&pcid='.$_GET['pcid'].'">
			<i class="fa fa-pencil" id="'.$row['id'].'"></i> Edit
			</a>
			<a class="btn btn-xs btn-success" href="'.BASE_URL.'childPages/projectChildPages-actions.php?pid='.$_GET['pid'].'&tab=script&action=delete&pcid='.$_GET['pcid'].'">
			<i class="fa fa-times" id="'.$row['id'].'"></i> Delete
			</a></p>';
		?>
	</div>
	<div class="row">
		<?php			
			$sql = 'SELECT * FROM project_child where id='.$_GET['pcid'];
			$result = mysql_query($sql);			
			$row = mysql_fetch_array($result);
		?>
		Child Record Name : </br><p><?=$row['child']?><br /></p>
		Description : </br><p><?=$row['description']?><br /></p>
	</div>
	<!-- /row -->
</div>
			