<?php

if ($_SERVER['HTTP_HOST'] === 'localhost')
   include_once($_SERVER['DOCUMENT_ROOT'] . 'generic-platforms/application/config.php');
else
  include_once($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'] . "/application/database/db.php");

class DAL
{

  public static function getConnection()
  {

    $db = new mysqli($GLOBALS['db-host'], $GLOBALS['db-username'], $GLOBALS['db-password'], $GLOBALS['db-database']);
    if ($db->connect_errno > 0)
    {
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    else
    {
      return $db;
    }
  }

  public static function closeConnection($db)
  {
    $db->close();
  }

}

?>