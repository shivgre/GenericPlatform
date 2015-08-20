<?php
require 'config/dbConfig.php';
$restRoot=$GLOBALS["REST_SYS_ROOT"];
require $restRoot.'testcontroller.php';
function getRestFuncParamsArray($RestFuncParamsString){
    $RestFuncParamsArray=explode('<~>',$RestFuncParamsString);
    $i=0;
    foreach($RestFuncParamsArray as $k=>$v){
        if($v!=null || $v!=''){
            $newArray[$i]=$v;
            $i++;
        }
    }
    return $newArray;
}
//$fnc='updateChallenge';
$RestFP=$_REQUEST['RestFP'];
echo("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> Execution result of REST Function Point :" .$RestFP."</b>");

//$RestFP='TestController::updateChallenge("00:11:22:33:44:55", "521"," accepted");';
$RestParams=$_REQUEST['RestParams'];//$RestFP','$RestParams
$RestParams=getRestFuncParamsArray($RestParams);
//print_r($RestParams);
//$tc=new TestController('TestController::updateChallenge');
// $uUsers = file_get_contents($jsonurl);
 echo "<div id='jsondata' style='float:left;width:100%;' class='rounded-corners'>";

switch($RestFP){
    case 'RFP_1':
        $returnResp=TestController::updateChallenge($RestParams[0],$RestParams[1],$RestParams[2]);
       // var_dump($returnResp);
        echo $returnResp="RESP[$returnResp->code]RESP";

        break;
    case 'RFP_2':
        $returnResp=TestController::updateChallenge($RestParams[0],$RestParams[1],$RestParams[2]);

      //  var_dump($returnResp);
        echo $returnResp="RESP[$returnResp->code]RESP";
        break;
    case 'RFP_3':
        $returnResp=TestController::simChallenge($RestParams[0],$RestParams[1], $RestParams[2]);

      //  var_dump($returnResp);
        echo $returnResp="RESP[$returnResp]RESP";
        break;
    case 'RFP_4':
        $returnResp= TestController::updateChallenge($RestParams[0],$RestParams[1], $RestParams[2]);

     //   var_dump($returnResp);
        echo $returnResp="RESP[$returnResp->code]RESP";
        break;
    case 'RFP_5':
        $returnResp= TestController::updateChallenge($RestParams[0],$RestParams[1], $RestParams[2]);

      //  var_dump($returnResp);
        echo $returnResp="RESP[$returnResp->code]RESP";
        break;
    case 'RFP_6':
        $returnResp= TestController::verifyChallenge($RestParams[0],$RestParams[1], $RestParams[2]);

     //   var_dump($returnResp);
        echo $returnResp="RESP[$returnResp]RESP";
        break;
    case 'RFP_7':
        $returnResp= TestController::verifyChallenge($RestParams[0],$RestParams[1], $RestParams[2]);

   ///     var_dump($returnResp);
        echo $returnResp="RESP[$returnResp]RESP";
        break;
   /* case '':
        break;*/
}



    //call_user_func($tc("00:11:22:33:44:55", "521","accepted"));
 echo "</div>";
?>