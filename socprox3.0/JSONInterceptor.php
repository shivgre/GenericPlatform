<?php
/**
 * Created by Susmit.
 * User: Susmit
 * Date: 8/23/14
 * Time: 3:32 PM
 */
function buidForm($submitURL,$jsonRequest){
	
}
//echo "TEST 12";
//$JSON_CASE='1';
socx_debug('JIntercep called');
socx_debug($json_pop);
$URL= $GLOBALS['APPLICATION_ROOT'].$_SERVER['REQUEST_URI'];
$appURL=$GLOBALS['APPLICATION_ROOT'];
//print_r( $_REQUEST);
echo "<form name='f1' id='f1' action='$appURL' method='GET'>";
echo "<div class='rounded-corners'>";
echo "<h5>FORM DATA GOING TO SEND</h5><br><br>";
foreach ($_REQUEST as $k=>$v){
	$curVal=$_REQUEST[$k];
	if($k=='pname' || $k=='json' || $k=='RESTCALL'){
		$display="readonly='readonly'";
    }else{
		$display="";
	}
	if($k=='json'){
		$curVal="json_gen1";
	}
	$URL=$URL.$v;
	echo "<div>
	        <div style='float:left;width:20%;'>
			    ".$k."
			</div>
	        <div style='float:left;width:70%;'> 
	            <input type='text' $display id='$k' name='$k' value='$curVal'>
	        </div>
	    </div>";
	
}

echo "<div> 
         <div style='float:left;width:90%;'><B>JSON URL GOING TO EXECUTE</B></div>
           <div style='float:left;width:90%;'> <br>
             <input type='text' id='JSON_URL' name='JSON_URL' value='$JSON_URL'><br>
             <input type='button' onclick=\"getJsonResp('$JSON_URL');\" value='EXECUTE THIS JSON'/>
           </div>
         </div>";
echo "</div>";
echo "<div id='jsondata' style='float:left;width:90%;'></div>";
/* switch ($JSON_CASE){
	case '1':
	echo $JSON_URL;
	break;
	case '2':
		echo $JSON_URL;
	break;
	case '3':
		echo $_SERVER['REQUEST_URI'];
	break;
	case '4':
		echo $_SERVER['REQUEST_URI'];
	break;
} */
echo "<input type='submit'  value='GO SIMULATION'/></div>";
echo "</form>";
exit;
?>
