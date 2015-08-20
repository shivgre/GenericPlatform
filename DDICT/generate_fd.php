<?php

/**
 * Created by Susmit.
 * User: work
 * Date: 10/12/14
 * Time: 3:25 AM
 */
/// $con is set already in db.php file///



echo "<h3>GENERATE Field Dictionary Table<h3>";

echo "<h4><a href='?action=newFD'> CLICK HERE TO GENERATE Field Dictionary Table</a></h4>";

///////////
///// FD functions starts here/////////
/////////////////////////////////////////


function getLabelName($name) {
    $new = explode("_", $name);
    $newval = "";
    foreach ($new as $k => $v) {
        $newval = $newval . " " . $v;
    }
    return $newval;
}

if ($con->connect_errno) {
    printf("Connect failed: %s\n", $con->connect_error);
    exit();
}

function newFD() {

    require('dictionaryConfig.php');

    //$con->query("TRUNCATE TABLE $FDtbl");


    $dd = $con->query("SELECT * from $DDtbl");

    while ($row = $dd->fetch_assoc()) {

        //print_r($row);die;


        $tbName = $row['database_table_name'];

        $tblAlias = $row['table_alias'];

        $rs = $con->query("SHOW COLUMNS FROM $tbName");

        $fields_used = $row['fields_used'];

        if (!empty($fields_used)) {
            $fields_used = explode('-', $fields_used);
//    print_r($fields_used);die;

            $start = $end = '';

            if (!empty($fields_used[0]) && !empty($fields_used[1])) {
//// 1-1
                $start = $fields_used[0];

                $end = $fields_used[1];
            }
        } else {
            $start = 1;

            $end = $rs->num_rows;
        }
        //else if (!empty($fields_used[0]) && empty($fields_used[1])) {
//
//        //// 10-  or only one row
//
//        if( isset($fields_used[1]) ){
//            
//            exit('yes');
//        }else{
//            
//            exit('no');
//        }
//        
//        $start = 1;
//
//        $end = $fields_used[0];
//    } else if (empty($fields_used[0]) && !empty($fields_used[1])) {
//
//        //// -10
//
//        $start =  ($rs->num_rows) - $fields_used[1];
//
//        $end = $rs->num_rows;
//    }
        //echo $start . '<br>' . $end;die;
        //*****///*** checking fd_initialization field value and dealing according to value 

        if ($row['fd_initialization'] == 'delete') {
            $con->query("delete from $FDtbl where table_alias='$row[table_alias]'");
            $action = 'delete';
        } else
        if ($row['fd_initialization'] == 'update') {

            $action = 'update';
        }


        $i = $j = 1;
        while ($fdCol = $rs->fetch_assoc()) {

            $field = $fdCol['Field'];

            if ($i >= $start) {

                if ($i <= $end) {
                    ////// DEFAULT VALUES FIRST///////////

                    $fdData['help_message'] = $DEFAULT['FD']['help_message'];

                    $fdData['error_message'] = $DEFAULT['FD']['error_message'];

                    $fdData['format_length'] = $DEFAULT['FD']['format_length'];

                    $fdData['privilege_level'] = $DEFAULT['FD']['privilege_level'];

                    $fdData['visibility'] = $DEFAULT['FD']['visibility'];

                    //$fdData['sub_tab_num'] = $DEFAULT['FD']['sub_tab_num'];

                    $fdData['dropdown_alias'] = $DEFAULT['FD']['dropdown_alias'];

                    $fdData['required'] = $DEFAULT['FD']['required'];

                    $fdData['editable'] = $DEFAULT['FD']['editable'];


                    ////////////

                    $fdData['generic_field_name'] = $field;

                    $fdData['table_alias'] = $tblAlias;

                    //$fdData['generic_field_order'] = $i; previous

                    //$fdData['display_field_order'] = $j; previous 
                    $fdData['display_field_order'] = $i;
                    $j++;
                    ///////////////////////
                    /////////////////////////////////////////*********
                    ////******** Special CASES  *******/////////

                    $label = explode('_', $field);







                    ////***** FOR FIELD_label_name ****/////

                    if (!empty($label[0]) && !empty($label[1])) {

                        $strLabel = implode(' ', $label);

                        $fdData['field_label_name'] = ucwords($strLabel);
                    } else {

                        $fdData['field_label_name'] = ucwords($label[0]);
                    }

///////////////////////////////////////////////////////////////////
                    /////////********** field_identifier=KEYFIELD ***********////////


                    if (!empty($label[0]) && !empty($label[1])) {

                        if ($label[1] == 'id')
                            $fdData['field_identifier'] = 'KEYFIELD';
                    }


                    //////////////////////////////////////////////////////////////
                    ///**** DEALING WITH FORMAT_TYPE = IMAGE EXTRA ..*****/////
                    //$field = 'ddevelopmentphotoproject11';

                    $field = 'd' . $field;

                    $photo = strpos($field, 'photo'); //// return false if found else true

                    $image = strpos($field, 'image');

                    $profile = strpos($field, 'profile');

                    $user = strpos($field, 'user');

                    $product = strpos($field, 'product');

                    $project = strpos($field, 'project');



                    if ((!empty($photo) || !empty($image)) && empty($user) && empty($profile) && empty($project) && empty($product)) {

                        $fdData['format_type'] = 'image';
                    } else if ((!empty($photo) || !empty($image)) && (!empty($user) || !empty($profile) ) && empty($project) && empty($product)) {


                        preg_match_all('!\d+!', $field, $matches);

                        $matches = array_filter($matches);

                        if (empty($matches)) {

                            $fdData['format_type'] = 'profileimage';
                        } else {

                            $fdData['format_type'] = 'image';
                        }
                    } else if ((!empty($photo) || !empty($image)) && empty($user) && empty($profile) && ( empty($project) || empty($product))) {


                        preg_match_all('!\d+!', $field, $matches);

                        $matches = array_filter($matches);

                        if (empty($matches)) {

                            $fdData['format_type'] = 'projectimage';
                        } else {

                            $fdData['format_type'] = 'image';
                        }
                    }

////************** /////////////////////////
                    /////////////////////////////////////////////////
////////////////////Actuall FD insertion goes here//////////

                    if ($action == 'update') {


                        $fcheck = $con->query("select * from $FDtbl where table_alias='projects' and generic_field_name='$fdData[generic_field_name]'");

                        $frow = $fcheck->fetch_assoc();


                        if (!isset($frow)) {

                            insert($FDtbl, $fdData);
                        }
                    }
                    else
                        if ($action == 'delete') {
                            
                             insert($FDtbl, $fdData);
                        }

                    ////******************************///////

                    unset($fdData);
                }
            }///end of start and i if 
            $i++;
        }///FD end of while
        //unset($j);
    }/// DD while ends here

    echo "<pre> Field Definition Generated Successfully";
    $con->close();
}

//////////newFD() ends here
////%%%%%%%%%%%%%%%%55///////

if (isset($_GET['action'])) {

    $_GET['action']();
}    