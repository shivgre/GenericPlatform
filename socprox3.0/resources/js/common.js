var globalRESTRoot = "http://cjcornell.com/bluegame/REST/";
var g_defaultPageSize = 30;
var g_enablePagination = false;
var g_pagenum, g_where, g_tablename, g_spanid, g_tabid, g_inpid, g_id, g_newvalue, g_oldvalue, g_colname, g_row, g_column, g_sortid, g_sortorder;

/**
 * @param rowId
 * @param bgColor
 * @param after
 */
function highlightRow(rowId, bgColor, after) {
	var rowSelector = $("#" + rowId);
	rowSelector.css("background-color", bgColor);
	rowSelector.fadeTo("normal", 0.5, function() {
		rowSelector.fadeTo("fast", 1, function() {
			rowSelector.css("background-color", '');
		});
	});
}
/**
 * @param div_id
 * @param style
 */
function highlight(div_id, style) {
	highlightRow(div_id, style == "error" ? "#e5afaf"
			: style == "warning" ? "#ffcc00" : "#8dc70a");
}
/**
 * @param $form
 * @returns {___anonymous947_948}
 */
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
/**
 * @param x
 * @returns
 */
function myTrim(x) {
	return x.replace(/^\s+|\s+$/gm, '');
}
/**
 * @param t
 */
function updateTips(t) {
	tips.text(t).addClass("ui-state-highlight");
	setTimeout(function() {
		tips.removeClass("ui-state-highlight", 1500);
	}, 500);
}
/**
 * @param o
 * @param n
 * @param min
 * @param max
 * @returns {Boolean}
 */
function checkLength(o, n, min, max) {
	if (o.val().length > max || o.val().length < min) {
		o.addClass("ui-state-error");
		updateTips("Length of " + n + " must be between " + min + " and " + max
				+ ".");
		return false;
	}
	return true;
}
/**
 * @param o
 * @param regexp
 * @param n
 * @returns {Boolean}
 */
function checkRegexp(o, regexp, n) {
	if (!(regexp.test(o.val()))) {
		o.addClass("ui-state-error");
		updateTips(n);
		return false;
	}
	return true;
}

$(function() {

	$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
	$('#cssmenu #menu-button').on('click', function() {
		var menu = $(this).next('ul');
		if (menu.hasClass('open')) {
			menu.removeClass('open');
		} else {
			menu.addClass('open');
		}
	});
	$("li.active").on(
			'click',
			function(e) {
               // alert("clicked");
			/*	var activity = $(this).attr('rel');
				var action = "index.php";
				var method = "post";
				$('body').append('<form id="pageNvg"></form>');
				$('#pageNvg').attr("action", action).attr("method", method);
				$('#pageNvg').append(
						'<input type="hidden" name="pname" id="pname" value="'
						+ activity + '">');
				$("#pageNvg").submit();
				e.stopPropagation();*/
			});
	$("#create-user").button().on("click", function() {
		var tblrequest = $("#tblrequest").val();
		$.ajax({
			url : 'addrowform.php',
			type : 'POST',
			dataType : "html",
			data : {
				tablename : tblrequest
			},
			success : function(response) {
				$("#table_fields").html(response);
				loadingDialog.dialog("close");
				dialog.dialog("open");
			},
			error : function(XMLHttpRequest, textStatus, exception) {
				alert("Ajax failure\n" + errortext);
			},
			async : true
		});
	});

	dialog = $("#dialog-form").dialog({
		autoOpen : false,
		height : 'auto',
		width : 0.3 * screen.width,
		modal : true,
		dialogClass : 'dialog-style',
		closeOnEscape : true,
		buttons : {
			"Add Record" : function() {
				addUser();
			},
			Cancel : function(e) {
				loadingDialog.dialog("close");
				dialog.dialog("close");
			}
		},
		cancel : function(e) {
			loadingDialog.dialog("close");
			form[0].reset();
			allFields.removeClass("ui-state-error");
		}
	});
	loadingDialog = $("#loading-msg").dialog({
		autoOpen : false,
		modal : true,
		closeOnEscape : false,
		height : 'auto',
		width : 'auto',
		dialogClass : 'no-close,ui-dialog-titlebar-close',
		open : function(e) {
			$(".ui-dialog-titlebar").hide();
		},
		close : function(e) {
			loadingDialog.dialog("close");
			$(".ui-dialog-titlebar").show();
		}
	});

	loadingDialog.dialog("close");
	$("li").on('click', function(e) {
		e.stopPropagation();
		if ($(this).text() != 'Cancel')
			loadingDialog.dialog("open");
	});
	$("button").on('click', function(e) {
		e.stopPropagation();
		if ($(this).text() != 'Cancel')
			loadingDialog.dialog("open");
	});
	$("#filterbtn").button();
	$("#filterbtn")
	.on(
			'click',
			function() {
				document.getElementById('tblload').style.display = 'block';
				var tablename = $('#tblrequest').val();
				var where = $("#filter").val();
				$
				.ajax({
					url : 'loaddata.php',
					type : 'POST',
					dataType : "html",
					data : {
						tblrequest : tablename,
						pagenum : 0,
						whereval : where,
						pagesize :g_defaultPageSize
					},
					success : function(response) {
						response = myTrim(response.replace(
								/(\r\n|\n|\r)/gm, ""));
						if (!response) {
							alert("Opps!! Data didn't update! Something must be wrong..Please contact app admin.");
						} else {
							$("#tabContent").html(response);
							document.getElementById('tblload').style.display = 'none';
						}
					},
					error : function(XMLHttpRequest,
							textStatus, exception) {
						alert("Ajax failure\n" + exception);
					},
					async : true
				});
			});

});

$.ajaxSetup({
	global : true
});
$(document).ajaxStart(function() { // On any ajax based call please wait start
	loadingDialog.dialog("open");
});
$(document).ajaxComplete(function() { // On any ajax based call end please wait close
	loadingDialog.dialog("close");
});