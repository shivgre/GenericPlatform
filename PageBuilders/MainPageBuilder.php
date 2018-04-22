<?php

//include("EditDatabase.php");

class MainPageBuilder
{
    private $tabNum; //Current Tab Number
    private $table_alias; //Table alias associated with current display page (located in Data Dictionary and related to Field Dictionary)
    private $database_table_name; //database_table_name field inside the
    private $list_fields; // fields to be displayed in table retrieved from data dictionary
    private $list_filter; //
    private $list_sort; //way to sort fields retrieved from field dictionary (retieved from data dictionary)
    private $dataDictQuery; // Resulting array from querying data dictionary
    private $WHERE; // Where statement
    private $oFactory;

    // Used for checking if a field dis editable or not.
    private $fieldEditStatus;

    // Used for when a cell is clicked on to edit it's values
    private $isEdit;

    // Used for whenever the page is editable by default
    private $page_editable = false;



    function LoadMainContent($displayPage, $menu_location, Factory $oFactory){

        $this->oFactory = $oFactory;

        if(empty($_GET["edit"])){
            $this->isEdit = false;
        }
        else{
            $this->isEdit = true;
        }
        if(!empty($_GET["tab_num"])){
            $this->tabNum = $_GET["tab_num"];
        }
        else{
            $this->tabNum = 1;
        }
        //$_SESSION["tab_num"] = $this->tabNum;
        // Query data dictionary for needed fields
        $this->dataDictQuery = $this->GetInfoFromDataDictionary($displayPage, $menu_location, $oFactory);
        // Create the main tabs for the body
        $this->CreateAndDisplayTabs($displayPage, $menu_location, $oFactory);
        $query = "SELECT * FROM `field_dictionary` WHERE `table_alias` = \"$this->table_alias\" ORDER BY `display_field_order`";
        $data_fields = $oFactory->SQLHelper()->queryToDatabase($query);

        // Make sure list fields are set
        $this->setListFields();

        if ($this->dataDictQuery[0]['dd_editable'] == '1'){
            $this->page_editable = true;
            echo "<div style='text-align:right; margin-top:10px; margin-bottom: 10px'><input type='button' class='btn btn-primary' value='Edit'/></div>";
        }
        if($this->isEdit){
            $this->LoadEditPage($displayPage, $menu_location, $oFactory);
        }
        else{
            // Get the list_view and create display field
            $list_view = explode(" ", $this->dataDictQuery[$this->tabNum - 1]["list_views"]);
            if ($list_view[0] == "listview"){

                $this->CreateTable($displayPage, $menu_location, $oFactory, $data_fields);
            }
            else if ($list_view[0] == "boxView"){
                $this->CreateBoxView($displayPage, $menu_location, $oFactory, $data_fields);

            }
            else if ($list_view[0] == "thumbView"){
                // To be made
                //  CreateThumbView($displayPage, $menu_location, $oFactory);
            }
            else if ($list_view[0] == "Cards"){
                // To be made
                // CreateCardVsiew($displayPage, $menu_location, $oFactory);
            }
            else if ($list_view[0] == "Xlist"){
                // To be made
                // CreateXListView($displayPage, $menu_location, $oFactory);
            }
            // Just display field_data
            else {

                $this->CreateDefaultView($oFactory, $data_fields);
            }
        }

        // Edit button for editable pages
        if ($this->dataDictQuery[0]['dd_editable'] == '1'){
            $this->page_editable = true;
            echo "<div style='text-align:right; margin-top:10px'><input type='button' class='btn btn-primary' value='Edit'/></div>";
        }
    }
    // No styling or specific layout, just display data listed in field_dictionary
    function CreateDefaultView($oFactory, $data_fields){
        // Grab fields from field_dictionary
        // will need to change some queries
        $query = "";

        $data_fields_copy = $data_fields;
        $data_fields =$this->GetDataFields($oFactory);
        // Unused, not sure Creates tabs
        if(empty($this->list_fields) || $this->list_fields == ""){
            foreach($data_fields_copy as $key=>$value){
                if($key < count($data_fields_copy) - 1){
                    $this->list_fields .= $value["generic_field_name"] . ",";
                }
                else {
                    $this->list_fields .= $value["generic_field_name"];
                }
            }
        }
        // Iterate through and display data
        echo "<form>";
        foreach($data_fields as $result){
            // Iterate through each fields's column's name and value
            foreach($result as $key=>$value){
                // Foreach field row (field_dict copy)
                foreach($data_fields_copy as $field_return) {
                    // If the column name matches the generic_field_name format the data
                    if ($field_return["generic_field_name"] == $key && $field_return["visibility"] == '1') {

                        //$this->DisplayData_NoLabel($field_return["format_type"], $value);
                        $this->DisplayData_Label($field_return["format_type"], $value, $field_return["field_label_name"], $field_return);

                    }
                }
            }
        }
        //why do we do this?
        echo "</form>";
        //$this->DisplayData($data_fields, $oFactory);
    }




