<style>
.select{width:27.3333%;}

.container .jumbotron {
padding-right: 30px !important;
padding-left: 30px !important;
}
</style>
<div class="jumbotron projects">
	<div class="row">
		<div class="col-6 col-sm-6 col-lg-9 sortby">
			<h3>
				<?=SORT_BY?>
			:</h3>
			<span>
			<div class="btn-group select2">
				<button type="button" class="btn btn-danger main-select2" id="sort_popular_users_value">---
				<?=SELECT?>
				---</button>
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">
				<?=TOGGLE_NAVIGATION?>
				</span> </button>
				<ul class="dropdown-menu" role="menu" id="sort_popular_users">
					<li data-value="Alpha"><a>
						<?=ALPHABETICALL?>
						</a></li>
					<li data-value="Date"><a>
						<?=PROJECT_CREATED_DATE?>
						</a></li>
					<li data-value="Relevance"><a>
						<?=RELEVANCE?>
						</a></li>
				</ul>
			</div>
			</span> <span>
			<input type="hidden"  name="sort_hidden_value" id="sort_hidden_value" class="input-bg form-control"  value="">
			<input type="button" id="sortList" value="<?=SORT?>" name="sortList" class="btn btn-primary">
			</span> </div>
		<div class="col-6 col-sm-6 col-lg-3 grid-type"> <span id="listView" class="glyphicon glyphicon-align-right"></span> <span id="boxView" class="glyphicon glyphicon-th-large"></span> <span id="thumbView" class="glyphicon glyphicon-th-list"></span> <span></span> </div>
	</div>
	<div class="row">
		<div class="col-12 col-sm-12 col-lg-12" id="pdwrapper">
			<?php
               // echo "<br><br><br><br>".$_REQUEST['tab'];
                foreach($userTblChildArray as $k=>$v){
                // echo "<br><br><br><br>".$v;
                if($_REQUEST['tab']==$v){
                    $tabData = $crossRef->getUserCrossReference("$v", array("{$userTblArray['uid_fld']}" , "{$userTblArray['uname_fld']}", "{$userTblArray['firstname_fld']}", "{$userTblArray['lastname_fld']}", "{$userTblArray['country_fld']}", "{$userTblArray['image_fld']}"),$userTblArray);

                    // print_r($tabData);
                    $crossRef->listWidgetForUserCrossReference($tabData,$userTblArray);
                }
            }
            foreach(projsTblChildArray as $k=>$v){
                // echo "<br><br><br><br>".$v;
                if($_REQUEST['tab']==$v){
                    $tabData = $crossRef->getUserCrossReferenceprojs("$v", $projectTblArray,$userTblArray);

                    // print_r($tabData);
                    $crossRef->listWidgetForProjCrossReference($tabData,$projectTblArray);
                }
            }

				/*if($_REQUEST['tab'] == "myFavorites" ){
					$favoritesData = $crossRef->getUserCrossReference("user_favorites", array("{$userTblArray['uid_fld']}" , "{$userTblArray['uname_fld']}", "{$userTblArray['firstname_fld']}", "{$userTblArray['lastname_fld']}", "{$userTblArray['country_fld']}", "{$userTblArray['image_fld']}", 'rating'),$userTblArray);
					$crossRef->listWidgetForUserCrossReference($favoritesData);	
				}
				if($_REQUEST['tab'] == "follow" ){
					$followData =  $crossRef->getUserCrossReference("user_follow" , array("{$userTblArray['uid_fld']}" , "{$userTblArray['uname_fld']}", "{$userTblArray['firstname_fld']}", "{$userTblArray['lastname_fld']}", "{$userTblArray['country_fld']}", "{$userTblArray['image_fld']}"),$userTblArray);
					$crossRef->listWidgetForUserCrossReference($followData);
				}
				
				if($_REQUEST['tab'] == "friends" ){
					$friendsData = $crossRef->getUserCrossReference(" user_friends" , array("{$userTblArray['uid_fld']}" ,"{$userTblArray['uname_fld']}", "{$userTblArray['firstname_fld']}", "{$userTblArray['lastname_fld']}", "{$userTblArray['country_fld']}", "{$userTblArray['image_fld']}"),$userTblArray);
					$crossRef->listWidgetForUserCrossReference($friendsData);
				}
				if($_REQUEST['tab'] == "likes" ){
                    //echo "VISITED";
					$categoriesData = $crossRef->getUserCrossReference("user_liked" , array("{$userTblArray['uid_fld']}" ,"{$userTblArray['uname_fld']}", "{$userTblArray['firstname_fld']}", "{$userTblArray['lastname_fld']}", "{$userTblArray['country_fld']}", "{$userTblArray['image_fld']}"),$userTblArray);
					$crossRef->listWidgetForUserCrossReference($categoriesData);
				}	*/
			?>
		</div>
	</div>
