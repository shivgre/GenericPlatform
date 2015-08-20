<?php

include("../application/config.php");
include($GLOBALS['APP_DIR'] . "application/database/db.php");
include($GLOBALS['APP_DIR'] . "system/special_config.php");

$hostname = $GLOBALS['db-host'];
$username = $GLOBALS['db-username'];
$password = $GLOBALS['db-password'];
$database = $GLOBALS['db-database'];

// List of Queries

$GLOBALS["transactions"] = "SELECT `{$transTblArray['transaction_id_fld']}` , `{$transTblArray['user_id_fld']}` , `{$transTblArray['project_id_fld']}` , `{$transTblArray['amount_fld']}` , `{$transTblArray['transaction_datetime_fld']}`, `{$transTblArray['transactionType_fld']}`, `{$transTblArray['status_fld']}`  FROM `{$transTblArray['tableAlias']}` ";
$GLOBALS["transaction_history_count"] = "SELECT COUNT(*) as num FROM `{$transTblArray['tableAlias']}` ";
$GLOBALS["project_config"] = 'SELECT `pt_id` , `pid` , `uid` , `copy` , `fork`, `subscribe`, `share`, `description`, `transaction`, `comments`, `image_gallery` FROM `project_config` ';
$GLOBALS["project_count"] = 'SELECT COUNT(*) as num FROM `project_config` ';

$mysqli = new mysqli($hostname, $username, $password, $database);
/* check connection */

