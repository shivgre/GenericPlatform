<form action='profile.php' method='post' id='user_profile_form' enctype='multipart/form-data'>
	<?php
		if($row['image']!=""){
			echo "<input type='hidden' name='oldimage' value='".$row['image']."' />";
		}
	?>
	<div class ="setborder">
		<div class='form_element'>
			<label>
			<?=USER_NAME?>
			</label>
			:<br />
			<span>
			<input type='text' id='uname' class='form-control' name='uname' value='<?=$row['uname']?>' readonly="readonly" required />
			</span>
		</div>
		<div class='form_element'>
			<label>
			<?=USER_EMAIL?>
			</label>
			: <br />
			<span>
			<input type='email' id='email' class='form-control' name='email' value='<?=$row['email']?>' readonly="readonly" required />
			</span>
		</div>
		<div class='form_element'>
			<label>
			<?=USER_FIRST_NAME?>
			</label>
			:<br />
			<span>
			<input type='text' id='ufname' class='form-control' name='ufirstname' value='<?=$row['firstname']?>' required />
			</span>
		</div>
		<div class='form_element'>
			<label>
			<?=USER_LAST_NAME?>
			</label>
			:<br />
			<span>
			<input type='text' id='ulname' class='form-control' name='ulastname' value='<?=$row['lastname']?>' required />
			</span>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="belowborder" >
		<div class='form_element'>
			<label>
			<?=USER_COMPANY?>
			</label>
			:<br />
			<span>
			<input type='text' id='company' class='form-control' name='company' value='<?=$row['company']?>' required />
			</span></div>
		<div class='form_element'>
			<label>
			<?=USER_CITY?>
			</label>
			:<br />
			<span>
			<input type='text' id='city' name='city' class='form-control' value='<?=$row['city']?>' required/>
			</span></div>
		<div style="clear:both"></div>
	</div>
	<div class="belowborder" >
		<div class='form_element'>
			<label>
			<?=USER_STATE?>
			</label>
			:<br />
			<span>
			<select name="state">
				<option selected='selected' value="<?=$row['state']?>">
				<?=$row['state']?>
				</option>
				<?php while($state = mysql_fetch_array($states)){?>
				<option value="<?=$state['statename']?>">
				<?=$state['statename']?>
				</option>
				<?php } ?>
			</select>
			</span> </div>
		<div class='form_element'>
			<label>
			<?=USER_COUNTRY?>
			</label>
			:<br />
			<span>
			<input type='text' id='country' class='form-control' name='country' value='<?=$row['country']?>' required/>
			</span></div>
		<div style="clear:both"></div>
	</div>
	<div class="belowborder" >
		<div class='form_element'>
			<label>
			<?=USER_ZIP?>
			</label>
			:<br />
			<span>
			<input type='text' id='zip' class='form-control' name='zip' value='<?=$row['zip']?>' />
			</span></div>
		<div style="clear:both"></div>
	</div>
	<div style="clear:both"></div>
	<div class='form_element update-btn2'>
		<label><br />
		<input type='hidden' name='uid' value="<?=$row["uid"]?>" />
		<input type='submit' name='profile_update' value='<?=UPDATE_PROFILE_BUTTON?>' class="btn btn-primary update-btn"  />
		</label>
	</div>
	<div style="clear:both"></div>
</form>