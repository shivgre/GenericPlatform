$(document).ready(function () {
    


$(document).on("click", ".uploadcare-widget-button-open", function () {
    
    
var widget1 = uploadcare.Widget($(this).parents(".img-holder").find(".file2"));
	var uploadcare_field = $($(this).parents(".img-holder").find(".file2"));
	var old_value = uploadcare_field.val();
	var value_changed = false;
	
        
        widget1.onUploadComplete(function(info) {
		console.log(info);
			$("#img-preview").attr("src", info.cdnUrl);
	
			
	});

	

	function track_value_change()
	{
	  if(!value_changed && uploadcare_field.val() != old_value)
	  {
		value_changed = true;

		var file = uploadcare.fileFrom('uploaded', uploadcare_field.val());
		widget1.value(file);
	  }
	}
	setInterval(function() { track_value_change()}, 100);
	

   
   $(this).parents(".img-holder").find(".user_thumb").attr("id", "img-preview");
   
});



    $(document).on("click", ".remove-img-btn", function () {

        var fieldName = $(this).attr('id');

        $.ajax({
            method: "GET",
            url: "ajax-actions.php",
            data: {fieldName: fieldName, check_action: "image_delete"}
        })
                .done(function (msg) {


                    if ($.trim(msg) != 'notDelete') {
                        location.reload();
                    }
                    else {
                        alert("image not Deleted. Please try Again!")
                    }
                });

    });

    $(document).on("click", ".login-btn", function () {



        var widget1 = uploadcare.Widget($(this).parents(".img-holder").find(".file2"));
        var uploadcare_field = $($(this).parents(".img-holder").find(".file2"));

        var fieldName = $(this).parents(".img-holder").find(".field_name").val();

        var file = uploadcare.fileFrom('uploaded', uploadcare_field.val());

        widget1.value(file);



        widget1.onUploadComplete(function (info) {
            //console.log(info);

            $(".user_thumb").attr("src", info.cdnUrl);
            $.ajax({
                method: "GET",
                url: "ajax-actions.php",
                data: {cdnUrl: info.cdnUrl, check_action: "image_submit", imgName: info.name, fieldName: fieldName}
            })
                    .done(function (msg) {


                        if ($.trim(msg) != 'notSaved') {
                            location.reload();
                        }
                        else {
                            alert("image not uploaded. Please try Again!")
                        }
                    });
            // $(".user_thumb").attr("src", info.cdnUrl);

            //$(".uploadcare_image_url").attr("value", info.cdnUrl);
            //$(".uploadcare_image_name").attr("value", info.name);
        });








        //var widget1 = uploadcare.Widget($(this).parents(".img-holder").find(".file2"));



        //widget1.onUploadComplete(function (info) {
        //  console.log(info);
        // $(".user_thumb").attr("src", info.cdnUrl);

        //alert(info.name);


//            });

        //alert($(this).parents(".img-holder").find(".field_name").val());

        //alert($(this).parents(".img-holder").find(".field_name").val());
        /*
         $.ajax({
         method: "GET",
         url: "ajax-actions.php",
         data: {id: id, check_action: "enable_edit"}
         })
         .done(function (msg) {
         
         if ($.trim(msg) == 'active') {
         alert('One edit form is active already');
         } else {
         location.reload();
         }
         });*/

    });

});
/*$().ready(function() {
 // validate login form on keyup and submit
 $("#user_profile_form").validate({
 rules: {
 uname: {
 required: true
 },
 email: {
 required: true,
 email: true
 },
 country: {
 required: true
 }
 },
 
 messages: {
 uname: "<p>Please enter your First Name</p>",
 
 country: "<p>Please enter your Country</p>",
 
 email: "<p>Please enter a valid email address</p>"
 
 }
 
 });	
 
 });*/