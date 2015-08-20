<?php

/**
 * @param unknown $request
 * @return unknown
 */
function pageController($request)
{
  switch ($request)
  {
    case 'users' :
      include "users.php";
      break;
    case 'stats' :
      include "stats.php";
      break;
    case 'simulation' :
      include "simulation.php";
      break;
    case 'gameschallenge' :
      include "game.php";
      break;
    case 'overview' :
      include "overview.php";
      break;
    default :
      if (isset($_GET ['mac']))
      {
        include "users.php";
      }
      else
      {
        include "views/table.view.php";
      }
      break;
  }
  return $request;
}

/**
 * @param unknown $column
 * @param unknown $sortID
 * @param unknown $count
 * @param unknown $tableSORT
 * @return string
 */
function sortImg($column, $sortID, $count, $tableSORT)
{
  $sortImg = '';
  if ($sortID != "default")
  {
    if ($column == $sortID)
    {
      if ($tableSORT == "DESC")
      {
        $sortImg = "<img style='padding-left:5px;' src='" . BASE_URL . "resources/images/bullet_arrow_down.png'>";
      }
      else
      {
        $sortImg = "<img style='padding-left:5px;' src='" . BASE_URL . "resources/images/bullet_arrow_up.png'>";
      }
    }
  }
  else
  {
    if ($count == 0)
    {
      $sortImg = "<img style='padding-left:5px;' src='" . BASE_URL . "resources/images/bullet_arrow_up.png'>";
    }
  }
  return $sortImg;
}

/**
 * @param unknown $mysqli
 * @param unknown $query
 * @return boolean|multitype:unknown
 */
function fetch_pairs($mysqli, $query)
{
  if (!($res = $mysqli->query($query)))
    return FALSE;
  $rows = array();
  while ($row = $res->fetch_assoc())
  {
    $first = true;
    $key = $value = null;
    foreach ($row as $val)
    {
      if ($first)
      {
        $key = $val;
        $first = false;
      }
      else
      {
        $value = $val;
        break;
      }
    }
    $rows[$key] = $value;
  }
  return $rows;
}

/**
 * @param unknown $column
 * @param unknown $data
 * @param unknown $tableAlias
 * @return string
 */
function getRelationalData($column, $data, $tableAlias)
{
  $query = "";
  if ($data == null || $data == '')
  {
    $data = "' '";
  }
  if ($GLOBALS['TABLEMAP'][$tableAlias][$column] != "")
  {
    return $GLOBALS['TABLEMAP'][$tableAlias][$column] . $data . " limit 1";
  }
  else
  {
    return '';
  }
}

/**
 * @param unknown $tableSORT
 * @return string
 */
function getNewSortOrder($tableSORT)
{
  $newSortOrder = "";
  if (isset($tableSORT))
  {
    if ($tableSORT == "ASC")
    { // toggleSortMe(pagenum,tablename,sortid,sortorder,where)
      $newSortOrder = "DESC";
    }
    else
    {
      $newSortOrder = "ASC";
    }
  }
  else
  {
    $newSortOrder = "ASC";
  }
  return $newSortOrder;
}

/**
 * @param unknown $where
 * @param unknown $count
 * @param unknown $colName
 * @return string
 */
function getNewAllWhere($where, $count, $colName)
{
  if (isset($where))
  {
    if ($count == 0)
    {
      $newWhere = $colName . " like '%$where%' ";
    }
    else
    {
      $newWhere = " or " . $colName . " like '%$where%'";
    }
  }
  return $newWhere;
}

/**
 * @param unknown $totalRecord
 * @param unknown $pageSize
 * @param unknown $curPage
 * @param unknown $tableAlias
 * @param unknown $sortID
 * @param unknown $tableSORT
 * @param unknown $all_where
 * @return string
 */
