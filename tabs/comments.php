<style>
  .cmt-container{
    margin: 0px auto;
    padding: 0px;
    margin-top: 2%;
    width: 100%;
    margin-bottom: 2%;
    float: left;
  }
  .userImage{
    margin: 0px auto;
    padding: 0px;
    width: 15%;
    float: left;
  }
  .userImage img{
    margin: 0px auto;
    padding: 0px;
    width: 100%;
    border-radius: 50%;
  }
  .comment-info{
    margin: 0px auto;
    padding: 0px;
    width: 85%;
    float: right;
  }
</style>
<?php
$cSql = "SELECT c.*, u.uid, u.uname, u.image FROM comments as c LEFT JOIN users as u ON c.comment_by_user_id = u.uid WHERE comment_on_user_id =" . $_GET['uid'] . " order by date_time desc limit 6";
$result = mysql_query($cSql);
if (mysql_num_rows($result) <= 0)
{
  echo "<h2>" . NO_COMMENTS . "</h2>";
}
else
{
  while ($row = mysql_fetch_array($result))
  {
    $comment['image'] = $row['image'];
    $comment['uname'] = $row['uname'];
    $comment['comment'] = $row['comment'];
    $comment['date_time'] = $row['date_time'];
    $comments[] = $comment;
  }
  $comments = array_reverse($comments);
  foreach ($comments as $comment)
  {
    ?>
    <div class="cmt-container">
      <div class="userImage">
        <?php
        if (isset($comment['image']) && $comment['image'] != "")
        {
          echo '<img src="' . BASE_URL . 'users_uploads/' . $comment['image'] . '" alt="User Image">';
        }
        else
        {
          echo '<img src="' . BASE_URL . 'images/thumb_defaultImageIcon" alt="No Image">';
        }
        ?>
      </div>
      <div class="comment-info">
        <div>
          <?php echo $comment['uname'] ?>
        </div>
        <div>
          <?php echo $comment['comment'] ?>
        </div>
        <div>
          <?php
          $date = date_create($comment['date_time']);
          echo date_format($date, 'g:ia \o\n l jS F Y');
          ?>
        </div>
      </div>
    </div>
    <?php
  }
}
?>
<div class="">
  <form action="<?php echo BASE_URL_SYSTEM ?>form-actions.php" method="post" id="comments-add">
    <input type="hidden" name="comment_on_user_id" value="<?php echo $_GET['uid'] ?>" id="cmnt-usr-id">
    <span>
      <textarea id="cmnt" name="comment" rows="4" cols="97" maxlength=150 class=''></textarea>
    </span>
    <div>
      <input id="validateBtn" class="btn btn-primary" type="submit" name="submit_cmt"  value="<?php echo COMMENT_BUTTON ?>" >
    </div>
  </form>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    jQuery(document).on("submit", '#comments-add', function (e) {
      var sumbit_cmnt = 1;
      var cmnt_on_uid = $("#cmnt-usr-id").val().trim();
      var cmnt = $("#cmnt").val().trim();
      if (cmnt_on_uid != "" && cmnt != "") {
        $.post(
                '<?php echo BASE_URL_SYSTEM ?>form-actions.php',
                {cmnt_on_uid: cmnt_on_uid, cmnt: cmnt, submit_cmt: sumbit_cmnt},
        function (data) {
          if (data) {
            //alert(data);
            $(".cmt-container").last().after(data);
          } else {
            alert("<?php echo LOGIN_REQUIRED_MESSAGE ?>.");
          }
        }
        );
      } else {
        alert("<?php echo NO_COMMENT_EMPTY ?>.");
      }
      e.preventDefault();
    }); //end load
  });
</script>