    // Creates html for a data field and retrieves the value to go inside
    // Creates html for a data field
    function DisplayData_NoLabel($formatType, $value){

        if ($formatType == "" || empty($formatType)){
            echo "<p> $value </p>";
        }
        else if ($formatType == "username"){
            echo "<input type='text' name='username'>";
        }
        else if ($formatType == "transaction_text"){
        }
        else if ($formatType == "transaction_execute"){
        }
        else if ($formatType == "transaction_confirmation"){
        }
        else if ($formatType == "transaction_cancel"){
        }
        else if ($formatType == "textbox"){

            $readonly = "";
            if ($this->page_editable == true){
                $readonly = 'readonly';
            }
            echo "<input type='text' value='$value' $readonly>";

        }
        else if ($formatType == "tag"){
            echo "<input type='text' value='$value' data-role='tagsinput'></input>";
        }
        else if ($formatType == "pdf_inline"){
        }
        else if ($formatType == "password"){
            echo "<input type='password' name='username'>";
        }
        else if ($formatType == "list_fragment"){
        }
        else if ($formatType == "image"){

            echo "<img src='" . $value . "' onerror='this.src=\"http://generic.cjcornell.net/users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg\"'  width='100' height='100'>";

        }
        else if ($formatType == "email"){
        }
        else if ($formatType == "dropdown"){
        }
        else if ($formatType == "crf"){
        }
    }




    /**
     * @return mixed
     */
    public function setListFields()
    {
        $query = "SELECT * FROM `field_dictionary` WHERE `table_alias` = \"$this->table_alias\" ORDER BY `display_field_order`";
        $data_fields = $this->oFactory->SQLHelper()->queryToDatabase($query);

        // Create the list fields
        if(empty($this->list_fields) || $this->list_fields == "") {
            foreach ($data_fields as $key => $value) {
                if ($value["visibility"] == 1) {
                    $this->list_fields .= $value["generic_field_name"] . ",";
                }
                $this->fieldEditStatus[$value["generic_field_name"]] = $value["editable"];
            }
            if ($this->list_fields[strlen($this->list_fields) - 1] == ',') {
                $this->list_fields = substr($this->list_fields, 0, -1);
            }
        }

    }

    function LoadEditPage($displayPage, $menu_location, Factory $oFactory){
        // Get the current tab number selected
        if(!empty($_GET["tab_num"])){
            $this->tabNum = $_GET["tab_num"];
        }
        else{
            $this->tabNum = 1;
        }
        $data_id = $_GET["search_id"];
        // Create the tabs
        //$this->CreateAndDisplayTabs($displayPage, $menu_location, $oFactory);
        // Generate globals for display page
        //$this->dataDictQuery = $this->GetInfoFromDataDictionary($displayPage, $menu_location, $oFactory);
        // Grab the selected cell

        $primary_key = "SHOW KEYS FROM $this->database_table_name WHERE Key_name = 'PRIMARY'";
        $primary_key = $oFactory->SQLHelper()->queryToDatabase($primary_key);
        $primary_key = $primary_key[0]["Column_name"];
        $data_result_query = "SELECT $this->list_fields from $this->database_table_name WHERE $primary_key = '$data_id'";
        $data_result = $oFactory->SQLHelper()->queryToDatabase($data_result_query);
        //echo "<form action='Helpers/EditDatabase.php' method='post'>";
        $location = $_SERVER['PHP_SELF'] . "?display=" . $_GET['display'];
        //might not be necessary since doing this in javascript now
        echo "<form action='$location' method='post'>";
        $data_result = $data_result[0];

        foreach($data_result as $key=>$value) {
            // If the column name matches the generic_field_name format the data

            //$this->DisplayData_NoLabel($field_return["format_type"], $value);

            $this->CreateEditView($key, $value);
            $_SESSION['$key'] = $value;
        }
        $_SESSION['oFactory'] = $oFactory;
        // Should return only one row

        echo "<br><input type='button' value='Submit Changes' onclick='UpdateValue(\"$this->database_table_name\", \"$primary_key\", \"$data_id\")'>";
        echo "</form>";
    }



