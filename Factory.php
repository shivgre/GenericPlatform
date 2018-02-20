<?php

include("Helpers/SQLHelper.php");
include("PageBuilders/NavigationPageBuilder.php");
include("PageBuilders/MainPageBuilder.php");

/* The Factory class is where objects that get reused a lot are created.
 * Generally, if the object exists we return the existing object. If it does not
 * we create the object set it to the private variable and return it.
 */
class Factory
{
    //Helpers
    private $oSQLHelper;
    private $oNavigationPageBuilder;
    private $oMainPageBuilder;

    function SQLHelper(){
        if(empty($this->oSQLHelper)){
            $this->oSQLHelper = new SQLHelper();
            return $this->oSQLHelper;
        }
        else{
            return $this->oSQLHelper;
        }
    }

    //PageBuilders Objects
    function NavigationPageBuilder(){
        if(empty($this->oNavigationPageBuilder)){
            $this->oNavigationPageBuilder = new NavigationPageBuilder();
            return $this->oNavigationPageBuilder;
        }
        else{
            return $this->oNavigationPageBuilder;
        }
    }

    function MainPageBuilder(){
        if(empty($this->oMainPageBuilder)){
            $this->oMainPageBuilder = new MainPageBuilder();
            return $this->oMainPageBuilder;
        }
        else{
            return $this->oMainPageBuilder;
        }
    }
    
    
}