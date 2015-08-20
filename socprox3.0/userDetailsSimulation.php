<?php
// Database connection
require 'config/dbConfig.php';

// Common Functions addition
require 'lib/functions/appFunctions.php';
$mac='';
if(isset($_REQUEST['mac'])){
    $mac=$_REQUEST['mac'];
}
$sql="select UserID,Username,MAC_Address,Name,Total_Points from $user where MAC_Address in ('$mac')";

$sqlChallengeTaken="select group_concat(distinct(c.Name ) SEPARATOR '<br>') as Challenges_done from $challenge c,$activity ac, $challengeinstance ci where
ac.ChallengeInstID=ci.ChallengeInstanceID and ci.ChallengeID=c.ChallengeID and ac.UserID=";

$sqlGamesPlayed="select group_concat(distinct(g.Name) SEPARATOR '<br>') as Games_Played from $activity ac,$challengeinstance ci,$challenge c,$game g,$user as u
where ci.ChallengeInstanceID=ac.ChallengeInstID and u.UserID=ac.UserID and c.ChallengeID=ci.ChallengeID and g.GameID=c.Game_ID and ac.UserID=";

$rows=getResultSets($config,$sql);
//$challangeTake=getChallengeTakeFromUser($config,$rows[0][0]);
//print_r($rows);

echo "<div class='rounded-corners' style=\"width: 90% ! important;\">
<div class='element-wrap'> <u> Player Summary  </u> </div>
<table class='listings' id='simulation'>
    <thead><tr><th >UserID</th><th >User Name</th> <th >MAC Address</th><th >Name</th><th >Total Points</th>
    <th >Challenges Taken</th><th >Game Played</th></thead><tbody>
    ";
    $i=0;
    $recCount=count($rows);
    while ($recCount > $i){
    echo "<tr>";
        $j=0;
        $tdCount=count($rows[$i]);
        while ($tdCount> $j){
        echo "<td>".$rows[$i][$j]."</td>";

        if($j==4){
        //echo "TEST";
        echo "<td>".getChallengeTakeFromUser($config,$rows[$i]['0'],$sqlChallengeTaken)."</td>";
        echo "<td>".getGamesPlayFromUser($config,$rows[$i]['0'],$sqlGamesPlayed)."</td>";
        }
        $j++;
        }
        echo "</tr>";
    $i++;
    }
    echo "</tbody></table>	  </div>";

?>