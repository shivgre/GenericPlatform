<script src="<?php echo BASE_URL ?>grid_widget/resources/js/jquery.min.js"></script>
<script src="<?php echo BASE_URL ?>grid_widget/resources/js/bootstrap.min.js"></script>
<script src="<?php echo BASE_URL ?>grid_widget/resources/js/prettify.js"></script>
<link href="http://mindmup.github.io/editable-table/index.css" rel="stylesheet">
<script src="<?php echo BASE_URL ?>grid_widget/resources/js/mindmup-editabletable.js"></script>
<script src="<?php echo BASE_URL ?>grid_widget/resources/js/numeric-input-example.js"></script>
<script src="<?php echo BASE_URL ?>socprox3.0/resources/js/jquery-ui.js"></script>
<style>
  @media only screen and (max-width: 768px) {
    #useremail, tr td:nth-child(4)		{ display:none; visibility:hidden; }
  }

  @media only screen and (max-width: 420px) {
    #useremail, tr td:nth-child(4)	{ display:none; visibility:hidden; }
    #username, tr td:nth-child(3)			{ display:none; visibility:hidden; }
    #project_id, tr td:nth-child(2)			{ display:none; visibility:hidden; }
  }

  @media only screen and (max-width: 320px) {
    #useremail, tr td:nth-child(4)	{ display:none; visibility:hidden; }
    #username, tr td:nth-child(3)			{ display:none; visibility:hidden; }
    #project_id, tr td:nth-child(2)			{ display:none; visibility:hidden; }
    #transaction_datetime, tr td:nth-child(6)			{ display:none; visibility:hidden; }
  }
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
<script type="text/javascript">

  jQuery(document).ready(function () {

    jQuery(window).bind("load", function () {
      page = 1;
      limit = 25;
      startpoint = (page * limit) - limit;
      //statement = 'where project_id=<?php echo $_GET['pid'] ?>';
      query_string = 'action=pagination&table=<?php echo $GLOBALS['table_count'] ?>&query=<?php echo $GLOBALS['sqlWhereConditions'] ?>&per_page=' + limit + '&page=' + page;
      jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
        var sHTML = data;
        jQuery('#pagination_div').html(sHTML);
      });
      query_string = 'action=load_table&table=<?php echo $GLOBALS['table'] ?>&query=<?php echo $GLOBALS['sqlWhereConditions'] ?>&startpoint=' + startpoint + '&limit=' + limit;
      jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
        var sHTML = data;
        //alert(sHTML);
        jQuery('#table_data').html(sHTML);
      });
    }); //end load

    jQuery(document).on("click", "ul.pagination li a", function () {
      //alert($(this).attr('title')); 
      if (jQuery(this).attr('title') != 'current')
      {
        page = jQuery(this).attr('title');
        limit = 25;
        startpoint = (page * limit) - limit;
        //statement = 'transactions where 1 = 1';
        query_string = 'action=pagination&table=<?php echo $GLOBALS['table_count'] ?>&query=<?php echo $GLOBALS['sqlWhereConditions'] ?>&per_page=' + limit + '&page=' + page;
        jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
          var sHTML = data;
          jQuery('#pagination_div').html(sHTML);
        });
        query_string = 'action=load_table&table=<?php echo $GLOBALS['table'] ?>&query=<?php echo $GLOBALS['sqlWhereConditions'] ?>&startpoint=' + startpoint + '&limit=' + limit;
        jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
          var sHTML = data;
          jQuery('#table_data').html(sHTML);
        });
      }
      return false;
    }); //end event 

    jQuery(document).on("click", ".sortBy", function () {
      //alert($(this).attr('title')); 
      var sortBy = jQuery(this).attr('id');
      var tableName = jQuery(this).closest('table').attr('id');

      var order = 'desc';
      if (jQuery(this).attr('data-order')) {
        if (jQuery(this).attr('data-order') == 'desc') {
          jQuery(this).attr("data-order", "asc");
          jQuery(this).toggleClass("asc");
          order = 'asc';
        }
        else {
          jQuery(this).attr("data-order", "desc");
          jQuery(this).toggleClass("desc");
          order = 'desc';
        }
      }
      else {
        jQuery(this).attr("data-order", "desc");
        jQuery(this).toggleClass("desc");
        order = 'desc';
      }

      page = 1;
      limit = 8;
      startpoint = (page * limit) - limit;
      statement = '<?php echo $GLOBALS['sqlWhereConditions'] ?> order by ' + sortBy;
      query_string = 'action=pagination&table=<?php echo $GLOBALS['table_count'] ?>&query=' + statement + '&data-order=' + order + '&per_page=' + limit + '&page=' + page;
      jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
        var sHTML = data;
        var tableName = jQuery(this).closest('table').attr('id');
        jQuery('#table_data').html(sHTML);
      });
      query_string = 'action=load_table&table=<?php echo $GLOBALS['table'] ?>&query=' + statement + '&data-order=' + order + '&startpoint=' + startpoint + '&limit=' + limit;
      jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
        var sHTML = data;
        //alert(sHTML);			
        jQuery('#table_data').html(sHTML);
      });
    }); //end event 

    jQuery(document).on("click", "th .fa-pencil", function () {
      alert("edit");
    });

    jQuery(document).on("click", "th .fa-times", function () {
      var tid = jQuery(this).attr('id');
      var tableName = jQuery(this).closest('table').attr('id');
      var th = $('#' + tableName + ' th').eq(0);
      var idName = th.attr('id'); //get column name
      var confirmFlag = confirm("do you want delete this transaction!");
      if (confirmFlag) {
        query_string = 'action=row_delete&table=' + tableName + '&column=' + idName + '&id=' + tid;
        jQuery.get('<?php echo BASE_URL ?>grid_widget/grid_actions.php', query_string, function (data) {
          if (data == 'true') {
            jQuery("#" + tableName + " tr #" + tid).hide();
            jQuery(".success_" + tid).hide();
            alert("Delete Success.");
          }
          else {
            alert("Delete Not Success.");
          }
        });
      }
    });

  });

</script>