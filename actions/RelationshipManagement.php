<?php
session_start();
include_once($GLOBALS['CONFIG_APP_DIR']);

if (isset($_SESSION['lang']))
{
  include_once($GLOBALS['LANGUAGE_APP_DIR'] . $_SESSION['lang'] . ".php");
}
else
{
  include_once($GLOBALS['LANGUAGE_APP_DIR'] . "en.php");
}
include("special_config.php");

/* * ******Relationship management class@starts ******** */

class RelationshipManagement
{

  protected $action;
  protected $target_user_id;
  protected $user_id;

  public function __construct($action = NULL, $userId = NULL, $targetUid = NULL)
  {
    $this->action = $action;
    $this->target_user_id = $targetUid;
    $this->user_id = $userId;
  }

  //Function to like a user
  public function likeUser()
  {
    $sql = "INSERT INTO user_liked(user_id, target_user_id) VALUES($this->user_id, $this->target_user_id)";
    return mysql_query($sql);
  }

  //Get User Likes
  public function getUserLikes($uid)
  {
    $sql = "Select * from user_liked where user_id = $uid";
    $result = mysql_query($sql);
    $user_likes = array();
    while ($row = mysql_fetch_assoc($result))
    {
      $user_likes[] = $row['target_user_id'];
    }
    return $user_likes;
  }

  //Unlike User
  public function unLikeUser($userId, $target_user)
  {
    $sql = "delete from user_liked where user_id = $userId and target_user_id = $target_user";
    return mysql_query($sql);
  }

  //Get Users Rating
  public function getUsersRating($uid)
  {
    $sql = "SELECT target_user_id, FORMAT( AVG( rating ) , 1 ) AS rating FROM  `user_favorites` GROUP BY target_user_id";
    $result = mysql_query($sql);
    $users_rating = array();
    while ($row = mysql_fetch_assoc($result))
    {
      $users_rating[$row['target_user_id']] = $row['rating'];
    }
    return $users_rating;
  }

  //Get Projects Rating
  public function getProjectsRating()
  {
    $sql = "SELECT pid, FORMAT( AVG( rating ) , 1 ) AS rating FROM  `project_favorites` GROUP BY pid";
    $result = mysql_query($sql);
    $projects_rating = array();
    while ($row = mysql_fetch_assoc($result))
    {
      $projects_rating[$row['pid']] = $row['rating'];
    }
    return $projects_rating;
  }

  //Function to like a Project
  public function likeProject($projectId, $target_user)
  {
    $sql = "INSERT INTO project_liked(project_id, target_user_id) VALUES($projectId, $target_user)";
    if (mysql_query($sql))
    {
      return $this->getProjectLikesById($projectId);
    }
    else
    {
      return false;
    }
  }

  //Get Project Likes
  public function getProjectLikes()
  {
    $sql = " SELECT `project_id` , count( `project_id` ) as likes FROM `project_liked` GROUP BY `project_id`";
    $result = mysql_query($sql);
    $project_likes = array();
    while ($row = mysql_fetch_assoc($result))
    {
      $project_likes[$row['project_id']] = $row['likes'];
    }
    return $project_likes;
  }

  //Unlike Project
  public function unLikeProject($projectId, $target_user)
  {
    $sql = "delete from project_liked where project_id = $projectId and target_user_id = $target_user";
    if (mysql_query($sql))
    {
      return $this->getProjectLikesById($projectId);
    }
    else
    {
      return false;
    }
  }

  //Get Likes
  public function getProjectLikesById($projectId)
  {
    $sql_likes = "SELECT COUNT( project_id ) AS likes FROM  `project_liked` WHERE project_id = $projectId GROUP BY project_id";
    $result_likes = mysql_query($sql_likes);
    $row_likes = mysql_fetch_assoc($result_likes);
    $likes = 0;
    if ($row_likes['likes'] > 0)
    {
      $likes = $row_likes['likes'];
    }
    return $likes;
  }

  //Follow User
  public function followUser($target_user, $userId)
  {
    $sql = "INSERT INTO user_follow(user_id, target_user_id) VALUES($userId, $target_user)";
    return mysql_query($sql);
  }

  //Unfollow User
  public function unFollowUser($target_user, $userId)
  {
    $sql = "delete from user_follow where user_id = $userId and target_user_id = $target_user";
    return mysql_query($sql);
  }

