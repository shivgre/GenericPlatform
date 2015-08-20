<?php
//echo "appcofig called";

ini_set('max_execution_time', 1200); //1200 seconds = 20 minutes


$GLOBALS['DEPLOY_ENV']="LOC"; // for local LOC or for production PRD or maintanance MNT
$GLOBALS['APP_TITLE']="SOC PROX DASHBOARD";
$GLOBALS['APP_NAME']="generic";
$GLOBALS['APP_ROOT']=$_SERVER['DOCUMENT_ROOT']."/".$GLOBALS['APP_NAME'];
/*$GLOBALS['REST_URL_ROOT']="http://cjcornell.com/bluegame/REST/";//"http://localhost/cjsoc/REST/";
$GLOBALS['JSON_INTERCEPT']="1"; //1 for ON JSON Interceptor 0 For JSON Interceptor OFf
$GLOBALS['REST_INTERCEPT']="1"; //1 for ON REST Interceptor 0 For REST Interceptor OFf
//$GLOBALS['REST_URL_ROOT']="http://localhost/socprox1.0/REST/";//"http://localhost/cjsoc/REST/";
$GLOBALS['APPLICATION_ROOT']="http://localhost/generic/index.php";
//$GLOBALS['REST_SYS_ROOT']="/home/content/c/j/c/cjcornell3/html/bluegame/REST/";//"http://localhost/cjsoc/REST/";
$GLOBALS['REST_SYS_ROOT']="REST/";//"http://localhost/cjsoc/REST/";
//$GLOBALS['REST_SYS_ROOT']="http://localhost/socprox1.0/REST/";
$GLOBALS['SERVER_REST_ROOT']=".//socprox/REST";*/
$GLOBALS['app_plugin_prefix']="socprox3.0/";
$GLOBALS['DEBUG']=0; // 0 for off 1 for On debug
/*if($GLOBALS['DEPLOY_ENV']=='PRD'){
	$user='User';
	$game='Game';
	$challengeinstance='ChallengeInstance';
	$payoffs='Payoffs';
	$verification='Verification';
	$challenge='Challenge';
	$gcm_users='gcm_users';
	$log='Log';
	$points='Points';
	$socialmedia='SocialMedia';
	$game='Game';
	$activity='Activity';
	$challengeacceptance='ChallengeAcceptance';
}else{
	$user='user';
	$game='game';
	$challengeinstance='challengeinstance';
	$payoffs='payoffs';
	$verification='verification';
	$challenge='challenge';
	$gcm_users='gcm_users';
	$log='log';
	$points='points';
	$socialmedia='socialmedia';
	$game='game';
	$activity='activity';
	$challengeacceptance='challengeacceptance';
}


//$GLOBALS['TABLEMAP']['activity']['ActivityID']='user';
$GLOBALS['TABLEMAP'][$activity]['UserID']="SELECT $user.Username FROM $user where $user.UserID = ";
$GLOBALS['TABLEMAP'][$activity]['GameID']="SELECT $game.Name FROM $game where $game.GameID =";
$GLOBALS['TABLEMAP'][$activity]['ChallengeInstID']="SELECT $challengeinstance.ChallengeID,$challengeinstance.UserIDs from $challengeinstance where $challengeinstance.ChallengeInstanceID = ";
$GLOBALS['TABLEMAP'][$activity]['PayoffID']="select $payoffs.Name from $payoffs where $payoffs.PayoffID =";
$GLOBALS['TABLEMAP'][$activity]['VerifiedBy']="select $user.Username FROM $user where $user.UserID = ";
$GLOBALS['TABLEMAP'][$activity]['VerifiedID']="select $verification.Name from $verification where $verification.VerificationID = ";

//$GLOBALS['TABLEMAP']['challenge']['ChallengeID']='challenge';
//$GLOBALS['TABLEMAP']['challenge']['Category_ID']='challenge';
$GLOBALS['TABLEMAP'][$challenge]['Game_ID']="select $game.name from $game where $game.GameID = ";
$GLOBALS['TABLEMAP'][$challenge]['P1_ID']="select $payoffs.name from $payoffs where $payoffs.PayoffID = ";
$GLOBALS['TABLEMAP'][$challenge]['P2_ID']="select $payoffs.name from $payoffs where $payoffs.PayoffID = ";
$GLOBALS['TABLEMAP'][$challenge]['P3_ID']="select $payoffs.name from $payoffs where $payoffs.PayoffID = ";
$GLOBALS['TABLEMAP'][$challenge]['P4_ID']="select $payoffs.name from $payoffs where $payoffs.PayoffID = ";
$GLOBALS['TABLEMAP'][$challenge]['VerificationID']="select $verification.Name from $verification where $verification.VerificationID = ";

//$GLOBALS['TABLEMAP']['challengeacceptance']['ChallengeAcceptanceID']='challenge';
$GLOBALS['TABLEMAP'][$challengeacceptance]['ChallengeInstanceID']="select $challenge.Name,$challengeinstance.UserIDs
from $challengeacceptance left join $challengeinstance on $challengeinstance.ChallengeInstanceID=$challengeacceptance.ChallengeInstanceID
left join $challenge on $challenge.ChallengeID=$challengeinstance.ChallengeID where $challengeacceptance.ChallengeInstanceID=";
$GLOBALS['TABLEMAP'][$challengeacceptance]['UserID']="SELECT $user.Username FROM $user where $user.UserID = ";

//$GLOBALS['TABLEMAP']['challengeinstance']['ChallengeInstanceID']="challenge';
$GLOBALS['TABLEMAP'][$challengeinstance]['ChallengeID']="select CONCAT($challenge.Name,' [ ',$challenge.Internal_Name,' ] ') from $challengeinstance ,$challenge where $challenge.ChallengeID=$challengeinstance.ChallengeID and $challenge.ChallengeID = ";
$GLOBALS['TABLEMAP'][$challengeinstance]['UserIDs']="";

$GLOBALS['TABLEMAP'][$game]['GameID']='';

$GLOBALS['TABLEMAP'][$gcm_users]['id']='';
//$GLOBALS['TABLEMAP']['gcm_users']['gcm_regid']="challenge';
//$GLOBALS['TABLEMAP']['gcm_users']['socproxID']='challenge';

$GLOBALS['TABLEMAP'][$log]['LogID']='';

$GLOBALS['TABLEMAP'][$payoffs]['PayoffID']='';


$GLOBALS['TABLEMAP'][$points]['GameID']="SELECT $game.Name FROM $game where $game.GameID = ";
$GLOBALS['TABLEMAP'][$points]['UserID']="SELECT $user.Username FROM $user where $user.UserID =";

$GLOBALS['TABLEMAP'][$socialmedia]['id']='';
//$GLOBALS['TABLEMAP']['socialmedia']['userid']='challenge';


$GLOBALS['TABLEMAP'][$user]['UserID']='';

$GLOBALS['TABLEMAP'][$verification]['VerificationID']='';


/* $MAPTABLE=array();
 *
*/
/* $GLOBALS['SOURCE_TO_TARGET_KEY']['UserID']='UserID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['GameID']='GameID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['ChallengeInstID']='ChallengeInstanceID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['PayoffID']='PayoffID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['VerifiedID']='VerificationID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['ChallengeID']='ChallengeID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['ChallengeAcceptanceID']='ChallengeAcceptanceID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['LogID']='LogID';
$GLOBALS['SOURCE_TO_TARGET_KEY']['id']='id';

$GLOBALS['TABLEMAP_TO_FIELD']['UserID']=$user;
$GLOBALS['TABLEMAP_TO_FIELD']['UserIDs']=$user;
$GLOBALS['TABLEMAP_TO_FIELD']['GameID']=$game;
$GLOBALS['TABLEMAP_TO_FIELD']['ChallengeInstID']=$challengeinstance;
$GLOBALS['TABLEMAP_TO_FIELD']['ChallengeInstanceID']=$challengeinstance;
$GLOBALS['TABLEMAP_TO_FIELD']['PayoffID']=$payoffs;
$GLOBALS['TABLEMAP_TO_FIELD']['P1_ID']=$payoffs;
$GLOBALS['TABLEMAP_TO_FIELD']['P2_ID']=$payoffs;
$GLOBALS['TABLEMAP_TO_FIELD']['P3_ID']=$payoffs;
$GLOBALS['TABLEMAP_TO_FIELD']['P4_ID']=$payoffs;
$GLOBALS['TABLEMAP_TO_FIELD']['VerifiedID']=$verification;
$GLOBALS['TABLEMAP_TO_FIELD']['VerifiedBy']=$user;
$GLOBALS['TABLEMAP_TO_FIELD']['VerificationID']=$verification;
$GLOBALS['TABLEMAP_TO_FIELD']['Game_ID']=$game;

$GLOBALS['TABLEMAP_TO_FIELD']['ChallengeID']=$challenge;
$GLOBALS['TABLEMAP_TO_FIELD']['ChallengeAcceptanceID']=$challengeacceptance;
$GLOBALS['TABLEMAP_TO_FIELD']['LogID']=$log;
$GLOBALS['TABLEMAP_TO_FIELD']['id']=$socialmedia;

$GLOBALS['SORTMAP'][$activity]='Time';
$GLOBALS['SORTMAP'][$challengeinstance]='Date';
$GLOBALS['SORTMAP'][$log]='Time';*/



?>