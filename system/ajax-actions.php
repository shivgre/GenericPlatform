<?php

session_start();

require_once '../application/config.php';


include_once($GLOBALS['APP_DIR'] . "application/database/db.php");
include_once($GLOBALS['APP_DIR'] . "application/functions.php");
include_once($GLOBALS['APP_DIR'] . "application/language/en.php");
//include_once($GLOBALS['APP_DIR'] . "actions/RelationshipManagement.php");
//include_once($GLOBALS['APP_DIR'] . "system/special_config.php");


/*
 * 
 * @checklist Multiple Deletion 
 */


if (isset($_POST["checkHidden"]) && !empty($_POST["checkHidden"]) && $_POST["checkHidden"] == 'delete') {


    $item = implode(",", $_POST['list']);

    //exit("delete from " . $_SESSION['update_table']['database_table_name'] ." where " . $_SESSION['update_table']['parent_key'] . " IN( $item )");


    mysqli_query($con, "delete from " . $_SESSION['update_table']['database_table_name'] . " where " . $_SESSION['update_table']['parent_key'] . " IN( $item )");
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
 * @checklist single deletion
 */

if (isset($_GET["list_delete"]) && !empty($_GET["list_delete"]) && $_GET["check_action"] == 'delete') {




    //exit("delete from " . $_SESSION['update_table']['database_table_name'] ." where " . $_SESSION['update_table']['parent_key'] . "=" . $_GET["list_delete"]);


    mysqli_query($con, "delete from " . $_SESSION['update_table']['database_table_name'] . " where " . $_SESSION['update_table']['parent_key'] . "=" . $_GET["list_delete"]);
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


    $row = getWhere('data_dictionary', array('dd_editable' => '11', 'display_page' => $check['display_page']));

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
/* * ********************************************* */
/* * *****code to create pagination links********* */
/* * ********************************************* */
if (isset($_GET["action"]) && $_GET["action"] == "pagination" && isset($_GET['query']) && isset($_GET['per_page'])) {
    $query = $_GET['query'];
    $per_page = $_GET['per_page'];
    $page = $_GET['page'];
    echo pagination($projectTblArray, $userTblArray, $query, $per_page, $page);
}
/* * ******************************************* */
/* * ***code for retrieving the popular projects********* */
/* * ******************************************* */
if (isset($_GET['startpoint'])) {

    $records = '<h2>' . POPULAR_PROJECTS . '</h2>';
    $statement = $_GET['query'];
    $startpoint = $_GET['startpoint'];
    $limit = $_GET['limit'];

    $rm = new RelationshipManagement();
    $projectLikes = $rm->getProjectLikes();
    $projectsRating = $rm->getProjectsRating();

    //show records
    $query = "SELECT * FROM {$projectTblArray['tableAlias']} where  {$projectTblArray['isLive_fld']}=1 and {$projectTblArray['isBought_fld']}=0 LIMIT $startpoint , $limit";

    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $query = "SELECT * FROM {$projectTblArray['database_table_name']} where  {$projectTblArray['isLive_fld']}=1 and {$projectTblArray['isBought_fld']}=0 LIMIT $startpoint , $limit";


    mysql_real_escape_string($query);
    $result = mysql_query($query);
    if (!$result) {
        echo 'Error ';
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'projectDetails.php?pid=' . $row[$projectTblArray['pid_fld']] . '">';
            $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
            $records = $records . '<div class="project-detail">';
            $records = $records . '<span class="profile-image">';
            if ($row[$projectTblArray['projectImage_fld']] != "") {
                $records = $records . "<img src='" . BASE_URL . "project_uploads/" . $row[$projectTblArray['projectImage_fld']] . "' alt='' class='img-responsive'>";
            } else {
                $records = $records . "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
            }
            $records = $records . '</span>';
            if (array_key_exists($row[$projectTblArray['pid_fld']], $projectLikes)) {
                $records = $records . '<span class="relationship like" data-id="' . $row[$projectTblArray['pid_fld']] . '" data-active="1"><i class="fa fa-heart-o fa-2x user_liked">' . $projectLikes[$row['pid']] . '</i></span>';
            } else {
                $records = $records . '<span class="relationship like" data-id="' . $row[$projectTblArray['pid_fld']] . '" data-active="0"><i class="fa fa-heart-o fa-2x"></i>' . $projectLikes[$row['pid']] . '</span>';
            }
            $records = $records . '<input id="input-21b" value="' . $projectsRating[$row[$projectTblArray['pid_fld']]] . '" type="number" class="rating" data-id="' . $row['pid'] . '" data-from="project" min=0 max=5 step=0.2 data-size="sm">';
            $records = $records . '<div class="project-info">';
            $records = $records . '<h3>' . $row[$projectTblArray['pname_fld']] . '</h3>';
            $records = $records . '<p> <span><strong>' . CREATED . ' -</strong> </span> <span class="date">' . $row[$projectTblArray['create_date_fld']] . '</span> </p>';
            $records = $records . '<div style="clear:both"></div>' . '</div>';
            $records = $records . '</div></div>';
            $records = $records . '</a>';
        }
        echo $records . '<link href="' . BASE_URL . 'star-rating/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
		 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="' . BASE_URL . 'star-rating/star-rating.js" type="text/javascript"></script>';
    } else {
        echo '<div  id="error_div">' . PROJECTS_NOT_AVAILABEL . '</div>';
    }
}
/* * ************************** */
/* * *****Search User********* */
/* * ************************** */
if (isset($_GET["action"]) && $_GET["action"] == "search_user") {
    $sql = "SELECT * FROM {$userTblArray['table_alias']} WHERE {$userTblArray['uname_fld']} LIKE '" . $_GET['user'] . "%' and {$userTblArray['isActive_fld']} = 1";

    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // CJ Note 1-20-15 .. I temporarily changed the SQL Line to below.
    $sql = "SELECT * FROM {$userTblArray['database_table_name']} WHERE {$userTblArray['uname_fld']} LIKE '" . $_GET['user'] . "%' and {$userTblArray['isActive_fld']} = 1";


    $result = mysql_query($sql);
    echo "<ul>";
    while ($row = mysql_fetch_array($result)) {
        echo "<li>

			<a href='" . BASE_URL_SYSTEM . "userDetails.php?uid=" . $row[$userTblArray['uid_fld']] . "'><div class='projectDetWrapper'><div class='projImgWrapper'>";
        if ($row['image'] != "") {
            echo "<img src='" . BASE_URL . "users_uploads/" . $row[$userTblArray['image_fld']] . "'>";
        } else {
            echo "<img src='" . BASE_URL . "users_uploads/defaultImageIcon.png'>";
        }
        echo "</div>

				<div class='projDetails'>

							<div>User - " . $row[$userTblArray['uname_fld']] . "</div>

							<div>Email - " . $row[$userTblArray['email_fld']] . "</div>

							<div>Country - " . $row[$userTblArray['country_fld']] . "</div>

							<div>Role - " . $row['role'] . "</div>

				</div>

			</div></a></li>";
    }
    echo "</ul>";
    exit();
}
/* * ************************** */
/* * *****Search Projects********* */
/* * ************************** */
if (isset($_GET["action"]) && $_GET["action"] == "search_projects") {
    $sql = "SELECT * FROM {$userTblArray['table_alias']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']}=" . $_SESSION['uid'] . " AND u.{$userTblArray['isActive_fld']} = 1
	AND p.{$projectTblArray['pname_fld']} LIKE '" . $_GET['project'] . "%'";

// CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
// temporarily changing to ...
    $sql = "SELECT * FROM {$userTblArray['database_table_name']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']}=" . $_SESSION['uid'] . " AND u.{$userTblArray['isActive_fld']} = 1
	AND p.{$projectTblArray['pname_fld']} LIKE '" . $_GET['project'] . "%'";




    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0) {
        echo "<div class='noProjects'>" . PROJECTS_NOT_AVAILABLE_MESSAGE . "</div>";
        exit;
    }
    echo "<h2>" . MY_PROJECTS_TITLE . "</h2>";
    while ($row = mysql_fetch_array($result)) {
        echo "<a href='" . BASE_URL_SYSTEM . "projectDetails.php?pid=" . $row[$projectTblArray['pid_fld']] . "'> <div class='col-6 col-sm-6 col-lg-3' data-scroll-reveal='enter bottom over 1s and move 100px' ><div class='project-detail'><span class='profile-image'>";
        if ($row[$projectTblArray['projectImage_fld']] != "") {
            echo "<img src='" . BASE_URL . "project_uploads/" . $row[$projectTblArray['projectImage_fld']] . "'>";
        } else {
            echo "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png'>";
        }
        echo "</span>

		<div class='project-info projDetails'>

			<h3>" . $row[$projectTblArray['pname_fld']] . "<h3>

			<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['create_date_fld']] . "</span></p>

			<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['expiry_date_fld']] . "</span></p>

			<p> <span><strong></strong></span> <span class='name'><a href='" . BASE_URL_SYSTEM . "createProject.php?pid=" . $row[$projectTblArray['pid_fld']] . "'><span class='btn btn-primary'>" . MANAGE_PROJECT . "</span></a></span></p>

			<p> <span><strong></strong></span> <span class='name'><a href='" . BASE_URL_SYSTEM . "project-form-actions.php?action=delete&pid=" . $row[$projectTblArray['pid_fld']] . "' >" . DELETE_PROJECT . "</a></span></p>

		</div>

		</div>

		</div>

		</a>";
    }
}
/* * ************************************ */
/* * *****Search Others Projects********* */
/* * ************************************ */
if (isset($_GET["action"]) && $_GET["action"] == "searchOthersProjects") {
    //$sql = "SELECT * FROM projects WHERE uid !=".$_SESSION['uid']." AND pname LIKE '".$_GET['project']."%'";
    $sql = "SELECT * FROM {$userTblArray['table_alias']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']} != " . $_SESSION['uid'] . " AND u.{$userTblArray['isActive_fld']} = 1
	AND p.{$projectTblArray['isLive_fld']}=1
	AND p.{$projectTblArray['pname_fld']}
	LIKE '" . $_GET['project'] . "%'";

    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $sql = "SELECT * FROM {$userTblArray['database_table_name']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']} != " . $_SESSION['uid'] . " AND u.{$userTblArray['isActive_fld']} = 1
	AND p.{$projectTblArray['isLive_fld']}=1
	AND p.{$projectTblArray['pname_fld']}
	LIKE '" . $_GET['project'] . "%'";

    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0) {
        echo "<div class='noProjects'>" . OTHERS_PROJECTS_NOT_AVAILABLE_MESSAGE . "</div>";
        exit;
    }
    echo "<h2>" . OTHERS_PROJECTS_TITLE . "</h2>";
    while ($row = mysql_fetch_array($result)) {
        echo "<a href='" . BASE_URL_SYSTEM . "projectDetails.php?pid=" . $row[$projectTblArray['pid_fld']] . "'><div class='col-6 col-sm-6 col-lg-3' data-scroll-reveal='enter bottom over 1s and move 100px'><div class='project-detail'><span class='profile-image'>";
        if ($row[$projectTblArray['projectImage_fld']] != "") {
            echo "<img src='" . BASE_URL . "project_uploads/" . $row[$projectTblArray['projectImage_fld']] . "'>";
        } else {
            echo "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png'>";
        }
        echo "</span>

		<div class='project-info projDetails'>

						<h3>" . $row[$projectTblArray['pname_fld']] . "</h3>

						<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['create_date_fld']] . "</span></p>

						<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['expiry_date_fld']] . "</span></p>

					</div>

		</div>

		</div>

		</a>";
    }
}
/* * ************************** */
/* * *****Others Projects********* */
/* * ************************** */
if (isset($_GET["action"]) && $_GET["action"] == "projects") {
    //$sql = "SELECT * FROM projects WHERE uid != ".$_SESSION['uid'];
    $sql = "SELECT * FROM {$userTblArray['table_alias']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']} != " . $_SESSION['uid'] . "
	AND u.{$userTblArray['isActive_fld']} = 1 AND p.{$projectTblArray['isLive_fld']}=1";

    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $sql = "SELECT * FROM {$userTblArray['database_table_name']} u, {$projectTblArray['tableAlias']} p
	WHERE  u.{$userTblArray['uid_fld']} = p.{$projectTblArray['uid_fld']}
	AND p.{$projectTblArray['uid_fld']} != " . $_SESSION['uid'] . "
	AND u.{$userTblArray['isActive_fld']} = 1 AND p.{$projectTblArray['isLive_fld']}=1";



    $result = mysql_query($sql);
    if (mysql_num_rows($result) < 0) {
        echo "<div class='noProjects'>Currently There are no projects available.</div>";
        exit;
    }
    echo "<h2>Others Projects</h2>";
    while ($row = mysql_fetch_array($result)) {
        echo "<a href='" . BASE_URL_SYSTEM . "projectDetails.php?pid=" . $row[$projectTblArray['pid_fld']] . "'><div class='col-6 col-sm-6 col-lg-3' data-scroll-reveal='enter bottom over 1s and move 100px'><div class='project-detail'><span class='profile-image'>";
        if ($row[$projectTblArray['projectImage_fld']] != "") {
            echo "<img src='" . BASE_URL . "project_uploads/" . $row[$projectTblArray['projectImage_fld']] . "'>";
        } else {
            echo "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png'>";
        }
        echo "</span>

		<div class='project-info projDetails'>

						<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['pname_fld']] . "</span></p>

						<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['create_date_fld']] . "</span></p>

						<p> <span><strong></strong></span> <span class='name'>" . $row[$projectTblArray['expiry_date_fld']] . "</span></p>

					</div>

		</div>

		</div>

		</a>";
    }
}
/* * ********************************************* */
/* * *****code for create pagination users links********* */
/* * ********************************************* */
if (isset($_GET["action"]) && $_GET["action"] == "pagination_users" && isset($_GET['query']) && isset($_GET['per_page'])) {
    $query = $_GET['query'];
    $per_page = $_GET['per_page'];
    $page = $_GET['page'];
    echo paginationUsers($projectTblArray, $userTblArray, $query, $per_page, $page);
}
/* * ******************************************* */
/* * ***code for get users records********* */
/* * ******************************************* */
if (isset($_GET['startpoint_users']) && $_GET["action"] == "pagination_users") {
    $records = '<h2>Popular Users</h2>';
    $statement = $_GET['query'];
    $startpoint = $_GET['startpoint_users'];
    $limit = $_GET['limit'];
    $userLikes = array();
    //show records
    if (isset($_SESSION['uid']) && $_SESSION['uid'] != "") {
        $query = "SELECT * FROM {$userTblArray['table_alias']}
		where {$userTblArray['isActive_fld']}=1 and {$userTblArray['uid_fld']} != " . $_SESSION['uid'] . "
		LIMIT $startpoint , $limit";

        // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
        // temporarily changing to ...
//    $query = "SELECT * FROM {$userTblArray['database_table_name]}
//		where {$userTblArray['isActive_fld']}=1 and {$userTblArray['uid_fld']} != " . $_SESSION['uid'] . "
//		LIMIT $startpoint , $limit";
        //Get current user likes
        $rm = new RelationshipManagement();
        $userLikes = $rm->getUserLikes($_SESSION['uid']);
        $usersRating = $rm->getUsersRating($_SESSION['uid']);
    } else {
        $query = "SELECT * FROM {$userTblArray['table_alias']} where {$userTblArray['isActive_fld']}=1  LIMIT $startpoint , $limit";

        // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
        // temporarily changing to ...
//    $query = "SELECT * FROM {$userTblArray['databse_table_name']} where {$userTblArray['isActive_fld']}=1  LIMIT $startpoint , $limit";
    }
    mysql_real_escape_string($query);
    $result = mysql_query($query);
    if (!$result) {
        echo 'Error ';
    }

    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'userDetails.php?uid=' . $row[$userTblArray['uid_fld']] . '">';
            $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
            $records = $records . '<div class="project-detail">';
            $records = $records . '<span class="profile-image">';
            if ($row[$userTblArray['image_fld']] != "") {
                $records = $records . "<img src='" . BASE_URL . "users_uploads/" . $row[$userTblArray['image_fld']] . "' alt='' class='img-responsive'>";
            } else {
                $records = $records . "<img src='" . BASE_URL . "users_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
            }
            $records = $records . '</span>';
            $records = $records . '<div class="project-info">';
            $records = $records . '<h3>' . $row[$userTblArray['uname_fld']] . '</h3>';
            if (in_array($row[$userTblArray['uid_fld']], $userLikes, true)) {
                //$records = $records . "Liked";
                $records = $records . '<span class="relationship like" data-id="' . $row[$userTblArray['uid_fld']] . '" data-active="1"><i class="fa fa-heart-o fa-2x user_liked"></i></span>';
            } else {
                $records = $records . '<span class="relationship like" data-id="' . $row[$userTblArray['uid_fld']] . '" data-active="0"><i class="fa fa-heart-o fa-2x"></i></span>';
            }
            $records = $records . '<input id="input-21b" value="' . $usersRating[$row[$userTblArray['uid_fld']]] . '" type="number" class="rating" data-id="' . $row[$userTblArray['uid_fld']] . '" data-from="user" min=0 max=5 step=0.2 data-size="sm">';
            $records = $records . '<p> <span><strong>Email -</strong></span> <span class="name">' . $row[$userTblArray['email_fld']] . '</span> </p>';
            $records = $records . '<p> <span><strong>Country -</strong> </span> <span class="name">' . $row[$userTblArray['country_fld']] . '</span> </p>';
            $records = $records . '<p> <span><strong>Date Joined -</strong> </span> <span class="date">' . $row[$userTblArray['date_added_fld']] . '</span> </p>';
            $records = $records . '<div style="clear:both"></div>' . '</div>';
            $records = $records . '</div></div>';
            $records = $records . '</a>';
        }
        echo $records . '<link href="' . BASE_URL . 'star-rating/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
		 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="' . BASE_URL . 'star-rating/star-rating.js" type="text/javascript"></script>';
    } else {
        echo '<div  id="error_div"> Users Not Available. </div>';
    }
}
/* * ********************************************* */
/* * ********Project Pagination Function********** */
/* * ********************************************* */

