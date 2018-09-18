<?php
/*
 * @Functions parseFieldType($row)
 * 
 * function uploadAudioFile($parameters)
 * 
 * function uploadImageFile($uploadCareURL, $imageName)
 * 
 * function uploadPdfFile($uploadCareURL, $imageName)
 * 
 * 
 * function listExtraOptions($list_extra_options) {
 * 
 * function editPagePagination()
 * 
 * function listFilter()
 * 
 * function boxViewPagination($pagination, $tab_num, $list_select_arr) {
 * 
 * other small functions for future use
 */





/*
 * 
 * it will parse field type  on the edit form
 */

function parseFieldType($row) {

    $con = connect();

    $result = $con->query("describe $row[database_table_name]");

    while ($result_rec = $result->fetch_assoc()) {
        if ($result_rec['Field'] == $row['generic_field_name']) {

            $field_type = $result_rec['Type'];
        }
    }

    $field_type = explode("(", $field_type);

    $field_length = '';

    if ($field_type[0] == 'varchar') {

        $field_length = defaultFeildLenText;
    } else if ($field_type[0] == 'text') {

        $field_length = defaultFeildLenTextarea;
    } else if ($field_type[0] == 'int') {

        $field_length = defaultFeildLenInteger;
    } else if ($field_type[0] == 'boolean') {

        $field_length = defaultFeildLenBoolean;
    } else if ($field_type[0] == 'double' || $field_type[0] == 'float' || $field_type[0] == 'tinyint') {

        $field_length = defaultFeildLenOtherInteger;
    }

    return $field_length;
}

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

    if ($parameters['type'] != "audio/wav" /* || $parameters['type'] != "audio/ogg" || $parameters['type'] != "audio/mpeg" */) {
        // throw new Exception("This file type is not allowed to upload");
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
 * 
 * Function @uploadPdfFile
 */

function uploadPdfFile($uploadCareURL, $imageName) {

    $src = "../users_uploads/pdf/";

    $uploadcare_image_url = $uploadCareURL;
    $filename = $imageName;
    $ext = pathinfo($filename, PATHINFO_EXTENSION);   //returns the extension
    $allowed_types = array('pdf');
    $randName = rand(124, 1000);
    $imgInfo = array();

    // If the file extension is allowed
    if (in_array($ext, $allowed_types)) {
        $new_filename = $filename;

        //$new_filepath = $base_path.'upload/orig/'.$new_filename;
        $imgpath = $src . $randName . '-' . $new_filename;


        // Attempt to copy the image from Uploadcare to our server
        if (copy($uploadcare_image_url, $imgpath)) {
            //Resize the image


            $imgInfo['image'] =  $randName . '-' .  $new_filename ;

            return $imgInfo;
        } else {
            return $imgInfo;
        }
    } else {
        return $imgInfo;
    }
}

/*
 * 
 * **********
 * ********************
 * ********************************listExtraOptions function goes here 
 * ********
 * **********************
 * ********************************
 */

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
            case 'popup_add':
                $popup_add_array = popup_add(trim($act[1]), trim($act[2]));
                break;
            case 'popup_openChild':
                $popup_openChild_array = popup_openChild(trim($act[1]), trim($act[2]));
                break;

            case 'pagination':
                $pagination_array = trim($act[1]);
                break;


            case 'users':
                $users_array = trim($act[1]);
                break;

            ////for FFFR TABLE

            case 'friends':
                $friends_tbl = trim($act[1]);
                break;
            case 'follow':
                $follow_tbl = trim($act[1]);
                break;

            case 'favorite':
                $favorite_tbl = trim($act[1]);
                break;

            case 'rating':
                $rating_tbl = trim($act[1]);
                break;

            case 'voting':
                $voting_tbl = trim($act[1]);
                break;

            case 'lowerLimit':
                $lowerLimit = trim($act[1]);
                break;

            case 'upperLimit':
                $upperLimit = trim($act[1]);
                break;

            case 'userLimit':
                $userLimit = trim($act[1]);
                break;

            case 'voteLimit':
                $voteLimit = trim($act[1]);
                break;

            case 'userVoteLimit':
                $userVoteLimit = trim($act[1]);
                break;


            case 'timeLimit':
                $timeLimit = trim($act[1]);
                break;

            case 'voteChange':
                $voteChange = trim($act[1]);
                break;

            case 'votingType':
                $votingType = trim($act[1]);
                break;

            case 'editPagePagination':
                $editPagePagination = trim($act[1]);
                break;

            case 'numberLabel':
                $numberLabel = trim($act[1]);
                break;

            case 'pattern':
                $pattern = trim($act[1]);
                break;

            default:
        }
    }


    // print_r($users);
    return array("del_array" => $del_array, "copy_array" => $copy_array, "add_array" => $add_array, "single_delete_array" => $single_delete_array, "single_copy_array" => $single_copy_array, "checklist_array" => $checklist_array, "popupmenu" => $popupmenu, "popup_delete" => $popup_delete_array, "popup_copy" => $popup_copy_array, "popup_add" => $popup_add_array, "popup_openChild" => $popup_openChild_array, "pagination" => $pagination_array, "users" => $users_array, "friend_tbl" => $friends_tbl, "follow_tbl" => $follow_tbl, "favorite_tbl" => $favorite_tbl, "rating_tbl" => $rating_tbl, "voting_tbl" => $voting_tbl, "lowerLimit" => $lowerLimit, "upperLimit" => $upperLimit, "userLimit" => $userLimit, "voteLimit" => $voteLimit, "userVoteLimit" => $userVoteLimit, "timeLimit" => $timeLimit, "voteChange" => $voteChange, "votingType" => $votingType, "editPagePagination" => $editPagePagination, "numberLabel" => $numberLabel, "pattern" => $pattern);
}