if (mysqli_connect_errno())
{
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

//magic quotes logic
if (get_magic_quotes_gpc())
{

  function stripslashes_deep($value)
  {
    $value = is_array($value) ?
      array_map('stripslashes_deep', $value) :
      stripslashes($value);
    return $value;
  }

  $_POST = array_map('stripslashes_deep', $_POST);
  $_GET = array_map('stripslashes_deep', $_GET);
  $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
  $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "update_cell")
{
  $tableArray = array_chunk($_REQUEST, 3, true);
  $flag = 'true';
  $keys = array_keys($tableArray[0]);
  $tableData = $tableArray[0];

  $tablename = $tableData[$keys[2]];
  $colname = $keys[0];
  $newvalue = $tableData[$keys[0]];
  $tblidx = $keys[1];
  $id = $tableData[$keys[1]];

  $query = "UPDATE $tablename SET $colname = '$newvalue' WHERE $tblidx = '$id' limit 1";

  $return = mysqli_query($mysqli, $query) or die("Error" . mysql_error($mysqli));
  if (!$return)
  {
    $flag = 'false';
  }

  echo $flag;
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "sort_column")
{

  $tablename = $_REQUEST['tableName'];
  $colname = $_REQUEST['columnName'];

  $query = "UPDATE $tablename SET $colname = '$newvalue' WHERE $tblidx = '$id' limit 1";
  $return = mysqli_query($mysqli, $query) or die("Error" . mysql_error($mysqli));

  if (!$return)
  {
    $flag = 'false';
  }

  echo $flag;
  exit();
}

/* * ********************************************* */
/* * *****code to create pagination users links********* */
/* * ********************************************* */
if (isset($_GET["action"]) && $_GET["action"] == "pagination")
{
  $query = $_GET['query'];
  $table = $_GET['table'];
  $per_page = $_GET['per_page'];
  $page = $_GET['page'];

  $query = $GLOBALS[$table] . $query;

  echo paginationUsers($query, $per_page, $page);
}

/* * ******************************************* */
/* * ***code for retrieving the users records********* */
/* * ******************************************* */
if (isset($_GET['action']) && $_GET["action"] == "load_table")
{

  $statement = $_GET['query'];
  $startpoint = $_GET['startpoint'];
  $limit = $_GET['limit'];
  $order = "";
  if (isset($_GET['data-order']))
  {
    $order = " " . $_GET['data-order'];
  }
  $output = "";
  //show records
  $query = $GLOBALS[$_GET['table']] . $statement . $order . " LIMIT $startpoint , $limit";

  $query = $mysqli->real_escape_string($query);

  $result = mysqli_query($mysqli, $query);
  if (!$result)
  {
    $message = 'ERROR:' . mysql_error();
    return $message;
  }
  else
  {
    if ($result = $mysqli->query($query))
    {
      if (isset($_GET['data-order']))
      {
        $output = prepareTableSortOutput($result);
      }
      else
      {
        $output = prepareTableOutput($result);
      }
      $result->close();
    }

    /* close connection */
    $mysqli->close();
  }
  echo $output;
  exit();
}

/* * ******************************************* */
/* * *************Delete Row********************* */
/* * ******************************************* */
if (isset($_GET['action']) && $_GET["action"] == "row_delete")
{

  $table = $_GET['table'];
  $column = $_GET['column'];
  $id = $_GET['id'];

  if (isset($_GET['id']) && $_GET['id'] != "")
  {
    $query = "delete from " . $table . " where $column = $id ";
    $query = $mysqli->real_escape_string($query);
    $result = mysqli_query($mysqli, $query);
    if ($result)
    {
      echo "true";
    }
    else
    {
      echo "false";
    }
  }
  else
  {
    echo "false";
  }

  exit();
}

/* * ************************************ */
/* * ********Users Pagination Function********** */
/* * ************************************ */

function paginationUsers($query, $per_page = 10, $page = 1, $url = '?')
{
  $hostname = "97.74.31.60";
  $username = "generic";
  $password = "Elance2014!";
  $database = "generic";

  $mysqli = new mysqli($hostname, $username, $password, $database);
  $query = $query;
  $result = mysqli_query($mysqli, $query);

  if (!$result)
  {
    echo 'Error ';
    exit();
  }

  while ($row = $result->fetch_row)
  {
    $total = $row['num'];
  }


  $adjacents = "2";

  $page = ($page == 0 ? 1 : $page);
  $start = ($page - 1) * $per_page;

  $firstPage = 1;



  $prev = $page - 1;
  $next = $page + 1;
  $lastpage = ceil($total / $per_page);
  $lpm1 = $lastpage - 1;

  $pagination = "";
  if ($lastpage > 1)
  {
    $pagination .= "<ul class='pagination'>";
    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    $prev = ($page == 1) ? 1 : $page - 1;
    //$pagination = '';
    if ($page == 1)
    {
      $pagination.= "<li><a class='current' title='current'>First</a></li>";
      $pagination.= "<li><a class='current' title='current'>Prev</a></li>";
    }
    else
    {
      $pagination.= "<li><a href='{$url}page=$firstPage' title='$firstPage'>First</a></li>";
      $pagination.= "<li><a href='{$url}page=$prev' title='$prev'>Prev</a></li>";
    }
    if ($lastpage < 7 + ($adjacents * 2))
    {
      for ($counter = 1; $counter <= $lastpage; $counter++)
      {
        if ($counter == $page)
          $pagination.= "<li><a class='current' title='current'>$counter</a></li>";
        else
          $pagination.= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
      }
    }
    elseif ($lastpage > 5 + ($adjacents * 2))
    {
      if ($page < 1 + ($adjacents * 2))
      {
        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current' title='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
        }
        $pagination.= "<li class='dot'>...</li>";
        $pagination.= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
        $pagination.= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
      }
      elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
      {
        $pagination.= "<li><a href='{$url}page=1' title='1'>1</a></li>";
        $pagination.= "<li><a href='{$url}page=2' title='2'>2</a></li>";
        $pagination.= "<li class='dot'>...</li>";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current' title='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
        }
        $pagination.= "<li class='dot'>..</li>";
        $pagination.= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
        $pagination.= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
      }
      else
      {
        $pagination.= "<li><a href='{$url}page=1' title='1'>1</a></li>";
        $pagination.= "<li><a href='{$url}page=2' title='2'>2</a></li>";
        $pagination.= "<li class='dot'>..</li>";
        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current' title='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
        }
      }
    }

    if ($page < $counter - 1)
    {
      $pagination.= "<li><a href='{$url}page=$next' title='$next'>Next</a></li>";
      $pagination.= "<li><a href='{$url}page=$lastpage' title='$lastpage'>Last</a></li>";
    }
    else
    {
      $pagination.= "<li><a class='current' title='current'>Next</a></li>";
      $pagination.= "<li><a class='current' title='current'>Last</a></li>";
    }
    $pagination.= "</ul>\n";
  }

  return $pagination;
}