function updatePaginator($totalRecord, $pageSize, $curPage, $tableAlias, $sortID, $tableSORT, $all_where)
{
  if ($all_where == "")
  {
    $all_where = "";
  }
  $i = 0;
  $pagiCount = round($totalRecord / $pageSize);
  if ($pagiCount > 1)
  {
    $pagination = "<div id='paginator'><a class=\"nobg\" onclick=\"moveFirst('$totalRecord','$pageSize', '$curPage','$tableAlias','$sortID','$tableSORT','$all_where');\" style=\"opacity: 0.4;\"><img alt=\"\" src=\"resources/images/move_first.png\" class=\"icon\"></a>
		<a class=\"nobg\" onclick=\"movePrev('$totalRecord','$pageSize', '$curPage','$tableAlias','$sortID','$tableSORT','$all_where');\" style=\"opacity: 0.4;\"><img alt=\"\" src=\"resources/images/move_prev.png\" class=\"icon\"></a>";

    while ($pagiCount > $i)
    {
      $page = $i + 1;
      if ($i > $curPage - 6 && $i < $curPage + 6)
      {
        if ($page - 1 == $curPage)
        {
          $pagination = $pagination . "<span id=\"currentpageindex\">$page</span>";
        }
        else
        {
          $pagination = $pagination . "<a  style='cursor: pointer;' onclick=paginate('$page','$tableAlias','$sortID','$tableSORT','$all_where');>$page</a>";
        }
      }$i++;
    }
    $pagination = $pagination . "<a class=\"nobg\" onclick=\"moveNext('$totalRecord','$pageSize', '$curPage','$tableAlias','$sortID','$tableSORT','$all_where');\"  style=\"cursor: pointer;\"><img alt=\"\" src=\"resources/images/move_next.png\" class=\"icon\"></a>
		<a class=\"nobg\" onclick=\"moveLast('$totalRecord','$pageSize', '$curPage','$tableAlias','$sortID','$tableSORT','$all_where');\" style=\"cursor: pointer;\"><img alt=\"\" src=\"resources/images/move_last.png\" class=\"icon\"></a></div>";
    return $pagination;
  }
  else
  {
    return "";
  }
}

/**
 * @param unknown $val
 */
function socx_debug($val)
{
  if ($GLOBALS['DEBUG'] === 1)
  {
    $bt = debug_backtrace();
    $caller = array_shift($bt);

    $caller['line'];
    echo "<pre>";
    print_r('[ File: ' . $caller['file'] . " at Line no " . $caller['line'] . " ] > DEBUG DATA: " . $val);
    echo "</pre>";
  }
}

/**
 * @param unknown $config
 * @return unknown
 */
function mysql_local_connect($config)
{
  $mysqli = mysqli_init();
  $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);

  $mysqli->real_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
  return $mysqli;
}

/**
 * @param unknown $config
 * @param unknown $tableAlias
 * @return unknown
 */
function getPK($config, $tableAlias)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  $rs = $mysqli->query("SELECT COLUMN_KEY,COLUMN_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_alias = '$tableAlias'
			AND table_schema = '$dbname'
			");

  $rows = array();
  $count = 0;
  while ($row = $rs->fetch_assoc())
  {
    if ($row['COLUMN_KEY'])
    {
      $tblidx = $row['COLUMN_NAME'];
    }

    $count++;
  }
  $mysqli->close();
  return $tblidx;
}

/**
 * @param unknown $config
 * @param unknown $tableAlias
 * @return Ambigous <multitype:, unknown>
 */
function getColfromTab($config, $tableAlias)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  $rs = $mysqli->query("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE,COLUMN_KEY
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_alias = '$tableAlias'
			AND table_schema = '$dbname'
			");
  $rows = array();
  $count = 0;
  while ($row = $rs->fetch_assoc())
  {
    $rows[$count][0] = $row['COLUMN_NAME'];
    $rows[$count][1] = $row['DATA_TYPE'];
    $rows[$count][2] = $row['IS_NULLABLE'];
    $rows[$count][3] = $row['COLUMN_KEY'];
    $count++;
  }
  $mysqli->close();
  return $rows;
}

function gettableHeader($rows)
{
  $html = "";
  foreach ($rows as $key => $val)
  {
    $html = $html . "<th>" . $val[0] . "</th>";
  }
  return $html;
}

