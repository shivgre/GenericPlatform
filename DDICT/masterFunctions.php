<?php
/*
 * 
 *  This is Intake array which take DD AND FD data and put in master array
 * 
 */

@session_start();

//$_SESSION['listCount'] = 0;



include_once("../application/database/db.php");



/*
 * 
 * Function @file_upload
 */

function uploadAudioFile($parameters) {

    $target_dir = "../users_uploads/audio";
    $randName = rand(124, 1000);
    $fileName = $randName . $parameters['name'];

    $target_file = $target_dir . '/' . $fileName;
    $uploadOk = 1;

    if ($parameters['type'] != "audio/wav" || $parameters['type'] != "audio/ogg" || $parameters['type'] != "audio/mpeg") {
        throw new Exception("This file type is not allowed to upload");

        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        throw new Exception("UploadFail");
// if everything is ok, try to upload file
    } else {


        if (@move_uploaded_file($parameters["tmp_name"], $target_file)) {

            return $fileName;
        } else {

            return FALSE;
        }
    }
}

/*
 * 
 * Function @uploadImageFile
 */

function uploadImageFile($uploadCareURL, $imageName) {

    $src = "../users_uploads/";

    $uploadcare_image_url = $uploadCareURL;
    $filename = $imageName;
    $ext = pathinfo($filename, PATHINFO_EXTENSION);   //returns the extension
    $allowed_types = array('jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF', 'png', 'PNG', 'bmp');
    $randName = rand(124, 1000);
    $imgInfo = array();

    // If the file extension is allowed
    if (in_array($ext, $allowed_types)) {
        $new_filename = $filename;

        //$new_filepath = $base_path.'upload/orig/'.$new_filename;
        $imgpath = $src . $randName . $new_filename;
        $thumb_imgpath = $src . "thumbs/" . $randName . $new_filename;

        // Attempt to copy the image from Uploadcare to our server
        if (copy($uploadcare_image_url, $imgpath)) {
            //Resize the image
            include_once('../application/resizeImage.php');
            $image = new ResizeImage();
            $wk_img_wt = '';
            $wk_img_ht = '';

            list($wk_img_wt, $wk_img_ht) = getimagesize($imgpath);
            if ($wk_img_wt >= 650 && $wk_img_wt > $wk_img_ht) {
                $image->load($imgpath);
                $image->setImgDim($wk_img_wt, $wk_img_ht);
                $image->resizeToWidth(650);
                $image->save($imgpath);
            }
            if ($wk_img_ht > $wk_img_wt && $wk_img_ht >= 430) {
                $image->load($imgpath);
                $image->setImgDim($wk_img_wt, $wk_img_ht);
                $image->resizeToHeight(430);
                $image->save($imgpath);
            }

            //For Thumb
            if ($wk_img_wt > $wk_img_ht && $wk_img_wt >= 325) {
                $image->load($imgpath);
                $image->setImgDim($wk_img_wt, $wk_img_ht);
                $image->resizeToWidth(325);
                $image->save($thumb_imgpath);
            }

            if ($wk_img_ht > $wk_img_wt && $wk_img_ht > 215) {
                $image->load($imgpath);
                $image->setImgDim($wk_img_wt, $wk_img_ht);
                $image->resizeToHeight(215);
                $image->save($thumb_imgpath);
            }

            $imgInfo['image'] = $randName . $new_filename;
            $imgInfo['thumb_image'] = "thumb_" . $randName . $new_filename;
            return $imgInfo;
        } else {
            return $imgInfo;
        }
    } else {
        return $imgInfo;
    }
}

/*
 * Cancel Button Action
 */



if (isset($_GET["button"]) && !empty($_GET["button"]) && $_GET["button"] == 'cancel') {



    update("data_dictionary", array("dd_editable" => '1'), array("display_page" => $_GET['display']));


    //exit($_SESSION[return_url2]);&fnc=onepage

    if ($_GET['fnc'] != 'onepage') {

        echo "<script>window.location='$_SESSION[return_url2]'</script>";
    } else {
        echo "<script>window.location='$_SESSION[return_url2]$_SESSION[anchor_tag]'</script>";
    }
}



/*
 * Add Function
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND $_GET['action'] == 'add') {


    if (array_key_exists('field_name_unique', $_POST)) {

        unset($_POST['field_name_unique']);

        //var_dump($_POST);
    }


    /*
      echo
      $_SESSION['dict_id'] .
      $_SESSION['update_table2']['database_table_name'] .
      $_SESSION['update_table2']['parent_key'] . "<br>" . $_SESSION[return_url2];
      die; */
    //print_r($_POST);die; 


    $row = get('data_dictionary', 'dict_id=' . $_SESSION['dict_id']);


    if (!empty($row['list_filter'])) {



        $parent_key = explode(";", $row['list_filter']);

        if (!empty($parent_key[1])) {

            $listCond = $parent_key[1];
        }

        if (!empty($parent_key[0])) {
            $i = 0;


            $parent_key = explode(",", $parent_key[0]);

            foreach ($parent_key as $val) {

                $keyField = explode("=", $val);

                $keyVal[$i] = array(trim($keyField[0]) => trim($keyField[1]));

                $i++;
            }
        }

        foreach ($keyVal as $val) {

            if (!empty($val['projects'])) {

                $pid = $val['projects'];
            }

            if (!empty($val['users'])) {

                $uid = $val['users'];
            }
        }

        if (!empty($pid)) {


            $project = $pid . "=" . $search;
        }

        if (!empty($uid)) {



            $user = array($uid => $_SESSION['uid']);
        }
    }

    if (!empty($user)) {

        $data = array_merge($user, $_POST);
    } else {

        $data = $_POST;
    }


    //print_r($data);die;

    $check = insert($_SESSION['update_table2']['database_table_name'], $data);



//$url = explode("&" ,$_SESSION[return_url]);
//print_r($url);die;
    if ($_GET['fnc'] != 'onepage') {

        echo "<script>window.location='$_SESSION[return_url2]'</script>";
    } else {
        echo "<script>window.location='$_SESSION[return_url2]$_SESSION[anchor_tag]'</script>";
    }
}



/*
 * Update function STARTS here
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' AND $_GET['action'] == 'update') {


    //echo "<pre>";
    //print_r($_POST);

    foreach ($_POST['imgu'] as $img => $img2) {
        // print_r($img);
        //print_r($img2['imageName']);
        //print_r($img2['uploadcare']);


        if (!empty($img2['uploadcare']) && !empty($img2['imageName'])) {

            $ret = uploadImageFile($img2['uploadcare'], $img2['imageName']);

            $oldImage = $_POST[$img]['img_extra'];

            if (!empty($ret['image'])) {

                unset($_POST['imgu'][$img]);

                //var_dump($_POST);

                $_POST[$img] = $ret['image'];
            }

            //if user want to replace current image

            if (!empty($oldImage)) {

                @unlink("../users_uploads/$oldImage");

                @unlink("../users_uploads/thumbs/$oldImage");

                //unset($_POST[$img]);
            }
            //if user didn't touch the with images
        } else {

//if user clicks on remove current image
            if (empty($img2['uploadcare']) && !empty($img2['imageName'])) {

                if (!empty($_POST[$img]['img_extra'])) {

                    @unlink("../users_uploads/$img2[imageName]");

                    @unlink("../users_uploads/thumbs/$img2[imageName]");


                    unset($_POST['imgu'][$img]);

                    $_POST[$img] = '';
                } else {

                    unset($_POST['imgu'][$img]);
                }
            } else {

                unset($_POST['imgu'][$img]);
            }
        }
    }
//deleting array which is used for holding imgu values
    if (empty($_POST['imgu']))
        unset($_POST['imgu']);

    //print_r($_POST);
    //exit();
    //echo '<br><br>';



    foreach ($_FILES as $file => $file2) {


        //checking if audio file is not empty
        if (!empty($_FILES[$file]['name'])) {

            $file_name = uploadAudioFile($file2);
            // if file successfully uploaded to target dir
            if (!empty($file_name)) {

                $_POST[$file] = $file_name;
            }
        } else {

            $_POST[$file] = '';
        }

        //Dealing with database now
        $row = getWhere($_SESSION['update_table2']['database_table_name'], array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2']));

        $oldFile = $row[0][$file];

        if ($oldFile != "") {
            if (file_exists("../users_uploads/audio/" . $oldFile)) {
                @unlink("../users_uploads/audio/" . $oldFile);
            }
        }
    }


//print_r($_POST);die;
    //exit($_SESSION['dict_id']);

    $check = update($_SESSION['update_table2']['database_table_name'], $_POST, array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2']));


    update('data_dictionary', array('dd_editable' => '1'), array('dict_id' => $_SESSION['dict_id']));

    //exit($_SESSION['display2']);
    //if ($check == 1) {


    if ($_GET['checkFlag'] == 'true') {

//$url = explode("&" ,$_SESSION[return_url]);
//print_r($url);die;
        if ($_GET['fnc'] != 'onepage') {

            echo "<script>window.location='$_SESSION[return_url2]'</script>";
        } else {
            echo "<script>window.location='$_SESSION[return_url2]$_SESSION[anchor_tag]'</script>";
        }
    } else {

        if (!empty($_SESSION['display2'])) {

            $_SESSION[display] = $_SESSION['display2'];
        }

        if ($_GET['fnc'] != 'onepage') {

            echo "<script>window.location = '?display=$_SESSION[display]&tab=$_SESSION[tab]&tabNum=$_GET[tabNum]';</script>";
        } else {
            echo "<script>window.location='$_SESSION[return_url2]$_SESSION[anchor_tag]'</script>";
        }
    }
    // } else
    // echo('update error');
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] == 'login') {


    $tbl = $_SESSION['select_table']['database_table_name'];

    $pKey = $_SESSION['select_table']['parent_key'];

    $con = connect();

    $loginKeys = array_keys($_POST);


    $value1 = $_POST[$loginKeys[0]];

    $value2 = $_POST[$loginKeys[1]];

    //exit("SELECT * FROM $tbl where $loginKeys[0] = '$value1' and $loginKeys[1] = '$value2' ");

    $rs = $con->query("SELECT * FROM $tbl where $loginKeys[0] = '$value1' and $loginKeys[1] = '$value2' ");

    $row = $rs->fetch_assoc();


    if ($row) {

        $_SESSION['uid'] = $row[$pKey];

        $_SESSION['uname'] = $row[$_SESSION['select_table']['username']];

        if (isset($_SESSION['callBackPage'])) {


            echo "<META http-equiv='refresh' content='0;URL=" . $_SESSION['callBackPage'] . "'>";

            unset($_SESSION['callBackPage']);
            exit();
        } else {

            FlashMessage::add(PROFILE_COMPLETE_MESSAGE);
            echo "<META http-equiv='refresh' content='0;URL=" . BASE_URL . "index.php'>";
            exit();
        }
    } else {

        FlashMessage::add('UserName or Password Incorrect.');
        echo "<META http-equiv='refresh' content='0;URL=" . BASE_URL_SYSTEM . "login.php'>";
        exit();
    }
}
/*
 * 
 * Getting tabs name for display_page
 */

