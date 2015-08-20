<?php
require ('config/dbConfig.php');
require 'lib/functions/appFunctions.php';

$column = $mysqli->real_escape_string ( strip_tags ( $_REQUEST ['column'] ) );
$data = $mysqli->real_escape_string ( strip_tags ( $_REQUEST ['data'] ) );
$table = $mysqli->real_escape_string ( strip_tags ( $_REQUEST ['table'] ) );
$idx = $mysqli->real_escape_string ( strip_tags ( $_REQUEST ['idx'] ) );
$targetTable = $mysqli->real_escape_string ( strip_tags ( $_REQUEST ['targetTable'] ) );

// $id = $mysqli->real_escape_string(strip_tags($_POST['id']));
// $tablename = $mysqli->real_escape_string(strip_tags($_POST['tablename']));
$tblidx = getPK ( $config, $targetTable );
// This very generic. So this script can be used to update several tables.
$return = false;
$query = "select * from $targetTable where $column=$data";
$rs = mysqli_query ( $link, $query ) or die ( mysqli_errno ( $link ) );
$rs_rows = mysqli_fetch_array ( $rs );
$rows = getColfromTab ( $config, $targetTable );
$i = 0;
$html = "";
if ($rs_rows > 0) {
	while ( count ( $rows ) > $i ) {
		
		$type = $rows [$i] ['1'];
		$colname = $rows [$i] ['0'];
		
		$disablefield = '';
		if ($colname == $tblidx) {
			$disablefield = 'disabled';
		}
		
		$html = $html . "<div style='float:left;width:100%;padding:3px;'>
			<div style='float:left;width:40%;'><label  for='" . $colname . "'>$colname</label></div>" . "<div style='float:left;width:60%;'><input $disablefield type='text' name='" . $colname . "'	id='" . $colname . "' value='" . $rs_rows [$i] . "' class='text ui-widget-content ui-corner-all'></div></div>";
		// echo "<input type='text' id='"+$colname+"' name='"+$colname+"' value=''>";
		$i ++;
	}
	$html = $html . "<input type='hidden' id='tabidx' value='$tblidx'><input type='hidden' id='id' value='$idx'>";
} else {
	$html = "No relation data matched with the target table name: $targetTable";
}
echo $html;
$mysqli->close ();
?>
      
