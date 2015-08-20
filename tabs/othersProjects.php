<script src="js/projects.js"></script>
<div class="jumbotron projects">
				<div class="container"> 
				<div class="search1">Search Project</div>									
                                <div id=""><input type='text' class="input-bg form-control myProjectSearch nw_width" id='searchOthersProjects' onkeyup='searchProjects()'/></div>					
					<div class="row" id="pdwrapper">
					
			 		<h2>Others Projects</h2> 
			 		<?php
						//$sql = "SELECT * FROM projects WHERE uid != ".$_SESSION['uid'];
						$sql = "SELECT * FROM users u, projects p WHERE  u.uid = p.uid AND p.uid != ".$_SESSION['uid']." AND u.isActive = 1 AND p.isLive=1";
						$result = mysql_query($sql);
						if (mysql_num_rows($result) < 0) {
							echo "<div class='noProjects'>Currently There are no projects available.</div>";
							exit;
						}
						
						while($row = mysql_fetch_array($result)){
						
							echo "<a href='".BASE_URL."projectDetails.php?pid=".$row['pid']."'><div class='col-6 col-sm-6 col-lg-3' data-scroll-reveal='enter bottom over 1s and move 100px'><div class='project-detail'><span class='profile-image'>";
							if($row['projectImage'] != ""){
								echo "<img src='TimThumb.php?src=".BASE_URL."img/thumb_".$row['projectImage']."&w=650&h=450'>";
							}
							else{
								echo "<img src='".BASE_URL."img/defaultImageIcon.png'>";
							}	
							echo "</span>
							<div class='project-info projDetails'>
											<p> <span><strong></strong></span> <span class='name'>".$row['pname']."</span></p>
											<p> <span><strong></strong></span> <span class='name'>".$row['create_data']."</span></p>
											<p> <span><strong></strong></span> <span class='name'>".$row['expiry_date']."</span></p>
										</div>
							</div>
							</div>
							</a>";
						}	
					?>					
				</div>			
				</div>
				</div>