</div>
</div>
<script>
jQuery(document).ready(function(){
	
	
	jQuery('.select2').click(function() {
		jQuery('#sort_popular_users').toggle();
	});
	jQuery('#sort_popular_users li').click(function() {
		var index = jQuery(this).index();
		var text = jQuery(this).text();
		var value = jQuery(this).attr('data-value');	
		jQuery("#sort_hidden_value").val(value);
		jQuery("#sort_popular_users_value").html(text);
		//alert('Index is: ' + index + ' , text is ' + text + ' and value is ' + value);
	});
	
	jQuery(document).on('click', '.grid-type span', function(){
		var gridType = $(this).attr('id');
		
		if(gridType == "listView"){
			jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper listView");
			jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-12 col-sm-12 col-lg-12");

		}

		if(gridType == "boxView"){
			jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper");
			//jQuery(".project-details-wrapper .profile-image").hide();
			jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-6 col-sm-6 col-lg-4");		

		}

		if(gridType == "thumbView"){
			jQuery(".project-details-wrapper").removeClass().addClass("project-details-wrapper thumbView");
			jQuery(".project-details-wrapper > div:first-child").removeClass().addClass("col-12 col-sm-12 col-lg-12");
		}
	});
	
	/* Sorting function on SORT button click */
	jQuery("#sortList").click(function() {
	
		var sortby = jQuery('#sort_hidden_value').val();
		if(sortby == "Alpha"){
			$('#sortList').data("sortKey", ".project-info h3");				//setup sort attributes
			sortUsingNestedText(jQuery('#popular_users'), "a", jQuery(this).data("sortKey"));
		}
		if(sortby == "Date"){
			$('#sortList').data("sortKey", ".project-info .date");			//setup sort attributes
			sortUsingNestedDate(jQuery('#popular_users'), "a", jQuery(this).data("sortKey"));
		}
	
	});
	
	/******Alphabetically Sorting function starts here******/
	function sortUsingNestedText(parent, childSelector, keySelector) {
		var items = parent.children(childSelector).sort(function(a, b) {
			var vA = jQuery(keySelector, a).text().toLowerCase();
			var vB = jQuery(keySelector, b).text().toLowerCase();
			return (vA < vB) ? -1 : (vA > vB) ? 1 : 0;
		});
		parent.append(items);
	}
	
	/******Date Sorting function starts here******/
	function sortUsingNestedDate(parent, childSelector, keySelector) {

		var items = parent.children(childSelector).sort(function(a, b) {
			var firstDate = new Date(jQuery(keySelector, a).text());
			var secondDate = new Date(jQuery(keySelector, b).text());
			return (firstDate < secondDate) ? -1 : (firstDate > secondDate) ? 1 : 0;
		});
		parent.append(items);
	}
	
	jQuery(document).on("click", "#search_transactions", function(){ 	
		//alert($(this).attr('title')); 
		var projectName = $("#projectName").val();
		var category = $("#category").val();
		var created = $("#relevant").val();
		var projectDesc = $("#projectDesc").val();
		
		query_string = 'action=search_transactions&projectName=' + projectName + '& category=' + category + '&created='+ created+ '&projectDesc='+ projectDesc ;
		jQuery.get('<?=BASE_URL?>ajax-actions.php', query_string, function(data){
			var sHTML = data;
			//jQuery('#pagination_div').hide();
			jQuery('#pdwrapper').html(sHTML);
		});	
		return false;
	}); //end event
	
});
</script>