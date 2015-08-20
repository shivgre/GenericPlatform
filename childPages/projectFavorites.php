<style>
  .wrapper{
    margin: 20px auto;
    padding: 0px;
    width: 100%;
    float: left;
    border: 1px solid #2ecc71;
  }
  .heading{
    margin: 0px auto;
    padding: 0px;
    width: 100%;
    background-color: #2ecc71;
    float: left;
    margin-bottom: 20px;
  }
  .heading h3{
    padding-left:15px;
  }
  .error_div{
    margin: 0px auto;
    padding: 15px;
    width: 100%;
    float: left;
    background-color: rgb(186, 226, 163);
  }
</style>
<!--Project likes-->
<div class="wrapper">
  <div class="likes">
    <div class="heading">
      <h3>Likes</h3>
    </div>
    <div class="content">
      <?php
      $sql = "SELECT * FROM project_liked as pl , users as u WHERE pl.target_user_id = u.uid AND pl.project_id =" . $_GET['pid'];
      $result = mysql_query($sql);
      if (mysql_num_rows($result) > 0)
      {
        while ($row = mysql_fetch_array($result))
        {
          $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'userDetails.php?uid=' . $row['uid'] . '">';
          $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
          $records = $records . '<div class="project-detail">';
          $records = $records . '<span class="profile-image">';
          if ($row['image'] != "")
          {
            $records = $records . "<img src='TimThumb.php?src=" . $BASE_URL . "users_uploads/" . $row['image'] . "&w=650&h=450' alt='' class='img-responsive'>";
          }
          else
          {
            $records = $records . "<img src='" . BASE_URL . "users_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
          }
          $records = $records . '</span>';
          $records = $records . '<div class="project-info">';
          $records = $records . '<h3>' . $row['uname'] . '</h3>';

          $records = $records . '<div style="clear:both"></div>' . '</div>';
          $records = $records . '</div></div>';
          $records = $records . '</a>';
        }
        echo $records;
      }
      else
      {
        echo '<div  class="error_div"> This project has no likes yet. </div>';
      }
      ?>
    </div>
    <div class="seeall">
    </div>
  </div>
</div>

<!--Project Subscription-->
<div class="wrapper">
  <div class="likes">
    <div class="heading">
      <h3>Subscription</h3>
    </div>
    <div class="content">
      <?php
      $records = '';
      $fsql = "SELECT * FROM project_subscribe as ps , users as u WHERE ps.user_id = u.uid AND ps.project_id = " . $_GET['pid'];
      $fresult = mysql_query($fsql);
      if (mysql_num_rows($fresult) > 0)
      {
        while ($row = mysql_fetch_array($fresult))
        {
          $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'userDetails.php?uid=' . $row['uid'] . '">';
          $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
          $records = $records . '<div class="project-detail">';
          $records = $records . '<span class="profile-image">';
          if ($row['image'] != "")
          {
            $records = $records . "<img src='TimThumb.php?src=" . $BASE_URL . "users_uploads/" . $row['image'] . "&w=650&h=450' alt='' class='img-responsive'>";
          }
          else
          {
            $records = $records . "<img src='" . BASE_URL . "users_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
          }
          $records = $records . '</span>';
          $records = $records . '<div class="project-info">';
          $records = $records . '<h3>' . $row['uname'] . '</h3>';

          $records = $records . '<div style="clear:both"></div>' . '</div>';
          $records = $records . '</div></div>';
          $records = $records . '</a>';
        }
        echo $records;
      }
      else
      {
        echo '<div  class="error_div"> No body has subscribed to this project yet. </div>';
      }
      ?>
    </div>
    <div class="seeall">
    </div>
  </div>
</div>