function pagination($projectTblArray, $userTblArray, $query, $per_page = 10, $page = 1, $url = '?') {
    // echo "<br><br><br><br><br><br><br><br><br><br>Name ".$projectTblArray['tableAlias'];
    $query = "SELECT COUNT(*) as num FROM {$projectTblArray['tableAlias']} where {$projectTblArray['isLive_fld']}=1";
    $result = mysql_query($query);
    if (!$result) {
        echo 'Error ';
        exit();
    }
    while ($row = mysql_fetch_array($result)) {
        $total = $row['num'];
    }
    $adjacents = "2";
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;
    $firstPage = 1;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details'>" . PAGE . " $page " . OF . " $lastpage</li>";
        $prev = ($page == 1) ? 1 : $page - 1;
        //$pagination = '';
        if ($page == 1) {
            $pagination .= "<li><a class='current' title='current'>" . FIRST . "</a></li>";
            $pagination .= "<li><a class='current' title='current'>" . PREV . "</a></li>";
        } else {
            $pagination .= "<li><a href='{$url}page=$firstPage' title='$firstPage'>" . FIRST . "</a></li>";
            $pagination .= "<li><a href='{$url}page=$prev' title='$prev'>" . PREV . "</a></li>";
        }
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                else
                    $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } else {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
            }
        }
        if ($page < $counter - 1) {
            $pagination .= "<li><a href='{$url}page=$next' title='$next'>" . NEXT . "</a></li>";
            $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>" . LAST . "</a></li>";
        } else {
            $pagination .= "<li><a class='current' title='current'>" . NEXT . "</a></li>";
            $pagination .= "<li><a class='current' title='current'>" . LAST . "</a></li>";
        }
        $pagination .= "</ul>\n";
    }
    return $pagination;
}

