<?php
include_once("DAL.php");

class UserModel extends DAL{
	
	public static function updateUserType($userId, $userTypeId){
		$con = parent::getConnection();
		$query = "UPDATE  users SET  user_type =  '".$userTypeId."' WHERE  uid = $userId";
		$result = mysqli_query($con, $query);
		parent::closeConnection($con);
		return $result;
	}
	
	public static function updateUserTypeStatus($userId, $userTypeStatusId){
		$con = parent::getConnection();
		$query = "UPDATE  users SET  user_type_status =  '".$userTypeStatusId."' WHERE  uid = $userId";
		$result = mysqli_query($con, $query);
		parent::closeConnection($con);
		return $result;
	}
	
	public static function getUserTypeById($userTypeId){
		$con = parent::getConnection();
		$query = "select user_type from user_type where user_type_id = ".$userTypeId;
		$result = mysqli_query($con, $query);
		$data = mysqli_fetch_assoc($result);
		parent::closeConnection($con);
		return $data["user_type"];
	}
	
}

?>