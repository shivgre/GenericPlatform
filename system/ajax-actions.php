<?php

session_start();

require_once("../appConfig/appConfig.php");
include_once("../application/database/db.php");
require_once '../application/config.php';
require_once '../application/functions.php';
/*
 * 
 * @checklist Multiple Deletion 
 */


if (isset($_POST["checkHidden"]) && !empty($_POST["checkHidden"]) && $_POST["checkHidden"] == 'delete') {

   // exit($_POST['dict_id'][0]);
    //// Image deletion first if there is any /////


    $row = get("data_dictionary", "dict_id='" . $_POST['dict_id'][0] . "'");


    $frow = getWhere("field_dictionary", array("table_alias" => $row['table_alias'], "format_type" => "image"));

    $image_name = getWhere($row['database_table_name'], array($row['parent_key'] => $_GET["list_delete"]));


    foreach ($frow AS $val) {


        foreach ($_POST['list'] as $list_id) {


            $image_name = getWhere($row['database_table_name'], array($row['parent_key'] => $list_id));

            if (!empty($image_name[0][$val['generic_field_name']])) {

                @unlink("../users_uploads/" . $image_name[0][$val['generic_field_name']]);
            }
        }/////inside list
    }





    //// actuall multiple deletion of records/////
    $item = implode(",", $_POST['list']);

    //exit("delete from " . $row['database_table_name'] ." where " . $row['parent_key'] . " IN( $item )");


    mysqli_query($con, "delete from " . $row['database_table_name'] . " where " . $row['parent_key'] . " IN( $item )");
    //exit('yasir');
}



/*
 * 
 * @checklist Multiple copy 
 */


if (isset($_POST["checkHidden"]) && !empty($_POST["checkHidden"]) && $_POST["checkHidden"] == 'copy') {


    $item = implode(",", $_POST['list']);



    mysqli_query($con, "CREATE table temporary_table2 AS SELECT * FROM " . $_SESSION['update_table']['database_table_name'] . " WHERE " . $_SESSION['update_table']['parent_key'] . " IN( $item )");


    mysqli_query($con, "UPDATE temporary_table2 SET " . $_SESSION['update_table']['parent_key'] . " =NULL;");

    mysqli_query($con, "INSERT INTO " . $_SESSION['update_table']['database_table_name'] . " SELECT * FROM temporary_table2;");

    mysqli_query($con, "DROP TABLE IF EXISTS temporary_table2;");

    //exit('yasir');
}

/*
 * 
 * @TAking care of Edit option when user click on tabs
 */

if (isset($_GET["tab_check"]) && !empty($_GET["tab_check"]) && $_GET["tab_check"] == 'true') {

    
    update("data_dictionary", array('dd_editable'=>'1'), array('table_alias' =>$_GET['tab_name'], 'tab_num' => $_GET['tab_num']));
    
}


/*
 * 
 * @checklist single deletion
 */

if (isset($_GET["list_delete"]) && !empty($_GET["list_delete"]) && $_GET["check_action"] == 'delete') {

/// Searching and deleting images from targeted table first

    $row = get("data_dictionary", "dict_id='" . $_GET['dict_id'] . "'");


    $frow = getWhere("field_dictionary", array("table_alias" => $row['table_alias'], "format_type" => "image"));

    $image_name = getWhere($row['database_table_name'], array($row['parent_key'] => $_GET["list_delete"]));


    foreach ($frow AS $val) {


        if (!empty($image_name[0][$val['generic_field_name']])) {

            @unlink("../users_uploads/" . $image_name[0][$val['generic_field_name']]);
        }
    }
    
/// deleting actual record////
   // mysqli_query($con, "delete from " . $_SESSION['update_table']['database_table_name'] . " where " . $_SESSION['update_table']['parent_key'] . "=" . $_GET["list_delete"]);
   
     mysqli_query($con, "delete from " . $row['database_table_name'] . " where " . $row['parent_key'] . "=" . $_GET["list_delete"]);
   
    //exit('yasir');
}

/*
 * 
 * @checklist single copy
 */