function Get_Links($display_page) {

    $_SESSION['display'] = $display_page;
    global $tab;
//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $con = connect();

    $rs = $con->query("SELECT * FROM  data_dictionary where display_page = '$display_page' and tab_num != 0 order by tab_num");
    $i = 1;

    echo " <ul class='nav nav-tabs' role='tablist' >";
    while ($row = $rs->fetch_assoc()) {



        if ($i == 1 && !( isset($_SESSION['tab']) )) {
//$_SESSION['first_tab'] = $row[table_alias];
//            if (!isset($_SESSION['first_tab']))
//                echo "<script>window.location='$actual_link';</script>";
            $tab = $row[table_alias];
            $_SESSION['tab'] = $tab;


            echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num] class='tab-class'>$row[tab_name]</a></li>";
        } else if ($_SESSION['tab'] == $row[table_alias] && $_GET['tabNum'] == $row['tab_num']) {

            echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num] class='tab-class'>$row[tab_name]</a></li>";
        } else {

            echo "<li><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num] class='tab-class'>$row[tab_name]</a></li>";
        }
        $i++;
    }

    echo "</ul>";
}

/////////// get_links() ends here
//////////////////////////////

function serial_layout($display_page, $style) {

    $con = connect();

    $rs = $con->query("SELECT * FROM  data_dictionary where display_page = '$display_page' and tab_num != 0 order by tab_num");

    $editable = 'false';
    while ($row = $rs->fetch_assoc()) {
        echo "<form class='$style'>
  <fieldset>
    <legend>$row[tab_name]:</legend>";
        Get_Data_FieldDictionary_Record($row['table_alias'], $display_page, $editable);
        echo "</fieldset>
</form>";
    }
}

//////////////
//////////////***** DISPALY TABS AS H1 TAG ******

function display_content($row) {

    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $editable = 'true';

    $con = connect();


///for taking inline anchoring
    $tab_anchor = trim($row[tab_name]);

    $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$row[tab_num]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");


    $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$row[tab_num]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order";

    $rs2 = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$row[tab_num]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");


    $row1 = $rs->fetch_assoc();


    ///////// for displaying image container
    $image_display = 'true';
//print_r($row1);die;

    /*
      if (trim($row1['table_type']) == 'profile-image') {

      $_SESSION['profile-image'] = 'profile-image';
      } else {

      unset($_SESSION['profile-image']);
      }
     */

    if ($row1['database_table_name'] == $_SESSION['select_table']['database_table_name'])
        $_SESSION['search_id'] = $_SESSION['uid'];
    else if (trim($row1['table_type']) == 'child') {

        $_SESSION['search_id'] = $_SESSION['parent_value'];
    }/* else
      $_SESSION['search_id'] = '76'; */ /// for displaying one user



    if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {

        //  $_SESSION['search_id'] = $_GET['search_id'];
    }

    if (isset($_GET['id']) && $_GET['id'] != '') {
        $_SESSION['search_id'] = $_GET['id'];
//$_SESSION['update_table']['parent_key'] = 'id';
    }


    $_SESSION['update_table']['database_table_name'] = $row1['database_table_name'];

    $_SESSION['update_table']['parent_key'] = $row1['parent_key'];

    /*     * ****** for update *** */



    if ($row1['dd_editable'] == '11') {

        $_SESSION['dict_id'] = $row1['dict_id'];

        if (!empty($_GET['search_id']))
            $_SESSION['search_id2'] = $_GET['search_id'];
        else
            $_SESSION['search_id2'] = $_SESSION['search_id'];

        $_SESSION['update_table2']['database_table_name'] = $_SESSION['update_table']['database_table_name'];

        $_SESSION['update_table2']['parent_key'] = $_SESSION['update_table']['parent_key'];

        $_SESSION['anchor_tag'] = "#" . $tab_anchor;

        if ($_GET['checkFlag'] == 'true') {

            //was giving error in child list propel so made changes///
            //$_SESSION['return_url2'] = $_SESSION['return_url'];

            $_SESSION['return_url2'] = BASE_URL . "system/profile.php?display=$_GET[display]&layout=$_GET[layout]&style=$_GET[layout]";
        } else {

            $_SESSION['return_url2'] = $actual_link;
        }
        //$_SESSION['table_alias'] = $row1['table_alias'];
    }


///rs use
//if($tab_anchor == 'My Gallery')
    //  $tab_anchor= 'my_gallery';
    echo "<section class='section-sep'><a name='$tab_anchor'></a><h1>$row[tab_name]</h1><!-- h1-content class not used-->";



    if (!empty($_GET['ta']) && $_GET['ta'] == $row1['table_alias'] && !empty($_GET['search_id'])) {

        if ($_GET['table_type'] == 'parent') {


            $_SESSION['parent_value'] = $_GET['search_id'];
        }

        $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_GET['search_id']);
    } else {

        $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id']);
    }


    if (isset($_GET['addFlag']) && $_GET['addFlag'] == 'true' && $_GET['tabNum'] == $row1['tab_num'] && $_GET['tab'] == $row1['table_alias']) {

        $_SESSION['dict_id'] = $row1['dict_id'];

        if (!empty($_GET['search_id']))
            $_SESSION['search_id2'] = $_GET['search_id'];
        else
            $_SESSION['search_id2'] = $_SESSION['search_id'];

        $_SESSION['update_table2']['database_table_name'] = $_SESSION['update_table']['database_table_name'];

        $_SESSION['update_table2']['parent_key'] = $_SESSION['update_table']['parent_key'];


        if ($_GET['checkFlag'] == 'true') {
            $_SESSION['return_url2'] = $_SESSION['return_url'];
        } else {
            $_SESSION['return_url2'] = $actual_link;
        }

        echo "<form action='?action=add&checkFlag=true&tabNum=$_GET[tabNum]&fnc=onepage' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";



        while ($row = $rs2->fetch_assoc()) {

            formating_Update($row);
        }//// end of while loop
        //if ($_GET['checkFlag'] == 'true') {

        $actual_link = $_SESSION[return_url2] . "&fnc=onepage";
        // }


        echo "<div id='form-footer'>  
                                    <br><br>
                            <div class='form_element update-btn2'>
                                <input type='submit'  value='Save Record' class='btn btn-primary update-btn' />
                            </div>
                            <div class='form_element'>
                                <label>
                                    <a href='$actual_link' ><input type='button' name='profile_cancel' value='Cancel' class='btn btn-primary update-btn' /></a>
                                </label>
                            </div>
                          
                            
                                </div>";


        echo "<div style='clear:both'></div></form>";
    } else {



        if (( ( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || ( $_GET['edit'] == 'true') && $_GET['ta'] == $row1['table_alias'] ) && $row1['table_type'] != 'content') {


            if (isset($_SESSION['return_url']) && $_GET['checkFlag'] == 'true') {
                echo "<form action='?action=update&checkFlag=true&tabNum=$row1[tab_num]&fnc=onepage' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
            } else {
                echo "<form action='?action=update&tabNum=$row1[tab_num]&fnc=onepage' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
            }

            $image_display = 'true';

            if ($_GET['checkFlag'] == 'true' && $row1['dd_editable'] == 11) {

                echo "<ol class='breadcrumb'>
  <li><a href='$_SESSION[return_url2]&button=cancel&fnc=onepage'>Back To Lists</a></li>
</ol>";
            }

            if ($row1['dd_editable'] == 1) {
                echo "<button type='button' class='btn btn-default pull-right edit-btn' id='$row1[dict_id]' >" . EDIT . "</button>";
                $image_display = 'false';
            }

            if (isset($_GET['id'])) {
                $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_GET['id']);
            }
//print_r($urow);die;

            while ($row3 = $rs2->fetch_assoc()) {

                formating_Update($row3, $urow, $image_display);
            }//// end of while loop
        } else {
//// fetching child list
// if ($row1['list_views'] == 'NULL' || $row1['list_views'] == '') {
/////////////
////////////////
//  echo "<form action='?action=update&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
// Child_List($qry);
// } else {

            if ($row1['table_type'] == 'content') {

                if (strpos(trim($row1['description']), "ttp://")) {

                    $url = trim($row1['description']);

                    echo "<iframe src='$url'></iframe>";
                } else {
                    echo "<div class='$row1[list_style]'>$row1[description]</div>";
                }
            } else {

                list_display($qry, $row1['tab_num'], $tab_anchor); //// list displays

                echo "<div style='clear:both'></div>";
            }
// }
        }
//check

        if ($editable == 'true') {
            if (( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || $_GET['edit'] == 'true') {
                //if (empty($_SESSION['profile-image'])) {

                $cancel_value = 'Cancel';

                if ($row1['dd_editable'] == 11) {

                    if ($_GET['checkFlag'] == 'true') {

                        $cancel_value = 'Cancel';
                    }

                    $actual_link = $_SESSION['return_url2'] . "&button=cancel&fnc=onepage";


                    echo "<div id='form-footer'>  
                                    <br><br>
                            <div class='form_element update-btn2'>
                                <input type='submit'  value='Update' class='btn btn-primary update-btn' />
                            </div>
                            <div class='form_element'>
                                <label>
                                    <a href='$actual_link' ><input type='button' name='profile_cancel' value='$cancel_value' class='btn btn-primary update-btn' /></a>
                                </label>
                            </div>
                          
                            
                                </div>";
                }/// if for submit and cancel ends here
                //profile-image }
            }

            echo "<div style='clear:both'></div></form></section><!--<div class='h1-sep'><span></span></div>-->";
        }
    }
}

