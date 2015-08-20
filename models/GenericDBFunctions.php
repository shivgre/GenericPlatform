<?php
session_start();
include_once("DAL.php");



class GenericDBFunctions extends DAL{

	public static function getDataByTableName($tableName){
		$con = parent::getConnection();
		$sql = "SELECT * from $tableName";
		$result = mysqli_query($con, $sql);
		$data = array();
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
    public static function getMaxTabByTableName($datadictTABLE,$tableAlias){
        $con = parent::getConnection();
        $sql = "SELECT `tab_num` from $datadictTABLE where `table_alias`='$tableAlias'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row["tab_num"];
    }

	public static function getTableColumnNames($tableAlias){
    $con = parent::getConnection();
    $result = mysqli_query($con, "SELECT `COLUMN_NAME` FROM INFORMATION_SCHEMA.COLUMNS WHERE table_alias = '$tableAlias'");
    $data = array();
    while($row = mysqli_fetch_array($result)){
        $data[] = $row[0];
    }
    return $data;
}
    public static function getListParams($tableAlias){
        $con = parent::getConnection();
        $result = mysqli_query($con, "SELECT `list_views`,`list_filter`,`list_sort`,`list_fields` FROM  data_dictionary WHERE table_alias = '$tableAlias' limit 1");
        $listParam = array();
        while($row = mysqli_fetch_array($result)){
            $listParam['list_view'] = $row['list_views'];
            $listParam['list_filter']=$row['list_filter'];
            $listParam['list_sort']=$row['list_sort'];
            $listParam['list_field']=$row['list_fields'];
        }
        return $listParam;
    }

	public static function getMaxColumnsByTableName($tableALias){
		$con = parent::getConnection();
		$sql = "select count(*) as maxColumns from field_definition as fd where fd.table_alias='".$tableAlias."'";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row["maxColumns"];
	}
    public static function get_DD_table_alias($tableType){
        $con = parent::getConnection();
        $sql = "select `table_alias` from data_dictionary as dd where dd.table_type='$tableType' and (`parent_table` is null or
        `parent_table`='')";

        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if($row["table_alias"]!=null or $row["table_alias"]!="")
            return $row["table_alias"];
        else
            return "No related table found in DD";

    }

        public static function get_DD_database_table_name($tableType){
	        $con = parent::getConnection();
	        $sql = "select `database_table_name` from data_dictionary as dd where upper(dd.table_type)='$tableType' and (`parent_table` is null or
	        `parent_table`='')";

	        $result = mysqli_query($con, $sql);
	        $row = mysqli_fetch_assoc($result);
	        if($row["database_table_name"]!=null or $row["database_table_name"]!="")
	            return $row["database_table_name"];
	        else
	            return "No related table found in DD";

    }


        public static function get_DD_crossref_table_name($tableAlias){
//	        $con = parent::getConnection();
//	        $sql = "select `database_table_name` from data_dictionary as dd where dd.table_type='$crossref' and dd.table_alias='$tableAlias' and (`parent_table` is null or `parent_table`='')";

//	d("crossref_table_name 1");

//	        d($sql);
//	        $result = mysqli_query($con, $sql);
//	        $row = mysqli_fetch_assoc($result);

//	        d($sql, $result, $row);

//	        if($row["database_table_name"]!=null or $row["database_table_name"]!="")
//	            return $row["database_table_name"];
//	        else
//	            return "No related table found in DD";

    }
}



?>