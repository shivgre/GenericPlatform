<?php 
include_once($GLOBALS[CONFIG_APP_DIR]);
include_once($GLOBALS[DATABASE_APP_DIR]."db.php");
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
			if(!isset($_GET['action'])){
				include_once("projectChildPages-list.php");
			}
			elseif(isset($_GET['action']) && ($_GET['action']=="create" || $_GET['action']=="update")){
				include_once("projectChildPages-form.php");
			}
			elseif(isset($_GET['action']) && $_GET['action']=="view"){
				include_once("projectChildPages-view.php");
			}
			?>
			<!-- /container -->
		</div>
	</div>
</div>
