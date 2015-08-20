<?php

require_once $appRoot.'Classes/User.php';
require_once $appRoot.'Classes/ChallengeInstance.php';
require_once $appRoot.'Database/DB_Controller.php';

class ExpireChallengeInstance {

    private static $m_oChallengeInstance;

    // RETURN NUMBERS:
    //  0 = good!
    // -1 = No challenge associated with this id
    // -2 =
    // -3 =

    public static function Execute ($challengeInstance){
        //TODO: Pass in the player who called this function, and see if they are associated with the challengeInstance
        //      If they are associated update the challenge, otherwise return a 403 (not allowed)
        self::$m_oChallengeInstance = DB_Controller::GetChallengeInstByID($challengeInstance);
        if (!isset(self::$m_oChallengeInstance))
            return -1;
        self::$m_oChallengeInstance->Status("expired");
        DB_Controller::UpdateChallengeInstance(self::$m_oChallengeInstance);

        DB_Controller::Log("ChallengeInstanceId = ". self::$m_oChallengeInstance->ID(),"Expired challenge instance");
        return 0;
    }
} 