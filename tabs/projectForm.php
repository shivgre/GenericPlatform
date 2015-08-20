<?php
if ($_SERVER['HTTP_HOST'] === 'localhost')

{ define('APP_DIR', $_SERVER['DOCUMENT_ROOT'].'generic-platforms/'); // Base Root or Directory Path For Application

  $GLOBALS['APP_DIR'] = $_SERVER['DOCUMENT_ROOT'] . 'generic-platforms/'; // Base Root or Directory Path For Application

}
else
{
  define('APP_DIR', $_SERVER['SUBDOMAIN_DOCUMENT_ROOT']); // Base Root or Directory Path For Application
  $GLOBALS['APP_DIR'] = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'] . '/'; // Base Root or Directory Path For Application
}

include_once($GLOBALS['APP_DIR'] . 'models/GenericDBFunctions.php');
include_once($GLOBALS['APP_DIR'] . "system/special_config.php");

?>
<form action='<?php echo BASE_URL_SYSTEM ?>project-form-actions.php' method='post' id='create-project' enctype='multipart/form-data'>
  <div class="col-6 col-sm-6 col-lg-3">
    <div class='left-content'> <span> <img id="project_thumb" src="<?php echo BASE_URL ?>project_uploads/defaultImageIcon.png" border="0" width="200" height="200" class="img-thumbnail img-responsive" style="width:100%;"> </span>
      <div>
        <input type="hidden" role="uploadcare-uploader" name="image" id="file2" data-locale="en" data-tabs="file url facebook gdrive instagram" data-images-only="true" data-path-value="false" data-preview-step="false" data-multiple="false"  value=""  data-crop="650x430 minimum" />
        <br />
        <input type="hidden" name="uploadcare_image_url" id="uploadcare_image_url" value="" />
        <input type="hidden" name="uploadcare_image_name" id="uploadcare_image_name" value="" />
      </div>
    </div>
  </div>
  <div class='col-6 col-sm-6 col-lg-9 right-content user-profile  createproject' style="border-top:1px solid #e7e7e7">
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_NAME ?>
      </label>
      :<br />
      <span>
        <input type="text" name="projectName" value="" class='form-control' >
      </span> </div>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_PRICE ?>
      </label>
      :<br />
      <span>
        <input type="text" name="projectPrice" value="" class='form-control'>
      </span> </div>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_QUANTITY ?>
      </label>
      :<br />
      <span>
        <input type="text" name="quantity" value="" class='form-control'>
      </span> </div>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_CATEGORY ?>
      </label>
      : <br />
      <span>
        <?php
        $dropDown = new CustomHtml();
        $dropDown->getDropDown('project_categories');
        ?>
        <?php
        $query = "select * from project_categories";
        $result = mysql_query($query);
        while ($category = mysql_fetch_array($result))
        {
          ?>
          <option value="<?php echo $category['project_category_id'] ?>" >
            <?php echo $category['project_categeory_name'] ?>
          </option>
          <?php
        }
        ?>
        </select>-->
      </span> </div>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_EXPIRY_DATE ?>
      </label>
      :<br />
      <span>
        <input type="text" name="expiryDate" value="" id="expiryDate" class='form-control'>
      </span> </div>
    <?php
    if (PROJECT_VISIBILITY)
    {
      ?>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_LAUNCH_STATUS_LABEL ?>
        </label>
        :<br />
        <span>
          <?php ?>
          <select name="isLive">
            <option value="">-----Select------</option>
            <?php
            $launch_status = GenericDBFunctions::getDataByTableName("project_launch_status");
            foreach ($launch_status as $status)
            {
              if ((PROJECT_DRAFT_MODE_ENABLED && $status['id'] == 1))
              {
                continue;
              }
              else
              {
                echo "<option value ='" . $status['id'] . "'>";
                echo $status['status'];
                echo "</option>";
              }
            }
            ?>
          </select>
        </span> </div>
    <?php } ?>
    <div style="clear:both;"></div>
    <div class='form_element description_gui'>
      <label>
        <?php echo PROJECT_DESCRIPTION ?>
      </label>
      :<br />
      <span>
        <textarea id="projectDescription" name="description" rows="4" cols="20" maxlength=150 class='form-control'></textarea>
      </span> </div>
    <?php
    $getAffiliations = "SELECT * FROM affiliations";
    $resultAffiliations = mysql_query($getAffiliations);
    ?>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_AFFILIATION_ONE ?>
      </label>
      :<br/>
      <span>
        <select name="affiliation1">
          <option value="">--Select First Organisation--</option>
          <?php
          while ($affiliations = mysql_fetch_assoc($resultAffiliations))
          {
            echo '<option value="' . $affiliations['affiliation_id'] . '">' . $affiliations['affiliation_name'] . '</option>';
          }
          ?>
        </select>
      </span> </div>
    <?php
    $getAffiliations = "SELECT * FROM affiliations";
    $resultAffiliations = mysql_query($getAffiliations);
    ?>
    <div class='form_element form-group increase'>
      <label class="control-label">
        <?php echo PROJECT_AFFILIATION_TWO ?>
      </label>
      :<br/>
      <span>
        <select name="affiliation2">
          <option value="">--Select Second Organisation--</option>
          <?php
          while ($affiliations = mysql_fetch_assoc($resultAffiliations))
          {
            echo '<option value="' . $affiliations['affiliation_id'] . '">' . $affiliations['affiliation_name'] . '</option>';
          }
          ?>
        </select>
      </span> </div>
    <div style="clear:both;"></div>
    <div class='form_element tags_main'>
      <label>
        <?php echo PROJECT_TAGS ?>
      </label>
      :<br />
      <span>
        <div class="tag_gui">
          <div  id="tags_list"></div>
          <input type="text" placeholder="<?php echo PROJECT_TAG_PLACEHOLDER ?>"  name="addNewTag" value="" class="form-control" id="addNewTag" />
        </div>
        <input class="btn btn-primary login"    type="button" name="addTag" value="<?php echo ADD_TAG_BUTTON ?>" id="addTag" onClick="addTagToSuggestion()"/>
        <input type="hidden" name="tags" value="" id="tags" />
      </span> </div>
    <!--Project Team Accordion -->
    <div class='form_element description_gui'>
      <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Project Team Members</a> </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
              <div style="margin:0px auto;width:100%;float: left">
                <div class="form-group">
                  <div class="col-md-4">
                    <select name="user[]" class="form-control">
                      <option value="">Select User</option>
                      <?php
                      $getUsers = "SELECT {$userTblArray['uid_fld']}, {$userTblArray['uname_fld']} FROM {$userTblArray['table_alias']}";
      // CJ NOTE 1-20-15 ... not sure of the correct usage of tableAlias  and tablename here ... we really want to get more specific about using database_table_name and table_alias (the key field in DD) ...
      // temporarily changing to ...
                      $getUsers = "SELECT {$userTblArray['uid_fld']}, {$userTblArray['uname_fld']} FROM {$userTblArray['database_table_name']}";

                      $resultUsers = mysql_query($getUsers);

                      while ($users = mysql_fetch_assoc($resultUsers))
                      {
                        echo '<option value="' . $users['uid'] . '">' . $users['uname'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select name="role[]" class="form-control">
                      <option value="">Select Role</option>
                      <?php
                      $getPermissions = "SELECT perm_id, perm_type FROM project_permissions";
                      $resultPermissions = mysql_query($getPermissions);

                      while ($permissions = mysql_fetch_assoc($resultPermissions))
                      {
                        echo '<option value="' . $permissions['perm_id'] . '">' . $permissions['perm_type'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <button type="button" class="btn btn-default addButton"> <i class="fa fa-plus"></i> </button>
                  </div>
                </div>
              </div>
              <!-- The option field template containing an option field and a Remove button -->
              <div class="form-group hide" id="optionTemplate">
                <div style="margin:0px auto;width:100%;float: left">
                  <div class="col-md-4">
                    <select name="user[]" class="form-control">
                      <option value="">Select User</option>
                      <?php
                      $getUsers = "SELECT {$userTblArray['uid_fld']}, {$userTblArray['uname_fld']} FROM {$userTblArray['table_alias']}";
                      $resultUsers = mysql_query($getUsers);

                      while ($users = mysql_fetch_assoc($resultUsers))
                      {
                        echo '<option value="' . $users['uid'] . '">' . $users['uname'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select name="role[]" class="form-control">
                      <option value="">Select Role</option>
                      <?php
                      $getPermissions = "SELECT perm_id, perm_type FROM project_permissions";
                      $resultPermissions = mysql_query($getPermissions);

                      while ($permissions = mysql_fetch_assoc($resultPermissions))
                      {
                        echo '<option value="' . $permissions['perm_id'] . '">' . $permissions['perm_type'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <button type="button" class="btn btn-default removeButton"> <i class="fa fa-minus"></i> </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="clear:both;"></div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="fork_project" value="1">
        <?php echo FORK_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="copy_project" value="1">
        <?php echo COPY_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="subscribe_project" value="1">
        <?php echo SUBSCRIBE_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="share_project" value="1">
        <?php echo SHARE_PROJECT ?>
      </label>
    </div>
    <?php
    if (CHILD_FILES_CONFIG == 'true')
    {
      ?>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="project_description" value="1">
          <?php echo SHOW_PROJECT_DESC ?>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="project_img_gallery" value="1">
          <?php echo SHOW_PROJECT_IMG_GALLERY ?>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="project_img_gallery" value="1">
          <?php echo SHOW_PROJECT_TRANSACTIONS ?>
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="project_img_gallery" value="1">
          <?php echo SHOW_PROJECT_COMMENTS ?>
        </label>
      </div>
    <?php } ?>
    <div style="clear:both;"></div>
    <div class='form_element'>
      <input id="validateBtn" class="btn btn-primary" type="submit" name="projectSubmit"  value="<?php echo PROJECT_SAVE_BUTTON ?>" >
    </div>
    <div class='form_element'> <a href="<?php echo BASE_URL ?>projects.php" class="btn btn-primary">
        <?php echo CANCEL_BUTTON ?>
      </a> </div>
  </div>
  <div style="clear:both;"></div>
</form>
<script>
  $(document).ready(function () {
    // The maximum number of options
    var MAX_OPTIONS = <?php echo PROJECT_TEAM_SIZE ?>;

    $('#create-project')
            // Add button click handler
            .bootstrapValidator()
            .on('click', '.addButton', function () {
              var $template = $('#optionTemplate'),
                      $clone = $template
                      .clone()
                      .removeClass('hide')
                      .removeAttr('id')
                      .insertBefore($template),
                      $option = $clone.find('[name="user[]"]');
              //$option1   = $clone.find('[name="role[]"]');

              // Add new field
              $('#create-project').bootstrapValidator('addField', $option);
            })

            // Remove button click handler
            .on('click', '.removeButton', function () {
              var $row = $(this).parents('.form-group'),
                      $option = $row.find('[name="user[]"]');

              // Remove element containing the option
              $row.remove();

              // Remove field
              $('#create-project').bootstrapValidator('removeField', $option);
            })

            // Called after adding new field
            .on('added.field.bv', function (e, data) {
              // data.field   --> The field name
              // data.element --> The new field element
              // data.options --> The new field options

              if (data.field === 'user[]') {
                if ($('#create-project').find(':visible[name="user[]"]').length >= MAX_OPTIONS) {
                  $('#create-project').find('.addButton').attr('disabled', 'disabled');
                }
              }
            })

            // Called after removing the field
            .on('removed.field.bv', function (e, data) {
              if (data.field === 'user[]') {
                if ($('#create-project').find(':visible[name="user[]"]').length < MAX_OPTIONS) {
                  $('#create-project').find('.addButton').removeAttr('disabled');
                }
              }
            });
  });
</script>