function Get_Data_FieldDictionary_Record($table_alias, $display_page, $tab_status = 'false', $tab_num = 'false', $editable = 'true') {

    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $con = connect();

    ///setting form editable if user click on list for editing purpose

    if (!empty($_GET['edit']) && $_GET['edit'] == 'true') {

        $con->query("update data_dictionary set dd_editable='1' where display_page=$_GET[display]");

        $con->query("update data_dictionary set dd_editable='11' where display_page='$_GET[display]' and tab_num='$_GET[tabNum]' and table_alias='$_GET[tab]'");
    }


    if (empty($_GET['tabNum'])) {
        $rs = $con->query("SELECT tab_num FROM data_dictionary where display_page='$display_page' and tab_num != 0 order by tab_num");
        $row = $rs->fetch_assoc();
        $_GET['tabNum'] = $row['tab_num'];
    }

/////tab_status will equal to zero when tab_num=0 exist for any dd->display_page
    if ($tab_status == 'true') {

        $rs = $con->query("SELECT * FROM data_dictionary where display_page='$display_page' and tab_num != 0 order by tab_num");
        while ($row = $rs->fetch_assoc()) {
            display_content($row);
        }
    } else {




        if ($tab_status == 'bars') {

            $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and data_dictionary.tab_num='$tab_num' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");


            $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$tab_num'  and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order";

            $rs2 = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page'  and tab_num='$tab_num' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");
        } else {

            $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");


            $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$_GET[tabNum]'  and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order";

            $rs2 = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page'  and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");
        }

        $row1 = $rs->fetch_assoc();

        ///////// for displaying image container
        $image_display = 'true';
//print_r($row1);die;

        /* profile-image
          if (trim($row1['table_type']) == 'profile-image') {

          $_SESSION['profile-image'] = 'profile-image';
          } else {

          unset($_SESSION['profile-image']);
          } */


        if ($row1['database_table_name'] == $_SESSION['select_table']['database_table_name'])
            $_SESSION['search_id'] = $_SESSION['uid'];
        else if (trim($row1['table_type']) == 'child') {

            $_SESSION['search_id'] = $_SESSION['parent_value'];
        } else
            $_SESSION['search_id'] = '76'; /// for displaying one user


        if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {

            // $_SESSION['search_id'] = $_GET['search_id'];
        }

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $_SESSION['search_id'] = $_GET['id'];
//$_SESSION['update_table']['parent_key'] = 'id';
        }


        $_SESSION['update_table']['database_table_name'] = $row1['database_table_name'];

        $_SESSION['update_table']['parent_key'] = $row1['parent_key'];

        /*         * ****** for update or ADD *** */


        if ($row1['dd_editable'] == '11') {

            $_SESSION['dict_id'] = $row1['dict_id'];

            //$_SESSION['table_alias_image'] = $row1['table_alias'];

            if (!empty($_GET['search_id']))
                $_SESSION['search_id2'] = $_GET['search_id'];
            else
                $_SESSION['search_id2'] = $_SESSION['search_id'];

            $_SESSION['update_table2']['database_table_name'] = $_SESSION['update_table']['database_table_name'];

            $_SESSION['update_table2']['parent_key'] = $_SESSION['update_table']['parent_key'];


            if ($_GET['checkFlag'] == 'true') {
                $_SESSION['return_url2'] = $_SESSION['return_url'];
            } else {
                $_SESSION['return_url2'] = $actual_link;
            }
            //$_SESSION['table_alias'] = $row1['table_alias'];
        }

        if (!empty($_GET['ta']) && $_GET['ta'] == $row1['table_alias'] && !empty($_GET['search_id'])) {

            if ($_GET['table_type'] == 'parent') {


                $_SESSION['parent_value'] = $_GET['search_id'];
            }


            $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_GET['search_id']);
        } else {

            $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id']);
        }
///rs use
///displaying **** EDIT BUTTON ****////

        if (isset($_GET['addFlag']) && $_GET['addFlag'] == 'true' && $_GET['tabNum'] == $row1['tab_num'] && $_GET['tab'] == $row1['table_alias']) {

            $_SESSION['dict_id'] = $row1['dict_id'];

            if (!empty($_GET['search_id']))
                $_SESSION['search_id2'] = $_GET['search_id'];
            else
                $_SESSION['search_id2'] = $_SESSION['search_id'];

            $_SESSION['update_table2']['database_table_name'] = $_SESSION['update_table']['database_table_name'];

            $_SESSION['update_table2']['parent_key'] = $_SESSION['update_table']['parent_key'];


            if ($_GET['checkFlag'] == 'true') {
                $_SESSION['return_url2'] = $_SESSION['return_url'];
            } else {
                $_SESSION['return_url2'] = $actual_link;
            }

            echo "<form action='?action=add&checkFlag=true&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";



            while ($row = $rs2->fetch_assoc()) {

                formating_Update($row);
            }//// end of while loop
            //if ($_GET['checkFlag'] == 'true') {

            $actual_link = $_SESSION[return_url2];
            // }


            echo "<div id='form-footer'>  
                                    <br><br>
                            <div class='form_element update-btn2'>
                                <input type='submit'  value='Save Record' class='btn btn-primary update-btn' />
                            </div>
                            <div class='form_element'>
                                <label>
                                    <a href='$actual_link' ><input type='button' name='profile_cancel' value='Cancel' class='btn btn-primary update-btn' /></a>
                                </label>
                            </div>
                          
                            
                                </div>";


            echo "<div style='clear:both'></div></form>";
        } else {

            if (( ( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || $_GET['edit'] == 'true') && $row1['table_type'] != 'content') {


                if (isset($_SESSION['return_url']) && $_GET['checkFlag'] == 'true') {
                    echo "<form action='?action=update&checkFlag=true&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
                } else {
                    echo "<form action='?action=update&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
                }

///// To show image uploader buttons

                if ($_GET['checkFlag'] == 'true' && $row1['dd_editable'] == 11) {

                    echo "<ol class='breadcrumb'>
  <li><a href='$_SESSION[return_url]&button=cancel'>Back To Lists</a></li>
</ol>";
                }


                if ($row1['dd_editable'] == 1) {
                    echo "<button type='button' class='btn btn-default pull-right edit-btn' id='$row1[dict_id]'>" . EDIT . "</button>";

                    $image_display = 'false';
                }


                if (isset($_GET['id'])) {
                    $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_GET['id']);
                }
//print_r($urow);die;

                while ($row = $rs2->fetch_assoc()) {

                    formating_Update($row, $urow, $image_display);
                }//// end of while loop
            } else {
//// fetching child list
// if ($row1['list_views'] == 'NULL' || $row1['list_views'] == '') {
/////////////
////////////////
//  echo "<form action='?action=update&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
// Child_List($qry);
// } else {

                if (trim($row1['table_type']) == 'content') {


                    if (strpos(trim($row1['description']), "ttp://")) {

                        $url = trim($row1['description']);

                        echo "<iframe src='$url'></iframe>";
                    } else {
                        echo "<div class='$row1[list_style]'>$row1[description]</div>";
                    }
                } else {

                    list_display($qry, $row1['tab_num']); //// list displays

                    echo "<div style='clear:both'></div>";
                }
// }
            }
/// formating ends here  ///

            if ($editable == 'true') {
                if (( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || $_GET['edit'] == 'true') {
                    // if (empty($_SESSION['profile-image'])) {
///when edit form is not list
                    $cancel_value = 'Cancel';

                    if ($row1['dd_editable'] == 11) {

                        if ($_GET['checkFlag'] == 'true') {

                            $actual_link = $_SESSION[return_url];

                            $cancel_value = 'Cancel';
                        }

                        $actual_link = $actual_link . "&button=cancel";


                        echo "<div id='form-footer'>  
                                    <br><br>
                            <div class='form_element update-btn2'>
                                <input type='submit'  value='Update' class='btn btn-primary update-btn' />
                            </div>
                            <div class='form_element'>
                                <label>
                                    <a href='$actual_link' ><input type='button' name='profile_cancel' value='$cancel_value' class='btn btn-primary update-btn' /></a>
                                </label>
                            </div>
                          
                            
                                </div>";
                    }/// if for submit and cancel ends here
                    // profile-image }
                }

                echo "<div style='clear:both'></div></form>";
            }
        }
    }//else ends here where tab_num=0 is not part of dd->display_page
}

