<?php

session_start();

if ($_SERVER['HTTP_HOST'] === 'localhost')

{ define('APP_DIR', $_SERVER['DOCUMENT_ROOT'].'generic-platforms/'); // Base Root or Directory Path For Application

  $GLOBALS['APP_DIR'] = $_SERVER['DOCUMENT_ROOT'] . 'generic-platforms/'; // Base Root or Directory Path For Application

}
else
{
  define('APP_DIR', $_SERVER['SUBDOMAIN_DOCUMENT_ROOT']); // Base Root or Directory Path For Application
  $GLOBALS['APP_DIR'] = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'] . '/'; // Base Root or Directory Path For Application
}


include_once($GLOBALS['APP_DIR'] . "application/database/db.php");
include_once($GLOBALS['APP_DIR'] . "application/functions.php");

if (isset($_POST['action']) && $_POST['action'] == "user_rating")
{
  if (!isUserLoggedin())
  {
    echo false;
    exit;
  }

  $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
  $tuid = $_POST['tuid'];
  $rating = $_POST['rating'];

  $query = "select avg(rating) as avg from user_favorites where user_id = $userId and target_user_id = $tuid";
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);
  if (mysql_num_rows($result) > 0 && $row['avg'] != NULL)
  {
    $uquery = "update user_favorites set rating = $rating where user_id = $userId and target_user_id = $tuid";
    //echo $uquery;
    echo mysql_query($uquery);
    exit;
  }
  else
  {
    $iquery = "insert into user_favorites (user_id, target_user_id, rating) values($userId, $tuid, $rating)";
    //echo $iquery;
    echo mysql_query($iquery);
    exit;
  }
}

if (isset($_POST['action']) && $_POST['action'] == "project_rating")
{
  if (!isUserLoggedin())
  {
    echo false;
    exit;
  }

  $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
  $tid = $_POST['tuid'];
  $rating = $_POST['rating'];
  $cdate = date("Y-m-d H:i:s");

  $query = "select avg(rating) as avg from project_favorites where uid = $userId and pid = $tid";
  echo $query;
  $result = mysql_query($query);
  $row = mysql_fetch_assoc($result);
  if (mysql_num_rows($result) > 0 && $row['avg'] != NULL)
  {
    $uquery = "update project_favorites set rating = $rating, last_update = '" . $cdate . "' where uid = $userId and pid = $tid";
    echo $uquery;
    echo mysql_query($uquery);
    exit;
  }
  else
  {
    $iquery = "insert into project_favorites (uid, pid, rating, last_update) values($userId, $tid, $rating, '" . $cdate . "')";
    echo $iquery;
    echo mysql_query($iquery);
    exit;
  }
}
?>
