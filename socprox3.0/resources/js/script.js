
/**
 * @param row
 * @param column
 */
function updateCell(row,column){
	id=row+"-"+column;
	spanid="span-"+row+"-"+column;
	$('input[inprel^=inprel]').css('display','none');
	$('[spnrel^=spnrel]').css('display','block');
	document.getElementById(id).style.display="block";
	document.getElementById(id).focus();
	document.getElementById(spanid).style.display="none";
}


/**
 * @param pagenum
 * @param tablename
 * @param sortid
 * @param sortorder
 * @param where
 */
function toggleSortMe(pagenum,tablename,sortid,sortorder,where){
	paginate(pagenum+1,tablename,sortid,sortorder,where);
}

/**
 * @param id
 * @param table
 * @param column
 * @param oldval
 * @param spanid
 * @param newval
 * @param tabid
 * @param pagenum
 * @param tabrow
 * @param inpid
 */
function updateCellVal(id,table,column,oldval,spanid,newval,tabid,pagenum,tabrow,inpid){
	$.ajax({
		url: 'update.php',
		type: 'POST',
		dataType: "html",
		data: {
			tablename : table,
			spanid:spanid,
			tabid:tabid,
			inpid:inpid,
			pagenum:pagenum,
			id: id,
			newvalue: newval, 
			oldvalue:oldval,
			colname: column						
		},
		success: function (response) 
		{ 
			response=response.split("~");
			tabcontent=response[0];
			resultr=response[1];
			spanid=response[2];
			inpid=response[3];
			$("#"+tabrow).html(tabcontent);
			if(resultr=='ok'){
				alert("Data updated successfully");
				success	=resultr;
				$("#"+inpid).css('display','none');
			}else{
				success='error';
				alert("Opps!! Data didn't update! Something went wrong. Last data being restored. Please contact app admin.");
			}
			highlight(tabrow, success); 
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
		async: true
	});
}


/**
 * @param id
 * @param tablename
 * @param pagenum
 */
function deleteRow(id,tablename,pagenum){

	if ( confirm('Are you sure you want to delete the row has id ' + id )  ) {
		$.ajax({
			url: 'delete.php',
			type: 'POST',
			dataType: "html",
			data: {
				tablename : tablename,
				id: id 
			},
			success: function (response) 
			{ response=myTrim(response.replace(/(\r\n|\n|\r)/gm,""));
			if (response == "ok" ){
				alert("Data deleted successfully.");
				paginate(pagenum+1,tablename);
			}else{
				alert("Opps!! Data didn't deleted ! Is it a dependent value ? Please contact app admin if there is no clue .");
			}
			},
			error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
			async: true
		});
	}
}
/**
 * @param id
 * @param tablename
 * @param pageNum
 * @param sortID
 * @param sortOrder
 * @param where
 */
function editRow(id,tablename,pageNum,sortID,sortOrder,where){
	$.ajax({
		url: 'edit.php',
		type: 'POST',
		dataType: "html",
		data: {
			tablename : tablename,
			id: id,
			sortID:sortID,
			sortOrder:sortOrder,
			where:where
		},
		success: function (response) 
		{       $("#edt_table_fields").html(response);
		edtdialog.dialog("open");
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
		async: true
	});

	function getFormData($form) {
		var unindexed_array = $form.find('input');
		var uniqueNames = [];
		$.each(unindexed_array, function(i, el) {
			if ($.inArray(el.name, uniqueNames) === -1)
				uniqueNames.push(el);
		});
		var indexed_array = {};
		$.map(unindexed_array, function(n, i) {
			indexed_array[n['name']] = $.trim(n['value']);
		});
		return indexed_array;
	}
	function saveEntry(pagenum){
		var tblrequest = $("#tblrequest").val();
		var tabidx=$('#tabidx').val();
		var id=$('#id').val();
		var $formData = $("#edt-dialog-form");
		var fData = getFormData($formData);
		$.ajax({
			url: 'saveedit.php',
			type: 'POST',
			dataType: "html",
			data: {
				table : tblrequest,
				fdata1 : fData,
				tabidx:tabidx,
				id:id
			},
			success: function (response) 
			{      response=myTrim(response.replace(/(\r\n|\n|\r)/gm,""));
			if(response=='ok'){
				edtdialog.dialog("close");
				loadingDialog.dialog("close");
				alert("Record updated successfully!!");
				paginate(pagenum+1,tblrequest,sortID,sortOrder,where);
			}else{
				alert("Record couldn't be updated. Something went wrong.");
			}
			},
			error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
			async: true
		});

	}	
	edtdialog = $("#edt-dialog-form").dialog({
		autoOpen : false,
		height : 0.7*screen.height,
		width : 0.7*screen.width,
		modal : true,
		dialogClass : 'dialog-style',
		closeOnEscape : true,
		buttons : {
			"Save" : function(){saveEntry(pageNum,sortID,sortOrder,where);},
			Cancel : function() {
				edtdialog.dialog("close");
				loadingDialog.dialog("close");
			}
		},
		cancel : function() {
			edtdialog.dialog("close");
			loadingDialog.dialog("close");
			form[0].reset();
			allFields.removeClass("ui-state-error");
		}
	});
}
/**
 * 
 */
function addUser() {
	var tblrequest = $("#tblrequest").val();
	var $formData = $("#dialog-form");
	var fData = getFormData($formData);
	$
	.ajax({
		url : 'createentry.php',
		type : 'POST',
		dataType : "html",
		data : {
			fdata1 : fData,
			table : tblrequest
		},
		success : function(response) {
			if (response != "Error") {
				alert("Record Added Successfully !!");
				$("#dialog-form").dialog("close");
				loadingDialog.dialog("close");
			} else {
				alert("Record couldn't be added ! Please insert proper values. ");
			}
		},
		error : function(XMLHttpRequest, textStatus, exception) {
			alert("Ajax failure\n" + errortext);
		},
		async : true
	});

}
/**
 * @param totalRecord
 * @param pageSize
 * @param curPage
 * @param tblname
 * @param sortid
 * @param sortorder
 * @param where
 */
function moveFirst(totalRecord,pageSize, curPage,tblname,sortid,sortorder,where){
	paginate(1,tblname,sortid,sortorder,where);
}
/**
 * @param totalRecord
 * @param pageSize
 * @param curPage
 * @param tblname
 * @param sortid
 * @param sortorder
 * @param where
 */
function moveLast(totalRecord,pageSize, curPage,tblname,sortid,sortorder,where){
	lastPage=parseInt(parseInt(totalRecord)/parseInt(pageSize));
	paginate(lastPage+1,tblname,sortid,sortorder,where);
}
/**
 * @param totalRecord
 * @param pageSize
 * @param curPage
 * @param tblname
 * @param sortid
 * @param sortorder
 * @param where
 */
function movePrev(totalRecord,pageSize, curPage,tblname,sortid,sortorder,where){
	curPage=parseInt(curPage);
	if(curPage!==0){
		paginate(curPage,tblname,sortid,sortorder,where);
	}
}
/**
 * @param totalRecord
 * @param pageSize
 * @param curPage
 * @param tblname
 * @param sortid
 * @param sortorder
 * @param where
 */
function moveNext(totalRecord,pageSize, curPage,tblname,sortid,sortorder,where){
	lastPage=parseInt(parseInt(totalRecord)/parseInt(pageSize));
	curPage=parseInt(curPage);
	if(curPage!=lastPage){
		paginate(curPage+2,tblname,sortid,sortorder,where);
	}
}
/**
 * @param column
 * @returns {String}
 */
function getRelevantColumn(column){
	switch(column){
	case 'UserIDs':
		column='UserID';
		break;
	case 'VerifiedBy':
		column='UserID';
		break;
	case 'VerifiedID':
		column="VerificationID";
		break;
	case 'Game_ID':
		column="GameID";
		break;
	case 'P1_ID':
		column="PayoffID";
		break;
	case 'P2_ID':
		column="PayoffID";
		break;
	case 'P3_ID':
		column="PayoffID";
		break;
	case 'P4_ID':
		column="PayoffID";
		break;
	default:		
		break;
	}
	return column;
}
/**
 * @param column
 * @param data
 * @param table
 * @param idx
 * @param targetTable
 * @param sortID
 * @param sortOrder
 * @param where
 * @param pageNum
 */
function showRelationalData(column,data,table,idx,targetTable,sortID,sortOrder,where,pageNum){
	edtdialog = $("#edt-dialog-form").dialog({
		autoOpen : false,
		height : 0.7*screen.height,
		width : 0.7*screen.width,
		modal : true,
		dialogClass : 'dialog-style',
		closeOnEscape : true,
		buttons : {
			"Save" : function(){saveEntry(pageNum);},
			Cancel : function() {
				edtdialog.dialog("close");
				loadingDialog.dialog("close");
			}
		},
		cancel : function() {
			edtdialog.dialog("close");
			loadingDialog.dialog("close");
			form[0].reset();
			allFields.removeClass("ui-state-error");
		}
	});
	function saveEntry(pagenum){
		var tblrequest = targetTable;
		var tabidx=column;
		var id=data;
		var $formData = $("#edt-dialog-form");
		var fData = getFormData($formData);
		$.ajax({
			url: 'saveedit.php',
			type: 'POST',
			dataType: "html",
			data: {
				table : tblrequest,
				fdata1 : fData,
				tabidx:tabidx,
				id:id
			},
			success: function (response) 
			{      response=myTrim(response.replace(/(\r\n|\n|\r)/gm,""));
			if(response=='ok'){
				edtdialog.dialog("close");
				loadingDialog.dialog("close");
				alert("Record updated successfully!!");
				paginate(pagenum+1,table,sortID,sortOrder,where);
			}else{
				alert("Record couldn't be updated. Something went wrong.");
			}
			},
			error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + errortext); },
			async: true
		});
	}	
	column=getRelevantColumn(column);
	
	$.ajax({
		url: 'relationalUpdate.php',
		type: 'POST',
		dataType: "html",
		data: {
			column:column,
			data:data,
			table:table,
			idx:idx,
			targetTable:targetTable
		},
		success: function (response) 
		{       $("#edt_table_fields").html(response);
		edtdialog.dialog("open");
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + exception); },
		async: true
	});

}
/**
 * @param pagenum
 * @param tablename
 * @param sortid
 * @param sortorder
 * @param where
 */