function getSingleSimulationTabData($config, $colidx, $idxData, $tableAlias)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  $rs = $mysqli->query("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE,COLUMN_KEY
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_alias = '$tableAlias'
			AND table_schema = '$dbname'
			");
  $rows = array();
  $count = 0;
  while ($row = $rs->fetch_assoc())
  {
    $rows[$count][0] = $row['COLUMN_NAME'];
    $rows[$count][1] = $row['DATA_TYPE'];
    $rows[$count][2] = $row['IS_NULLABLE'];
    $rows[$count][3] = $row['COLUMN_KEY'];
    $count++;
  }
  //$mysqli->close();
  //return $rows;




  $query = "SELECT * from $tableAlias
			WHERE $colidx = '$idxData'
			limit 1";
  $rs = $mysqli->query($query);
  $length = $mysqli->field_count;
  $row = $rs->fetch_array();
  //echo "<br>".count($row);
  $count = 0;
  $html = "";
  while ($length > $count)
  {
    $html = $html . "<td>" . $row[$count];
    $relatonalData = "";
    if (array_key_exists($rows[$count][0], $GLOBALS ['TABLEMAP'] [$tableAlias]))
    {
      $relatonalData = "";
      $targetTable = $GLOBALS ['TABLEMAP_TO_FIELD'] [$rows[$count][0]];
      if ($rows[$count][0] == "UserIDs")
      {
        //$targetTable = $GLOBALS ['TABLEMAP_TO_FIELD'] [$columns [$i]];
        $Q1 = "";
        $userIDS = explode(";", $row [$count]);
        $w = 0;
        if (count($userIDS) > 0)
        {
          $showResult = "  ";
          while (count($userIDS) > $w)
          {
            $Q2 = "select User.UserID,User.Username from User where User.UserID = $userIDS[$w]";
            $rs1 = $mysqli->query($Q2);
            $row23 = $rs1->fetch_array();
            $showResult = $showResult . "  <br><a href='#' style='float:right' onclick='showRelationalData(\"$columns[$i]\",\"$row23[0]\",\"$tableAlias\",\"$row[0]\",\"$targetTable\",\"$sortID\",\"$tableSORT\",\"$where\",\"$pagenum\");'>" . $row23 [0] . "  :  " . $row23 [1] . "</a>";
            $w ++;
          }
          $relatonalData = $showResult;
        }
        else
        {
          if (getRelationalData($rows[$count][0], $row [$count], $tableAlias) != '')
            $relatonalData = getRelationalData($rows[$count][0], $row [$count], $tableAlias);
        }
      } elseif ($rows[$count][0] == "ChallengeInstID")
      {
        if (getRelationalData($rows[$count][0], $row [$count], $tableAlias) != '')
        {
          $relatonalData = getRelationalData($rows[$count][0], $row [$count], $tableAlias);
        }
      }
      else
      {
        if (getRelationalData($rows[$count][0], $row [$count], $tableAlias) != '')
        {
          $relatonalData = getRelationalData($rows[$count][0], $row [$count], $tableAlias);
        }
      }
    }
    $showResult = "";
    //echo "<br>".$relatonalData;
    if ($relatonalData != '' && $rows[$count][0] != "UserIDs")
    { //// Generation for general IDS relational
      $rs12 = $mysqli->query($relatonalData);
      socx_debug($relatonalData);
      $row223 = $rs12->fetch_array();
      $rowCount2 = $mysqli->field_count;
      $n = 0;
      $showResult = "";
      while ($rowCount2 > $n)
      {
        if ($n == 0)
        {
          $showResult = $showResult . $row223 [0];
        }
        else
        {
          $showResult = $showResult . ":" . $row223 [0];
        }

        $n ++;
      }
      $html = $html . " " . $showResult;
    }
    //echo "<br>".$showResult;


    $html = $html . "</td>";
    $count++;
  }
  $mysqli->close();
  return $html;
}

function getResults($config, $sql)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  $rs = $mysqli->query($sql);
  //$Fieldlength=$mysqli->field_count;
  //$rows= array();
  //$count=0;
  $row = $rs->fetch_assoc();
  $mysqli->close();
  return $row;
}

function getResultSets($config, $sql)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  $rs = $mysqli->query($sql);
  $Fieldlength = $mysqli->field_count;
  $rows = array();
  $count = 0;
  while ($row = $rs->fetch_array())
  {
    $i = 0;
    //echo "1s <br>". print_r($row);
    while ($Fieldlength > $i)
    {
      $rows[$count][$i] = $row[$i];
      $i++;
    }
    $count++;
  }
  //echo "vv->".$count;
  $mysqli->close();
  //print_r($rows[0]);
  return $rows;
}

function getChallengeTakeFromUser($config, $UserID, $sql)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  /* $sql="select group_concat(distinct(c.Name ) SEPARATOR '<br>') as Challenges_done from challenge c,activity ac,challengeinstance ci where
    ac.ChallengeInstID=ci.ChallengeInstanceID and
    ci.ChallengeID=c.ChallengeID and
    ac.UserID='$UserID'"; */
  $sql = $sql . "'$UserID'";
  $rs = $mysqli->query($sql);
  if ($rs != null)
    $row = $rs->fetch_array();
  $ret = "No Challenge";
  if (isset($row[0]))
  {
    if ($row[0] != "" || $row[0] != null)
    {

      $ret = $row[0];
    }
  }
  return $ret;
}

function getGamesPlayFromUser($config, $UserID, $sql)
{
  $mysqli = mysql_local_connect($config);
  $dbname = $config['db_name'];
  /* $sql="select group_concat(distinct(g.Name) SEPARATOR '<br>') as Games_Played from activity ac,challengeinstance ci,challenge c,game g,user as u
    where ci.ChallengeInstanceID=ac.ChallengeInstID and u.UserID=ac.UserID
    and c.ChallengeID=ci.ChallengeID and g.GameID=c.Game_ID
    and ac.UserID='$UserID'"; */
  $sql = $sql . "'$UserID'";
  $rs = $mysqli->query($sql);
  if ($rs != null)
    $row = $rs->fetch_array();
  $ret = "No Game";
  if (isset($row[0]))
  {
    if ($row[0] != "" || $row[0] != null)
    {
      $ret = $row[0];
    }
  }
  return $ret;
}

?>