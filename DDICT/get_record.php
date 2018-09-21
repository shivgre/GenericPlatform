<?php

/*
 * 
 * function get_single_record($db_name, $pkey, $search) {
 * ***********
 * 
 * function get_multi_record($db_name, $pkey, $search, $listFilter = 'false', $singleSort = 'false', $listCheck = 'false')
 * 
 * **********************
 * **********************************
 * 
 * function get_listFragment_record($db_name, $pkey, $listFilter = 'false', $limit = 'false', $fields = 'false')
 * ****
 * ***********************8
 */

function get_single_record($db_name, $pkey, $search) {


    $_SESSION['update_table']['search'] = $search;

    $con = connect();

//exit("select * from $db_name where $pkey='$search'");

    $user = $con->query("select * from $db_name where $pkey='$search'");


    return $user->fetch_assoc();
}

function get_multi_record($db_name, $pkey, $search, $listFilter = 'false', $singleSort = 'false', $listCheck = 'false') {

//echo "<font color=red>\$db_name:$db_name, \$pkey:$pkey, \$search:$search, \$listFilter:$listFilter, \$singleSort:$singleSort, \$listCheck:$listCheck</font><br>";

    $_SESSION['update_table']['search'] = $search;


    $con = connect();

// exit(" db=$db_name, keyfield = $pkey, search_id = $search, list_filter = $listFilter, single_sort = $singleSort, listCheck = $listCheck");


    if ($listFilter != 'false')
        $clause = listFilter($listFilter, $search);

//    echo "\$clause:$clause<br>";die;

    // exit("select * from $db_name $clause");

    if (!empty($clause))
        $clause = 'where ' . $clause;

    // exit("select * from $db_name $clause");

    $user = $con->query("select * from $db_name $clause");


//exit("select * from $db_name where $pkey=$search order by $singleSort");

    return $user;
}

function get_listFragment_record($db_name, $pkey, $listFilter = 'false', $limit = 'false', $fields = 'false') {


    $con = connect();



    if ($listFilter != 'false')
        $clause = listFilter($listFilter, $search);


    // exit("select * from $db_name $clause");

    if (!empty($clause))
        $clause = 'where ' . $clause;

    // exit("select * from $db_name $clause");
    
    if(!$fields)
        $fields = "*";
    
    if ($limit)
        $user = $con->query("select $fields from $db_name $clause limit 0, $limit");
    else
        $user = $con->query("select $fields from $db_name $clause");




    return $user;
}
