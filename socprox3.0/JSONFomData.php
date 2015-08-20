<?php
$jsonurl=$_REQUEST['jsonurl'];
 $uUsers = file_get_contents($jsonurl);
 echo "<div id='jsondata' style='float:left;width:100%;' class='rounded-corners'>";
 echo $uUsers;
 echo "</div>";
?>