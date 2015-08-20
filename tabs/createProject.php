<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo BASE_URL ?>bootstrap-validator/css/bootstrapValidator.css"/>
<script type="text/javascript" src="<?php echo BASE_URL ?>bootstrap-validator/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>bootstrap-validator/js/bootstrapValidator.min.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
  UPLOADCARE_PUBLIC_KEY = '4c3637988f9b93d343e8';
</script>
<script src="https://ucarecdn.com/widget/1.3.1/uploadcare/uploadcare-1.3.1.min.js"></script>
<!-- CAPSTONE: Override Uploadcare text -->
<!-- CAPSTONE: Override Uploadcare text -->
<script type="text/javascript">
  UPLOADCARE_LOCALE_TRANSLATIONS = {
    ready: 'Update Profile Photo',
    ready: ''
  };
  UPLOADCARE_PATH_VALUE = true;
  UPLOADCARE_CROP = "2:3";
</script>
<div class="content">
  <div id="profile">
    <?php
    $getStates = "SELECT * FROM  states";
    $states = mysql_query($getStates);
    echo "<div class='contentWrapper'>";
    ?>
    <div class="jumbotron search-form">
      <div class="container">
        <div class="row">
          <!--	  <div class="col-6 height2">-->
          <?php
          if (isset($_SESSION["messages"]))
          {
            echo "<div class='alert alert-info'>";
            echo " <a href='#' class='close' data-dismiss='alert'>&times;</a>";
            echo FlashMessage::render();
            echo "</div>";
          }
          ?>
          <!--	 </div> -->
        </div>
        <form action='<?php echo BASE_URL_SYSTEM ?>project-form-actions.php' method='post' id='create-project' enctype='multipart/form-data'>
          <div class="col-6 col-sm-6 col-lg-3">
            <div class='left-content'> <span> <img id="project_thumb" src="img/defaultImageIcon.png" border="0" width="200" height="200" class="img-thumbnail img-responsive" style="width:100%;"> </span>
              <div>
                <input type="hidden" role="uploadcare-uploader" name="image" id="file2" data-locale="en" data-tabs="file url facebook gdrive instagram" data-images-only="false" data-path-value="false" data-preview-step="false" data-multiple="false" data-crop="enabled" value="" />
                <br />
                <input type="hidden" name="uploadcare_image_url" id="uploadcare_image_url" value="" />
                <input type="hidden" name="uploadcare_image_name" id="uploadcare_image_name" value="" />
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
                <select name="category" class='form-control'>
                  <option value="">-----Select------</option>
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
                </select>
              </span> </div>
            <div class='form_element form-group increase'>
              <label class="control-label">
                <?php echo PROJECT_EXPIRY_DATE ?>
              </label>
              :<br />
              <span>
                <input type="text" name="expiryDate" value="" id="expiryDate" class='form-control'>
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
                  <select name="isLive">
                    <option value="">-----Select------</option>
                    <?php
                    $query = "select * from project_visibility";
                    $result = mysql_query($query);
                    while ($visibility = mysql_fetch_array($result))
                    {
                      ?>
                      <option value="<?php echo $visibility['id'] ?>" >
                      <?php echo $visibility['visibility'] ?>
                      </option>
                      <?php
                    }
                    ?>
                  </select>
                </span> </div>
<?php } ?>
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
            <div style="clear:both;"></div>
            <div class='form_element description_gui'>
              <label>
<?php echo PROJECT_DESCRIPTION ?>
              </label>
              :<br />
              <span>
                <textarea id"projectDescription"   name="description" rows="4" cols="20" maxlength=150 class='form-control'>
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
          <div class='form_element'>
            <input id="validateBtn" class="btn btn-primary" type="submit" name="projectSubmit"  value="<?php echo PROJECT_SAVE_BUTTON ?>" >
          </div>
          <div class='form_element'>
            <input id="resetBtn" class="btn btn-primary" type="reset" name="projectreset" value="<?php echo RESET_BUTTON ?>" >
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
  // When the document is ready
  $(document).ready(function () {

    $('#expiryDate').datepicker({
      format: "yyyy/mm/dd"
    });

  });
</script>
<script type="text/javascript">
  $(document).ready(function () {

    $('#create-project').bootstrapValidator({
      message: 'This value is not valid',
      fields: {
        projectName: {
          validators: {
            notEmpty: {
              message: 'Project Name is required.'
            }
          }
        },
        projectPrice: {
          validators: {
            notEmpty: {
              message: 'Project Price is required.'
            },
            regexp: {
              regexp: /^[0-9\.]+$/,
              message: 'Project Price can only consist of number, dot.'
            }
          }
        },
        quantity: {
          validators: {
            notEmpty: {
              message: 'Quantity is required and cannot be empty'
            },
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'Quantity can only consist of  numbers.'
            }
          }
        },
        category: {
          validators: {
            notEmpty: {
              message: 'Category is required.'
            }
          }
        }
      }
    });

    // Validate the form manually
    /* $('#validateBtn').click(function() {
     $('#create-project').bootstrapValidator('validate');
     });
     
     $('#resetBtn').click(function() {
     $('#create-project').data('bootstrapValidator').resetForm(true);
     });*/
  });

  $(document).ready(function () {
    var widget = uploadcare.Widget('#file2');
    var uploadcare_field = $('#file2');
    var old_value = uploadcare_field.val();
    var value_changed = false;

    widget.onUploadComplete(function (info) {
      console.log(info);
      $("#project_thumb").attr("src", info.cdnUrl);
      $("#uploadcare_image_url").attr("value", info.cdnUrl);
      $("#uploadcare_image_name").attr("value", info.name);
      //document.getElementById('preview1').src = info.cdnUrl + '/preview/160x120/';
    });

    widget.onChange(function (info) {
      //document.getElementById('preview1').src = info.cdnUrl + '/preview/160x120/';
    });
  });

  function addTagToSuggestion() {
    var tag = $('#addNewTag').val();
    var tags = $('#tags').val();
    if (tag != "") {
      tags = tags.trim();
      if (tags != "" && tags != "undefined") {
        tags = tags + ', ' + tag;
      } else {
        tags = tag;
      }
      $("#tags").val(tags);
      $("#addNewTag").val("");
      var random_id = Math.floor(Math.random() * 90000) + 10000;
      $('#tags_list').append('<span id="' + random_id + '"> ' + tag + ' <img alt="" title="Remove" src="images/closebox.png" onclick="removetag(\'' + tag + '\', \'' + random_id + '\');"></span>');
    }
    else {
      alert("Please Enter Tag Name.");
    }
  }

  function removetag(tag, tag_id) {
    var tags = $('#tags').val();
    var listItem = $("#" + tag_id);
    var index = $("#tags_list span").index(listItem);

    var array = tags.split(',');
    var index = array.indexOf(array[index]);
    if (index > -1) {
      array.splice(index, 1);
    }
    $('#tags').val(array);
    $('#tags_list' + ' #' + tag_id).html("").remove();
  }
</script>
<script src="ckeditor/ckeditor.js"></script>
<script>
  //CKEDITOR.replace('description'); 
  CKEDITOR.replace('description', {
    toolbarGroups: [
      {name: 'document', groups: ['mode']}, // Line break - next group will be placed in new line.
      {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
      {name: 'styles', groups: ['Styles', 'Format', 'Font', 'FontSize', 'TextColor', 'BGColor']},
      {name: 'insert', groups: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']}
    ]},
  {
    allowedContent: true
  });
</script>
