<form action='profile.php' method='post' id='user_profile_form' enctype='multipart/form-data'>
	<div class="belowborder" >
		<div class='form_element' style="width:98% !important">
			<label>
			<?=USER_DESCRIPTION?>
			</label>
			:<br />
			<span>
			<textarea id"description"   name="description">
			<?=stripslashes($row['description'])?>
			</textarea>
			</span></div>
		<div style="clear:both"></div>
	</div>
	<div style="clear:both"></div>
	<div class='form_element update-btn2'>
		<label><br />
		<input type='hidden' name='uid' value="<?=$row["uid"]?>" />
		<input type='submit' name='profile2_update' value='<?=UPDATE_PROFILE_BUTTON?>' class="btn btn-primary update-btn"  />
		</label>
	</div>
	<div style="clear:both"></div>
</form>
<script src="<?=BASE_URL?>/ckeditor/ckeditor.js"></script>
<script> 
	  //CKEDITOR.replace('description'); 
	  CKEDITOR.replace('description', {
	toolbarGroups: [
		{ name: 'document',	   groups: [ 'mode' ] },																// Line break - next group will be placed in new line.
 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'styles', groups: [ 'Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor'] },
 		{ name: 'insert', groups: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe', 'uploadcare', 'youtube'] }
	]},
{
		allowedContent: true
	});
</script>
