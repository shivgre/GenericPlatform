<?php 
include_once($GLOBALS['CONFIG_APP_DIR']);
include_once($GLOBALS['DATABASE_APP_DIR']."db.php");
include("../special_config.php");
?>
<div class="jumbotron projects">
	<div class="container">
		<?php
		if($_SESSION['messages']!=""){
			echo "<div class='message'>
					".FlashMessage::render()."
				</div>";
		}
		?>
		<div class="row" id="pdwrapper" style="margin-left:-6%; margin-right:-6%;">
			<?php
            //include_once("userChildPages-list.php");
			if(!isset($_GET['action'])){

                //echo "userChildPages-list.php php<br>";

				include_once("userChildPages-list.php");
			}
			elseif(isset($_GET['action']) && ($_GET['action']=="create" || $_GET['action']=="update")){
                //echo "userChildPages-form.php php<br>";
				include_once("userChildPages-form.php");
			}
			elseif(isset($_GET['action']) && $_GET['action']=="view"){
                //echo "userChildPages-view.php<br>";
				include_once("userChildPages-view.php");
			}else{
                //include_once("userChildPages-list.php");
            }
			?>
			<!-- /container -->
		</div>
	</div>
</div>