///////////
/////////////////////////
function Child_List($qry) {

    $con = connect();

    $child_record = get_multi_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id']);

///////// Fetching Headings////

    $urow = $child_record->fetch_assoc();

    $tab = '';
    $rs = $con->query($qry);

    echo "<table class='table table-striped'><tr><th>Action</th>";

    while ($row = $rs->fetch_assoc()) {

        $listFilter = $row['list_filter'];

        $id = $row['parent_key'];

        $tab = $row['table_alias'];

        echo "<th>" . $row['field_label_name'] . "</th>";
    }//// end of while loop

    echo "</tr>";


///////////////

    $child_record = get_multi_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id'], $listFilter);

    while ($urow = $child_record->fetch_assoc()) {

        $rs = $con->query($qry);

        if ($_GET['checkFlag'] == 'true') {
            echo "<tr><td><a href='?display=$_SESSION[display]&tab=$tab&id=$urow[$id]&checkFlag=true&tabNum=$_GET[tabNum]'>Edit</a></td>";
        } else
            echo "<tr><td><a href='?display=$_SESSION[display]&tab=$tab&id=$urow[$id]&tabNum=$_GET[tabNum]'>Edit</a></td>";
        while ($row = $rs->fetch_assoc()) {

            ChildFormating_Update($row, $urow);
        }//// end of while loop

        echo "</tr>";
    }

    echo "</table>";
}

///////////////////////////childFormating///////////

function ChildFormating_Update($row, $urow) {

    $field = $row['generic_field_name'];

    echo "<td>" . $urow[$field] . "</td>";
}

/////////////////////
//////////////////////////



function formating_Update($row, $urow = 'false', $image_display = 'false') {


    $field = $row['generic_field_name'];

// $_SESSION[$field] = $field;

    $readonly = '';
    $required = '';

    if ($row['editable'] == 'false')
        $readonly = 'readonly';


    if (!empty($row['required']))
        $required = 'required';


    if (empty($row['format_type'])) {
        $row['format_type'] = 'text';
    }

    $fieldValue = ($urow != 'false') ? $urow[$field] : '';

    switch ($row['format_type']) {

        case "richtext":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            echo "<textarea class='ckeditor' name='$field' >$fieldValue</textarea>";
            echo "</div>";
            break;

        case "dropdown":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            if ($urow != 'false')
                dropdown($row, $urow);
            else
                dropdown($row);
            echo "</div>";
            break;

        case "email":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            echo "<input type='email' name='$field' value='$fieldValue' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'> ";
            echo "</div>";
            break;

        case "textbox":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            echo "<textarea name='$field' class='form-control' cols='$row[format_length]'>$fieldValue</textarea>";
            echo "</div>";
            break;

        case "checkbox":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            if ($urow != 'false')
                checkbox($row, $urow);
            else
                checkbox($row);
            echo "</div>";
            break;

        case "new_line":
            echo "<br>";
            break;


        case "line_divider":
            echo "<hr>";
            break;

        case "image":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            if ($urow != 'false')
                image_upload($row, $urow, $image_display);
            else
                image_upload($row);
            echo "</div>";
            break;

        case "audio":
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            if ($urow != 'false')
                audio_upload($row, $urow, $image_display);
            else
                audio_upload($row);
            echo "</div>";
            break;


        default :
            echo "<div class='new_form'><label>$row[field_label_name]</label>";
            echo "<input type='$row[format_type]' name='$field' value='$fieldValue' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'>";
            echo "</div>";
    }
}

/*
 * audio UPLOAD FUNCTION
 */

function audio_upload($row, $urow = 'false', $image_display = 'false') {


    if (!empty($urow[$row[generic_field_name]])) {

        $audio_path = BASE_URL . "users_uploads/audio/" . $urow[$row[generic_field_name]];

        echo "<div class='audio-css'><audio controls>
  <source src='$audio_path' type='audio/wav'>
Your browser does not support the audio element.
</audio></div>";

        if ($image_display == 'true') {
            echo "<button class='btn btn-primary update-btn remove-audio-btn rem-img-size' id='$row[generic_field_name]' type='button'>REMOVE</button><div class='audio-placing'></div>";
        }
    } else {

        if ($image_display == 'true') {
            //accept='audio/*;capture=microphone'
            echo "<input type='file' name='$row[generic_field_name]' class='form-control' >";
        } else {

            echo "<input type='file' name='$row[generic_field_name]' class='form-control'  disabled>";
        }
    }
}

/*
 * IMAGE UPLOAD FUNCTION
 */

function image_upload($row, $urow = 'false', $image_display) {

    $row[generic_field_name] = trim($row[generic_field_name]);

    $img = ($urow != 'false') ? $urow[$row[generic_field_name]] : '';


    $img_show = (!empty($img) ) ? $img : 'NO-IMAGE-AVAILABLE-ICON.jpg';

    if ($image_display == 'true') {
        echo "<div class='left-content'>";
        $masterToolTip = "masterTooltip";

        $title = "title='Click on the Image!'";
    } else {
        echo "<div class='left-content-clone'>";

        $masterToolTip = $title = "";
    }
    echo "<span> <img src='" . BASE_URL . "users_uploads/" . $img_show . "' border='0' width='150' class='img-thumbnail img-responsive user_thumb $masterToolTip' alt='$row[generic_field_name]' $title /> </span>";

    /* if (!empty($_SESSION['profile-image'])) {


      $img_name = $_SESSION['dict_id'];
      } else {

      $img_name = 'no-profile';
      } */

    if (!empty($urow[$row[generic_field_name]])) {
        $field_val = $urow[$row[generic_field_name]];
        echo "<input type='hidden' name='imgu[$row[generic_field_name]][imageName]' class='$row[generic_field_name]' value='$field_val'/>";
    } else {
        echo "<input type='hidden' name='imgu[$row[generic_field_name]][imageName]' class='$row[generic_field_name]' />";
    }


    echo "<input type='hidden' name='imgu[$row[generic_field_name]][uploadcare]' id='$row[generic_field_name]' />";

    echo "<div class='img-extra'></div>";



    echo " </div>";
}

/*



 */

/*
 * 
 * CHECKBOX FUNCTION 
 */

function checkbox($row, $urow = 'false') {

    $readonly = '';
    $required = '';

    if ($row['editable'] == 'false')
        $readonly = 'readonly';


    if (!empty($row['required']))
        $required = 'required';

    echo "<input type='hidden' name='$row[generic_field_name]' value='0' >";

    if ($urow != 'false') {
        if ($urow[$row['generic_field_name']] == '1')
            echo "<input type='checkbox' name='$row[generic_field_name]' value='1' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control' checked='checked'>";
        else
            echo "<input type='checkbox' name='$row[generic_field_name]' value='1' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'>";
    }else {

        echo "<input type='checkbox' name='$row[generic_field_name]' value='1' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'>";
    }
}

////////////////DROPDOWN function///


function dropdown($row, $urow = 'false') {

    $con = connect();

    $rs = $con->query("SELECT * FROM  data_dictionary where table_alias = '$row[dropdown_alias]'");

    $dd = $rs->fetch_assoc();

//print_r($dd);die;
    $list_fields = explode(',', $dd['list_fields']);

//print_r($list_fields);die;

    $keyfield = '';

    if ($row['editable'] == 'false')
        $readonly = 'disabled="disabled"';


    if (!empty($row['required']))
        $required = 'required';

    if (!empty($row[format_length]))
        $length = "style='width:$row[format_length]em'";

    $itemDis = array();
    foreach ($list_fields as $val) {


        $newVal = explode('*', $val);

        if ($newVal[0] == '' && !empty($newVal[1])) {

            $tilde = explode('~', $newVal[1]);

//print_r($tilde);die;

            if ($tilde[0] == '' && !empty($tilde[1])) {

                $inviKey = $tilde[1];
            } else {

                $visiKey = $tilde[0];
            }
        } else
            $itemDis[] = $val;
    }/// foreach ends here

    if (isset($inviKey)) {
        $itemDis[] = $inviKey;

        $key = $inviKey;
    } else {
        $itemDis[] = $visiKey;

        $key = $visiKey;
    }
//print_r($itemDis);die;
    $list_fields = implode(',', $itemDis);


    $list_sort = explode('-', $dd['list_sort']);


    if ($list_sort[0] == '' && !empty($list_sort[1])) {

        $order = "order by " . $list_sort[1] . " DESC";
    } else {

        $order = "order by " . $list_sort[0] . " ASC";
    }
//exit("SELECT $list_fields FROM  $dd[database_table_name] $order");
    $qry = $con->query("SELECT $list_fields FROM  $dd[database_table_name] $order");

    echo "<select name='$row[generic_field_name]'  class='form-control' $readonly $length>";

    while ($res = $qry->fetch_assoc()) {

        $res2 = $res;
        unset($res2[$inviKey]);


        $data = implode('&nbsp&nbsp', $res2);

        if ($urow[$row[generic_field_name]] == $res[$key]) {

            echo "<option value='$res[$key]' selected >$data</option>";
        } else
            echo "<option value='$res[$key]'>$data</option>";
    }
    echo "</select>";
}

