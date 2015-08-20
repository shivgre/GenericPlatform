<?php
include("../application/header.php");

include_once($GLOBALS['APP_DIR'] . 'actions/CustomHtml.php');
include_once($GLOBALS['APP_DIR'] . 'models/GenericDBFunctions.php');

$obj = new CustomHtml();

function getGenericForm($tableName, $populate = array())
{
  $table = $_SESSION["datadictionaryArray"][$tableName];
  $columnsArr = GenericDBFunctions::getTableColumnNames($tableName);
  if ($_REQUEST['sub_tab'] == null || $_REQUEST['sub_tab'] == '')
  {
    $current_page_num = 1;
  }
  else
  {
    $current_page_num = $_REQUEST['sub_tab'];
  }
  //echo GenericDBFunctions::getMaxColumnsByTableName($tableName)."]]]]";
  //print_r($table[2]["keyfield"]);
  for ($field = 0; $field <= GenericDBFunctions::getMaxColumnsByTableName($tableName); $field++)
  {
    $fieldName = $field;
    /* echo $table."<br>";
      echo $table[$fieldName]["keyfield"]."<br>"; */
    if ($table[$fieldName]["keyfield"] == "Y")
    {
      echo "<input type='hidden' name='" . $table[$fieldName]["generic_field_name"] . "' value='" . $populate[$table[$fieldName]["generic_field_name"]] . "' />";
    }
    elseif ($table[$fieldName]["visibility"] >= 1 && ($table[$fieldName]["sub_tab_num"] == $current_page_num))
    {
      echo "<div class='form_element'>";
      echo "<label>" . $table[$fieldName]["field_label_name"] . "</label>: <br />";
      echo "<span>";
      if (isset($table[$fieldName]["dropdownFN"]) && $table[$fieldName]["dropdownFN"] != "")
      {
        $obj->getDropDown($table[$fieldName]["generic_field_name"], $populate[$table[$fieldName]["generic_field_name"]]);
      }
      else if ($table[$fieldName]["generic_field_name"] == "description")
      {
        echo "<textarea id='description'   name='" . $table[$fieldName]["generic_field_name"] . "'>
				" . stripslashes($populate[$table[$fieldName]["generic_field_name"]]) . "
				</textarea>";
      }
      else
      {
        echo "<input type='text' id='" . $table[$fieldName]["generic_field_name"] . "' class='form-control' name='" . $table[$fieldName]["generic_field_name"] . "' value='" . $populate[$table[$fieldName]["generic_field_name"]] . "' />";
      }
      echo "</span>";
      echo "</div>";
      if ($sb == 2)
      {
        echo "<div style='clear:both'></div>";
        $sb = 0;
      }
      $sb++;
    }
  }
}
?>
<style>
  .form_element{
    width:45%;
  }
</style>
<form action='<?php echo BASE_URL_SYSTEM ?>profile.php' method='post' id='user_profile_form' enctype='multipart/form-data'>
  <?php
  getGenericForm($userTblArray['table_alias'], $userRow);
  ?>
  <div class='form_element update-btn2'>
    <input type='submit' name='profile_update' value='<?php echo UPDATE_PROFILE_BUTTON ?>' class="btn btn-primary update-btn" />
  </div>
  <div class='form_element'>
    <label>
      <a href="<?php echo BASE_URL_SYSTEM ?>profile.php" ><input type='button' name='profile_cancel' value='<?php echo CANCEL_BUTTON ?>' class="btn btn-primary update-btn" /></a>
    </label>
  </div>
  <div style="clear:both"></div>
</form>
<script src="<?php echo BASE_URL ?>/ckeditor/ckeditor.js"></script>
<script>
  $("#user_type").val();
  //CKEDITOR.replace('description');
  CKEDITOR.replace('description', {
    toolbarGroups: [
      {name: 'document', groups: ['mode']}, // Line break - next group will be placed in new line.
      {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
      {name: 'styles', groups: ['Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor']},
      {name: 'insert', groups: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe', 'uploadcare', 'youtube']}
    ]},
  {
    allowedContent: true
  });
</script>