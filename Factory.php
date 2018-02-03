<?php

include("Helpers/SQLHelper.php");
class Factory
{
    //Helpers
    private $oSQLHelper;

    function SQLHelper(){
        if(empty($this->oSQLHelper)){
            return $this->oSQLHelper = new SQLHelper();
        }
        else{
            return $this->oSQLHelper;
        }
    }
}