//// end of list_extra_option function







/* * ***
 * **************
 * 
 * 
 * *****
 * 
 * editPagePagination fucntion code goes here
 * ******************************
 * ************************************
 * 
 */


function editPagePagination($list_extra_options, $pkey) {

    /////////////Checking next/prev option on list edit page

   $list_extra_options = listExtraOptions($list_extra_options); 
    
    //\\ print_r($list);die;



    $record = getWhere('data_dictionary', array('table_alias' => $_GET['tab'], 'display_page' => $_GET['display'], 'tab_num' => $_GET['tabNum']));


    if (trim($record[0]['table_type']) == 'child')
        $search_key = $_SESSION['parent_value'];
    else
        $search_key = $_SESSION['search_id'];


    ///////fetching forigen keys


    if (!empty($record[0]['list_filter']))
        $clause = listFilter($record[0]['list_filter'], $search_key);



    $next_id = nextKey($record[0]['database_table_name'], $pkey, $_GET['search_id'], $clause);

    $prev_id = prevKey($record[0]['database_table_name'], $pkey, $_GET['search_id'], $clause);

    $first_id = firstKey($record[0]['database_table_name'], $pkey, $clause);

    $last_id = lastKey($record[0]['database_table_name'], $pkey, $clause);




    if (trim($list_extra_options['editPagePagination']) == 'true') {

        $retData = "               

<div class='editPagePagination'>
       
<a href='" . helperOfEPP($first_id, 'url') . "' class='button" . helperOfEPP($first_id) . "'>". pageFirst ."</a>

<a href='" . helperOfEPP($prev_id, 'url') . "' class='button" . helperOfEPP($prev_id) . "'>". pagePrev ."</a>

<a href='" . helperOfEPP($next_id, 'url') . "' class='button" . helperOfEPP($next_id) . "'>". pageNext ."</a>

<a href='" . helperOfEPP($last_id, 'url') . "' class='button" . helperOfEPP($last_id) . "'>". pageLast ."</a>

</div>";
    }

    return $retData;
}

/*
 * 
 * /////////////////////////Function Return URL and CSS CLASS NAME FOR 
 * 
 * *******
 * ******************
 * *******************************
 * **
 * ******function editPagePagination
 * 
 * ***
 * **************
 * *********************
 * 
 * 
 */

function helperOfEPP($id, $mode = 'false') {


    if ($mode == 'url' && !empty($id)) {


        return BASE_URL . "system/profile.php?display=$_GET[display]&tab=$_GET[tab]&tabNum=$_GET[tabNum]&ta=$_GET[ta]&search_id=$id&checkFlag=true&table_type=$_GET[table_type]&edit=true";
    } else if ($mode == 'false' && ( empty($id) || $id == trim($_GET['search_id']) )) {


        return ' disabled';
    }
}

/*
 * 
 * Filtering dd->listFilter for obtaining 2 forigen keyes.. one is of current user and other can be anything
 */

function listFilter($listFilter, $search) {



    $parent_key = explode(";", $listFilter);


    $firstParent = $parent_key[0];
  // print_r($parent_key);die;
    
        if (!empty($parent_key[1])) {

        $listCond = $parent_key[1];
    }


  //  $checkFlag = false;

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


    if (!empty($pid) && !empty($search)) {

        $clause = "$pid = '$search'";
        
       //$checkFlag = true;
    }

    if (!empty($uid)) {

        if (!empty($clause)) {

            $clause = $clause . " and " . $uid . "=" . $_SESSION['uid'];
        
            
        } else {

            $clause = $uid . "=" . $_SESSION['uid'];
        }
        
      //  $checkFlag = true;
    }


   


        if (!empty($clause) && !empty($listCond) ) {

            $clause = $clause . " and " . $listCond;
            
        } else if (empty($clause) && !empty($firstParent) ) {

            
            $clause = $firstParent;
        }
    


   // exit($clause);

    return $clause;
}