  //Subscribe Project
  public function subscribeProject($user_id, $project_id)
  {
    $sql = "INSERT INTO project_subscribe(user_id, project_id) VALUES($user_id, $project_id)";
    return mysql_query($sql);
  }

  //Unsubscribe Project
  public function unSubscribeProject($user_id, $project_id)
  {
    $sql = "delete from project_subscribe where user_id = $user_id and project_id = $project_id";
    return mysql_query($sql);
  }

  //Get User Cross Reference Tables
  public function getUserCrossReference($tableName, $colmns, $userTblArray)
  {
    $query = "SELECT " . implode(",", $colmns) . " FROM  " . $tableName . " uf, {$userTblArray['table_alias']} u WHERE uf.target_user_id = u.{$userTblArray['uid_fld']} and uf.user_id=" . $_SESSION['uid'];
    return $this->getResultOfQuery($query);
  }

  public function getUserCrossReferenceprojs($tableName, $projectTblArray, $userTblArray)
  {
    //$query = "SELECT * FROM  ".$tableName." uf, {$userTblArray['table_alias']} u WHERE uf.target_user_id = u.{$userTblArray['uid_fld']} and uf.user_id=".$_SESSION['uid'];
    $query = "SELECT * FROM  $tableName";
    return $this->getResultOfQuery($query);
  }

  public function getResultOfQuery($query)
  {

    $result = mysql_query($query);
    $queryArray = array();
    while ($row = mysql_fetch_assoc($result))
    {
      $queryArray[] = $row;
    }
    return $queryArray;
  }

  public function listWidgetForUserCrossReference($dataArr, $userTblArray, $type = "card")
  {
    echo '<div class="row" id="popular_users">';
    if (!empty($dataArr))
    {
      foreach ($dataArr as $data)
      {
        ?>
        <a class="project-details-wrapper" href="<?php echo BASE_URL_SYSTEM ?>userDetails.php?uid=<?php echo $data[$userTblArray['uid_fld']] ?>">
          <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
            <div class="project-detail">
              <span class="profile-image"><img src="TimThumb.php?src=users_uploads/<?php echo $data[$userTblArray['image_fld']] ?>&amp;w=650&amp;h=450" alt="" class="img-responsive"></span>
              <div class="project-info">
        <?php echo (isset($data[$userTblArray['firstname_fld']]) && $data[$userTblArray['firstname_fld']] != "") ? $data[$userTblArray['firstname_fld']] . "&nbsp;" . $data[$userTblArray['lastname_fld']] : $data[$userTblArray['uname_fld']] ?>
                <p>
                  <span><?php echo $data[$userTblArray['country_fld']] ?> </span>
                </p>
                <div style="clear:both"></div></div>
            </div>
          </div>
        </a>
        <?php
      }
    }
    else
    {
      echo "Data Not avilable";
    }
    echo "</div>";
  }

  public function listWidgetForProjCrossReference($dataArr, $userTblArray, $type = "list")
  {
    echo '<div class="row" id="popular_users">';
    if (!empty($dataArr))
    {
      foreach ($dataArr as $data)
      {
        ?>
        <a class="project-details-wrapper" href="<?php echo BASE_URL_SYSTEM ?>userDetails.php?uid=<?php echo $data[$userTblArray['uid_fld']] ?>">
          <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
            <div class="project-detail">
              <span class="profile-image"><img src="TimThumb.php?src=users_uploads/<?php echo $data[$userTblArray['image_fld']] ?>&amp;w=650&amp;h=450" alt="" class="img-responsive"></span>
              <div class="project-info">
        <?php echo (isset($data[$userTblArray['firstname_fld']]) && $data[$userTblArray['firstname_fld']] != "") ? $data[$userTblArray['firstname_fld']] . "&nbsp;" . $data[$userTblArray['lastname_fld']] : $data[$userTblArray['uname_fld']] ?>
                <p>
                  <span><?php echo $data[$userTblArray['country_fld']] ?> </span>
                </p>
                <div style="clear:both"></div></div>
            </div>
          </div>
        </a>
        <?php
      }
    }
    else
    {
      echo "Data Not avilable";
    }
    echo "</div>";
  }

}

/* * ******Relationship management class@ends ******** */
?>