/* * ********************************************* */
/* * *****code to search pagination links********* */
/* * *********************************************

  if(isset($_GET["action"]) && $_GET["action"]=="search_pagination" && isset($_GET['query']) && isset($_GET['per_page'])){

  $query = $_GET['query'];

  $per_page = $_GET['per_page'];

  $page = $_GET['page'];



  $uname = $_GET['user_name'];

  $location = $_GET['location'];

  $relevant = $_GET['joined'];

  $state = $_GET['state'];

  $country = $_GET['country'];



  echo search_pagination($query, $per_page, $page, $uname, $location, $relevant, $state, $country);

  }



 */
/* * ********************************************* */
/* * *****code to search projects links********* */
/* * ******************************************** */
if (isset($_GET["action"]) && $_GET["action"] == "search_all_projects") {
    $projectName = $_GET['projectName'];
    $category = $_GET['category'];
    $created = $_GET['created'];
    $projectDesc = $_GET['projectDesc'];
    echo searchProjects($projectTblArray, $userTblArray, $projectName, $category, $created, $projectDesc);
}
/* * ******************************************* */
/* * ***code for Searching Users********* */
/* * ******************************************* */
if (isset($_GET['search_startpoint'])) {
    $records = '<h2>Popular Users</h2>';
    $statement = $_GET['query'];
    $startpoint = $_GET['search_startpoint'];
    $limit = $_GET['limit'];
    $uname = $_GET['user_name'];
    $location = $_GET['location'];
    $relevant = $_GET['joined'];
    $state = $_GET['state'];
    $country = $_GET['country'];
    $query = "SELECT * FROM {$userTblArray['table_alias']}";
    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $query = "SELECT * FROM {$userTblArray['database_table_name']}";



    $conditions = array();
    if ($uname != "") {
        $conditions[] = "{$userTblArray['uname_fld']} LIKE '$uname%'";
    }
    if ($location != "") {
        $conditions[] = "{$userTblArray['city_fld']} LIKE '$location%'";
    }
    if ($state != "") {
        $conditions[] = "{$userTblArray['state_fld']} LIKE '$state%'";
    }
    if ($country != "") {
        $conditions[] = "{$userTblArray['country_fld']} LIKE '$country%'";
    }
    if ($relevant != "") {
        $conditions[] = "{$userTblArray['date_added_fld']} > DATE_SUB(NOW(), INTERVAL 1 " . $relevant . ")";
    }
    $sql = $query;
    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }
    //echo $sql;exit;
    $result = mysql_query($sql);
    //show records
    //$query = "SELECT * FROM users where isActive=1 LIMIT $startpoint , $limit";
    //mysql_real_escape_string($query);
    //$result = mysql_query($query);
    if (!$result) {
        echo 'Error ';
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'userDetails.php?uid=' . $row[$userTblArray['uid_fld']] . '">';
            $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
            $records = $records . '<div class="project-detail">';
            $records = $records . '<span class="profile-image">';
            if ($row[$userTblArray['image_fld']] != "") {
                $records = $records . "<img src='" . BASE_URL . "users_uploads/" . $row[$userTblArray['image_fld']] . "' alt='' class='img-responsive'>";
            } else {
                $records = $records . "<img src='" . BASE_URL . "users_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
            }
            $records = $records . '</span>';
            $records = $records . '<div class="project-info">';
            $records = $records . '<h3>' . $row[$userTblArray['uname_fld']] . '</h3>';
            $records = $records . '<p> <span><strong>Country -</strong> </span> <span class="name">' . $row[$userTblArray['country_fld']] . '</span> </p>';
            $records = $records . '<p> <span><strong>Date Joined -</strong> </span> <span class="date">' . $row[$userTblArray['date_added_fld']] . '</span> </p>';
            $records = $records . '<div style="clear:both"></div>' . '</div>';
            $records = $records . '</div></div>';
            $records = $records . '</a>';
        }
        echo $records;
    } else {
        echo '<div  id="error_div"> Users Not Available. </div>';
    }
}
/* * ************************************ */
/* * ********Users Pagination Function********** */
/* * ************************************ */

