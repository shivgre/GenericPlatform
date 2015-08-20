<?php 
include("includes/header.php");
if((isUserLoggedin())&&(isAdmin()==0)){
	FlashMessage::add('You are not logged in. Please Login.');
	echo '<META http-equiv="refresh" content="0;URL='.BASE_URL.'admin/index.php">';
	exit();
}

if(isset($_GET["catId"]) && $_GET["catId"]!=""){
	$query = "SELECT * FROM project_categories where project_category_id=".$_GET["catId"];
	$result = mysql_query($query);
	$category = mysql_fetch_array($result);	
}
?>
<script>
$().ready(function() {
	
	// validate signup form on keyup and submit
	$("#createCategoryForm").validate({
		rules: {
			cname: {
				required: true
				},
			newCategory: {
				required: true
			}
		},
		messages: {
			cname: "<p>Please enter Category Name</p>",
			newCategory: "<p>Category Already Exists.Please choose another.</p>"
	});
	
});
function checkCategory(){
//ajax functionality here for checking category is already there or not.
	var category = $('#cname').val();
	$.post( "<?=BASE_URL?>ajax-actions.php",{'category':category}, function( data ){
	  //$( ".result" ).html( data );
	  if(data=='false'){
	  $('#newCategory').val('yes');
	  }
	  else{
	  $('#newCategory').val('');
	  }
	});
}
</script>
<div id="contentWrapper">
	<div id="content">
		<?php 
		if(isset($_SESSION["messages"])){
			echo "<div class='message'>".FlashMessage::render()."</div>";
		}?>
		 <form action="<?=BASE_URL_ADMIN?>category-form-actions.php" method="post" id="createCategoryForm">
		<fieldset>
			<legend><?=isset($category)?"Update Category":"Add New Category"?></legend>
			<div class="form-controller"><span>Category</span>
				<input name="cname" id="cname" placeholder="Enter Category" type="text" value="<?=isset($category)?$category['project_categeory_name']:""?>">
			</div>
			<div class="form-controller">
				<span>Category Description</span>
				<textarea name="description" placeholder="Enter a description"><?=isset($category)?$category['project_category_descr']:""?></textarea>
			</div>
			<div class="form-controller">&nbsp;</div>
			<div class="form-controller">
			<?php
				if(isset($category)){
					echo "<input type='hidden' name='cat_id' value='".$category['project_category_id']."' />";
				}
			?>
			<input name="<?=isset($category)?"update_category_submit":"Category_submit"?>" value="<?=isset($category)?"Update Category":"Create Category"?>" type="submit">&nbsp;&nbsp;<input name="cancel" id="cancel" value="Cancel" type="reset"></div>
		</fieldset>
		</form>
	</div>
</div>
<?php include("includes/footer.php");  ?>