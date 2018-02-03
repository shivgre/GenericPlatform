<?php
session_start();
include("Factory.php");
if(!isset($_SESSION["dbHost"])) {
    include_once "setup.php";
}

if(empty($oFactory)){
    $oFactory = new Factory();
}
$displayPage = ["display"]
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body style="padding-top:60px;">
<?php
    GenerateNavigation("home", "header", $oFactory);
    $resultArr = $oFactory->SQLHelper()->queryToDatabase("SELECT affiliation_name FROM affiliations");
    echo "<table style=\"border: 2px solid black; border-radius:5px;\"><tr><th>Name</th></tr>";
    for($i = 0; $i < count($resultArr); $i++) {
            echo "<tr><td>" . $resultArr[$i]["affiliation_name"]."</td></tr>";
        }
    echo "</table>";
?>
    </body>
</html>
<!--GenerateNavigation Function-->
<?php
/**
 * @param $displayPage
 * @param $menu_location
 * @param $oFactory
 */
function GenerateNavigation($displayPage, $menu_location, Factory $oFactory)
    {
        $BASE_URL = "http://home.localhost/GenericNew/main.php";
        ?>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container fluid">
                <div class="navbar-header">
                </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php
                                $resultArr = $oFactory->SQLHelper()->queryToDatabase("SELECT * FROM navigation where display_page='$displayPage' and item_number != 0 and menu_location='$menu_location' order by item_number ASC, dropdown_order ASC");
                                $currentIndex = 0;
                                while($currentIndex < count($resultArr)){
                                    $targetDisplayPage = $resultArr[$currentIndex]["target_display_page"];
                                    if($resultArr[$currentIndex]["dropdown_order"] == 0){ //not a drop down
                                        echo "<li><a href=" . $BASE_URL . "?display=" . $targetDisplayPage . ">" . $resultArr[$currentIndex]["item_label"] . "</a></li>";
                                        $currentIndex++;
                                    }
                                    else{ //is a drop down.
                                        echo "
                                                <li class = 'dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>" . $resultArr[$currentIndex]['item_label'] . "<span class='caret'></span></a>
                                                    <ul class='dropdown-menu'>";
                                                        $currentIndex++;
                                                        while($currentIndex < count($resultArr) && $resultArr[$currentIndex]["item_number"] == $resultArr[$currentIndex - 1]["item_number"]){
                                                            $targetDisplayPage = $resultArr[$currentIndex]["target_display_page"];
                                                            echo "<li><a href=" . $BASE_URL . "?display=" . $targetDisplayPage . ">" . $resultArr[$currentIndex]["item_label"] . "</a></li>";
                                                            $currentIndex++;
                                                        }
                                        echo"        </ul>
                                                </li>
                                                ";
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
<?php
    }
?>