/* * ***
 * **************BoxView Pagination function which call jquery function code
 * Goes here
 * ******************************
 * ************************************
 * 
 */

function boxViewPagination($pagination, $tab_num, $list_select_arr) {

    //// BoxView Pagination code inserted here
    ?>
    <!-- An empty div which will be populated using jQuery -->

    <br>
    <div class='page_navigation'></div>


    <?php
    if (!empty($list_select_arr[2][0])) {


        echo " <a href='" . BASE_URL . "system/profile.php?display=" . $list_select_arr[2][2] . "&tab=" . $list_select_arr[2][0] . "&tabNum=" . $list_select_arr[2][1] . "' class='show_all ' id='test-super'>" . SHOW_ALL . "</a>";
    }
    ?>


    </div>



    <script type="text/javascript">



        $(document).ready(function () {



            pag = $('#content<?= $tab_num; ?>');

            pag_id = "#content<?= $tab_num; ?>";
            //how much items per page to show
            var show_per_page = <?= $pagination; ?>;
            //getting the amount of elements inside content div
            var number_of_items = $('#content<?= $tab_num; ?>').children('.boxView').size();
            //calculate the number of pages we are going to have
            var number_of_pages = Math.ceil(number_of_items / show_per_page);

            //set the value of our hidden input fields
            $('#content<?= $tab_num; ?>').children('.current_page').val(0);
            $('#content<?= $tab_num; ?>').children('.show_per_page').val(show_per_page);

            //now when we got all we need for the navigation let's make it '

            /* 
             what are we going to have in the navigation?
             - link to previous page
             - links to specific pages
             - link to next page
             */
            var navigation_html = '<a class="previous_link" href="#">Prev</a>';
            var current_link = 0;
            while (number_of_pages > current_link) {
                navigation_html += '<a class="page_link" href="javascript:go_to_page<?= $tab_num; ?>(' + current_link + ')" longdesc="' + current_link + '">' + (current_link + 1) + '</a>';
                current_link++;
            }
            navigation_html += '<a class="next_link" href="#">Next</a>';

            $('#content<?= $tab_num; ?>').find('.page_navigation').html(navigation_html);

            //add active_page class to the first page link
            $('#content<?= $tab_num; ?>').find('.page_navigation .page_link:first').addClass('active_page');

            //hide all the elements inside content div
            $('#content<?= $tab_num; ?>').children('.boxView').css('display', 'none');

            //and show the first n (show_per_page) elements
            $('#content<?= $tab_num; ?>').children('.boxView').slice(0, show_per_page).css('display', 'block');



            ///next function goes here



            $('#content<?= $tab_num; ?>').on("click", ".next_link", function (event) {


                event.preventDefault();

                new_page = parseInt($(this).parents("#content<?= $tab_num; ?>").find('.current_page').val()) + 1;
                //if there is an item after the current active link run the function
                if ($(this).parents("#content<?= $tab_num; ?>").find('.active_page').next('.page_link').length == true) {
                    go_to_page<?= $tab_num; ?>(new_page);
                }

            });



            ////previous function goes here


            $('#content<?= $tab_num; ?>').on("click", ".previous_link", function (event) {

                event.preventDefault();

                new_page = parseInt($(this).parents("#content<?= $tab_num; ?>").find('.current_page').val()) - 1;
                //if there is an item before the current active link run the function
                if ($(this).parents("#content<?= $tab_num; ?>").find('.active_page').prev('.page_link').length == true) {
                    go_to_page<?= $tab_num; ?>(new_page);
                }

            });

        });




        function go_to_page<?= $tab_num; ?>(page_num) {
            //get the number of items shown per page
            var show_per_page = parseInt($('#content<?= $tab_num; ?>').children('.show_per_page').val());

            //get the element number where to start the slice from
            start_from = page_num * show_per_page;

            //get the element number where to end the slice
            end_on = start_from + show_per_page;

            //hide all children elements of content div, get specific items and show them
            $('#content<?= $tab_num; ?>').children('.boxView').css('display', 'none').slice(start_from, end_on).css('display', 'block');

            /*get the page link that has longdesc attribute of the current page and add active_page class to it
             and remove that class from previously active page link*/
            $('#content<?= $tab_num; ?>').find('.page_link[longdesc=' + page_num + ']').addClass('active_page').siblings('.active_page').removeClass('active_page');

            //update the current page input field
            $('#content<?= $tab_num; ?>').find('.current_page').val(page_num);
        }





    </script>




    <?php
}

//////boxView functions end here            






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

function popup_add($label, $look) {

    return array("label" => $label, "style" => $look);
}

function popup_openChild($label, $look) {

    return array("label" => $label, "style" => $look);
}