function paginationUsers($projectTblArray, $userTblArray, $query, $per_page = 10, $page = 1, $url = '?') {
    $query = "SELECT COUNT(*) as num FROM {$userTblArray['table_alias']} where {$userTblArray['isActive_fld']}=1";
    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $query = "SELECT COUNT(*) as num FROM {$userTblArray['database_table_name']} where {$userTblArray['isActive_fld']}=1";


    $result = mysql_query($query);
    if (!$result) {
        echo 'Error ';
        exit();
    }
    while ($row = mysql_fetch_array($result)) {
        $total = $row['num'];
    }
    $adjacents = "2";
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;
    $firstPage = 1;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details'>Page $page of $lastpage</li>";
        $prev = ($page == 1) ? 1 : $page - 1;
        //$pagination = '';
        if ($page == 1) {
            $pagination .= "<li><a class='current' title='current'>First</a></li>";
            $pagination .= "<li><a class='current' title='current'>Prev</a></li>";
        } else {
            $pagination .= "<li><a href='{$url}page=$firstPage' title='$firstPage'>First</a></li>";
            $pagination .= "<li><a href='{$url}page=$prev' title='$prev'>Prev</a></li>";
        }
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                else
                    $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } else {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
            }
        }
        if ($page < $counter - 1) {
            $pagination .= "<li><a href='{$url}page=$next' title='$next'>Next</a></li>";
            $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>Last</a></li>";
        } else {
            $pagination .= "<li><a class='current' title='current'>Next</a></li>";
            $pagination .= "<li><a class='current' title='current'>Last</a></li>";
        }
        $pagination .= "</ul>\n";
    }
    return $pagination;
}

