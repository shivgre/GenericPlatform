<?php
include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']."/application/config.php");
include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']."/models/GenericDBFunctions.php");

class ChildPagesActions{
	public static function getChildPagesList(){
		$data = GenericDBFunctions::getDataByTableName($GLOBALS['CHILD_FILES_TABLE_CONFIG']['tableName']);
		print_r($data);
	}
}
?>
