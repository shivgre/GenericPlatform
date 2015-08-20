<?php
if ($_REQUEST['tab'] && $_REQUEST['pname'])
{
  $request = $_REQUEST['pname'];
}
if ($request != 'index')
{
  echo ("<h4 class='current-msg-section'>  Selected table : <u style='color:green;font-size:18px;'>" . $request . "</u> </h4>");
}
?>
<input type="hidden" id="tblrequest" name="tblrequest" value='<?php echo $request; ?>'>
<input type="hidden" id="default_list_view" name="default_list_view" value='<?php echo $_REQUEST['default_list_view']; ?>'>
<input type="hidden" id="list_view" name="list_view" value='<?php echo ucwords($_REQUEST['list_view']); ?>'>
<input type="hidden" id="list_filter" name="list_filter" value='<?php echo $_REQUEST['list_filter']; ?>'>
<input type="hidden" id="list_sort" name="list_sort" value='<?php echo $_REQUEST['list_sort']; ?>'>
<input type="hidden" id="list_field" name="list_field" value='<?php echo $_REQUEST['list_field']; ?>'>

<input type="hidden" id="tblidx" name="tblidx" value=''>
<div id="wrap">
  <h1><?php $request; ?></h1>
<?php if ($request != 'index')
{ ?>
    <div style="float: right; padding-right: 20px;">
      <button id="create-user">New entry</button>
    </div>

    <!-- Feedback message zone -->
    <div id="message"></div>

    <div id="toolbar">
      <input type="text" id="filter" name="filter"
             placeholder="Filter :type any text here" /><input style="left: 10px;"
             type="button" id="filterbtn" value="Find">

    </div>
    <!-- Grid contents -->
    <div id="tabContent"></div>
  </div>
  <div id="tblload" style="position: static !important; display: none;">Please
    wait while table data is loading...</div>
  <script type="text/javascript">
    $(function () {
      if (document.getElementById('loading-msg')) {
        document.getElementById('loading-msg').style.display = 'block';
      }
      var tablename = $('#tblrequest').val();
      var list_view = $('#list_view').val();
      var list_filter = $('#list_filter').val();
      var list_sort = $('#list_sort').val();
      var list_field = $('#list_field').val();
      var default_list_view = $('#default_list_view').val();
  //alert(tablename);
      $.ajax({
        url: '<?php echo BASE_URL_SYSTEM ?>loaddata.php',
        type: 'POST',
        dataType: "html",
        data: {
          tblrequest: tablename,
          pagenum: 0,
          list_sort: list_sort,
          pagesize: 1000
        },
        success: function (response)
        {
          if (!response) {
            alert("1-OOPs!! Data didn't update! Something must be wrong..Please contact app admin.");
          } else {
            $("#tabContent").html(response);
            if (default_list_view != null) {
              if (default_list_view == 'list') {
                $('#tablecontent').css('display', 'block');

                $('#xlist-view-container').css('display', 'none');
                $('#card-view-container').css('display', 'none');
              } else if (default_list_view == 'xlist') {
                $('#tablecontent').css('display', 'none');
                $('#xlist-view-container').css('display', 'block');
                $('#card-view-container').css('display', 'none');
              } else if (default_list_view == 'card') {
                $('#tablecontent').css('display', 'none');
                $('#xlist-view-container').css('display', 'none');
                $('#card-view-container').css('display', 'block');
              }

            }
            //$("#list-view-hold").html(response);
            if (document.getElementById('loading-msg')) {
              document.getElementById('loading-msg').style.display = 'none';
            }
          }
        },
        error: function (XMLHttpRequest, textStatus, exception) {
          alert("Ajax failure\n" + exception);
        },
        async: true
      });

    });
  </script>
<?php
}
?>