/* * ************************************ */
/* * ********Search project Function** */
/* * ************************************ */

function search_pagination($query, $per_page = 10, $page = 1, $uname, $location, $relevant, $state, $country, $url = '?') {
    $query = "SELECT COUNT(*) as num FROM {$userTblArray['table_alias']} where ";
    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $query = "SELECT COUNT(*) as num FROM {$userTblArray['database_table_name']} where ";


    $conditions = array();
    $conditions[] = "{$userTblArray['isActive_fld']} = 1";
    if ($uname != "") {
        $conditions[] = "{$userTblArray['uname_fld']} LIKE '$uname%'";
    }
    if ($location != "") {
        $conditions[] = "{$userTblArray['city_fld']} LIKE '$location%'";
    }
    if ($state != "") {
        $conditions[] = "{$userTblArray['state_fld']} LIKE '$state%'";
    }
    if ($country != "") {
        $conditions[] = "{$userTblArray['country_fld']} LIKE '$country%'";
    }
    if ($relevant != "") {
        $conditions[] = "{$userTblArray['date_added_fld']} > DATE_SUB(NOW(), INTERVAL 1 " . $relevant . ")";
    }
    $sql = $query;
    if (count($conditions) > 0) {
        $sql .= implode(' AND ', $conditions);
    }
    $result = mysql_query($sql);
    if (!$result) {
        echo 'Error ';
        exit();
    }
    while ($row = mysql_fetch_array($result)) {
        $total = $row['num'];
    }
    $adjacents = "2";
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;
    $firstPage = 1;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='details'>Page $page of $lastpage</li>";
        $prev = ($page == 1) ? 1 : $page - 1;
        //$pagination = '';
        if ($page == 1) {
            $pagination .= "<li><a class='current' title='current'>First</a></li>";
            $pagination .= "<li><a class='current' title='current'>Prev</a></li>";
        } else {
            $pagination .= "<li><a href='{$url}page=$firstPage' title='$firstPage'>First</a></li>";
            $pagination .= "<li><a href='{$url}page=$prev' title='$prev'>Prev</a></li>";
        }
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                else
                    $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>...</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
                $pagination .= "<li class='dot'>..</li>";
                $pagination .= "<li><a href='{$url}page=$lpm1'  title='$lpm1'>$lpm1</a></li>";
                $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>$lastpage</a></li>";
            } else {
                $pagination .= "<li><a href='{$url}page=1' title='1'>1</a></li>";
                $pagination .= "<li><a href='{$url}page=2' title='2'>2</a></li>";
                $pagination .= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current' title='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter' title='$counter'>$counter</a></li>";
                }
            }
        }
        if ($page < $counter - 1) {
            $pagination .= "<li><a href='{$url}page=$next' title='$next'>Next</a></li>";
            $pagination .= "<li><a href='{$url}page=$lastpage' title='$lastpage'>Last</a></li>";
        } else {
            $pagination .= "<li><a class='current' title='current'>Next</a></li>";
            $pagination .= "<li><a class='current' title='current'>Last</a></li>";
        }
        $pagination .= "</ul>\n";
    }
    return $pagination;
}

