<?php

require_once $appRoot.'Classes/Challenge.php';
require_once $appRoot.'Database/DB_Controller.php';

class DeleteChallenge {
	
	private static $m_oChallenge;
    public static function Execute ($challengeId)
    {	
		self::$m_oChallenge = self::prepareDeletedChallenge($challengeId);
		// Call database function
		DB_Controller::DeleteChallenge(self::$m_oChallenge);
        DB_Controller::Log("Controller", "Deleted challenge with Id = $challenge");
        return 0;
	}

	public static function Challenge(){
		return self::$m_oChallenge;
	}
	
	private function setChallenge($challenge)
	{
		self::$m_oChallenge = $challenge;
	}
	
	private static function prepareDeletedChallenge($Id){
		$challenge = new Challenge();
		$challenge->ID($Id);
		return $challenge;
	}
}