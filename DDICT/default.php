<?php
/**
 * Created by Susmit.
 * User: Admin
 * Date: 10/19/14
 * Time: 6:07 AM
 */
$DEFAULT=array();
$DEFAULT['FD']['display_page']=1;
//$DEFAULT['tab_name']=slice_database_table_name; //special handle
$DEFAULT['visibility']=1;
$DEFAULT['privilege_level']=1;
$DEFAULT['page_num']=1;
$DEFAULT['publicdisplay']=1;

ini_set('max_execution_time', 600);

$fieldDefTABLE='field_dictionary';
$datadictTABLE='data_dictionary';

$DATA= array();
//$DEFAULT['start_page']=1;
//$DEFAULT['start_page']=1;


////////// Default Values for FD//////////////

$DEFAULT['FD']['help_message'] = '';

$DEFAULT['FD']['error_message'] = '';

$DEFAULT['FD']['format_length'] = '';

$DEFAULT['FD']['privilege_level'] = '1';

$DEFAULT['FD']['visibility'] = '1';

$DEFAULT['FD']['sub_tab_num'] = '1';

$DEFAULT['FD']['dropdown_alias'] = '';

$DEFAULT['FD']['required'] = '0';

$DEFAULT['FD']['editable'] = 'true';



//print_r($DEFAULT['FD']);

/*************
 * ***************Defaul value for DD
 */


$DEFAULT['DD']['comments'] = 'system';

$DEFAULT['DD']['change'] = 'script';


