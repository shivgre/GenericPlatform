<?php
session_start();
include_once($GLOBALS['CONFIG_APP_DIR']);

if(isset($_SESSION['lang'])){
	include_once($GLOBALS['LANGUAGE_APP_DIR'].$_SESSION['lang'].".php");
}
else{
	include_once($GLOBALS['LANGUAGE_APP_DIR']."en.php");
}

/******** Manage Custom Fields class@start *********/
class crossReferenceTables{

	 
	
	/********  Cross Refference Tables Config ********/
	private $tables = array("user_favorites" => array("tableName"=>"user_favorites", "labelName"=>"favorites", "display"=>"field1 field2", "editable"=>"field2"),
						"user_follow" => array("tableName"=>"user_follow", "labelName"=>"Follow", "display"=>"field1 field2", "editable"=>"field2"),
						"user_friends" => array("tableName"=>"user_friends", "labelName"=>"Friends", "display"=>"field1 field2", "editable"=>"field2"),
						"user_liked" => array("tableName"=>"user_liked", "labelName"=>"Likes", "display"=>"field1 field2", "editable"=>"field2"));
	
	//Get Dropdown	
	public function getUserCrossReferenceTables(){
		$cTables = array($this->tabel_config1, $this->tabel_config2);
		
		$output = "<select name = 'ctable'> <option value=''>--Select Table--</option>";
		foreach($cTables as $table){
			$output = $output . "<option value='".$table['tableName']."'>".$table['fieldName']."</option>";	
		}
		$output = $output . "</select>";
		return $output;
	
	}
	
	public function getCrossReferenceTabByName($tabName){
	$tableInfo = $this->tables[$tabName];
	echo "<pre>";
	echo "user id".$_SESSION['uid'];
	print_r($tableInfo);
	exit();
		?>
		<div class="container">

	<div class="row">
		<table class="table table-striped table-bordered table-hover">
			<tr class="tableheader">
			    <th>&nbsp;</th>
				<th>#</th>
				<th class="sortable child" data-id="0"><i class="fa fa-sort"></i>&nbsp; Name</th>
				<th class="sortable description" data-id="0"><i class="fa fa-sort"></i>&nbsp; Description</th>
				<th>Visibility</th>
				<th class="sortable copy" data-id="0"><i class="fa fa-sort"></i>&nbsp; Copy</th>
				<th class="sortable fork" data-id="0"><i class="fa fa-sort"></i>&nbsp; Fork</th>
				<th class="sortable share" data-id="0"><i class="fa fa-sort"></i>&nbsp; Share</th>
				<th class="sortable subscribe" data-id="0"><i class="fa fa-sort"></i>&nbsp; Subscribe</th>				
				<th>&nbsp;</th>
			</tr>
				
		</table>
	</div>
	<!-- /row -->
</div>

		<?php
	}	
	
}
/********Manage Custom Fields class@end *********/

?>