if (isset($_GET["list_copy"]) && !empty($_GET["list_copy"]) && $_GET["check_action"] == 'copy') {

    
    
    

    mysqli_query($con, "CREATE table temporary_table2 AS SELECT * FROM " . $_SESSION['update_table']['database_table_name'] . " WHERE " . $_SESSION['update_table']['parent_key'] . " = $_GET[list_copy]");


    mysqli_query($con, "UPDATE temporary_table2 SET " . $_SESSION['update_table']['parent_key'] . " =NULL;");

    mysqli_query($con, "INSERT INTO " . $_SESSION['update_table']['database_table_name'] . " SELECT * FROM temporary_table2;");

    mysqli_query($con, "DROP TABLE IF EXISTS temporary_table2;");
    
}



/*
 * Enabling submit buttons for forms
 */

if (isset($_GET["id"]) && !empty($_GET["id"]) && $_GET["check_action"] == 'enable_edit') {



    $check = getWhere('data_dictionary', array('dict_id' => $_GET["id"]));

//print_r($check[0]['display_page']);die;
    $row = getWhere('data_dictionary', array('dd_editable' => '11', 'display_page' => $check[0]['display_page']));

    if ($row) {

        exit('active');
    } else {

        update('data_dictionary', array('dd_editable' => 11), array('dict_id' => $_GET['id']));

        exit('not-active');
    }
}

////////////
////////////////////////
if (isset($_GET["checkEmail"]) && !empty($_GET["checkEmail"])) {

    $email = getWhere('users', array('email' => $_GET["email"]));
    if ($email) {
        echo "true";
    } else {
        echo "false";
    }
}


if (isset($_GET["checkUserName"]) && !empty($_GET["checkUserName"])) {


    $uname = getWhere('users', array('uname' => $_GET["userName"]));

    if ($uname) {
        echo "true";
    } else {
        echo "false";
    }
}

/* * ********************************* */
/* * ********Image Submit******************* */
/* * ********************************* */
if (!empty($_GET["check_action"]) && $_GET["check_action"] == 'image_submit') {

    $uploadcare_image_url = $_GET['cdnUrl'];
    $filename = $_GET['imgName'];
    $fieldName = $_GET['fieldName'];

    $imageInfo = fileUploadCare($uploadcare_image_url, $filename, "../users_uploads");
    
     if( $_GET['profile_img'] != 'no-profile'){
    
        update("data_dictionary", array("dd_editable" => '1'), array("dict_id" => $_GET['profile_img']));
    }    

    if (!empty($imageInfo)) {

        update($_SESSION['update_table2']['database_table_name'], array($fieldName => $imageInfo['image']), array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2']));
        
    } else {
        exit('notSaved');
    }
    
   
}


/* * ********************************* */
/* * ********Remove Image******************* */
/* * ********************************* */
if (!empty($_GET["check_action"]) && $_GET["check_action"] == 'image_delete') {


    $fieldName = $_GET['fieldName'];

    $row = getWhere($_SESSION['update_table2']['database_table_name'], array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2']));

    $fileName = $row[0][$fieldName];


    if ($fileName != "") {
        if (file_exists("../users_uploads/" . $fileName)) {
            unlink("../users_uploads/" . $fileName);
        }
    }


    $check = update($_SESSION['update_table2']['database_table_name'], array($fieldName => ''), array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2']));
    
     if( $_GET['profile_img'] != 'no-profile'){
    
        update("data_dictionary", array("dd_editable" => '1'), array("dict_id" => $_GET['profile_img']));
    }    

    if ($check)
        exit('Deleted');
}


/* * ********************************* */
/* * ********Image Revert******************* */
/* * ********************************* */
if (!empty($_GET["img_revert"]) && $_GET["img_revert"] == 'img-revert') {
    
$query = getwhere($_SESSION['update_table2']['database_table_name'], array($_SESSION['update_table2']['parent_key'] =>  $_SESSION['search_id2']));

$fieldValue = trim($query[0][$_GET[field_name]]);

exit($fieldValue);
}

?>
