<div class="">
	<div class="container">
		<div class="row">
			<?php
			if(mysql_num_rows($projects) > 0){
			while($row = mysql_fetch_array($projects)){
			?>
			<?php #	"<a href='".BASE_URL."projectDetails.php?pid=".$row['pid']."'>"; ?>
				<a href="<?=BASE_URL?>projectDetails.php?pid=<?php echo $row[$projectTblArray['pid_fld']]; ?>" >
				<div class="col-6 col-sm-6 col-lg-4" data-scroll-reveal="enter bottom over 1s and move 100px">
				<div class="project-detail nw_height">
				<span class="profile-image">
			<?php
			if($row[$projectTblArray['projectImage_fld']] != ""){
				echo "<img src='".BASE_URL."project_uploads/thumbs/".$row[$projectTblArray['projectImage_fld']]."' class='img-responsive' >";
			}else{
				echo "<img src='".BASE_URL."project_uploads/defaultImageIcon.png' class='img-responsive' >";
			}
			?>
			</span>
			<div class='project-info projDetails'>
			<h3><?php echo $row['pname'] ?></span> </p></h3>
			<p> <span><strong></strong> </span> <span class="name"> <?php echo  $row[$projectTblArray['create_date_fld']] ?></span> </p>
			<p> <span><strong></strong> </span> <span class="name"><?php echo  $row[$projectTblArray['expiry_date_fld']] ?></span> </p>
			</div>
			</div>
			</div>
			</a>
			<?php } }
			else{
				echo "<div style='margin:10px 20px;'>".$users['uname']." did not create any project."."</div>";
			}
			?>			
		</div>
	</div>
</div>