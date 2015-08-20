<?php
////////////////////////
////////////////////////////////////
/////////////////////////////////////////////////////////
include("../application/header.php");


$display_page = $_GET['display'];

$page_layout_style = $_GET['layout'];

$style = $_GET['style'];


if (isset($_GET['tab']) || !empty($_GET['tab'])) {
    $_SESSION['tab'] = $_GET['tab'];
} else {
    $_SESSION['tab'] = '';

    unset($_SESSION['tab']);
}
?>


<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://ucarecdn.com/widget/2.4.0/uploadcare/uploadcare.full.min.js" charset="utf-8"></script>
<!-- CAPSTONE: Override Uploadcare text -->
<script src="<?php echo BASE_URL ?>ckeditor/ckeditor.js"></script>

<script src="<?= BASE_JS_URL ?>profile.js"></script>
<?php
///// copy these two files for displaying navigation/////


Navigation($display_page);
//////////////
?>


<div class="content">
    <div id="profile">

        <div class="jumbotron search-form profile-complete">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-sm-6 col-lg-3 height2">

                    </div>
                    <div class="col-6 col-sm-6 col-lg-9">

                    </div>
                </div>
            </div>




            <div class="container">
                <div class="row" >

                    <!-- Left sidebar content Area --> 
                    <?php
                    /*
                     * Finding page layout by DD->tab_num
                     */
                    $con = connect();

                    $rs = $con->query("SELECT tab_num FROM data_dictionary where display_page='$display_page'");

                    //$right_sidebar = $left_sidebar = 'false';

                    while ($row = $rs->fetch_assoc()) {


                        if (trim($row['tab_num']) == 'R1') {

                            $right_sidebar = 'right';
                        }

                        if (trim($row['tab_num']) == 'L1') {

                            $left_sidebar = 'left';
                        }
                    }

                    if ($left_sidebar == 'left' && $right_sidebar == 'right') {

                        $both_sidebar = 'both';
                    }



                    /*
                     * Right sidebar code
                     */

                    sidebar($right_sidebar, $both_sidebar, $display_page);

                    /*
                     * displaying tab area
                     */

                    if ($both_sidebar == 'both') {
                        echo "<div class='col-6 col-sm-6 col-lg-8 right-content user-profile'>";
                    } else if ($both_sidebar != 'both' && ( $right_sidebar == 'right' || $left_sidebar == 'left' )) {

                        echo "<div class='col-6 col-sm-6 col-lg-9 right-content user-profile'>";
                    } else {
                        echo "<div class='col-12 col-sm-12 col-lg-12 right-content user-profile'>";
                    }
                    //if( $both_sidebar == 'false' &&  $right_sidebar == 'false' && $left_sidebar == 'false'  )
                    ?>



                    <!-- Tab Content area .. -->
                    <?php
                    if (isset($page_layout_style) && ($page_layout_style == 'serial-layout')) {
                        serial_layout($display_page, $style);
                    } else {

                        $rs = $con->query("SELECT * FROM data_dictionary where display_page='$display_page' and tab_num='0'");


                        $row = $rs->fetch_assoc();

                        if (!empty($row)) {

                            $tab_status = 'true';

                            $_SESSION['display2'] = $display_page;

                            Get_Data_FieldDictionary_Record($tab, $display_page, $tab_status);
                        } else {

                            $_SESSION['display2'] = '';

                            unset($_SESSION['display2']);

                            echo Get_Links($display_page);

                            global $tab;

                            $tab_status = 'false';

                            if (isset($_SESSION['tab'])) {
                                Get_Data_FieldDictionary_Record($_SESSION['tab'], $display_page, $tab_status);
                            } else {

                                Get_Data_FieldDictionary_Record($tab, $display_page, $tab_status);
                            }
                        }/// tab_num else ends here
                    }//// page_layout           
                    ?>
                    <div style="clear:both"></div>
                </div>

                <!-- Right sidebar content Area -->


                <?php
                /*
                 * Right sidebar code
                 */

                sidebar($left_sidebar, $both_sidebar, $display_page);
                ?>

            </div>
        </div>

    </div>

</div>

</div>



