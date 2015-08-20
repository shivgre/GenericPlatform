<?php 
//include_once($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']."/actions/ChildPagesActions.php");

//print_r(ChildPagesActions::getChildPagesList());
?>
<div class="container">
	<div class="row">
	<p><a class="btn btn-xs btn-success" href="<?=BASE_URL?>createProject.php?pid=<?=$_GET['pid']?>&tab=script&action=create" style="float:right;"><i class="fa fa-building-o"></i> Create</a></p>
		<table class="table table-striped table-bordered table-hover">
			<tr class="tableheader">
			    <th>&nbsp;</th>
				<th>#</th>
				<th class="sortable child" data-id="0"><i class="fa fa-sort"></i>&nbsp; Name</th>
				<th class="sortable description" data-id="0"><i class="fa fa-sort"></i>&nbsp; Description</th>
				<th>Visibility</th>
				<th class="sortable copy" data-id="0"><i class="fa fa-sort"></i>&nbsp; Copy</th>
				<th class="sortable fork" data-id="0"><i class="fa fa-sort"></i>&nbsp; Fork</th>
				<th class="sortable share" data-id="0"><i class="fa fa-sort"></i>&nbsp; Share</th>
				<th class="sortable subscribe" data-id="0"><i class="fa fa-sort"></i>&nbsp; Subscribe</th>				
				<th>&nbsp;</th>
			</tr>
				<?php
			
				$sql = 'SELECT * FROM project_child WHERE pid='.$_GET['pid'].' ORDER BY id ASC';
				$result = mysql_query($sql);
				if($result === FALSE) {
					die(mysql_error()); // TODO: better error handling
				}
				$count = 1;
				while($row = mysql_fetch_array($result)){
					echo '<tr class="tablebody">
					<td><a class="btn btn-xs btn-success" href="'.BASE_URL.'createProject.php?pid='.$_GET['pid'].'&tab=script&action=update&pcid='.$row['id'].'">
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
				if(!mysql_num_rows($result)) {
					//die(mysql_error()); // TODO: better error handling
					echo "<p>No Child records for this project has been created yet.</p>";
				}							
				?>
		</table>
	</div>
	<!-- /row -->
</div>
<script>
$(document).ready(function(){

	//Function to change the visibility of a child record
	$(document).on('click', '.permission', function(){
		var checkboxId = $(this).attr('id');
		var permission = checkboxId.split('_')[0];
		var value = $(this).attr('value')==1?0:1;
		var childId = checkboxId.split('_')[1];
		
		$.post(
				'<?=BASE_URL?>childPages/projectChildPages-actions.php', 
				{childid:childId, permission:permission, value:value}, 
				function(data){
					if(data){
						$('#'+checkboxId).attr({
							'value' : ''+value,
							'checked' : (value==0)?false:true
						});
					}
					else
						alert(permission+" permission could not be changed. Try it out later.");
				});
	});
	
	//Function for sorting the child records
	$(document).on('click', '.sortable', function(){
		var pid = "<?=$_GET['pid']?>";
		//alert(pid);die();
		var action = "sort";
		var sortOn = $(this).attr('class').split(' ')[1];
		var orderby = ($(this).attr('data-id') == "1") ? 'desc' : 'asc';
		var sortFlag = $(this);
		$.post(
				'<?=BASE_URL?>childPages/projectChildPages-actions.php', 
				{action:action, sortOn:sortOn, orderby:orderby, projId:pid}, 
				function(data){
					if(data){
						if(sortFlag.attr('data-id')==0){
							sortFlag.attr('data-id', 1);
						}
						else{
							sortFlag.attr('data-id', 0);
						}
						$('.tablebody').hide();
						$('table .tableheader').after(data);
					}
					else
						alert("Couldnt sort this out. Sorry buddy.");
				});
	});
});

</script>
			
<style>
.sortable{
	cursor:pointer;
}
</style>