    function CreateEditView($key, $value){
        $readonly = 'readonly';
        if ($this->fieldEditStatus[$key] == 'true'){
            $readonly = '';
        }
        echo "<div style='display: inline-table; margin-right: 20px; margin-top:19px;' id='$key' class='form-group row'>";
        echo "<label style='display:inline-block;'> $key </label>";
        echo "<input type='text' name='$key' value='$value' class='form-control' $readonly>";
//        $oldValue = $value;
//        $oldKey = "old_" . $key;
//        echo "<input type='hidden' name='$oldKey' value='$oldValue'>
        echo "</div>";
    }



    function DisplayData_Label($formatType, $value, $field_display_name,$field_return){
        $readonly = "";
        if ($this->page_editable == true){
            //$readonly = 'readonly';
        }
        $field_size = '';
        if (!empty($field_return["format_length"])){
            $field_size = $field_return["format_length"];
        }
        if ($formatType == "" || empty($formatType)){
            echo "<div style='display: inline-table;margin: auto; padding: 5px' id='$field_display_name' class='form-group row'>";
            echo "<label style='display:inline-block;'> $field_display_name </label>";
            echo "<input type='text' size='$field_size' value='$value' class='form-control' $readonly></div>";

        }
        else if ($formatType == "username"){
            echo "<input type='text' name='username'>";
        }
        else if ($formatType == "transaction_text"){
        }
        else if ($formatType == "transaction_execute"){
        }
        else if ($formatType == "transaction_confirmation"){
        }
        else if ($formatType == "transaction_cancel"){
        }
        else if ($formatType == "textbox"){

            echo "<input type='text' value='$value' $readonly>";
        }
        else if ($formatType == "tag"){
            echo "<div style='display: inline-table;margin: auto; padding: 5px' id='$field_display_name' class='form-group row'>";
            echo "<label> $field_display_name </label>";
            echo "<div id='$field_display_name'_inpu style='max-width: 200px'><input class='form-control' type='text' value='$value' data-role='tagsinput' $readonly></input></div>";
            echo "</div>";


        }
        else if ($formatType == "pdf_inline"){
        }
        else if ($formatType == "password"){
            echo "<input type='password' name='username'>";
        }
        else if ($formatType == "list_fragment"){
        }
        else if ($formatType == "image"){


            echo "<div style='margin:5px; display:inline-block'><img  width='150' height='150' src='http://generic.cjcornell.net/users_uploads/$value' class='img-thumbnail img-responsive user_thumb' onerror='this.src=\"http://generic.cjcornell.net/users_uploads/NO-IMAGE-AVAILABLE-ICON.jpg\"'></div>";

        }
        else if ($formatType == "email"){
        }
        else if ($formatType == "dropdown"){
        }
        else if ($formatType == "crf"){
        }
    }
    // Creates the HTML tab tags to be displayed
    function CreateAndDisplayTabs($displayPage, $menu_location, Factory $oFactory){
        // Create tabs
        echo "<ul class='nav nav-tabs'>";

        $BASE_URL =  $_SESSION['baseURL'];

        // Iterate through each entry
        foreach($this->dataDictQuery as $key=>$value){
            // If current tab is current active tab
            if(!empty($this->tabNum) && $key + 1 == $this->tabNum){
                echo "<li class='active'><a href='$BASE_URL?display=$displayPage&tab_num=" . ($key + 1) . "'>" . $value["tab_name"] . "</a> </li>";
            } else{ // Else, Inactive tab
                echo "<li><a href='$BASE_URL?display=$displayPage&tab_num=" . ($key + 1) . "'>" . $value["tab_name"] . "</a> </li>";
            }
        }
        echo "</ul>";
    }
    // Grab the Relevant information from data dictionary to prepare query for field_dictionary
    function GetInfoFromDataDictionary($displayPage, $menu_location, Factory $oFactory){
        // Grab Tabs
        $query = "SELECT * FROM `data_dictionary` WHERE `display_page` = '$displayPage'  ORDER BY `tab_num`";
        $resArr = $oFactory->SQLHelper()->queryToDatabase($query);
        // Set current tab to be tab 0
        $this->table_alias = $resArr[$this->tabNum - 1]["table_alias"];
        // Set reference to database_table name to current tab's reference
        $this->database_table_name = $resArr[$this->tabNum - 1]["database_table_name"];
        // Grab the list fields
        $this->list_fields = $resArr[$this->tabNum - 1]["list_fields"]; //explode(",", $resArr[$this->tabNum - 1]["list_fields"]);
        $this->list_fields = str_replace('*', '', $this->list_fields);
        // Grab list sort method
        $this->list_sort = $resArr[$this->tabNum - 1]["list_sort"];
        // Get WHERE statement
        if ($resArr[$this->tabNum-1]["list_filter"] != "" && !empty($resArr[$this->tabNum-1]["list_filter"])){
            $where = $resArr[$this->tabNum-1]['list_filter'];
            $this->WHERE = "WHERE $where";
        }
        else {
            $this->WHERE = "";
        }
        return $resArr;
    }


