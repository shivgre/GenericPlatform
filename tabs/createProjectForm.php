<?php
include_once($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'] . '/models/GenericDBFunctions.php');
$getStates = "SELECT * FROM  states";
$states = mysql_query($getStates);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>bootstrap-validator/css/bootstrapValidator.css"/>
<script type="text/javascript" src="<?php echo BASE_URL ?>bootstrap-validator/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_JS_URL ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>bootstrap-validator/js/bootstrapValidator.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://ucarecdn.com/widget/1.3.1/uploadcare/uploadcare-1.3.1.min.js"></script>
<div id="wrapper-create-proj"> <br/>
  <form action='<?php echo BASE_URL_SYSTEM ?>project-form-actions.php' method='post' id='create-project' enctype='multipart/form-data'>
    <div class="col-6 col-sm-6 col-lg-3">
      <div class='left-content'> <span> <img id="project_thumb" src="<?php echo (($project['projectImage'] != "") && isset($project['projectImage'])) ? BASE_URL . "project_uploads/thumbs/" . $project['projectImage'] : "project_uploads/defaultImageIcon.png" ?>" border="0" width="200" height="200" class="img-thumbnail img-responsive" style="width:100%;"> </span>
        <div>
          <?php
          if (isset($project['projectImage']) && $project['projectImage'] != "")
          {
            echo "<a href='" . BASE_URL_SYSTEM . "project-form-actions.php?action=remove_project_image&pid=" . $_GET['pid'] . "' class='btn btn-primary update-btn'>Remove Image</a>";
          }
          else
          {
            ?>
            <input type="hidden" role="uploadcare-uploader" name="image" id="file2" data-locale="en" data-tabs="file url facebook gdrive instagram" data-images-only="true" data-path-value="false" data-preview-step="false" data-multiple="false"  value="" data-crop="650x430 minimum"/>
            <br />
            <input type="hidden" name="uploadcare_image_url" id="uploadcare_image_url" value="" />
            <input type="hidden" name="uploadcare_image_name" id="uploadcare_image_name" value="" />
            <?php
          }
          ?>
        </div>
      </div>
    </div>
    <div class='col-6 col-sm-6 col-lg-9 right-content user-profile  createproject'>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_NAME ?>
        </label>
        :<br />
        <span>
          <input type="text" name="projectName" value="<?php echo (isset($project)) ? $project['pname'] : "" ?>" class='form-control' >
        </span> </div>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_PRICE ?>
        </label>
        :<br />
        <span>
          <input type="text" name="projectPrice" value="<?php echo (isset($project)) ? $project['amount'] : "" ?>" class='form-control'>
        </span> </div>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_QUANTITY ?>
        </label>
        :<br />
        <span>
          <input type="text" name="quantity" value="<?php echo (isset($project)) ? $project['quantity'] : "" ?>" class='form-control'>
        </span> </div>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_CATEGORY ?>
        </label>
        : <br />
        <span>
          <select name="category" class='form-control'>
            <option value="">-----Select------</option>
            <?php
            $query = "select * from project_categories";
            $result = mysql_query($query);
            while ($category = mysql_fetch_array($result))
            {
              ?>
              <option value="<?php echo $category['project_category_id'] ?>" <?php echo ($category['project_category_id'] == $project['cid']) ? "Selected='selected'" : "" ?>>
                <?php echo $category['project_categeory_name'] ?>
              </option>
              <?php
            }
            ?>
          </select>
        </span> </div>
      <div class='form_element form-group increase'>
        <label class="control-label">
          <?php echo PROJECT_EXPIRY_DATE ?>
        </label>
        :<br />
        <span>
          <input type="text" name="expiryDate" value="<?php echo (isset($project)) ? $project['expiry_date'] : "" ?>" id="expiryDate" class='form-control'>
        </span> </div>

      <?php if (PROJECT_VISIBILITY)
      {
        ?>
        <div class='form_element form-group increase'>
          <label class="control-label">
  <?php echo PROJECT_VISBILITY_LABEL ?>
          </label>
          :<br />
          <span>							  
            <?php /* ?><select name="isLive">
              <option value="">-----Select------</option>
              <?php
              $query = "select * from project_visibility";
              $result = mysql_query($query);
              while($visibility = mysql_fetch_array($result)){
              ?>
              <option value="<?php echo$visibility['id']?>" <?php echo($visibility['id']== $project['isLive'])?"SELECTED":""?> ><?php echo$visibility['visibility']?></option>
              <?php
              }
              ?>
              </select><?php */ ?>
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
          </span>
        </div>
<?php } ?>
      <div style="clear:both;"></div>
      <div class='form_element description_gui'>
        <label>
<?php echo PROJECT_DESCRIPTION ?>
        </label>
        :<br />
        <span>
          <textarea id"projectDescription"   name="description" rows="4" cols="20" maxlength=150 class='form-control'>
<?php echo (isset($project)) ? stripslashes($project['description']) : "" ?>
        </textarea>
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
            if ($affiliations['affiliation_id'] == $project['affiliation_id_1'])
            {
              echo '<option value="' . $affiliations['affiliation_id'] . '" Selected="selected">' . $affiliations['affiliation_name'] . '</option>';
            }
            else
            {
              echo '<option value="' . $affiliations['affiliation_id'] . '">' . $affiliations['affiliation_name'] . '</option>';
            }
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
            if ($affiliations['affiliation_id'] == $project['affiliation_id_2'])
            {
              echo '<option value="' . $affiliations['affiliation_id'] . '" Selected="selected">' . $affiliations['affiliation_name'] . '</option>';
            }
            else
            {
              echo '<option value="' . $affiliations['affiliation_id'] . '">' . $affiliations['affiliation_name'] . '</option>';
            }
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
          <div  id="tags_list">
            <?php
            if (isset($project))
            {
              $tags = explode(",", trim($project['tname']));
              $i = 0;
              foreach ($tags as $tag)
              {
                if ($tag != "")
                {
                  ?>
                  <span id="<?php echo $i ?>">
                  <?php echo " " . $tag . " " ?>
                    <img alt='' title='Remove' src='<?php echo BASE_IMAGES_URL ?>closebox.png' onClick="removetag('<?php echo $tag ?>', '<?php echo $i ?>');" /></span>
                  <?php
                  $i++;
                }
              }
            }
            ?>
          </div>
          <input type="text" placeholder="<?php echo PROJECT_TAG_PLACEHOLDER ?>"  name="addNewTag" value="" class="form-control" id="addNewTag" />
        </div>
        <input class="btn btn-primary login"    type="button" name="addTag" value="<?php echo ADD_TAG_BUTTON ?>" id="addTag" onClick="addTagToSuggestion()"/>
        <input type="hidden" name="tags" value="<?php echo (isset($project)) ? $project['tname'] : "" ?>" id="tags" />
      </span> </div>
    <div style="clear:both;"></div>
    <!--Project Team Accordion -->
    <div class='form_element description_gui'>

      <?php
      $query = "SELECT pt_id, member_id, uname, permission_id, perm_type FROM  `project_team` pt, users u, project_permissions pp WHERE pt.member_id = u.uid AND pt.`permission_id` = pp.perm_id AND pt.project_id =" . $_GET['pid'];

      $resultQuery = mysql_query($query);

      while ($data = mysql_fetch_assoc($resultQuery))
      {
        $memberRole[] = $data;
      }
      ?>
      <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Project Team Members</a> </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
              <?php
              for ($i = 0; $i <= count($memberRole); $i++)
              {
                if ($i == 0)
                {
                  ?>
                  <div class="form-group">
                    <div style="margin:0px auto;width:100%;float: left">	
                      <div class="col-md-4">
                        <select name="user[]" class="form-control">
                          <option value="">please select</option>
                          <?php
                          $getUsers = "SELECT uid, uname FROM users";
                          $resultUsers = mysql_query($getUsers);

                          while ($users = mysql_fetch_assoc($resultUsers))
                          {
                            if ($users['uid'] == $memberRole[0]['member_id'])
                              echo '<option value="' . $users['uid'] . '" selected>' . $users['uname'] . '</option>';
                            else
                              echo '<option value="' . $users['uid'] . '">' . $users['uname'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <select name="role[]" class="form-control">
                          <option value="">please select</option>
                          <?php
                          $getPermissions = "SELECT perm_id, perm_type FROM project_permissions";
                          $resultPermissions = mysql_query($getPermissions);

                          while ($permissions = mysql_fetch_assoc($resultPermissions))
                          {
                            if ($permissions['perm_id'] == $memberRole[0]['permission_id'])
                              echo '<option value="' . $permissions['perm_id'] . '" selected>' . $permissions['perm_type'] . '</option>';
                            else
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
                  <?php
                }
                else
                {
                  ?>
                  <div class="form-group">
                    <div style="margin:0px auto;width:100%;float: left">	
                      <div class="col-md-4">
                        <select name="user[]" class="form-control">
                          <option value="">please select</option>
                          <?php
                          $getUsers = "SELECT uid, uname FROM users";
                          $resultUsers = mysql_query($getUsers);

                          while ($users = mysql_fetch_assoc($resultUsers))
                          {
                            if ($users['uid'] == $memberRole[$i]['member_id'])
                              echo '<option value="' . $users['uid'] . '" selected>' . $users['uname'] . '</option>';
                            else
                              echo '<option value="' . $users['uid'] . '">' . $users['uname'] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <select name="role[]" class="form-control">
                          <option value="">please select</option>
                          <?php
                          $getPermissions = "SELECT perm_id, perm_type FROM project_permissions";
                          $resultPermissions = mysql_query($getPermissions);

                          while ($permissions = mysql_fetch_assoc($resultPermissions))
                          {
                            if ($permissions['perm_id'] == $memberRole[$i]['permission_id'])
                              echo '<option value="' . $permissions['perm_id'] . '" selected>' . $permissions['perm_type'] . '</option>';
                            else
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
                  <!-- The option field template containing an option field and a Remove button -->
                  <?php
                }
              }
              ?>
              <div class="form-group hide" id="optionTemplate">
                <div style="margin:0px auto;width:100%;float: left">	
                  <div class="col-md-4">
                    <select name="user[]" class="form-control">
                      <option value="">please select</option>
                      <?php
                      $getUsers = "SELECT uid, uname FROM users";
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
                      <option value="">please select</option>
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
        <input type="checkbox" name="fork_project" value="1" <?php echo (isset($project_config['fork']) && $project_config['fork'] == 1) ? "checked" : "" ?> >
<?php echo FORK_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="copy_project" value="1" <?php echo (isset($project_config['copy']) && $project_config['copy'] == 1) ? "checked" : "" ?>>
<?php echo COPY_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="subscribe_project" value="1" <?php echo (isset($project_config['subscribe']) && $project_config['subscribe'] == 1) ? "checked" : "" ?>>
<?php echo SUBSCRIBE_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="share_project" value="1" <?php echo (isset($project_config['share']) && $project_config['share'] == 1) ? "checked" : "" ?>>
<?php echo SHARE_PROJECT ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="project_description" value="1" <?php echo (isset($project_config['description']) && $project_config['description'] == 1) ? "checked" : "" ?>>
<?php echo SHOW_PROJECT_DESC ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="project_img_gallery" value="1" <?php echo (isset($project_config['image_gallery']) && $project_config['image_gallery'] == 1) ? "checked" : "" ?>>
<?php echo SHOW_PROJECT_IMG_GALLERY ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="project_transactions" value="1" <?php echo (isset($project_config['transaction']) && $project_config['transaction'] == 1) ? "checked" : "" ?>>
<?php echo SHOW_PROJECT_TRANSACTIONS ?>
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="project_comments" value="1" <?php echo (isset($project_config['comments']) && $project_config['comments'] == 1) ? "checked" : "" ?>>
<?php echo SHOW_PROJECT_COMMENTS ?>
      </label>
    </div>
    <div style="clear:both;"></div>
    <div class='form_element'>
      <?php
      if (isset($project))
      {
        echo "<input type='hidden' name = 'oldProjectImage' value='" . $project['projectImage'] . "' />";
        echo "<input type='hidden' name = 'pid' value='" . $_GET['pid'] . "' />";
      }
      ?>
      <input id="validateBtn" class="btn btn-primary" type="submit" name="<?php echo (isset($project)) ? "projectUpdate" : "projectSubmit" ?>" value="<?php echo (isset($project)) ? "Update Project" : "Save Project" ?>" value="<?php echo PROJECT_SAVE_BUTTON ?>" >
    </div>
    <div class='form_element'> <a href="<?php echo BASE_URL ?>projects.php" class="btn btn-primary">
<?php echo CANCEL_BUTTON ?>
      </a> </div>
  </div>
  <div style="clear:both;"></div>
</form>
</div>

<script type="text/javascript" src="<?php echo BASE_JS_URL ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>bootstrap-validator/js/bootstrapValidator.min.js"></script>
<script>
          $(document).ready(function () {
// The maximum number of options
            var MAX_OPTIONS = <?php echo PROJECT_TEAM_SIZE ?>;

            $('#create-project')
// Add button click handler
                    .on('click', '.addButton', function () {
                      var $template = $('#optionTemplate'),
                              $clone = $template
                              .clone()
                              .removeClass('hide')
                              .removeAttr('id')
                              .insertAfter($template),
                              $option = $clone.find('[name="user[]"]');
                      //$option1   = $clone.find('[name="role[]"]');

                      // Add new field
                      //$('#create-project').bootstrapValidator('addField', $option);
                    })

// Remove button click handler
                    .on('click', '.removeButton', function () {
                      var $row = $(this).parents('.form-group'),
                              $option = $row.find('[name="user[]"]');

// Remove element containing the option
                      $row.remove();

// Remove field
//$('#create-project').bootstrapValidator('removeField', $option);
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
<script src="<?php echo BASE_JS_URL ?>my_projects.js"></script>

<script>
          $("#myprojects_tab").click(function () {
            $("#myprojects_tab").toggleClass("open");
          });
</script>
<script src="ckeditor/ckeditor.js"></script>
<script>
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