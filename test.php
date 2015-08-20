
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
Generic CJcornell</title>
<link href='http://fonts.googleapis.com/css?family=Galdeano' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,400,600,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/carousel.css" type="text/css">
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/font-awesome.css" type="text/css">
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/style.css" type="text/css">
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/common-responsive.css" type="text/css">
<link rel="stylesheet" href="http://genericveryold.cjcornell.com/application/css/responsive.css">
<script src="http://scrollrevealjs.org/js/scrollReveal.min.js?ver=0.2.0-rc.1"></script>
<!-- CAPSTONE: Override Uploadcare text -->
<script type="text/javascript">

	UPLOADCARE_PUBLIC_KEY = '4c3637988f9b93d343e8';
	
	UPLOADCARE_LOCALE_TRANSLATIONS = {
		ready: 'Update Profile Photo'
	};
	UPLOADCARE_LOCALE_TRANSLATIONS = {
	  errors: {
		'portrait': "Landscape images only",
		'dimensions': "Dimensions should be more or equal to 650 X 130"  // message for widget
	  },
	  dialog: {tabs: {preview: {error: {
		'portrait': {  // messages for dialog's error page
		  title: "No portrait images are allowed.",
		  text: "We are sorry but image should be landscape.",
		  back: "Back"
		},
		'dimensions': {  // messages for dialog's error page
		  title: "Dimensions should be more or equal to 650 X 130",
		  text: "We are sorry but image Dimensions should be more or equal to 650 X 130.",
		  back: "Back"
		}
	  } } } }
	};
	UPLOADCARE_PATH_VALUE = true; 
	UPLOADCARE_CROP = "2:3";
</script>
</head>
<body>

<!-- <div style="height:450px"></div>-->
<script type="text/javascript">
	(function($) {
	"use strict";
	window.scrollReveal = new scrollReveal({ reset: true, move: "50px" });
	})();
	
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://ucarecdn.com/widget/2.4.0/uploadcare/uploadcare.full.min.js" charset="utf-8"></script>

<script>
$( "#myprojects_tab" ).mouseover(function() {	
	  $( this).addClass("open");	 
});
$( "#myprojects_tab" ).mouseout(function() {	
	  $( this).removeClass("open");	 
});
</script>

<div class="content">
  <div id="profile">
    <div class='title'><br/></div><div class='contentWrapper'>    <div class="jumbotron search-form profile-complete">
      <div class="container">
        <div class="row">
          <div class="col-6 col-sm-6 col-lg-3 height2">
                      </div>
        </div>
      </div>
      <div class="container">
        <div class="row" >
          <div class="col-6 col-sm-6 col-lg-3">
            <form action="form-actions.php" method="post">
              <div class='left-content'> <span> <img id="user_thumb" src="users_uploads/defaultImageIcon.png" border="0" width="200" height="200" class="img-thumbnail img-responsive" style="width:100%;" /> </span>
                <div>
                                    <input type="hidden" role="uploadcare-uploader" name="image" id="file2" data-locale="en" data-tabs="file url facebook gdrive instagram" data-images-only="false" data-path-value="false" data-preview-step="false" data-multiple="false"  value="" data-crop="650x430 minimum"/>
                  <br />
                  <input type="hidden" name="uploadcare_image_url" id="uploadcare_image_url" value="" />
                  <input type="hidden" name="uploadcare_image_name" id="uploadcare_image_name" value="" />
                  <input type="hidden" name="profile_id" id="profile_id" value="16" />
                  <div style="margin:5% 0%">
                    <input type="submit" class="submit btn btn-primary pull-left" name="profile_image_submit" id="login" value="SAVE">
                    <input type="button" onclick="location.href='http://genericveryold.cjcornell.com/profile.php'" class="submit btn btn-primary  pull-right" name="login" value="CANCEL"/>
                  </div>
                                  </div>
              </div>
                
                
                
                    <div class='left-content'> <span> <img id="user_thumb" src="users_uploads/defaultImageIcon.png" border="0" width="200" height="200" class="img-thumbnail img-responsive" style="width:100%;" /> </span>
                <div>
                                    <input type="hidden" role="uploadcare-uploader" name="image" id="file2" data-locale="en" data-tabs="file url facebook gdrive instagram" data-images-only="false" data-path-value="false" data-preview-step="false" data-multiple="false"  value="" data-crop="650x430 minimum"/>
                  <br />
                  <input type="hidden" name="uploadcare_image_url2" id="uploadcare_image_url" value="" />
                  <input type="hidden" name="uploadcare_image_name2" id="uploadcare_image_name" value="" />
                  <input type="hidden" name="profile_id" id="profile_id" value="16" />
                  <div style="margin:5% 0%">
                    <input type="submit" class="submit btn btn-primary pull-left" name="profile_image_submit" id="login" value="SAVE">
                    <input type="button" onclick="location.href='http://genericveryold.cjcornell.com/profile.php'" class="submit btn btn-primary  pull-right" name="login" value="CANCEL"/>
                  </div>
                                  </div>
              </div>
                
                
                
            </form>
          </div>
        
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<!--<script src="http://genericveryold.cjcornell.com/application/js/jquery-1.10.2.min.js"></script> -->
<script src="http://genericveryold.cjcornell.com/application/js/bootstrap.min.js"></script>
<script type="text/javascript">
     /* (function($) {
        'use strict';
        window.scrollReveal = new scrollReveal({ reset: true, move: '50px' });
      })();*/
    </script>
</body>
</html>