    function CreateTable($displayPage, $menu_location,$oFactory, $data_fields){
        //Create Add and Delete Buttons
        echo "<input type=button value='Add' style='margin-right: 20px; float: left;' onclick='DisplayAddPopUp()'>";

        //Add Button Modal Setup
        echo "<div id='AddDialog' title='Add To List' style='text-align: center; padding-top:40px;'>
            <label id='AddSuccess' style='color:#4CAF50'>Added Successfully</label>";

        $resArr = $oFactory->SQLHelper()->queryToDatabase("SELECT * FROM `field_dictionary` WHERE `table_alias` = \"$this->table_alias\" ORDER BY `display_field_order`");
        echo "<div id='AddDialogContent'>";
        foreach($resArr as $key=>$value){
            //Format Type is empty in the call enventually we might want it to pass in the format type however we are not there yet.
            //$this->DisplayData2($value["format_type"], "", $value["field_label_name"]);

            // Only fields that should be visible are displayed
            if ($value["visibility"] == 1) {
                $this->DisplayData_Label(null, null, $value["field_label_name"], null);
            }
        }
        echo "</div>";


        echo"
                <input type='button' value='Add' style='background-color: #4CAF50; color: white;' onclick='AddValues(\"$this->database_table_name\")'/>
                <input type='button' value='Cancel' onclick='CloseAddPopUp()'/>
             </div>";

        //End Add Button Modal Setup
        $primary_key_query = "SHOW KEYS FROM $this->database_table_name WHERE Key_name = 'PRIMARY'";
        $primary_key_arr = $oFactory->SqlHelper()->queryToDatabase($primary_key_query);
        $primary_key = $primary_key_arr[0]["Column_name"];
        $primary_field_name_query = "SELECT * FROM `field_dictionary` WHERE `table_alias` = '$this->database_table_name' AND `generic_field_name` = '$primary_key'";
        $primary_field_name_arr =  $oFactory->SqlHelper()->queryToDatabase($primary_field_name_query);
        $primary_field_name = $primary_field_name_arr[0]["field_label_name"];
        echo "<input type=button value='Delete' style='margin-right: 20px; margin-bottom:10px; float: left;' onclick='DeleteValues(\"$this->database_table_name\", \"$primary_key\", \"$primary_field_name\")'>";

        // Create the table headers
        $this->CreateTableHeadersFromFieldDictionary($displayPage, $menu_location, $oFactory, $data_fields);
        // Grab field data and populate the table
        $this->PopulateTable($displayPage, $menu_location, $oFactory, $data_fields);
    }
    // Iterate through the field_dictionary to grab the table headers
    Function CreateTableHeadersFromFieldDictionary($displayPage, $menu_location, Factory $oFactory, $data_fields){
        $resArr = $data_fields;

        echo "<table class='table table-bordered table-striped' id='example'><thead><tr>";
        echo "<th></th>";
        foreach($resArr as $result){
            if ($result["visibility"] == 1)
                echo "<th>" . $result["field_label_name"] . "</th>";
        }
        echo "</thead></tr>";
    }
    // Grab all the data for the field_data and populate the table

