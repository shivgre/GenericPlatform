<?php 
include("includes/header.php"); 
if((isUserLoggedin())&&(isAdmin()==0)){
	FlashMessage::add('You are not logged in. Please Login.');
	echo '<META http-equiv="refresh" content="0;URL='.BASE_URL.'admin/index.php">';
	exit();
}

if(isset($_GET["userId"]) && $_GET["userId"]!=""){
	$query = "SELECT * FROM users where uid=".$_GET["userId"];
	$result = mysql_query($query);
	$user = mysql_fetch_array($result);	
}
?>
<script>
$().ready(function() {
	
	// validate signup form on keyup and submit
	$("#createAccountForm").validate({
		rules: {
			uname: {
				required: true
				},
			country: {
				required: true
				},
			password: {
				required: true,
				minlength: 5
			},
			repassword: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			newEmail:{
				required:true
			}
		},
		messages: {
			uname: "<p>Please enter your First Name</p>",
			country: "<p>Please enter your Country</p>",
			password: {
				required: "<p>Please provide a password<p>",
				minlength: "<p>Your password must be at least 5 characters long</p>"
			},
			repassword: {
				required: "<p>Please provide a password</p>",
				minlength: "<p>Your password must be at least 5 characters long</p>",
				equalTo: "<p>Please enter the same password as above</p>"
			},
			email: "<p>Please enter a valid email address</p>",
			newEmail: "<p> This email alreday exists Please use another</p>"
		}
	});

	// propose username by combining first- and lastname
	$("#username").focus(function() {
		var firstname = $("#fname").val();
		var lastname = $("#lname").val();
		if(firstname && lastname && !this.value) {
			this.value = firstname + "." + lastname;
		}
	});

	
});
function checkEmail(){
//ajax functionality here for checking email is already there or not.
	var email = $('#email').val();
	$.post( "<?=BASE_URL?>ajax-actions.php",{'email':email}, function( data ){
	  //$( ".result" ).html( data );
	  if(data=='false'){
	  $('#newEmail').val('yes');
	  }
	  else{
	  $('#newEmail').val('');
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
		 <form action="<?=BASE_URL_ADMIN?>user-form-actions.php" method="post" id="createAccountForm">
		<fieldset>
			<legend><?=isset($user)?"Update User":"Add New User"?></legend>
			<div class="form-controller"><span>User Name</span><input name="uname" id="uname" placeholder="Enter Username" type="text" value="<?=isset($user)?$user['uname']:""?>"></div>
			<div class="form-controller"><span>Email</span>
			<input name="email" id="email" placeholder="Email" onchange="checkEmail()" type="text" value="<?=isset($user)?$user['email']:""?>">
			</div>	
			<?php 
				if(!isset($user)){
			?>
			<div class="form-controller"><span>Password</span><input name="password" id="password" placeholder="Password" type="password"></div>
			<div class="form-controller"><span>Re-Password</span><input name="repassword" id="repassword" placeholder="Re-Password" type="password"></div>
			<?php 
				}
				else{
				echo '<input type="hidden" name="uid" value="'.$user['uid'].'" />';
				echo '<div class="form-controller"><span>Active Status</span>';
				if($user['isActive']==0)
					echo '<select name="active"><option value="0" selected="selected">InActive</option><option value="1">Active</option></select></div>';
				else
					echo '<select name="active"><option value="0">In Active</option><option value="1" selected>Active</option></select></div>';
				echo '<div class="form-controller"><span>User Level</span>';
				?>
				<select name="level">
					<?php
					for($i=0; $i<=5; $i++){
						if($user['level']==$i){
							echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
						}
						else{
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
					}
					?>
				</select>
				<?php
				echo '</div>';
				}
			?>
			<div class="form-controller"><span>Company</span>
			<input name="company" id="company" placeholder="Enter company" type="text" value="<?=isset($user)?$user['company']:""?>" />
			</div>
			<div class="form-controller"><span>Country</span>
			<input name="country" id="country" placeholder="Enter Country" type="text" value="<?=isset($user)?$user['country']:""?>" />
			</div>
			<div class="form-controller">&nbsp;</div>
			<div class="form-controller">&nbsp;</div>
			<div class="form-controller"><input name="<?=isset($user)?"update_reg_submit":"reg_submit"?>" value="<?=isset($user)?"Update Account":"Create Account"?>" type="submit">&nbsp;&nbsp;<input name="cancel" id="cancel" value="Cancel" type="reset"></div>
		</fieldset>
		</form>
	</div>
</div>
<?php include("includes/footer.php");  ?>