<script src="js/projects.js"></script>
<div class="jumbotron projects">
  <div class="container"> 
    <div class="search1">Search Project</div>									
    <div id=""><input type="text" class="input-bg form-control myProjectSearch nw_width"></div>
    <?php
    if ($_SESSION['messages'] != "")
    {
      echo "<div class='message'>
								" . FlashMessage::render() . "
							</div>";
    }
    ?>
    <div class="row" id="pdwrapper">

      <h2>My Projects</h2> 
      <?php
      $sql = "SELECT * FROM projects WHERE uid=" . $_SESSION['uid'];
      $result = mysql_query($sql);
      if (mysql_num_rows($result) == 0)
      {
        echo "<div class='noProjects'>You currently do not have any project. Please <a href='" . BASE_URL . "createProject.php'>CREATE</a> one.</div>";
      }
      else
      {
        while ($row = mysql_fetch_array($result))
        {
          ?>
          <a href="<?php echo BASE_URL ?>projectDetails.php?pid=<?php echo $row['pid']; ?>" >	
            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">	
              <div class="project-detail"> 	
                <span class="profile-image">				
                  <?php
                  if ($row['projectImage'] != "")
                  {
                    echo "<img src='TimThumb.php?src=" . BASE_URL . "img/thumb_" . $row['projectImage'] . "&w=650&h=450' class='img-responsive' >";
                  }
                  else
                  {
                    echo "<img src='TimThumb.php?src=" . BASE_URL . "img/defaultImageIcon.png&w=650&h=450' class='img-responsive' >";
                  }
                  ?>
                </span>	

                <div class='project-info projDetails'>

                  <h3><?php echo $row['pname'] ?></h3>

                  <p> <span><strong></strong> </span> <span class="name"> <?php echo $row['create_data'] ?></span> </p>

                  <p> <span><strong></strong> </span> <span class="name"><?php echo $row['expiry_date'] ?></span> </p>

                  <p> <span><strong></strong> </span> 
                    <a href="<?php echo BASE_URL_SYSTEM ?>createProject.php?pid=<?php echo $row['pid'] ?>"><span class="btn btn-primary">Manage Project</span></a>
                  </p>

                  <p> <span><strong></strong> </span> <span class="name">

                      <a href="<?php echo BASE_URL_SYSTEM ?>project-form-actions.php?action=delete&pid=<?php echo $row['pid'] ?>" onclick="return confirm('Are you sure you want delete this Project?')">Delete</a></span> </p>

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