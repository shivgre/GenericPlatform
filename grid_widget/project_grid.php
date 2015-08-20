<script src="grid_widget/resources/js/jquery.min.js"></script>
<script src="grid_widget/resources/js/bootstrap.min.js"></script>
<script src="grid_widget/resources/js/prettify.js"></script>
<link href="http://mindmup.github.io/editable-table/index.css" rel="stylesheet">
<script src="grid_widget/resources/js/mindmup-editabletable.js"></script>
<script src="grid_widget/resources/js/numeric-input-example-project.js"></script>
<style>
table {
    table-layout: fixed;
    word-wrap: break-word;
}
th .fa-pencil, th .fa-times{
cursor:pointer;
}
</style>
<div class="row">
<div class="col-lg-12">
<div id="table_data"></div>
<div id="pagination_div" style="float: left;text-align: center;"></div>
</div>
</div>
<script>
  jQuery('#project_config').editableTableWidget().numericInputExample().find('td:first').focus();
jQuery('#project_config').editableTableWidget({editor: jQuery('<textarea>')});
window.prettyPrint && prettyPrint();
</script>
<script type="text/javascript">
	jQuery(document).ready(function(){
	
	jQuery(window).bind("load", function() {
		page = 1;
		limit = 3;
		startpoint = (page * limit) - limit;
		statement = 'where uid = <?=$_SESSION['uid']?>';
		query_string = 'action=pagination&table=project_count&query=' + statement + '&per_page=' + limit + '&page=' + page;
		jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
			var sHTML = data;
			jQuery('#pagination_div').html(sHTML);
		});
		query_string = 'action=load_table&table=project_config&query=' + statement + '&startpoint=' + startpoint + '&limit=' + limit;
		jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
			var sHTML = data;
			//alert(sHTML);
			jQuery('#table_data').html(sHTML);
		});	
	}); //end load
	
	jQuery(document).on("click", "ul.pagination li a", function(){ 	
		//alert($(this).attr('title')); 
		if (jQuery(this).attr('title') != 'current')
		{
			page = jQuery(this).attr('title');
			limit = 3;
			startpoint = (page * limit) - limit;
			statement = 'where uid = <?=$_SESSION['uid']?>';
			query_string = 'action=pagination&table=project_count&query=' + statement + '&per_page=' + limit + '&page=' + page;
			jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
				var sHTML = data;
				jQuery('#pagination_div').html(sHTML);
			});
			query_string = 'action=load_table&table=project_config&query=' + statement + '&startpoint=' + startpoint + '&limit=' + limit;
			jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
				var sHTML = data;
				jQuery('#table_data').html(sHTML);
			});	
		}
		return false;
	}); //end event 
	
	jQuery(document).on("click", ".sortBy", function(){ 	
		//alert($(this).attr('title')); 
		var sortBy = jQuery(this).attr('id');
		var tableName = jQuery(this).closest('table').attr('id');
		
		var order = 'desc';
		if (jQuery( this ).attr('data-order')) {
			if(jQuery( this ).attr('data-order') == 'desc'){	
				jQuery( this ).attr("data-order", "asc" );
				jQuery( this ).toggleClass( "asc" );	
				order = 'asc';
			}
			else{
				jQuery( this ).attr("data-order", "desc" );
				jQuery( this ).toggleClass( "desc" );
				order = 'desc';
			}
		}
		else{
			jQuery( this ).attr("data-order", "desc" );
			jQuery( this ).toggleClass( "desc" );
			order = 'desc';
		}
		
		page = 1;
		limit = 3;
		startpoint = (page * limit) - limit;
		statement = 'where uid = <?=$_SESSION['uid']?> order by '+sortBy;
		query_string = 'action=pagination&table=projects_count&query=' + statement + '&data-order='+order+'&per_page=' + limit + '&page=' + page;
		jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
			var sHTML = data;
			var tableName = jQuery(this).closest('table').attr('id');
			jQuery('#'+tableName+' tbody').html(sHTML);
		});
		query_string = 'action=load_table&table=project_config&query=' + statement +'&data-order='+order+ '&startpoint=' + startpoint + '&limit=' + limit;
		jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
			var sHTML = data;
			//alert(sHTML);			
			jQuery('#'+tableName+' tbody').html(sHTML);
		});	
	}); //end event 
		
		jQuery(document).on("click", "th .fa-pencil", function(){ 
			alert("edit");
		});
		
		jQuery(document).on("click", "th .fa-times", function(){ 
			var tid = jQuery(this).attr('id');
			var tableName = jQuery(this).closest('table').attr('id');
			var th = $('#'+tableName+' th').eq(0);
    		var idName = th.attr('id'); //get column name
			 var confirmFlag = confirm("do you want delete this transaction!");
			 if(confirmFlag){
				 query_string = 'action=row_delete&table='+tableName+'&column='+idName+'&id='+tid;
				 jQuery.get('<?=BASE_URL?>grid_widget/project_grid_actions.php', query_string, function(data){
					if(data == 'true'){
						jQuery("#"+tableName+" tr #"+tid).hide();
						jQuery(".success_"+tid).hide();					
						alert("Delete Success.");
					}
					else{
						alert("Delete Not Success.");
					}
				});
			}
		});
		
	});	  
</script>