/////////////////////////////
///////////// Selection//////////////
function Select_Data_FieldDictionary_Record($alias) {

    $con = connect();

    $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$alias' and table_type='users' order by field_dictionary.display_field_order");

    $row = $rs->fetch_assoc();

    $_SESSION['select_table']['database_table_name'] = $row['database_table_name'];

    $_SESSION['select_table']['parent_key'] = $row['parent_key'];


    $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_type='users' and data_dictionary.table_alias = '$alias'  order by field_dictionary.display_field_order");





    while ($row = $rs->fetch_assoc()) {


        formating_Select($row);
    }//// end of while loop
}

function formating_Select($row) {


    $field = $row[generic_field_name];


    $readonly = '';
    $required = '';



    if ($row['editable'] == 'false')
        $readonly = 'readonly';


    if (!empty($row['required']))
        $required = 'required';

    if ($row['format_type'] != 'username') {

        if (!empty($row['format_type'])) {
            echo "<input type='$row[format_type]' name='$field' value='' $readonly $required title='$row[help_message]' class='form-control' placeholder='Your $row[field_label_name]'>";
        } else {

            echo "<input type='text' name='$field' value='' $readonly $required title='$row[help_message]' class='form-control' placeholder='Your $row[field_label_name]'>";
        }
    } else {

        $_SESSION['select_table']['username'] = $field;
    }
}

function get_single_record($db_name, $pkey, $search) {


    $_SESSION['update_table']['search'] = $search;

    $con = connect();

//exit("select * from $db_name where $pkey='$search'");

    $user = $con->query("select * from $db_name where $pkey='$search'");


    return $user->fetch_assoc();
}

function get_multi_record($db_name, $pkey, $search, $listFilter = 'false', $singleSort = 'false', $listCheck = 'false') {


    $_SESSION['update_table']['search'] = $search;

    $con = connect();

// exit(" db=$db_name, parent_key = $pkey, search_id = $search, list_filter = $listFilter, single_sort = $singleSort, listCheck = $listCheck");


    if ($listFilter != 'false') {

        $parent_key = explode(";", $listFilter);


        if (!empty($parent_key[1])) {

            $listCond = $parent_key[1];
        }

        if (!empty($parent_key[0])) {
            $i = 0;


            $parent_key = explode(",", $parent_key[0]);

            foreach ($parent_key as $val) {

                $keyField = explode("=", $val);

                $keyVal[$i] = array(trim($keyField[0]) => trim($keyField[1]));

                $i++;
            }
        }

        foreach ($keyVal as $val) {

            if (!empty($val['projects'])) {

                $pid = $val['projects'];
            }

            if (!empty($val['users'])) {

                $uid = $val['users'];
            }
        }

        if (!empty($pid)) {

            if (!empty($search))
                $clause = $pid . "=" . $search;
        }

        if (!empty($uid)) {

            if (!empty($clause)) {

                $clause = $clause . " and " . $uid . "=" . $_SESSION['uid'];
            } else {

                $clause = $uid . "=" . $_SESSION['uid'];
            }
        }


        if (!empty($listCond)) {


            if (!empty($clause)) {

                $clause = $clause . " and " . $listCond;
            } else {

                $clause = $listCond;
            }
        }

        if (!empty($clause)) {
            $clause = "where " . $clause;
        }
    }

//exit($singleSort);
    if ($listCheck == 'yes') {


        if (empty($_GET['orderFlag'])) {

            $tblOrder = " ASC";
        } else if ($_GET['orderFlag'] == 'down') {

            $tblOrder = " ASC";
        } else if ($_GET['orderFlag'] == 'up') {

            $tblOrder = " DESC";
        }


        if (!empty($clause)) {
            if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                $user = $con->query("select * from $db_name $clause order by $_GET[sort] $tblOrder");
            } else if ($singleSort != 'false') {
//exit("select * from $db_name $clause order by $singleSort");
                $user = $con->query("select * from $db_name $clause order by $singleSort");
            } else {
//exit("select * from $db_name $clause");
                $user = $con->query("select * from $db_name $clause");
            }
        } else {


            if (isset($_GET['sort']) && !empty($_GET['sort'])) {
                $user = $con->query("select * from $db_name order by $_GET[sort] $tblOrder");
            } else if ($singleSort != 'false') {

                $user = $con->query("select * from $db_name order by $singleSort");
            } else {
                $user = $con->query("select * from $db_name");
            }
        }
    } else {
        $user = $con->query("select * from $db_name $clause");
    }

//exit("select * from $db_name where $pkey=$search order by $singleSort");

    return $user;
}

///////////////////////
/*
 * 
 * Navigations fucntions starts here
 */

