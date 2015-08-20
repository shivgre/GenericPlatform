<?php
//error_reporting(E_ERROR | E_PARSE);
session_start();
include_once("../includes/config.php");
include_once("../language/".$_SESSION['lang'].".php");
include_once("../includes/db.php");
include_once("../includes/functions.php");
include_once("transactionUtilityFunctions.php");

/**************************************************************/
/***********Buy A Project**************************************/
/**************************************************************/
if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="buyProject"){

	if(!isUserLoggedin()){
		FlashMessage::add(LOGIN_TO_BUY);
		echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."login.php'>";
		exit();
	}
	
	//This is for first record inserted manually for testing wallet transaction please remove after completing the test
	if(isFirstWallet()){
		$dsql = "INSERT INTO wallet(credit, wallet_balance, user_id, wallet_date) values('100000','100000', 17, '".date("Y-m-d H:i:s")."' )";
	    mysql_query($dsql);
	}
	
	$sql = "SELECT * FROM projects WHERE pid=".$_GET['pid'];
	$result = mysql_query($sql);
	$project = mysql_fetch_array($result);
	
	/***********CHECK VERTUAL MONEY AVAILABLE**********************/
	$wallet  = getCurrentUserWallet();
	
	if($wallet >=  $project['amount']){
		/***********START TRANSACTION HERE*******************************/		
		$transaction['user_id'] = $_SESSION['uid']; //Current User Id
		$transaction['owner_id'] = $project['uid']; //Project Owner Id
		$transaction['project_id'] = $project['pid'];
		$transaction['amount'] = $project['amount'];
		$transaction['username'] = $_SESSION['uname'];
		$transaction['useremail'] = $_SESSION['email'];
		$transaction['transaction_datetime'] = date("Y-m-d H:i:s");
		
		
		$tSql = "INSERT INTO  transactions(user_id ,owner_id, project_id ,amount ,username ,useremail ,transaction_datetime) VALUES (".$transaction['user_id'].", ".$transaction['owner_id'].",  ".$transaction['project_id'].",  '".$transaction['amount']."',  '".$transaction['username']."',  '".$transaction['useremail']."', '".$transaction['transaction_datetime']."')";
			
		mysql_query($tSql);
		$transactionId = mysql_insert_id();
		
		if($transactionId > 0){
			/***********UPDATE PROJECT TABLE********************************/
			$psql = "UPDATE projects SET quantity = ".($project['quantity']-1)." WHERE pid=".$project['pid'];
			
			mysql_query($psql);
			/***********CLOSE UPDATE PROJECT TABLE**************************/
			
			/***********UPDATE USER WALLET( DEBIT BALLENCE )****************/
			$debit_flag = debitCurrentUserWallet($transactionId, $wallet, $transaction['amount'], $transaction['user_id']);
			/***********CLOSE UPDATE USER WALLET( DEBIT BALLENCE )**********/
			
			/***********UPDATE OWNER WALLET( CREDIT BALLENCE )***************/
			$owner_wallet = getUserWalletBalance($transaction['owner_id']);
			$credit_flag = creditCurrentUserWallet($transactionId, $owner_wallet, $transaction['amount'], $transaction['owner_id']);
			/***********CLOSE UPDATE OWNER WALLET( CREDIT BALLENCE )*********/
						
			if($debit_flag !== FALSE && $credit_flag !== FALSE){
			
				$current_date = date("Y-m-d h:i:s");
				
				$psql = "SELECT * FROM transactions WHERE project_id=".$_GET['pid']." and user_id=".$_SESSION['uid'];
				$presult = mysql_query($psql);
				$aeProject = mysql_fetch_array($presult); //Already Exsits this project for this user
				$rows = mysql_num_rows($presult);
				
				if($rows > 0){
					$tquery = "UPDATE projects SET quantity = ".($aeProject['quantity']+1)." WHERE pid=".$project['pid']." and uid=".$_SESSION['uid'];
				}
				else{	
					$tquery = "INSERT INTO projects(uid, cid, pname, description, projectImage, upload_care_img_url, create_date, expiry_date, amount, quantity, isLive, affiliation_id_1, affiliation_id_2, isBought) VALUES('".$_SESSION['uid']."', ".$project['cid'].", '".$project['pname']."', '".$project['description']."', '".$project['projectImage']."', '".$project['upload_care_img_url']."', '".$current_date."', '".$project['expiry_date']."', '".$project['amount']."', 1, ".$project['isLive'].", '".$project['affiliation_id_1']."', '".$project['affiliation_id_2']."', 1)";	
				}
				echo $tquery;
				
				$result = mysql_query($tquery);
				exit();
				$psql = "UPDATE transactions SET status = 'COMPLETE' WHERE transaction_id=".$transactionId;
				mysql_query($psql);
				
				FlashMessage::add(TRANSACTION_SUCCESS);
				echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."projectDetails.php?pid=".$_GET['pid']."'>";
				exit;				
			}
			else{
				if($debit_flag !== FALSE){
					$dsql = "delete from wallet where id = ".$debit_flag;
					mysql_query($dSql);
				}
				if($credit_flag !== FALSE){
					$csql = "delete from wallet where id = ".$credit_flag;
					mysql_query($cSql);
				}
				
				$psql = "UPDATE projects SET quantity = ".($project['quantity']+1)." WHERE pid=".$project['pid'];
				mysql_query($pSql);
				
				FlashMessage::add(TRANSACTION_FAIL);
				echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."projectDetails.php?pid=".$_GET['pid']."'>";
				exit;
			}
			
		}
		else{
			FlashMessage::add(TRANSACTION_FAIL);
			echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."projectDetails.php?pid=".$_GET['pid']."'>";
			exit;
		}		
		/***********CLOSE TRANSACTION HERE*******************************/	
	}
	else{
		FlashMessage::add(WALLET_BALANCE_ERROR);
		echo "<META http-equiv='refresh' content='0;URL=".BASE_URL."projectDetails.php?pid=".$_GET['pid']."'>";
		exit;
	}
	/***********CLOSE CHECK VERTUAL MONEY AVAILABLE**********************/
}

?>