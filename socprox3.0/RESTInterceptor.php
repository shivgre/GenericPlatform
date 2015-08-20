<?php
/**
 * Created by Susmit.
 * User: Susmit
 * Date: 8/23/14
 * Time: 3:32 PM
 */
function getRestFuncParamsString($RestParams){
    $RestFuncParamsString='';
    foreach ($RestParams as $k=>$v){
        $RestFuncParamsString=$RestFuncParamsString.'<~>'.$v;
    }
    return $RestFuncParamsString;
}
function getRequestFuncParamsString($RestParams){
    $RestFuncParamsString='';
    foreach ($RestParams as $k=>$v){
        $RestFuncParamsString=$RestFuncParamsString.'<~>'.$v;
    }
    return $RestFuncParamsString;
}
function getRequestFuncParamsString_inArray($RestParams){
    $RestFuncParamsString='';
    foreach ($RestParams as $k=>$v){
        $RestFuncParamsString=$RestFuncParamsString.'<~~>'.$v;
    }
    return $RestFuncParamsString;
}
function getRequestFuncParamsStringKey($RestParams){
    $RestFuncParamsString='';
    foreach ($RestParams as $k=>$v){
        $RestFuncParamsString=$RestFuncParamsString.'<~>'.$k;
    }
    return $RestFuncParamsString;
}
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
//echo "TEST 1";
//$JSON_CASE='1';
$URL= $GLOBALS['APPLICATION_ROOT'].$_SERVER['REQUEST_URI'];
$appURL=$GLOBALS['APPLICATION_ROOT'];
//print_r( $_REQUEST);
//echo "<form name='f1' id='f1' action='index.php' method='GET' >";
echo "<div class='rounded-corners'>";
echo "<h5>FORM DATA GOING TO SEND</h5><br><br>";
echo "REST Function Point ID: ". $RestFP."<br><br>";
foreach ($RestParams as $k=>$v){
    $curVal=$v;
    if($k=='pname' || $k=='json'){
        $display="readonly='readonly'";
    }else{
        $display="";
    }
    if($k=='json'){
        $curVal="json_gen1";
    }
    $URL=$URL.$v;
    echo "<div>
			<div style='float: left;padding-top: 7px;width: 30%;'>
			    ".$k."
			</div>
	        <div style='float:left;width:70%;'>
	          <input type='text' $display id='$k' name='$k' value='$curVal'>
	        </div>
	    </div>";
}
$RestFuncParamsString=getRestFuncParamsString($RestParams);
echo "<div>
         <div style='float:left;width:90%;'><B>REST FUNCTION GOING TO EXECUTE</B></div>
           <div style='float:left;width:90%;'> <br>
             <span> $RestFunctionPrint </span><br><br>
             <input name='execrest' id='execrest' type='button' onclick=\"getRestResp('$RestFP','$RestFuncParamsString');\" value='EXECUTE THIS REST'/>
           </div>
         </div>";
echo "</div>";


echo "<div id='restdata' style='float:left;width:90%;'>";

echo "</div>";
echo "<input type='hidden' name='InterceptRestResp' id='InterceptRestResp' value='NA'/>";
array_push($CompeteRestFP,$RestFP);
$completedRFP=getRequestFuncParamsString_inArray($CompeteRestFP);
$_REQUEST['CompleteRestFP']=$completedRFP;
//$_REQUEST['InterceptRestResp']='';
$_REQUEST['RESTCALL']='rest_gen';
$requestParamStrKey=getRequestFuncParamsStringKey($_REQUEST);
echo "<input type='hidden' name='requestParamStrKey' id='requestParamStrKey' value=\"$requestParamStrKey\"/>";
$requestParamStrVal=getRequestFuncParamsString($_REQUEST);
echo "<input type='hidden' name='requestParamStrVal' id='requestParamStrVal' value=\"$requestParamStrVal\"/>";
echo "<input type='hidden' name='CompleteRestFP' id='CompleteRestFP' value=\"$completedRFP\"/>";
echo "<input type='submit' id='nextSimu' name='nextSimu' onclick='goNextSimulation();'  value='GO SIMULATION'/>";
//echo "</form>";
exit;

