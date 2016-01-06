<?php


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