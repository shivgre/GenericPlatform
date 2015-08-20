<?php

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $GLOBALS['db-host'] = "localhost";
    $GLOBALS['db-username'] = "root";
    $GLOBALS['db-password'] = "";
    $GLOBALS['db-database'] = "generic";
} else {
    /*
     * Here make changes if you want to change the DB Credentials
     */
    $GLOBALS['db-host'] = "97.74.31.60";
    $GLOBALS['db-username'] = "generic";
    $GLOBALS['db-password'] = "Elance2014!";
    $GLOBALS['db-database'] = "generic";
}

function connect() {

    return mysqli_connect($GLOBALS['db-host'], $GLOBALS['db-username'], $GLOBALS['db-password'], $GLOBALS['db-database']);
}

/////////
//////////////////////insert/////

function insert($table, $data) {
    $con = connect();
    $is = insertString($data);
    //echo "INSERT INTO $table $is";die;
    mysqli_query($con, "INSERT INTO $table $is");
    return mysqli_insert_id($con);
}

function insertString($data) {
    $f = implode(", ", array_keys($data));
    $v = array();
    foreach ($data as $d) {
        $v[] = "'$d'";
    }
    $v = implode(", ", $v);
    return "($f) VALUES ($v)";
}

function update($table, $data, $where) {
    $ws = whereString($where);
    $us = updateString($data);
    //exit("UPDATE $table SET $us WHERE $ws");
    return mysqli_query(connect(), "UPDATE $table SET $us WHERE $ws");
}

function updateString($data) {
    $i = array();
    foreach ($data as $key => $value) {
        $i[] = "$key = '$value'";
    }
    return implode(", ", $i);
}

function whereString($data) {
    $w = array();
    foreach ($data as $key => $value) {
        $w[] = "$key = '$value'";
    }
    return implode(" AND ", $w);
}

function getWhere($table, $where, $order = "") {
    $ws = whereString($where);

    //exit("SELECT * FROM $table WHERE $ws $order");
    
    $result = mysqli_query(connect(), "SELECT * FROM $table WHERE $ws $order");

    $r = array();
    while ($row = mysqli_fetch_array($result)) {
        $r[] = $row;
    }
    return $r;
}

function get($table, $ws) {
//echo "SELECT * FROM $table WHERE $ws ";die;
    $result = mysqli_query(connect(), "SELECT * FROM $table WHERE $ws ");

    $r = array();
    $row = mysqli_fetch_array($result);

    return $row;
}

$con = connect();

//$_GET["checkUserName"] = 'testuser';
//
//$uname = getWhere('users', array('uname' => $_GET["checkUserName"]));
//
//
//print_r($uname);