/* * ************************************ */
/* * ********SEARCH PROJECTS FUNCTIONS** */
/* * ************************************ */

function searchProjects($projectTblArray, $userTblArray, $projectName, $category, $created, $projectDesc) {
    //echo "<br><br><br><br><br><br><br><br><br><br><br>NAME{$projectTblArray['tableAlias']}";
    //exit;
    $query = "SELECT * FROM {$projectTblArray['tableAlias']}";
    // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_name (the key field in DD) ...
    // temporarily changing to ...
    $query = "SELECT * FROM {$projectTblArray['database_table_name']}";


    $conditions = array();
    if ($projectName != "") {
        $conditions[] = "{$projectTblArray['pname_fld']} LIKE '$projectName%'";
    }
    if ($category != "") {
        $conditions[] = "{$projectTblArray['cid_fld']} = $category";
    }
    if ($created != "") {
        $conditions[] = "{$projectTblArray['create_date_fld']} > DATE_SUB(NOW(), INTERVAL 1 " . $created . ")";
    }
    if ($projectDesc != "") {
        $conditions[] = "MATCH({$projectTblArray['description_fld']}) AGAINST('$projectDesc' IN BOOLEAN MODE ) ";
    }
    $sql = $query . "  WHERE {$projectTblArray['isLive_fld']}=1 and {$projectTblArray['isBought_fld']}=0 ";
    if (count($conditions) > 0) {
        $sql .= " AND " . implode(' AND ', $conditions);
    }
    //echo "<br><br><br><br><br>$sql";
    $records = '<h2>' . POPULAR_PROJECTS . '</h2>';
    //show records
    mysql_real_escape_string($sql);
    $result = mysql_query($sql);
    if (!$result) {
        echo 'Error ';
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'projectDetails.php?pid=' . $row[$projectTblArray['pid_fld']] . '">';
            $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
            $records = $records . '<div class="project-detail">';
            $records = $records . '<span class="profile-image">';
            if ($row[$projectTblArray['projectImage_fld']] != "") {
                $records = $records . "<img src='" . BASE_URL . "project_uploads/thumbs/" . $row[$projectTblArray['projectImage_fld']] . "' alt='' class='img-responsive'>";
            } else {
                $records = $records . "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
            }
            $records = $records . '</span>';
            $records = $records . '<div class="project-info">';
            $records = $records . '<h3>' . $row[$projectTblArray['pname_fld']] . '</h3>';
            $records = $records . '<p> <span><strong>' . CREATED . ' -</strong> </span> <span class="date">' . $row[$projectTblArray['create_date_fld']] . '</span> </p>';
            $records = $records . '<div style="clear:both"></div>' . '</div>';
            $records = $records . '</div></div>';
            $records = $records . '</a>';
        }
        echo $records;
    } else {
        echo '<div  id="error_div"> ' . PROJECTS_NOT_AVAILABEL . ' </div>';
    }
}

