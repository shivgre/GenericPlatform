<?php

// if you want to run cyrano database or any other database than uncomment below db_name and provide corresponding 
// credentials

$config['db_name'] = "generic";

$config['db_user'] = "root";

$config['db_password'] = "";

$config['db_host'] = "localhost";


 
 

if(!empty($config['db_name'])){
    
    $_SESSION['config'] = $config;
}else{
    
    unset($_SESSION['config']);
}

?>