function prepareTableOutput($result)
{

  /* Get field information for all columns */
  $output = '<table id="' . $_GET['table'] . '" width="100%" class="table table-hover">';
  $output .= "<thead><tr>";
  while ($finfo = $result->fetch_field())
  {
    $output .= "<th id='" . $finfo->name . "' class='sortBy'><a href='#'>" . $finfo->name . "</a></th>";
  }
  $output .= "<th>&nbsp;</th><tr></thead>";
  $output .= "<tbody>";

  /* Get information for all columns */
  $i = 0;
  while ($row = $result->fetch_row())
  {
    $output .= '<tr class="success success_' . $row[0] . '" id ="' . $row[0] . '">';
    $count = count($row);
    $y = 0;
    while ($y < $count)
    {
      $c_row = current($row);
      if ($y == 0)
      {
        $output .= '<th id="' . $c_row . '">' . $c_row . '</th>';
      }
      else
      {
        $output .= '<td>' . $c_row . '</td>';
      }
      next($row);
      $y = $y + 1;
    }
    $output .= '<th><i class="fa fa-pencil" id="' . $row[0] . '"></i>&nbsp;&nbsp;<i class="fa fa-times" id="' . $row[0] . '"></i></th>';
    $output .= '</tr>';
    $i = $i + 1;
  }
  $output .="</tbody></table> ";
  $output .= "
<script>
  jQuery('#" . $_GET['table'] . "').editableTableWidget().numericInputExample().find('td:first').focus();
jQuery('#" . $_GET['table'] . "').editableTableWidget({editor: jQuery('<textarea>')});
window.prettyPrint && prettyPrint();
</script>
";
  return $output;
}

function prepareTableSortOutput($result)
{

  /* Get field information for all columns */
  $output = '<table id="' . $_GET['table'] . '" width="100%" class="table table-hover">';
  $output .= "<thead><tr>";
  while ($finfo = $result->fetch_field())
  {
    $output .= "<th id='" . $finfo->name . "' class='sortBy'><a href='#'>" . $finfo->name . "</a></th>";
  }
  $output .= "<th>&nbsp;</th><tr></thead>";
  $output .= "<tbody>";

  /* Get information for all columns */


  /* Get information for all columns */
  $i = 0;
  while ($row = $result->fetch_row())
  {
    $output .= '<tr class="success" id ="' . $row[0] . '">';
    $count = count($row);
    $y = 0;
    while ($y < $count)
    {
      $c_row = current($row);
      if ($y == 0)
      {
        $output .= '<th id="' . $c_row . '">' . $c_row . '</th>';
      }
      else
      {
        $output .= '<td>' . $c_row . '</td>';
      }
      next($row);
      $y = $y + 1;
    }
    $output .= '<th><i class="fa fa-pencil"></i>&nbsp;&nbsp;<i class="fa fa-times"></i></th>';
    $output .= '</tr>';
    $i = $i + 1;
  }
  $output .="</tbody></table> ";
  $output .= "
<script>
  jQuery('#" . $_GET['table'] . "').editableTableWidget().numericInputExample().find('td:first').focus();
jQuery('#" . $_GET['table'] . "').editableTableWidget({editor: jQuery('<textarea>')});
window.prettyPrint && prettyPrint();
</script>
";
  return $output;
}