/* * **************************************** */
/* * ********SEARCH TRANSACTION FUNCTIONS**** */
/* * **************************************** */
if (isset($_GET['action']) && $_GET['action'] == "search_transactions") {
    //echo "searchTransactions i sbeing called";
    //exit;
    $projectName = $_REQUEST["projectName"];
    $created = $_REQUEST["created"];
    $category = $_REQUEST["category"];
    $projectDesc = $_REQUEST["projectDesc"];
    $query = "SELECT * FROM {$projectTblArray['tableAlias']}";
    $conditions = array();
    if ($projectName != "") {
        $conditions[] = "{$projectTblArray['pname_fld']} LIKE '$projectName%'";
    }
    if ($category != "") {
        $conditions[] = "{$projectTblArray['cid_fld']} = $category";
    }
    if ($created != "") {
        $conditions[] = "{$projectTblArray['create_date_fld']} > DATE_SUB(NOW(), INTERVAL 1 " . $created . ")";
    }
    if ($projectDesc != "") {
        $conditions[] = "MATCH({$projectTblArray['description_fld']}) AGAINST('$projectDesc' IN BOOLEAN MODE ) ";
    }
    $sql = $query . "  WHERE {$projectTblArray['isLive_fld']}=1 and {$projectTblArray['isBought_fld']}=0 ";
    if (count($conditions) > 0) {
        // echo "INSIDE";
        $sql .= " AND " . implode(' AND ', $conditions);
    }
    $records = '<h2>' . POPULAR_PROJECTS . '</h2>';
    //show records
    //echo $sql;
    mysql_real_escape_string($sql);
    $result = mysql_query($sql);
    if (!$result) {
        echo 'Error ';
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $records = $records . '<a class="project-details-wrapper" href="' . BASE_URL_SYSTEM . 'projectDetails.php?pid=' . $row[$projectTblArray['pid_fld']] . '">';
            $records = $records . '<div class="col-6 col-sm-6 col-lg-3" data-scroll-reveal="enter bottom over 1s and move 100px">';
            $records = $records . '<div class="project-detail">';
            $records = $records . '<span class="profile-image">';
            if ($row[$projectTblArray['projectImage_fld']] != "") {
                $records = $records . "<img src='" . $BASE_URL . "project_uploads/thumbs/" . $row[$projectTblArray['projectImage_fld']] . "' alt='' class='img-responsive'>";
            } else {
                $records = $records . "<img src='" . BASE_URL . "project_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
            }
            $records = $records . '</span>';
            $records = $records . '<div class="project-info">';
            $records = $records . '<h3>' . $row[$projectTblArray['pname_fld']] . '</h3>';
            $records = $records . '<p> <span><strong>' . CREATED . ' -</strong> </span> <span class="date">' . $row[$projectTblArray['create_date_fld']] . '</span> </p>';
            $records = $records . '<div style="clear:both"></div>' . '</div>';
            $records = $records . '</div></div>';
            $records = $records . '</a>';
        }
        echo $records;
    } else {
        echo '<div  id="error_div"> ' . PROJECTS_NOT_AVAILABEL . ' </div>';
    }
}

