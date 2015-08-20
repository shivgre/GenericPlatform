/* global $ */
/* this is an example for validation and change events */
$.fn.numericInputExample = function () {
	'use strict';
	var element = $(this),
		footer = element.find('tfoot tr'),
		dataRows = element.find('tbody tr'),
		initialTotal = function () {
			var column, total;
			for (column = 1; column < footer.children().size(); column++) {
				total = 0;
				dataRows.each(function () {
					var row = $(this);
					total += parseFloat(row.children().eq(column).text());
				});
				footer.children().eq(column).text(total);
			};
		};
	element.find('td').on('change', function (evt, value) {
		
		var tableName = $(this).closest('table').attr('id');
		var th = $('#'+tableName+' th').eq($(this).index());
    	var columnName = th.attr('id'); //get column name		
		var columnValue = value; //get column value
		
		var th = $('#'+tableName+' th').eq(0);
    	var idName = th.attr('id'); //get column name
		var idValue =$(this).closest('tr').attr('id');//get id value
		//alert(columnName+":"+columnValue+", "+idName+": "+idValue);
		var query = columnName+"="+columnValue+"&"+idName+"="+idValue+"&tableName="+tableName+"&action=update_cell";
		$.get("/grid_widget/grid_actions.php?"+query,function(data){
			if(data == 'true'){
				alert("Update Successfull");
			}
			else{
				alert("Update Not Successull");	
			}
		});
		
	}).on('validate', function (evt, value) {
		var cell = $(this),
			column = cell.index();
		if (column === 1) {			
			return !isNaN(parseFloat(value)) && isFinite(value);
		} 
		else if (column === 4) {			
			return !isNaN(parseFloat(value)) && isFinite(value);
		} 
		else {
			return !!value && value.trim().length > 0;
		}
	});
	initialTotal();
	return this;
};
