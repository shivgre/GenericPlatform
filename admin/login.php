<script>
$().ready(function() {
	
	// validate login form on keyup and submit
	$("#checkLogin").validate({
		rules: {
			log_pwd: {
				required: true
			},
			log_email: {
				required: true,
				email: true
			}
		},
		messages: {
			log_pwd: {
				required: "<p>Please provide a password</p>"
			},
			log_email: "<p>Please enter a valid email address</p>"
		}
	});	
});
</script>
<div id="home_container">
 <div id="wrapper" class="login_form">
 		<?php if(!empty($_SESSION['messages'])){?>
 			<div class="message"><?php echo FlashMessage::render(); ?></div>
		<?php  } ?>
        <form action="<?=BASE_URL?>form-actions.php" method="post" id="checkLogin">
		
            <fieldset>
                <legend>Admin Login</legend>
				<div>
                    <input name="log_email" id="email" placeholder="Enter Your Email" type="text">
                </div>
                <div>
                    <input name="log_pwd" id="password" placeholder="Password" type="password">
                </div>
                	<input name="admin_log_submit" value="Login" type="submit"> 
            </fieldset>    
        </form>
    </div>
</div>

</div>