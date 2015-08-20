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

/* * ****** Manage Custom DropDown class@start ******** */

class CustomHtml
{

  //Define tables here
  private $tables = array(
    "project_categories" => array(
      "tableName" => "project_categories", //the tablename to the dropdown from
      "renderColumn" => "project_categeory_name",
      "labelName" => "Project Category", //the field in the table that will be the label
      "primaryKey" => "project_category_id", //table's primary key
      "ownerId" => ""
    ),
    "user_type" => array(
      "tableName" => "user_type",
      "renderColumn" => "user_type",
      "labelName" => "User types",
      "primaryKey" => "user_type_id",
      "ownerId" => ""
    ),
    "states" => array(
      "tableName" => "states",
      "renderColumn" => "statename",
      "labelName" => "States",
      "primaryKey" => "stateid",
      "ownerId" => ""
    )
  );
  private $tableInfo = array();

  public function getDropDown($tableName, $defaultSelect = NULL, $ownerId = NULL)
  {

    $this->setTableInfo($tableName);

    if ($ownerId != NULL && $tableName != "")
    {
      $sql = "SELECT * FROM " . $this->tableInfo['tableName'] . " WHERE " . $this->tableInfo['tableName'] . "." . $this->tableInfo['ownerId'] . " = " . $ownerId;
    }
    elseif ($tableName != "")
    {
      $sql = "SELECT * FROM " . $this->tableInfo['tableName'];
    }
    else
    {
      $sql = "";
    }


    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0)
    {
      $dropDown = "";
      $dropDown .= "<select id='" . $this->tableInfo['tableName'] . "' name='" . $this->tableInfo['tableName'] . "' class='form-control'>";
      $dropDown .= "<option value = 'null'>---Select " . $this->tableInfo['labelName'] . "---</option>";
      while ($row = mysql_fetch_array($result))
      {
        if ($row[$this->tableInfo['primaryKey']] == $defaultSelect)
        {
          $dropDown .= "<option value='" . $row[$this->tableInfo['primaryKey']] . "' selected='selected'>" . $row[$this->tableInfo['renderColumn']] . "</option>";
        }
        else
        {
          $dropDown .= "<option value='" . $row[$this->tableInfo['primaryKey']] . "'>" . $row[$this->tableInfo['renderColumn']] . "</option>";
        }
      }
      $dropDown .= "</select>";
    }

    echo $dropDown;
  }

  public function setTableInfo($tableName)
  {
    foreach ($this->tables as $table => $info)
    {
      if ($table == $tableName)
      {
        foreach ($info as $key => $value)
        {
          $this->tableInfo[$key] = $value;
        }
      }
    }
  }

}

/* * *Manage Custom Fields class@end ******** */
?>