function paginate(pagenum,tablename,sortid,sortorder,where){
	$.ajax({
		url: 'loaddata.php',
		type: 'POST',
		dataType: "html",
		data: {
			tblrequest : tablename,
			pagenum:pagenum-1,
			sortid:sortid,
			sortorder:sortorder,
			whereval:where,
			pagesize:g_defaultPageSize
		},
		success: function (response) 
		{ response=myTrim(response.replace(/(\r\n|\n|\r)/gm,""));
		if (!response){
			alert("Opps!! Data didn't update! Something must be wrong..Please contact app admin.");
		}else{
			$("#tabContent").html(response);

		}
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + exception); },
		async: true
	});
}

function getJsonResp(url){
	$.ajax({
		url: 'JSONFomData.php',
		type: 'POST',
		dataType: "html",
		data: {
			jsonurl : url
		},
		success: function (response) 
		{ response=myTrim(response.replace(/(\r\n|\n|\r)/gm,""));
		if (!response){
			alert("Opps!! Data didn't update! Something must be wrong..Please contact app admin.");
		}else{
			//$("#tabContent").html(response);
			$("#jsondata").html(response);
			//document.getElementById('jsondata').innerHTML=response;
		}
		},
		error: function(XMLHttpRequest, textStatus, exception) { alert("Ajax failure\n" + exception); },
		async: true
	});
	
}
function executedAlready(){
    alert("This REST call has been executed already for this simulation session! It can be executed once in a simulation!");
}





