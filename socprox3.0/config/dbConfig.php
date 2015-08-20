<?php

require 'appConfig.php';

if ($_SERVER['HTTP_HOST'] === 'localhost')
{
  $config = array(
    "db_name" => "generic",
    "db_user" => "root",
    "db_password" => "",
    "db_host" => "localhost"
  );
}
else
{
  $config = array(
    "db_name" => "generic",
    "db_user" => "generic",
    "db_password" => "Elance2014!",
    "db_host" => "97.74.31.60"
  );
}

$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
static $link;
if (!isset($link))
{
  $link = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
}
try
{
  $hostname = $config['db_host'];
  $dbname = $config['db_name'];
  $username = $config['db_user'];
  $pw = $config['db_password'];
  $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
}
catch (PDOException $e)
{
  echo "Failed to get DB handle: " . $e->getMessage() . "\n";
  exit;
}
?>