    Function PopulateTable($displayPage, $menu_location, Factory $oFactory, $data_fields){

        // Not sure?
        if(empty($this->list_sort)){
            $this->list_sort = "\"\"";
        }
        // Grab the fields from field dictionary

        $resArr = $data_fields;

        // Create a copy of the fields
        $resArrCopy = $resArr;
        $keyfield = "";
        foreach($resArr as $myresult){
            if($myresult["field_identifier"] == "KEYFIELD"){
                $keyfield = $myresult["generic_field_name"];
            }
        }
        if(empty($keyfield)){
            $keyfield = $resArr[0]["generic_field_name"];
        }
        // Check for specific fields and if no fields specified, grab them all
        $resArr = $this->GetDataFields($oFactory);
        ?>
        <script>
            $(document).ready(function(){

                selectedIndex = [];

                $('.odd input:checkbox').click(function (e) {
                    // button's stuff
                    e.stopImmediatePropagation();
                    var row = $(this).closest("tr").index() + 1;
                    selectedIndex.push(row);
                    console.log(selectedIndex);
                });
                $('.even input:checkbox').click(function (e) {
                    // button's stuff
                    e.stopImmediatePropagation();
                    var row = $(this).closest("tr").index() + 1;
                    selectedIndex.push(row);
                    console.log(selectedIndex);
                });

                $(".odd").click(function() {
                    var base_url = "<?php echo $_SESSION['baseURL']; ?>";
                    var displayPage = $(this).attr("displaypage");
                    var tabNum = $(this).attr("tabnum");
                    var search_id = $(this).attr("search_id");
                    var totalURI = base_url + "?display=" + displayPage + "&tab_num=" + tabNum + "&edit=true&search_id=" + search_id;
                    window.location = totalURI;
                });
                $(".even").click(function() {
                    var base_url =  "<?php echo $_SESSION['baseURL']; ?>";
                    var displayPage = $(this).attr("displaypage");
                    var tabNum = $(this).attr("tabnum");
                    var search_id = $(this).attr("search_id");
                    var totalURI = base_url + "?display=" + displayPage + "&tab_num=" + tabNum + "&edit=true&search_id=" + search_id;
                    window.location = totalURI;
                });
            });
        </script>
        <?php
        // Iterate through each field (database_table row)
        echo "<tbody>";
        foreach($resArr as $result){
            echo "<tr id='clickable-row' search_id='$result[$keyfield]' displaypage='$displayPage' tabnum='$this->tabNum'>";
            // Iterate through each fields's column's name and value
            echo "<td><input type='checkbox'></td>";
            foreach($result as $key=>$value){
                echo "<td>";
                // Foreach field row (field_dict copy)
                foreach($resArrCopy as $field_return) {
                    // If the column name matches the generic_field_name format the data
                    if ($field_return["generic_field_name"] == $key && $field_return["visibility"] == '1') {

                        $this->DisplayData_NoLabel($field_return["format_type"], $value);

                    }
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    function GetDataFields($oFactory){
        // Check for specific fields and if no fields specified, grab them all
        if($this->list_fields == null || $this->list_fields == ""){ //shouldnt ever happen anymore
            $query = "SELECT * FROM `$this->database_table_name` ORDER BY '$this->list_sort'";
            $field_list = $oFactory->SQLHelper()->queryToDatabase($query);
        }
        elseif(is_numeric($this->list_fields)){
            //if is number grab the first four fields from field dictionary and populate only those?
            //They don't even do this. Note table_alias transactions... For now just getting everything
            $field_list = $oFactory->SQLHelper()->queryToDatabase("SELECT * FROM `$this->database_table_name` ORDER BY `'$this->list_sort'`");
        }
        else{
            $query = "SELECT $this->list_fields FROM `$this->database_table_name` ORDER BY '$this->list_sort'";
            $field_list = $oFactory->SQLHelper()->queryToDatabase($query);
        }
        return $field_list;
    }

    //This gets an object from the field dictionary and displays it with a box around it.
    function CreateBoxView($displayPage, $menu_location, Factory $oFactory){
        $this->CreateBoxViewFromFieldDictionary($displayPage, $menu_location, $oFactory);
    }

    function CreateBoxViewFromFieldDictionary($displayPage, $menu_location, Factory $oFactory){
        //Get the object from the field dictionary.
        $query = $oFactory->SQLHelper()->queryToDatabase("SELECT * FROM `field_dictionary` WHERE `table_alias` = \"$this->table_alias\" ORDER BY `display_field_order`");
        $dataFields =$this->GetDataFields($oFactory);

        if(empty($this->list_fields) || $this->list_fields == ""){
            foreach($query as $key=>$value){
                if($key < count($query) - 1){
                    $this->list_fields .= $value["generic_field_name"] . ",";
                }
                else {
                    $this->list_fields .= $value["generic_field_name"];
                }
            }
        }
        //Display the object from the field dictionary with a box around it.
        foreach($dataFields as $field) {
            foreach($field as $key=>$value) {
                foreach ($query as $result) {
                    if($result["generic_field_name"] == $key) {
                        //The formatting should probably be edited.
                        //It currently displays as a vertical list with one element per row.
                        echo '<div style="border: 2px solid grey;">';
                        echo $result["field_label_name"];
                        echo "<br>";
                        echo "$value";
                        echo "</div>";
                    }
                }
            }
        }
    }
}