/* * ************************************************ */
/* * ********Relationship(like, follow) FUNCTIONS**** */
/* * ************************************************ */
if (isset($_POST['relationship_action']) && $_POST['relationship_action'] == "like") {
    if (!isUserLoggedin()) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $action = $_POST['relationship_action'];
    $target_user = $_POST['userLiked'];

    $relation = new RelationshipManagement($action, $userId, $target_user);
    echo $relation->likeUser();
}

if (isset($_POST['relationship_action']) && $_POST['relationship_action'] == "unLikeUser") {
    if (!isUserLoggedin()) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $action = $_POST['relationship_action'];
    $target_user = $_POST['userLiked'];

    $relation = new RelationshipManagement();
    echo $relation->unLikeUser($userId, $target_user);
}

if (isset($_POST['relationship_action']) && $_POST['relationship_action'] == "project_like") {
    if (!isUserLoggedin) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $action = $_POST['relationship_action'];
    $target_project = $_POST['projectLiked'];

    $relation = new RelationshipManagement();
    echo $relation->likeProject($target_project, $userId);
}


//Like a project
if (isset($_POST['projectLiked']) && $_POST['projectLiked'] != "") {
    if (!isUserLoggedin) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $action = $_POST['action'];
    $target_project = $_POST['projectLiked'];

    $relation = new RelationshipManagement();
    if ($action == '1') {
        echo $relation->unLikeProject($target_project, $userId);
    }
    if ($action == '0') {
        echo $relation->likeProject($target_project, $userId);
    }
}

if (isset($_POST['userFollowed']) && $_POST['userFollowed'] != "") {
    //echo "from ajax actions";exit;
    if (!isUserLoggedin) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $target_user = $_POST['userFollowed'];

    $relation = new RelationshipManagement();
    if ($_POST['relationship_action'] == '0') {
        echo $relation->followUser($target_user, $userId);
    }
    if ($_POST['relationship_action'] == '1') {
        echo $relation->unFollowUser($target_user, $userId);
    }
}

if (isset($_POST['projectSubscribe']) && $_POST['projectSubscribe'] != "") {
    //echo "from ajax actions";exit;
    if (!isUserLoggedin) {
        echo false;
        exit;
    }

    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
    $projectId = $_POST['projectSubscribe'];

    $relation = new RelationshipManagement();
    if ($_POST['relationship_action'] == '0') {
        echo $relation->subscribeProject($userId, $projectId);
    }
    if ($_POST['relationship_action'] == '1') {
        echo $relation->unSubscribeProject($userId, $projectId);
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

    if (!empty($imageInfo)) {

        update($_SESSION['update_table2']['database_table_name'], array($fieldName => $imageInfo['image']), array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2'] = $_SESSION['search_id']));
    } else {
        exit('notSaved');
    }
}


/* * ********************************* */
/* * ********Remove Image******************* */
/* * ********************************* */
if (!empty($_GET["check_action"]) && $_GET["check_action"] == 'image_delete') {

    $fieldName = $_GET['fieldName'];

    $row = getWhere($_SESSION['update_table2']['database_table_name'], array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2'] = $_SESSION['search_id']));

    $fileName = $row[0][$fieldName];


    if ($fileName != "") {
        if (file_exists("../users_uploads/" . $fileName)) {
            unlink("../users_uploads/" . $fileName);
        }
    }


    $check = update($_SESSION['update_table2']['database_table_name'], array($fieldName => ''), array($_SESSION['update_table2']['parent_key'] => $_SESSION['search_id2'] = $_SESSION['search_id']));

    if (!$check)
        exit('notDeleted');
}
?>
