<?php
/*
 * 
 *  This is Intake array which take DD AND FD data and put in master array
 * 
 */

session_start();

//$_SESSION['listCount'] = 0;



include_once("../application/database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND $_GET['action'] == 'update') {


    $check = update($_SESSION['update_table']['database_table_name'], $_POST, array($_SESSION['update_table']['parent_key'] => $_SESSION['search_id']));


    if ($check == 1) {


        if ($_GET['checkFlag'] == 'true') {

//$url = explode("&" ,$_SESSION[return_url]);
//print_r($url);die;
            echo "<script>window.location = '$_SESSION[return_url]';</script>";
        } else
            echo "<script>window.location = '?display=$_SESSION[display]&tab=$_SESSION[tab]&tabNum=$_GET[tabNum]';</script>";
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['submit'] == 'login') {

    $tbl = $_SESSION['select_table']['database_table_name'];

    $con = connect();

//exit("SELECT * FROM $tbl where email = '$_POST[email]' and password = '$_POST[password]' ");

    $rs = $con->query("SELECT * FROM $tbl where email = '$_POST[email]' and password = '$_POST[password]' ");

    $row = $rs->fetch_assoc();

    if ($row) {

        $_SESSION['uid'] = $row['uid'];
        $_SESSION['uname'] = $row['uname'];
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

            if ($_GET['checkFlag'] == 'true') {
                echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]&checkFlag=true&search_id=$_GET[search_id]>$row[tab_name]</a></li>";
            } else
                echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]>$row[tab_name]</a></li>";
        } else if ($_SESSION['tab'] == $row[table_alias] && $_GET['tabNum'] == $row['tab_num']) {
            if ($_GET['checkFlag'] == 'true') {

                echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]&checkFlag=true&search_id=$_GET[search_id]>$row[tab_name]</a></li>";
            } else
                echo "<li class='active'><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]>$row[tab_name]</a></li>";
        } else {
            if ($_GET['checkFlag'] == 'true') {
                echo "<li><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]&checkFlag=true&search_id=$_GET[search_id]>$row[tab_name]</a></li>";
            } else
                echo "<li><a href=?display=$display_page&tab=$row[table_alias]&tabNum=$row[tab_num]>$row[tab_name]</a></li>";
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
//////////////

function Get_Data_FieldDictionary_Record($table_alias, $display_page, $tab_status = 'false', $tab_num = 'false', $editable = 'true') {

    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $con = connect();

    if ($tab_status == 'true') {

        exit('yasir');
    } else {


echo $_GET['tabNum'] = $tab_num;

       


if( $tab_status == 'bars'){
    
   $_GET['tabNum'] = $tab_num;
    
}

echo ("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");
        $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");

        $row1 = $rs->fetch_assoc();

//print_r($row1);die;
        $_SESSION['update_table']['database_table_name'] = $row1['database_table_name'];

        $_SESSION['update_table']['parent_key'] = $row1['parent_key'];


        if ($row1['database_table_name'] == 'users')
            $_SESSION['search_id'] = $_SESSION['uid'];
        else
            $_SESSION['search_id'] = '76'; /// for displaying one user


        if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {

            $_SESSION['search_id'] = $_GET['search_id'];
        }

        if (isset($_GET['id']) && $_GET['id'] != '') {
            $_SESSION['search_id'] = $_GET['id'];
//$_SESSION['update_table']['parent_key'] = 'id';
        }


        $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_SESSION['search_id']);

        $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and tab_num='$_GET[tabNum]'  and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order";

//exit("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");

        $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$table_alias' and data_dictionary.display_page='$display_page'  and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order");


        if (( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || $_GET['edit'] == 'true') {

            if (isset($_SESSION['return_url']) && $_GET['checkFlag'] == 'true') {
                echo "<form action='?action=update&checkFlag=true&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
            } else {
                echo "<form action='?action=update&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
            }

            if (isset($_GET['id'])) {
                $urow = get_single_record($_SESSION['update_table']['database_table_name'], $_SESSION['update_table']['parent_key'], $_GET['id']);
            }
//print_r($urow);die;
            while ($row = $rs->fetch_assoc()) {

                formating_Update($row, $urow);
            }//// end of while loop
        } else {
//// fetching child list
// if ($row1['list_views'] == 'NULL' || $row1['list_views'] == '') {
/////////////
////////////////
//  echo "<form action='?action=update&tabNum=$_GET[tabNum]' method='post' id='user_profile_form' enctype='multipart/form-data' class='$style'><br>";
// Child_List($qry);
// } else {

            list_display($qry); //// list displays

            echo "<div style='clear:both'></div>";
// }
        }


        if ($editable == 'true') {
            if (( $row1['list_views'] == 'NULL' || $row1['list_views'] == '' ) || ( isset($_GET['id'])) || $_GET['edit'] == 'true') {
                if ($row1['dd_editable'] > 0) {

                    if ($_GET['checkFlag'] == 'true') {

                        $actual_link = $_SESSION[return_url];
                    }
                    echo "<div id='form-footer'>  
                                    <br><br>
                          
                            <div class='form_element update-btn2'>
                                <input type='submit'  value='Update' class='btn btn-primary update-btn' />
                            </div>
                            <div class='form_element'>
                                <label>
                                    <a href='$actual_link' ><input type='button' name='profile_cancel' value='Cancel' class='btn btn-primary update-btn' /></a>
                                </label>
                            </div>
                          
                            
                                </div>";
                }/// if for submit and cancel ends here
            }

            echo "<div style='clear:both'></div></form>";
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



function formating_Update($row, $urow) {


    $field = $row['generic_field_name'];

// $_SESSION[$field] = $field;

    $readonly = '';
    $required = '';

    if ($row['editable'] == 'false')
        $readonly = 'readonly';


    if (!empty($row['required']))
        $required = 'required';


    if (empty($row['format_type']))
        $row['format_type'] = 'text';

    echo "<div class='new_form'><label>$row[field_label_name]</label>";


    switch ($row['format_type']) {
        case "richtext":
            echo "<textarea class='ckeditor' name='$field' >$urow[$field]</textarea>";
            break;

        case "dropdown":
            dropdown($row, $urow);
            break;

        case "email":
            echo "<input type='email' name='$field' value='$urow[$field]' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'> ";
            break;

        case "textbox":
            echo "<textarea name='$field' class='form-control' cols='$row[format_length]'>$urow[$field]</textarea>";
            break;

        case "checkbox":
            checkbox($row, $urow);
            break;

        default :
            echo "<input type='$row[format_type]' name='$field' value='$urow[$field]' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'>";
    }




    echo "</div>";
}

/*
 * 
 * CHECKBOX FUNCTION 
 */

function checkbox($row, $urow) {

    $readonly = '';
    $required = '';

    if ($row['editable'] == 'false')
        $readonly = 'readonly';


    if (!empty($row['required']))
        $required = 'required';

    echo "<input type='hidden' name='$row[generic_field_name]' value='0' >";

    if ($urow[$row['generic_field_name']] == '1')
        echo "<input type='checkbox' name='$row[generic_field_name]' value='1' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control' checked='checked'>";
    else
        echo "<input type='checkbox' name='$row[generic_field_name]' value='1' $readonly $required title='$row[help_message]' size='$row[format_length]' class='form-control'>";
}

////////////////DROPDOWN function///


function dropdown($row, $urow) {

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
function Select_Data_FieldDictionary_Record($alias, $sub_tab_num) {
    $con = connect();

    $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$alias' order by field_dictionary.display_field_order");

    $row = $rs->fetch_assoc();

    $_SESSION['select_table']['database_table_name'] = $row['database_table_name'];

//$_SESSION['update_table']['parent_key'] = $row['parent_key'];


    $rs = $con->query("SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$alias' order by field_dictionary.display_field_order");





    while ($row = $rs->fetch_assoc()) {



        if ($sub_tab_num == $row['sub_tab_num']) {

            formating_Select($row, $urow);
        }
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


    echo "<p><label>$row[field_label_name]";

    if (!empty($row['format_type'])) {
        echo "<input type='$row[format_type]' name='$field' value='' $readonly $required title='$row[help_message]'>";
    } else {

        echo "<input type='text' name='$field' value='' $readonly $required title='$row[help_message]'>";
    }


    echo "</label></p>";
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

                            $target = BASE_URL . $arr[$i]['item_target'] . "?display=" . $arr[$i]['target_display_page'] . "&layout=" . $arr[$i]['page_layout_style'] . "&style=" . $arr[$i]['item_style'];

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


                    <?php } ///////else if ends here                            ?>

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

function list_display($qry) {

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
        jQuery('.list-del').show();";
    } else if ($defView == 'boxView') {

        echo "jQuery('.project-details-wrapper').removeClass().addClass('project-details-wrapper boxView');
	jQuery('.project-details-wrapper > div:first-child').removeClass().addClass('col-6 col-sm-6 col-lg-3');	
        jQuery('.edit').removeClass('invisible');
        jQuery('.project-detail').removeClass('project-detail-list');
        jQuery('.list-checkbox').remove();
        jQuery('#checklist-div').hide();
        jQuery('.list-del').hide();";
    } else if ($defView == 'thumbView') {

        echo "jQuery('.project-details-wrapper').removeClass().addClass('project-details-wrapper thumbView');
	jQuery('.project-details-wrapper > div:first-child').removeClass().addClass('col-12 col-sm-12 col-lg-12');
        jQuery('.edit').addClass('invisible');
        jQuery('.project-detail').addClass('project-detail-list');
        jQuery('.list-checkbox').remove();
        jQuery('#checklist-div').hide();
        jQuery('.list-del').hide();";
    }

    echo "});  </script>";

    /*
     * @function listExtraOptions
     * 
     * Fetching list_extra_options
     */

    $ret_array = listExtraOptions($row['list_extra_options']);

    //echo "<pre>";print_r($ret_array);die;



    global $popup_menu;

    $popup_menu = array("popupmenu" => $ret_array['popupmenu'], "popup_delete" => $ret_array['popup_delete'], "popup_copy" => $ret_array['popup_copy']);


    if (count($list_sort) > 1) {
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

    $list_select = trim($row['list_select']);

    $list_style = $row['list_style'];

    $parent_key = trim($row['parent_key']);

    $table_type = trim($row['table_type']);

    $table_name = trim($row['database_table_name']);

    $list_fields = trim($row['list_fields']);

    /*
     * getting image field name from FD 
     */

    $fdRS = $con->query("SELECT generic_field_name FROM `field_dictionary` WHERE table_alias='$row[table_alias]' and format_type like '%image%' limit 1");

    $imageField = $fdRS->fetch_assoc();




    /*     * **** checking list_fields **** */

    if (!empty($list_fields)) {



        if (preg_match('/^\d+\.?\d*$/', $row['list_fields'])) {
            $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 order by field_dictionary.display_field_order LIMIT " . $row['list_fields'];
        } else {

            $fields = explode(",", $row[list_fields]);

            $fieldsFinal = '';
            foreach ($fields as $f) {

                if (empty($fieldsFinal))
                    $fieldsFinal = "'" . $f . "'";
                else
                    $fieldsFinal = "'" . $f . "' , " . $fieldsFinal;
            }

            $qry = "SELECT * FROM field_dictionary INNER JOIN data_dictionary ON data_dictionary.`table_alias` = field_dictionary.`table_alias` where data_dictionary.table_alias = '$row[table_alias]' and data_dictionary.display_page='$row[display_page]' and  tab_num='$_GET[tabNum]' and field_dictionary.visibility >= 1 and field_dictionary.generic_field_name IN(  $fieldsFinal ) order by field_dictionary.display_field_order";
        }
    }





// exit();
    ?>  

    <br><br><br>



    <div class="row" id="popular_users" >
        <form name="list-form" id="list-form" action="ajax-actions.php" method="post">

            <?php
            if ($ret_array['checklist_array'] == 'true') {

                echo "<input type='hidden' name='checkHidden' id='checkHidden'>"
                . " <div id='checklist-div'> <input type='checkbox' id='selectAll'> &nbsp;<strong>Select All </strong> 
                    &nbsp;&nbsp;";
                if (isset($ret_array['del_array']) && !empty($ret_array['del_array'])) {

                    echo "<button type='submit' class='btn action-delete " . $ret_array['del_array']['style'] . "' name='delete' >" . $ret_array['del_array']['label'] . "</button>";
                }
                echo "</div><br>";
                ?>

            <?php }/// checklist if ends here  ?>
            <?php
            while ($listRecord = $list->fetch_assoc()) {

                $rs = $con->query($qry);
                ?>
                <div class="project-details-wrapper">
                    <div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">
                        <div class="project-detail <?php echo (!empty($list_style) ? $list_style : '') ?>"> 

                            <?php
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


                                    //print_r($list_select_arr);die;

                                    if (isset($list_select_arr[0]) && !empty($list_select_arr[0])) {

                                        $nav = $con->query("SELECT * FROM navigation where target_display_page='$_GET[display]'");
                                        $navList = $nav->fetch_assoc();

                                        $target_url = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[0][0] . "&tabNum=" . $list_select_arr[0][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true";
                                    }

                                    if (isset($list_select_arr[1]) && !empty($list_select_arr[1])) {

                                        $target_url2 = BASE_URL . $navList['item_target'] . "?display=" . $navList['target_display_page'] . "&tab=" . $list_select_arr[1][0] . "&tabNum=" . $list_select_arr[1][1] . "&layout=" . $navList['page_layout_style'] . "&style=" . $navList['item_style'] . "&search_id=" . $listRecord[$parent_key] . "&checkFlag=true";
                                    }

                                    $checkbox_id = $listRecord[$_SESSION['update_table']['parent_key']];


                                    /*
                                     * Putting Delete icon on left side of lists
                                     */


                                    if (isset($ret_array['single_delete_array']) && !empty($ret_array['single_delete_array'])) {

                                        $sing_del_style = $ret_array['single_delete_array']['style'];

                                        if ($ret_array['single_delete_array']['loc'] == 'right') {

                                            echo "<span class='glyphicon glyphicon-remove list-del pull-right $sing_del_style' title-'Delete' id='$checkbox_id'></span>";
                                        } else {
                                            echo "<span class='glyphicon glyphicon-remove list-del $sing_del_style' title-'Delete' id='$checkbox_id'></span>";
                                        }
                                    }


                                    /*
                                     * displaying checkboxes
                                     * checking in database if checklest is there
                                     */


                                    if ($ret_array['checklist_array'] == 'true') {
                                        echo "<span class='span-checkbox'><input type='checkbox'  name='list[]'  value='$checkbox_id' class='list-checkbox' style='marginright:6px;'/></span>";
                                    }






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


                                    listViews($listData, $table_type, $target_url, $imageField, $listRecord, $parent_key, $target_url2);
                                }///end of else if
                            }/// end of mainIF

                            echo "</div></div></div>";
                        }//// end of while loop

                        echo "</form></div>";
                    }

                    /*
                     * @listViews function 
                     * 
                     * give LIST UI and data inside lists
                     */

                    function listViews($listData, $table_type, $target_url, $imageField, $listRecord, $parent_key, $target_url2) {


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

                        if ($table_type == 'child') {


                            echo "<a href='$_SESSION[return_url]&edit=true&search_id=$listRecord[$parent_key]'>";
                        } else {
                            echo "<a href='$target_url&edit=true'>";
                        }

                        echo "<span class='list-span'>" . substr($listData, 0, 110) . "</span></a>";

                        /*
                         * displaying Edit button
                         */

                        if ($table_type == 'child') {

                            echo "<a href='$_SESSION[return_url]&edit=true&search_id=$listRecord[$parent_key]' class='btn btn-primary edit'>Edit</a>";
                        } else {

                            echo "<a href='$target_url&edit=true' class='btn btn-primary edit' >Edit</a>";
                        }

                        echo "</span>"; ///left margin span ends here
                    }

///end of listViews function



                    function listExtraOptions($list_extra_options) {


                        $list_extra_options = explode(';', $list_extra_options);
//print_r($list_extra_options);die;

                        foreach ($list_extra_options as $opt) {

                            $action[] = explode(',', $opt);
                        }

                        //print_r($action);die;

                        foreach ($action as $act) {

                            switch (trim($act[0])) {
                                case 'delete':
                                    $del_array = list_delete(trim($act[1]), trim($act[2]));
                                    break;
                                case 'copy':
                                    $copy_array = list_copy(trim($act[1]), trim($act[2]));
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
                                case 'popup_delete':
                                    $popup_delete_array = popup_delete(trim($act[1]), trim($act[2]));
                                    break;
                                case 'popup_copy':
                                    $popup_copy_array = popup_copy(trim($act[1]), trim($act[2]));
                                    break;

                                default:
                            }
                        }

                        // print_r($single_delete_array);die;
                        return array("del_array" => $del_array, "copy_array" => $copy_array, "single_delete_array" => $single_delete_array, "checklist_array" => $checklist_array, "popupmenu" => $popupmenu, "popup_delete" => $popup_delete_array, "popup_copy" => $popup_copy_array);
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

                    function single_delete($loc, $look) {

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

                               
                                Get_Data_FieldDictionary_Record($row['table_alias'], $display_page , $tab_status = 'bars', $row['tab_num']);
                                
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

                                echo $row['tab_name'] . "<br>";
                            }

                            echo "</div>";
                        }
                    }

///end of sidebar function
                    