<script src="http://generic.cjcornell.com/ckeditor/ckeditor.js"></script>
<script>
    //CKEDITOR.replace('description'); 
    CKEDITOR.replace('description', {
        toolbarGroups: [
            {name: 'document', groups: ['mode']}, // Line break - next group will be placed in new line.
            {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
            {name: 'styles', groups: ['Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor']},
            {name: 'insert', groups: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe', 'uploadcare', 'youtube']}
        ]},
    {
        allowedContent: true
    });</script>   
<?php
global $popup_menu;



/*
 * Popup Menu codes starts here
 */

//print_r($popup_menu);die;

if ($popup_menu['popupmenu'] == 'true') {

    echo "<ul class='custom-menu'>";

    if (isset($popup_menu['popup_delete']) && !empty($popup_menu['popup_delete'])) {



        echo "<li data-action='delete'  class='" . $popup_menu['popup_delete']['style'] . "'>" . $popup_menu['popup_delete']['label'] . "</li>";
    }


    if (isset($popup_menu['popup_copy']) && !empty($popup_menu['popup_copy'])) {



        echo "<li data-action='copy'  class='" . $popup_menu['popup_copy']['style'] . "'>" . $popup_menu['popup_copy']['label'] . "</li>";
    }

    echo "</ul>";
}
?>

<script>


    $(document).ready(function () {


        $('.project-detail:even').css('background-color', '#bbbbff'); //list rows zebra colors



        /*
         * 
         * Selecting all checkboxes
         * 
         */

        $('#selectAll').click(function (event) {  //on click 
            if (this.checked) { // check select status
                $('.list-checkbox').each(function () { //loop through each checkbox
                    this.checked = true; //select all checkboxes with class "checkbox1"               
                });
            } else {
                $('.list-checkbox').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                       
                });
            }
        });
        var value;
        jQuery(document).on('click', '.grid-type span', function () {
            var gridType = $(this).attr('id');
//alert(gridType);
            if (gridType == "listView") {
                jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper listView");
                jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-12 col-sm-12 col-lg-12");
                jQuery(".edit").addClass("invisible");
                jQuery('.project-detail').addClass('project-detail-list');
                jQuery(".list-checkbox").show();
                jQuery('#checklist-div').show();
                jQuery('.list-del').show();
            }

            if (gridType == "boxView") {
                jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper boxView");
                //jQuery(".project-details-wrapper .profile-image").hide();
                jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-6 col-sm-6 col-lg-3");
                jQuery(".edit").removeClass("invisible");
                jQuery('.project-detail').removeClass('project-detail-list');
                jQuery(".list-checkbox").hide();
                jQuery('#checklist-div').hide();
                jQuery('.list-del').hide();
            }

            if (gridType == "thumbView") {
                jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper thumbView");
                jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-12 col-sm-12 col-lg-12");
                jQuery(".edit").addClass("invisible");
                jQuery('.project-detail').addClass('project-detail-list');
                jQuery(".list-checkbox").hide();
                jQuery('#checklist-div').hide();
                jQuery('.list-del').hide();
            }
        });
        var def_sort;
<?php
if (!empty($_GET['sort'])) {
    ?>

            def_sort = jQuery("#sort_popular_users li[data-value='<?= $_GET['sort'] ?>'").text();
    <?php
    if ($_GET['orderFlag'] == 'down') {
        
    }
    ?>

            jQuery("#sort_popular_users_value").html(def_sort);
<?php } ?>
        $("#sort_popular_users li").click(function () {

            var text = jQuery(this).text();
            jQuery("#sort_popular_users_value").html(text);
            value = $(this).attr("data-value");
            var order;
            var orderFlag;
            order = ($(this).find("span").attr('class'));
//alert(order);
            switch (order) {
                case 'glyphicon glyphicon-chevron-down':
                    orderFlag = "&orderFlag=down";
                    break;
                case 'glyphicon glyphicon-chevron-up':
                    orderFlag = "&orderFlag=up";
                    break;
                default:
                    orderFlag = "&orderFlag=down";
            }

            var url;
            var split;
            url = window.location + "&sort=" + value + orderFlag;
            split = url.split("&sort=");
//alert(split[0] + "&sort=" + value + orderFlag);
            //    $.get(split[0] + "&sort=" + value + orderFlag);


            window.location.href = split[0] + "&sort=" + value + orderFlag;
        });
        /* when click on dropdown SORT */

        jQuery('#sort_popular_users li').click(function () {


            //  var index = jQuery(this).index();
            // var text = jQuery(this).text();
            // var value = jQuery(this).attr('data-value');	
            //jQuery("#sort_hidden_value").val(value);
            //jQuery("#sort_popular_users_value").html(text);
            //alert('Index is: ' + index + ' , text is ' + text + ' and value is ' + value);
        });
        /* Sorting function on SORT button click */
        jQuery("#sortList").click(function () {
            // var sortby = jQuery('#sort_hidden_value').val();


        });


        $(".action-delete").click(function () {


            $("#checkHidden").val('delete');
            $('#list-form').ajaxForm(function (data) {
                //alert(data);
                location.reload();
            });
        });
        $(".list-del").click(function () {


            if (confirm("Are you sure ,You want to delete the Record!") == true) {


                var del_id = $(this).attr('id');
                $.ajax({
                    method: "GET",
                    url: "<?= BASE_URL_SYSTEM ?>ajax-actions.php",
                    data: {list_delete: del_id, check_action: "delete"}
                })
                        .done(function (msg) {
                            location.reload();
                        });
            }

        });




        $(".action-copy").click(function () {


            $("#checkHidden").val('copy');
            $('#list-form').ajaxForm(function (data) {
                // console.log(data);
                location.reload();
            });
        });
        $(".list-copy").click(function () {


            if (confirm("Are you sure ,You want to Copy the Record!") == true) {


                var del_id = $(this).attr('id');
                $.ajax({
                    method: "GET",
                    url: "<?= BASE_URL_SYSTEM ?>ajax-actions.php",
                    data: {list_copy: del_id, check_action: "copy"}
                })
                        .done(function (msg) {
                            location.reload();
                        });
            }

        });



  $(".action-add").click(function () {


           window.location.href='<?=$_SESSION['add_url_list']?>';
        });
        /*
         
         var test = 'something ';
         $(".span-checkbox").click(function(){
         
         test = test.concat($(this).html());
         
         alert(test);
         
         
         });
         
         */

        /* Sorting function on SORT button click */


        /* action perform by list_select button on click */



        /*
         * it calls when right click on single line list
         */

        var popup_del;
        // Trigger action when the contexmenu is about to be shown
        $(".project-detail").bind("contextmenu", function (event) {

            popup_del = $(this).children().attr('id');
            // Avoid the real one
            event.preventDefault();
            // Show contextmenu
            $(".custom-menu").finish().toggle(100).
                    // In the right position (the mouse)
                    css({
                        top: event.pageY + "px",
                        left: event.pageX + "px"
                    });
        });
// If the document is clicked somewhere
        $(document).bind("mousedown", function (e) {

            // If the clicked element is not the menu
            if (!$(e.target).parents(".custom-menu").length > 0) {

                // Hide it
                $(".custom-menu").hide(100);
            }
        });
// If the menu element is clicked
        $(".custom-menu li").click(function () {

            // This is the triggered action name
            switch ($(this).attr("data-action")) {

                // A case for each action. Your actions here
                case "delete":
                    popup_delete(popup_del);
                    break;
                case "copy":
                    popup_copy(popup_del);
                    break;

            }

            // Hide it AFTER the action was triggered
            $(".custom-menu").hide(100);
        });


        /***** popup DELETE Function ****/

        function popup_delete(del_id) {


            if (confirm("Are you sure ,You want to delete the Record!") == true) {



                $.ajax({
                    method: "GET",
                    url: "<?= BASE_URL_SYSTEM ?>ajax-actions.php",
                    data: {list_delete: del_id, check_action: "delete"}
                })
                        .done(function (msg) {
                            location.reload();
                        });
            }
        }


        /***** popup COPY Function ****/

        function popup_copy(del_id) {


            if (confirm("Are you sure ,You want to copy the Record!") == true) {



                $.ajax({
                    method: "GET",
                    url: "<?= BASE_URL_SYSTEM ?>ajax-actions.php",
                    data: {list_copy: del_id, check_action: "copy"}
                })
                        .done(function (msg) {
                            location.reload();
                        });
            }
        }



        /************** Enabling submit and cancel button *******/


        $(".edit-btn").click(function () {


            var id = $(this).attr('id');


            $.ajax({
                method: "GET",
                url: "<?= BASE_URL_SYSTEM ?>ajax-actions.php",
                data: {id: id, check_action: "enable_edit"}
            })
                    .done(function (msg) {

                        if ($.trim(msg) == 'active') {
                            alert('One edit form is active already');
                        } else {
                            location.reload();
                        }
                    });


        });


        //$(".login-btn").click(function () {


        // alert(uploadcare.Widget("#uploadcare_image_url").value());
        // alert($(this).parents(".img-holder").find("#file2").val());

        // alert($(this).parents(".img-holder").find(".uploadcare_image_url").val());




        //  });



    });
</script>



<?php include("../application/footer.php"); ?>