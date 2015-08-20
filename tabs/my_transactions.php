<?php
include("../special_config.php");
$current_page_num=2;
?>
<div class="">
	 <div class="container">
		<div class="row" id="pdwrapper">
		<h2>My Transactions</h2>
		<?php
	    $sql = "SELECT * FROM {$transTblArray['tableAlias']} t, {$projectTblArray['tableAlias']} p where t.{$transTblArray['project_id_fld']} = p.{$projectTblArray['pid_fld']}
		 and t.{$transTblArray['user_id_fld']}=".$_SESSION['uid'];
      // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_alias (the key field in DD) ...
      // temporarily changing to ...
	    $sql = "SELECT * FROM {$transTblArray['database_table_name']} t, {$projectTblArray['tableAlias']} p where t.{$transTblArray['project_id_fld']} = p.{$projectTblArray['pid_fld']}
		 and t.{$transTblArray['user_id_fld']}=".$_SESSION['uid'];



		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
			echo "<div class='noProjects'>You currently do not have any Transactions. </div>";
		}
		else{
			while($row = mysql_fetch_array($result)){
			?>
			<a href="<?=BASE_URL?>projectDetails.php?tid=<?php echo $row[$transTblArray['transaction_id_fld']]; ?>&pid=<?php echo $row[$projectTblArray['pid_fld']]; ?>">
			<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
			<div class="project-detail">
			<span class="profile-image">
			<?php
				if($row['projectImage'] != ""){
					echo "<img src='".BASE_URL."project_uploads/".$row[$projectTblArray['projectImage_fld']]."' class='img-responsive' >";
				}
				else{
					echo "<img src='".BASE_URL."project_uploads/".defaultImageIcon.png."' class='img-responsive' >";
				}
			?>
			</span>

			<div class='project-info projDetails'>

				<h3><?php echo $row[$projectTblArray['pname_fld']] ?></h3>

				<p> <span><strong></strong> </span> <span class="name"> <?php echo  $row[$projectTblArray['create_date_fld']] ?></span> </p>

				<p> <span><strong></strong> </span> <span class="name"><?php echo  $row[$projectTblArray['expiry_date_fld']] ?></span> </p>

			</div>

			</div>
			</div>

			  </a>
			<?php
			}
		}
		?>
		</div>
	</div>
</div>