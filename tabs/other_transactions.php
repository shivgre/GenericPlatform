<?php
$current_page_num=3;
?>
<style>
.select{width:27.3333%;}
</style>
<div class="">
	<!--Search transactions-->
	<div class="container">
		<div class="row row-offcanvas row-offcanvas-right">
			<div class="col-12 col-sm-12 col-lg-12">
				<h3>
					<?=SEARCH_TRANSACTIONS?>
					:</h3>
					<form action="">
					<input type="text" placeholder="<?=PROJECT_NAME_PLACEHOLDER?>" name="projectName" id="projectName" class="input-bg form-control" value="" />
					<input type="text" placeholder="<?=PROJECT_DESC_PLACEHOLDER?>" name="projectDesc" id="projectDesc" class="input-bg form-control" value="" />
					<input type="hidden"  name="category" id="category" class="input-bg form-control"  value="">
					<div class="btn-group select">
						<button type="button" class="btn btn-danger main-select" id="selected_category_value">------
						<?=PROJECT_CATEGORY?>
						------</button>
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">
						<?=TOGGLE_NAVIGATION?>
						</span> </button>
						<ul class="dropdown-menu" role="menu" id="select_category">
							<?php 
							$query = "select * from project_categories";
							$result = mysql_query($query);
							while($category = mysql_fetch_array($result)){
							?>
								<li data-value="<?=$category['project_category_id']?>">
									<a><?=$category['project_categeory_name']?></a>
								</li>
							<?php 
							}
							?>
						</ul>
					</div>
					<input type="hidden"  name="relevant" id="relevant" class="input-bg form-control"  value="">
					<div class="btn-group select">
						<button type="button" class="btn btn-danger main-select" id="selected_joined_value">----
						<?=CREATED?>
						------</button>
						<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">
						<?=TOGGLE_NAVIGATION?>
						</span> </button>
						<ul class="dropdown-menu" role="menu" id="select_joined">
							<li data-value="DAY"><a>
								<?=TODAY?>
								</a>
							</li>
							<li data-value="WEEK"><a>
								<?=LAST_WEEK?>
								</a>
							</li>
							<li data-value="MONTH"><a>
								<?=LAST_MONTH?>
								</a>
							</li>
						</ul>
					</div>
					<input type="button" value="<?=SEARCH?>" name="submitSearch" class="btn btn-primary" id="search_transactions">
				</form>
			</div>
		</div>
		<!--/row-->
	</div>
	<!--//Search transactions-->
	
	<!--Sort and listing UI-->
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
	<!--Sort and listing UI-->
	
	<div class="container">
		<div class="row" id="pdwrapper">
			<?php
			$sql = "SELECT * FROM transactions t, projects p where t.project_id = p.pid and t.owner_id=".$_SESSION['uid'];
			$result = mysql_query($sql);
			if (mysql_num_rows($result) == 0) {
				echo "<div class='noProjects'>You currently do not have any Transactions.</div>";
			}
			else{
			?>
			
			<div class="row" id="popular_users">
			<?php
			while($row = mysql_fetch_array($result)){
				
			  
			    $records = $records . '<a class="project-details-wrapper listView" href="projectDetails.php?pid='.$row['pid'].'">';
				
				$records = $records . '<div class="col-12 col-sm-12 col-lg-12" data-scroll-reveal="enter bottom over 1s and move 100px">';
				$records = $records . '<div class="project-detail">';				
				$records = $records . '<span class="profile-image">';	
				
				if($row['projectImage'] != ""){
					$records = $records ."<img src='TimThumb.php?src=".$BASE_URL."project_uploads/".$row['projectImage']."&w=650&h=450' alt='' class='img-responsive'>";
				}
				else{
					$records = $records ."<img src='".BASE_URL."project_uploads/defaultImageIcon.png' alt='' class='img-responsive'>";
				}	
							
				$records = $records . '</span>';
				$records = $records . '<div class="project-info">';
				
				$records = $records . '<h3>'.$row['pname'].'</h3>';						
				$records = $records . '<p> <span><strong>'.CREATED.' -</strong> </span> <span class="date">'.$row['create_date'].'</span> </p>';
				
				$records = $records . '<div style="clear:both"></div>'.'</div>';
				$records = $records . '</div></div>';	
					
				$records = $records . '</a>';
		
				echo $records;
			}
			echo '</div>';
		}		
		?>
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