function Navigation($page, $menu_location = 'header') {

    $con = connect();

    $rs = $con->query("SELECT * FROM navigation where display_page='$page' and item_number=0 and menu_location='$menu_location' ");
    $row = $rs->fetch_assoc();


    /*
     *  //echo "<br><br><br><br><br><br>";
      // print_r($row);
     * Checking whether user have access to current page or not
     */

    if ($row['loginRequired'] == 'true' && !isset($_SESSION['uid'])) {

        $_SESSION['callBackPage'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


        FlashMessage::add('Login required to view the current page!');

        echo "<META http-equiv='refresh' content='0;URL=" . BASE_URL_SYSTEM . "login.php'>";
        exit();
    }

    $item_style = $row['item_style'];

    $rs = $con->query("SELECT * FROM navigation where display_page='$page' and item_number != 0 and menu_location='$menu_location' order by item_number");

    $arr = array();
    $i = 0;
    while ($row = $rs->fetch_assoc()) {

        $arr[$i] = $row;

        $i++;
    }

//////html version of navigation will be displayed....
    ?>

    <!-- Navigation starts here -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">
                        <?php echo TOGGLE_NAVIGATION ?>
                    </span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand logo" href="<?php echo BASE_URL ?>">
                    <?php echo BRAND_LOGO ?>
                </a> </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right <?= $item_style; ?>">

                    <?php
                    if (isUserLoggedin()) {
                        for ($i = 0; count($arr) > $i; $i++) {



                            $label = $arr[$i]['item_label'];

                            if (strpos($arr[$i]['item_target'], 'http://') !== false) {

                                $target = $arr[$i]['item_target'];
                            } else {

                                $target = BASE_URL . $arr[$i]['item_target'] . "?display=" . $arr[$i]['target_display_page'] . "&layout=" . $arr[$i]['page_layout_style'] . "&style=" . $arr[$i]['item_style'];
                            }
                            $curr_item_number = explode('.', $arr[$i]['item_number']);

                            $next_item_number = explode('.', $arr[$i + 1]['item_number']);


                            if ($arr[$i]['enabled'] > 0) {
                                $enabled = 'enabled';
                            } else
                                $enabled = 'disabled';

                            if ($arr[$i]['item_visibility'] <= 0)
                                $visibility = 'hidden';
                            else
                                $visibility = 'show';


                            if ($arr[$i]['admin_level'] <= 0) {
                                $admin_enabled = 'enabled';
                            } else
                                $admin_enabled = 'disabled';


                            $title = $arr[$i]['item_help'];

                            //$sub_item_style = $arr[$i]['item_style'];
//         if ( $user_privilege < $arr[$i]['item_privilege'])
//            $enabled = 'disable';








                            if (($curr_item_number[0] == $next_item_number[0]) && ( $curr_item_number[1] == 0 && $next_item_number[1] == 1 )) {
/// Menu name have sub menu
                                echo "<li class='dropdown $enabled $visibility $admin_enabled $sub_item_style' id='newone'> <a href='#' class='dropdown-toggle' data-toggle='dropdown-menu' title='$title'> ";
                                if ($label == 'CURRENTUSERNAME') {
                                    echo $_SESSION[uname];
                                } else
                                    echo $label;
                                echo "<span class='caret'></span></a>
                  
                   <ul class='dropdown-menu' role='menu'>
                    ";
                            } else
                            if (isset($curr_item_number[0]) && isset($curr_item_number[1]) && ( $curr_item_number[1] > 0 ) && ( $next_item_number[1] > 0 )) {
/// Child names
                                echo " <li class='$enabled $visibility $admin_enabled $sub_item_style'>
                    <a href='$target' title='$title'>$label</a>
                  </li>";
                            } else
                            if (isset($curr_item_number[0]) && isset($curr_item_number[1]) && ( $curr_item_number[1] > 0 ) && !($next_item_number[1] > 0 )) {
/// last child
                                echo "<li class='$enabled $visibility $admin_enabled $sub_item_style'>
                    <a href='$target' title='$title'>$label</a>
                  </li>
                </ul>
              </li>";
                            } else
                            if (($curr_item_number[0] != $next_item_number[0]) && ( $curr_item_number[1] == 0 && $next_item_number[1] != 1 )) {
/// Menu name which have no childs
                                echo "<li class='$enabled $visibility $admin_enabled $sub_item_style'> <a href='$target' title='$title'>
               $label
                 </a></li>";
                            }
                        }/////////// for loop ends here/// 
                    } else {
                        ?>



                        <li><a href="<?php echo BASE_URL_SYSTEM ?>login.php" class="top-btns btn-primary login"><i class="fa fa-sign-in"></i>
                                <?php echo LOGIN_MENU ?>
                            </a></li>
                        <li><a href="<?php echo BASE_URL_SYSTEM ?>register.php" class="top-btns btn-primary"><i class="fa fa-edit"></i>
                                <?php echo SIGNUP_MENU ?>
                            </a></li>


                    <?php } ///////else if ends here                                                                  ?>

                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>


    <?php
}

////////main navigation function ends here/// 



/*
 * *****************
 * ******************LIST display FUNCTION STARTS FROM HERE********************************* 
 * **********************************
 */

function list_display($qry, $tab_num = 'false', $tab_anchor = 'false') {

    $con = connect();
//how list will know on basis of which key to show the record///
//$_SESSION['update_table']['parent_key'] = 'uid';

    $rs = $con->query($qry);
    $row = $rs->fetch_assoc();
//print_r($row);die;
//    if ($row['list_filter'] != 'NULL') {
//
//        //$laterChange = $_SESSION['uid'];
//
//        $laterChange = $row['list_filter'];
//    }

    $listCheck = 'yes';

    $list_sort = explode(',', $row['list_sort']);
//echo count($list_sort);die;

    if (count($list_sort) == 1 && !empty($row['list_sort'])) {

        $list = get_multi_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id'], $row['list_filter'], $list_sort[0], $listCheck);
    } else {
        $list = get_multi_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id'], $row['list_filter'], $listSort = 'false', $listCheck);
    }


    $listView = trim($row['list_views']);

    /*
     * 
     * This will be hide, old


      $listView = explode(',', $row['list_views']);


      foreach ($listView as $v) {

      $def = explode('*', $v);

      if (isset($def['1'])) {

      $defView = $def['1'];
      break;
      }
      }


      ///setting default View using jquery///////
      echo "<script>jQuery(document).ready(function(){";

      if ($defView == 'listView') {

      echo "jQuery('.project-details-wrapper').removeClass().addClass('project-details-wrapper listView');
      jQuery('.project-details-wrapper > div:first-child').removeClass().addClass('col-12 col-sm-12 col-lg-12');
      jQuery('.edit').addClass('invisible');
      jQuery('.project-detail').addClass('project-detail-list');
      jQuery('#checklist-div').show();
      jQuery('.list-del').show();
      jQuery('.list-copy').show();";
      } else if ($defView == 'boxView') {

      echo "jQuery('.project-details-wrapper').removeClass().addClass('project-details-wrapper boxView');
      jQuery('.project-details-wrapper > div:first-child').removeClass().addClass('col-6 col-sm-6 col-lg-3');
      jQuery('.edit').removeClass('invisible');
      jQuery('.project-detail').removeClass('project-detail-list');
      jQuery('.list-checkbox').remove();
      jQuery('#checklist-div').hide();
      jQuery('.list-del').hide();
      jQuery('.list-copy').hide();";
      } else if ($defView == 'thumbView') {

      echo "jQuery('.project-details-wrapper').removeClass().addClass('project-details-wrapper thumbView');
      jQuery('.project-details-wrapper > div:first-child').removeClass().addClass('col-12 col-sm-12 col-lg-12');
      jQuery('.edit').addClass('invisible');
      jQuery('.project-detail').addClass('project-detail-list');
      jQuery('.list-checkbox').remove();
      jQuery('#checklist-div').hide();
      jQuery('.list-del').hide();
      jQuery('.list-copy').hide();";
      }

      echo "});  </script>";

      old list view ends here */
    /*
     * @function listExtraOptions
     * 
     * Fetching list_extra_options
     */

    $ret_array = listExtraOptions($row['list_extra_options']);

    //echo "<pre>";print_r($ret_array);die;



    global $popup_menu;

    $popup_menu = array("popupmenu" => $ret_array['popupmenu'], "popup_delete" => $ret_array['popup_delete'], "popup_copy" => $ret_array['popup_copy']);


    if (count($list_sort) > 1 && $listView == 'boxView') {
        ?>

        <div class="col-6 col-sm-6 col-lg-6 sortby">
            <h3>Sort by </h3>

            <span>
                <div class="btn-group select2">

                    <button type="button" class="btn btn-danger main-select2" id="sort_popular_users_value">
                        ---Select----</button>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> 

                        <span class="sr-only">Toggle navigation</span> </button>
                    <ul class="dropdown-menu" role="menu" id="sort_popular_users">

                        <?php
                        $tbl = $row['table_alias'];

                        if (!empty($_GET['sort']) && empty($_GET['orderFlag'])) {

                            $orderFlag = 'down';
                        } else if (!empty($_GET['sort']) && $_GET['orderFlag'] == 'down') {

                            $orderFlag = 'up';
                        } else if (!empty($_GET['sort']) && $_GET['orderFlag'] == 'up') {

                            $orderFlag = 'down';
                        }

                        //$orderFlag = $_GET['orderFlag'];

                        $order = "<span class='glyphicon glyphicon-chevron-up'></span>";


                        foreach ($list_sort as $val) {

                            if ($val != $_GET['sort']) {
                                $order = "<span class='glyphicon'></span>";
                            } else {

                                switch ($orderFlag) {
                                    case 'up':
                                        $order = "<span class='glyphicon glyphicon-chevron-up'></span>";
                                        break;
                                    case 'down':
                                        $order = "<span class='glyphicon glyphicon-chevron-down'></span>";
                                        break;
                                    default:
                                        $order = "<span class='glyphicon'></span>";
                                }
                            }

                            $q = $con->query("select field_label_name from field_dictionary where generic_field_name='$val' and table_alias='$tbl'");
                            $fdField = $q->fetch_assoc();

                            echo "  <li id='sort-li' data-value='$val'><a>
                           
$fdField[field_label_name]$order
</a></li>";
                        }
                        ?>


                    </ul>


                </div>
            </span></div>


        <?php
    } ////list sort if ends here


    /*     * ******* setting DisplayView icons **** *//////
    /*
      $listView = str_replace('*', '', $listView);

      //print_r($listView);die;

      if (count($listView) != 1) {
      echo "<div class='col-6 col-sm-6 col-lg-6 grid-type'>";

      foreach ($listView as $v) {

      $v = trim($v);

      if ($v == "listView") {

      echo "<span id='listView' class='glyphicon glyphicon-align-right'></span>";
      } else if ($v == "boxView") {

      echo "<span id='boxView' class='glyphicon glyphicon-th-large'></span>";
      } else if ($v == "thumbView") {

      echo "<span id='thumbView' class='glyphicon glyphicon-th-list'></span>";
      }
      }


      echo " <span></span></div>";
      }
     */
    $list_select = trim($row['list_select']);

    $list_style = $row['list_style'];

    $parent_key = trim($row['parent_key']);

    $table_type = trim($row['table_type']);

    $table_name = trim($row['database_table_name']);

    $list_fields = trim($row['list_fields']);

    $dict_id = $row['dict_id'];


    /*
     * getting image field name from FD 
     */

    $fdRS = $con->query("SELECT generic_field_name FROM `field_dictionary` WHERE table_alias='$row[table_alias]' and format_type like '%image%' limit 1");

    $imageField = $fdRS->fetch_assoc();




    /**     * *** checking list_fields **** */
    if (!empty($list_fields)) {



        if (preg_match('/^\d+\.?\d*$/', $row['list_fields'])) {

            if ($tab_num == 'false') {
                $tbQry = $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order LIMIT " . $row['list_fields'];
            } else {
                $tbQry = $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$tab_num' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order LIMIT " . $row['list_fields'];
            }
        } else {

            $fields = explode(",", $row[list_fields]);

            $fieldsFinal = '';
            foreach ($fields as $f) {

                if (empty($fieldsFinal))
                    $fieldsFinal = "'" . $f . "'";
                else
                    $fieldsFinal = "'" . $f . "' , " . $fieldsFinal;
            }
            if ($tab_num == 'false') {
                $tbQry = $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and  tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 and field_dictionary.generic_field_name IN(  $fieldsFinal ) order by field_dictionary.display_field_order";
            } else {
                $tbQry = $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and  tab_num='$tab_num' and field_dictionary.visibility >= 1 and field_dictionary.generic_field_name IN(  $fieldsFinal ) order by field_dictionary.display_field_order";
            }
        }
    }
    ?>  

    <br><br><br>



    <div class="row" id="popular_users" >
        <form name="list-form" id="list-form" action="ajax-actions.php" method="post">
            <div id='checklist-div'>
                <?php
                if ($ret_array['checklist_array'] == 'true' && $listView != 'boxView') {

                    echo "<input type='hidden' name='checkHidden' id='checkHidden'>"
                    . " <input type='checkbox' id='selectAll'> &nbsp;<strong>Select All </strong> 
                    &nbsp;&nbsp;";

                    /// setting for  delete button
                    if (isset($ret_array['del_array']) && !empty($ret_array['del_array'])) {

                        echo "<button type='submit' class='btn action-delete " . $ret_array['del_array']['style'] . "' name='delete' >" . $ret_array['del_array']['label'] . "</button>";
                    }

                    //// setting for  copy button
                    if (isset($ret_array['copy_array']) && !empty($ret_array['copy_array'])) {

                        echo "<button type='submit' class='btn action-copy " . $ret_array['copy_array']['style'] . "' name='copy' >" . $ret_array['copy_array']['label'] . "</button>";
                    }
                    echo "";
                }/// checklist if ends here  
                /// ADD BUTTON

                if (isset($ret_array['add_array']) && !empty($ret_array['add_array'])) {

                    echo "<button type='button' class='btn action-add " . $ret_array['add_array']['style'] . "' name='add' >" . $ret_array['add_array']['label'] . "</button>";
                }

                echo "</div><br>"; /// select checkbox div ends here



                if ($list->num_rows == 0) {

                    $list_select_sep = explode(';', $list_select);

                    foreach ($list_select_sep as $listArray) {

                        $list_select_arr[] = explode(",", $listArray);
                    }


                    //print_r($list_select_arr);die;  


                    $nav = $con->query("SELECT * FROM navigation where target_display_page='$_GET[display]'");
                    $navList = $nav->fetch_assoc();

                    /// Extracting action ,when user click on edit button or on list
                    if (isset($list_select_arr[0]) && !empty($list_select_arr[0])) {

                        if (count($list_select_arr[0]) == 2) {
                            $target_url = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&ta=" . $list_select_arr[0][0] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true&table_type=" . $$table_type;

                            /// add button url
                            $_SESSION['add_url_list'] = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&addFlag=true&checkFlag=true&ta=" . $list_select_arr[0][0] . "&table_type=" . $$table_type;
                        } else {
                            $target_url = BASE_URL . "system/profile.php?display=" . $list_select_arr[0][2] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&ta=" . $list_select_arr[0][0] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true&table_type=" . $$table_type;

                            /// add button url
                            $_SESSION['add_url_list'] = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&addFlag=true&checkFlag=true&ta=" . $list_select_arr[0][0] . "&table_type=" . $table_type;
                        }
                    }

                    $_SESSION['return_url'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                }//// if record is zero... ends here

                if ($listView != 'boxView') {



                    echo "<table id='example' class='display' cellspacing='0' width='100%'>
        <thead>
            <tr class='tr-heading'>
                <th class='tbl-action'></th>";

                    $tbRs = $con->query($tbQry);
                    ///fetching table headings
                    while ($tbRow = $tbRs->fetch_assoc()) {

                        echo "<th>$tbRow[field_label_name]</th>";
                    }
                    echo "</tr></thead><tbody>";
                    
                }else if( isset($ret_array['pagination']) && !empty($ret_array['pagination']) ){
                    
                    //// BoxView Pagination code inserted here
                    
                    echo "  <!-- the input fields that will hold the variables we will use -->
	<input type='hidden' id='current_page' />
	<input type='hidden' id='show_per_page' />
	
	<!-- Content div. The child elements will be used for paginating(they don't have to be all the same,
		you can use divs, paragraphs, spans, or whatever you like mixed together). '-->
        <div id='content'>";
                }

                while ($listRecord = $list->fetch_assoc()) {


                    $rs = $con->query($qry);

                    if ($listView == 'boxView') {
                        ?>
                        <div class="project-details-wrapper boxView">
                            <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                                <div class="project-detail <?php echo (!empty($list_style) ? $list_style : '') ?>"> 

                                    <?php
                                }///boxview ends here

                                if (!empty($list_select) || $table_type == 'child') {


                                    if (strpos($list_select, '()')) {

                                        exit('function calls');
                                    } else
                                    if (strpos($list_select, '.php')) {

                                        exit('php file has been called');
                                    } else {

                                        $list_select_sep = explode(';', $list_select);

                                        foreach ($list_select_sep as $listArray) {

                                            $list_select_arr[] = explode(",", $listArray);
                                        }


                                        //print_r($list_select_arr);

                                        $nav = $con->query("SELECT * FROM navigation where target_display_page='$_GET[display]'");
                                        $navList = $nav->fetch_assoc();

                                        /// Extracting action ,when user click on edit button or on list
                                        if (isset($list_select_arr[0]) && !empty($list_select_arr[0])) {

                                            if (count($list_select_arr[0]) == 2) {
                                                $target_url = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&ta=" . $list_select_arr[0][0] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true&table_type=" . $table_type;

                                                /// add button url
                                                $_SESSION['add_url_list'] = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&addFlag=true&checkFlag=true&ta=" . $list_select_arr[0][0] . "&table_type=" . $table_type;
                                            } else {
                                                $target_url = BASE_URL . "system/profile.php?display=" . $list_select_arr[0][2] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&ta=" . $list_select_arr[0][0] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true&table_type=" . $table_type;

                                                /// add button url
                                                $_SESSION['add_url_list'] = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&addFlag=true&checkFlag=true&ta=" . $list_select_arr[0][0] . "&table_type=" . $table_type;
                                            }
                                        }

                                        /// Extracting action, when user click on boxView Image of list
                                        if (isset($list_select_arr[1]) && !empty($list_select_arr[1])) {


                                            if (count($list_select_arr[1]) == 2) {
                                                $target_url2 = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[1][0] . "&tabNum=" . $list_select_arr[1][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true";
                                            } else {

                                                $target_url2 = BASE_URL . "system/profile.php?display=" . $list_select_arr[1][2] . "&tab=" . $list_select_arr[1][0] . "&tabNum=" . $list_select_arr[1][1] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true";
                                            }
                                        }

                                        if ($listView != 'boxView') {

                                            echo "<tr id='$target_url&edit=true#$tab_anchor'><td><!--<a href='$target_url&edit=true#$tab_anchor' title='Edit' class='btn btn-default' style='color: #E6B800'>
                        <span class='glyphicon glyphicon-edit'></span> 
                    </a>-->";


                                            $checkbox_id = $listRecord[$_SESSION['update_table']['parent_key']];


                                            
                                               /*
                                             * displaying checkboxes
                                             * checking in database if checklest is there
                                             */

                          
                                            if ($ret_array['checklist_array'] == 'true') {
                                           
                                                echo "<span class='span-checkbox'><input type='checkbox'  name='list[]'  value='$checkbox_id' class='list-checkbox' style='marginright:6px;'/></span>";

                                                echo "<input type='hidden' name='dict_id[]' value='$dict_id' >";
                                            }
                                            
                                            
                                            
                                            /*
                                             * Putting Delete icon on left/right side of lists
                                             */


                                            if (isset($ret_array['single_delete_array']) && !empty($ret_array['single_delete_array'])) {

                                                $sing_del_style = $ret_array['single_delete_array']['style'];

                                                echo " <a  class='glyphicon glyphicon-remove list-del $sing_del_style' title='Delete' style='color:#FF3300' id='$checkbox_id' name='$dict_id'>
                        
                    </a>";
                                            }

                                            /*
                                             * Putting Copy icon on left/Right side of lists
                                             */


                                            if (isset($ret_array['single_copy_array']) && !empty($ret_array['single_copy_array'])) {

                                                $sing_del_style = $ret_array['single_copy_array']['style'];

                                                echo " <a class='btn btn-default' title='Copy' ><span class='list-copy $sing_del_style' title='Delete' id='$checkbox_id' name='$row[dict_id]'>
                        <img src='" . BASE_URL . "'application/images/copy.ico' width='15' height='15'></span>
                    </a>";
                                            }


                                            ////data td ends here

                                            echo "</td>";
                                        }
//                                        if ($listView != 'boxView') {
//
//                                           
//                                        }

                                        if ($listView == 'boxView') {

                                            /*
                                             * @while loop
                                             * get FD info and put data into @lisdata array 
                                             */

                                            $listData = array();


                                            while ($row = $rs->fetch_assoc()) {

                                                $listData[] = strip_tags($listRecord[$row[generic_field_name]]);
                                            }

                                            /*
                                             * setting up an array to pass to listViews function
                                             */


                                            //$listPara = array("listData" =>$listData, "table_type" =>$table_type, "target_url" );

                                            /*
                                             * @listViews function 
                                             * 
                                             * give LIST UI and data inside lists
                                             */


                                            listViews($listData, $table_type, $target_url, $imageField, $listRecord, $parent_key, $target_url2, $tab_anchor); ///boxview ends here
                                        } else {
                                            /////table view starts here                                          
                                            //fetching data from corresponding table
                                            while ($row = $rs->fetch_assoc()) {

                                                $fieldValue = $listRecord[$row[generic_field_name]];

                                                echo "<td>$fieldValue</td>";
                                            }

                                            $_SESSION['return_url'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        }
                                    }///end of else if
                                }/// end of mainIF

                                if ($listView == 'boxView') {
                                    echo "</div></div></div>";
                                } else {

                                    echo "</tr>";
                                }
                            }//// end of while loop


                            if ($listView != 'boxView') {

                                echo "</tbody></table> ";
                                
                            }else if( isset($ret_array['pagination']) && !empty($ret_array['pagination']) ){
                    
                    //// BoxView Pagination code inserted here
                            
                                echo " </div>
        
        
        <br>
	<!-- An empty div which will be populated using jQuery -->
	<div id='page_navigation'></div>";
                                
                                echo boxViewPagination($ret_array['pagination']);
                                
                            }
                            
                            echo "</form></div>";
                        }

                        /*
                         * @listViews function 
                         * 
                         * give LIST UI and data inside lists
                         */

                        function listViews($listData, $table_type, $target_url, $imageField, $listRecord, $parent_key, $target_url2, $tab_anchor) {
                            /*
                             * 
                             * displaying of image in list
                             */


                            $tbl_img = $listRecord[$imageField['generic_field_name']];


                            $filename = $GLOBALS['APP_DIR'] . "users_uploads/" . $tbl_img;


                            echo "<a href='$target_url2'>";
                            if (!empty($tbl_img) && file_exists($filename)) {

                                echo "<span class='profile-image'>
        <img src='" . BASE_URL . "users_uploads/$tbl_img' alt='' class='img-responsive'></span>";
                            } else {

                                echo "<span class='profile-image'>
        <img src='" . BASE_URL . "users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg' alt='' class='img-responsive'></span>";
                            }


                            echo "</a>";
                            $_SESSION['return_url'] = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


                            $listData = implode(" ", $listData);


                            /*
                             * 
                             * adding span to give lift margin
                             */

                            echo "<span  class='list-data'>";

                            /* if ($table_type == 'child') {


                              echo "<a href='$_SESSION[return_url]&edit=true&search_id=$listRecord[$parent_key]'>";
                              } else { */
                            echo "<a href='$target_url&edit=true#$tab_anchor'>";
                            //}

                            echo "<span class='list-span'>" . substr($listData, 0, 110) . "</span></a>";

                            /*
                             * displaying Edit button
                             */

                            /* if ($table_type == 'child') {

                              echo "<a href='$_SESSION[return_url]&edit=true&search_id=$listRecord[$parent_key]' class='btn btn-primary edit'>Edit</a>";
                              } else { */

                            echo "<a href='$target_url&edit=true#$tab_anchor' class='btn btn-primary edit' >Edit</a>";
                            //}

                            echo "</span>"; ///left margin span ends here
                        }

///end of listViews function



                        function listExtraOptions($list_extra_options) {


                            $list_extra_options = explode(';', $list_extra_options);
//print_r($list_extra_options);die;

                            foreach ($list_extra_options as $opt) {

                                $action[] = explode(',', $opt);
                            }

                           // print_r($action);die;

                            foreach ($action as $act) {

                                switch (trim($act[0])) {
                                    case 'delete':
                                        $del_array = list_delete(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'copy':
                                        $copy_array = list_copy(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'add':
                                        $add_array = list_add(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'checklist':
                                        $checklist_array = 'true';
                                        break;
                                    case 'popupmenu':
                                        $popupmenu = 'true';
                                        break;
                                    case 'single_delete':
                                        $single_delete_array = single_delete(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'single_copy':
                                        $single_copy_array = single_copy(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'popup_delete':
                                        $popup_delete_array = popup_delete(trim($act[1]), trim($act[2]));
                                        break;
                                    case 'popup_copy':
                                        $popup_copy_array = popup_copy(trim($act[1]), trim($act[2]));
                                        break;
                                    
                                     case 'pagination':
                                        $pagination_array = trim($act[1]);
                                        break;

                                    default:
                                }
                            }

                            // print_r($single_delete_array);die;
                            return array("del_array" => $del_array, "copy_array" => $copy_array, "add_array" => $add_array, "single_delete_array" => $single_delete_array, "single_copy_array" => $single_copy_array, "checklist_array" => $checklist_array, "popupmenu" => $popupmenu, "popup_delete" => $popup_delete_array, "popup_copy" => $popup_copy_array,"pagination" => $pagination_array);
                        }

//// end of list_extra_option function

                        /*
                         * these functions are for future enhancements
                         */
                        function list_delete($label, $look) {

                            return array("label" => $label, "style" => $look);
                        }

                        function list_copy($label, $look) {

                            return array("label" => $label, "style" => $look);
                        }

                        function list_add($label, $look) {

                            return array("label" => $label, "style" => $look);
                        }

                        function single_delete($loc, $look) {

                            return array("loc" => $loc, "style" => $look);
                        }

                        function single_copy($loc, $look) {

                            return array("loc" => $loc, "style" => $look);
                        }

                        function popup_delete($label, $look) {

                            return array("label" => $label, "style" => $look);
                        }

                        function popup_copy($label, $look) {

                            return array("label" => $label, "style" => $look);
                        }

                        /*
                         *  @sidebar function
                         */

                        function sidebar($sidebar, $both_sidebar, $display_page) {

                            $con = connect();


                            if ($sidebar == 'right') {


                                if ($both_sidebar == 'both') {
                                    echo "  <div class='col-3 col-sm-3 col-lg-2'>";
                                } else {
                                    echo " <div class='col-6 col-sm-6 col-lg-3'>";
                                }

                                $rs = $con->query("SELECT * FROM data_dictionary where display_page='$display_page' and tab_num LIKE 'R%' order by tab_num");

                                while ($row = $rs->fetch_assoc()) {


                                    Get_Data_FieldDictionary_Record($row['table_alias'], $display_page, $tab_status = 'bars', $row['tab_num']);
                                }

                                echo "</div>";
                            } else if ($sidebar == 'left') {


                                if ($both_sidebar) {
                                    echo "  <div class='col-3 col-sm-3 col-lg-2'>";
                                } else {
                                    echo " <div class='col-6 col-sm-6 col-lg-3'>";
                                }

                                $rs = $con->query("SELECT * FROM data_dictionary where display_page='$display_page' and tab_num LIKE 'L%' order by tab_num");

                                while ($row = $rs->fetch_assoc()) {

                                    Get_Data_FieldDictionary_Record($row['table_alias'], $display_page, $tab_status = 'bars', $row['tab_num']);
                                }

                                echo "</div>";
                            }
                        }

///end of sidebar function

                        
                        
                        
                        /*****
                         * **************BoxView Pagination function which call jquery function code
                         * Goes here
                         * ******************************
                         * ************************************
                         * 
                         */
                        
                        function boxViewPagination($pagination){
                            
                            ?>
                                    
                          
                                    
                                    
                                    <script type="text/javascript">
$(document).ready(function(){
	
	//how much items per page to show
	var show_per_page = <?= $pagination;?>; 
	//getting the amount of elements inside content div
	var number_of_items = $('#content').children('.boxView').size();
	//calculate the number of pages we are going to have
	var number_of_pages = Math.ceil(number_of_items/show_per_page);
	
	//set the value of our hidden input fields
	$('#current_page').val(0);
	$('#show_per_page').val(show_per_page);
	
	//now when we got all we need for the navigation let's make it '
	
	/* 
	what are we going to have in the navigation?
		- link to previous page
		- links to specific pages
		- link to next page
	*/
	var navigation_html = '<a class="previous_link" href="javascript:previous();">Prev</a>';
	var current_link = 0;
	while(number_of_pages > current_link){
		navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link +')" longdesc="' + current_link +'">'+ (current_link + 1) +'</a>';
		current_link++;
	}
	navigation_html += '<a class="next_link" href="javascript:next();">Next</a>';
	
	$('#page_navigation').html(navigation_html);
	
	//add active_page class to the first page link
	$('#page_navigation .page_link:first').addClass('active_page');
	
	//hide all the elements inside content div
	$('#content').children('.boxView').css('display', 'none');
	
	//and show the first n (show_per_page) elements
	$('#content').children('.boxView').slice(0, show_per_page).css('display', 'block');
	
});

function previous(){
	
	new_page = parseInt($('#current_page').val()) - 1;
	//if there is an item before the current active link run the function
	if($('.active_page').prev('.page_link').length==true){
		go_to_page(new_page);
	}
	
}

function next(){
	new_page = parseInt($('#current_page').val()) + 1;
	//if there is an item after the current active link run the function
	if($('.active_page').next('.page_link').length==true){
		go_to_page(new_page);
	}
	
}
function go_to_page(page_num){
	//get the number of items shown per page
	var show_per_page = parseInt($('#show_per_page').val());
	
	//get the element number where to start the slice from
	start_from = page_num * show_per_page;
	
	//get the element number where to end the slice
	end_on = start_from + show_per_page;
	
	//hide all children elements of content div, get specific items and show them
	$('#content').children('.boxView').css('display', 'none').slice(start_from, end_on).css('display', 'block');
	
	/*get the page link that has longdesc attribute of the current page and add active_page class to it
	and remove that class from previously active page link*/
	$('.page_link[longdesc=' + page_num +']').addClass('active_page').siblings('.active_page').removeClass('active_page');
	
	//update the current page input field
	$('#current_page').val(page_num);
}
  
</script>
                                    
                                    
                                    
                                    
                         <?php   
                        }//////boxView functions end here
                        
                        ?>