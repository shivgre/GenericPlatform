<?php

require_once $appRoot.'Classes/User.php';
require_once $appRoot.'Classes/Stats.php';
require_once $appRoot.'Database/DB_Controller.php';

class GetUserStats {
	
	private static $m_oStats;
	
	// RETURN CODES:
	// 0  = success
	// -1 = no user
	
	public static function Execute ($macaddress){
	    // Get the user from the database
		$user = DB_Controller::GetUserByMac($macaddress);
        
        // User doesn't exist - error out
		if (!isset($user)) return -1;
        
		DB_Controller::Log($macaddress, "Get User Stats");
        
        // Get the stats object from the DB_Controller for the specified user
        self::$m_oStats = DB_Controller::GetUserStats($user);
        
        return 0;
	}
	
	public static function Stats(){
		return self::$m_oStats;
	}
}

?>